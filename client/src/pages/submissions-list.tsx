import { useState, useMemo } from "react";
import { useQuery } from "@tanstack/react-query";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Skeleton } from "@/components/ui/skeleton";
import { AlertCircle, CheckCircle2, Download, ChevronLeft, ChevronRight } from "lucide-react";

const PER_PAGE_OPTIONS = [25, 50, 100] as const;

export default function SubmissionsList() {
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState<number>(25);

  const { data, isLoading, error } = useQuery<{
    success: boolean;
    spreadsheetId: string;
    rows: string[][];
    count: number;
  }>({
    queryKey: ["/api/consent/list"],
  });

  const allDataRows = useMemo(() => data?.rows?.slice(1) ?? [], [data]);
  const totalRows = allDataRows.length;
  const totalPages = Math.max(1, Math.ceil(totalRows / perPage));

  const currentPage = Math.min(page, totalPages);
  const offset = (currentPage - 1) * perPage;
  const visibleRows = allDataRows.slice(offset, offset + perPage);

  const pageNumbers = useMemo(() => {
    const maxVisible = 7;
    let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
    let end = Math.min(totalPages, start + maxVisible - 1);
    if (end - start + 1 < maxVisible) {
      start = Math.max(1, end - maxVisible + 1);
    }
    const pages: (number | "ellipsis-start" | "ellipsis-end")[] = [];
    if (start > 1) {
      pages.push(1);
      if (start > 2) pages.push("ellipsis-start");
    }
    for (let p = start; p <= end; p++) {
      pages.push(p);
    }
    if (end < totalPages) {
      if (end < totalPages - 1) pages.push("ellipsis-end");
      pages.push(totalPages);
    }
    return pages;
  }, [currentPage, totalPages]);

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-orange-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8 px-4">
      <div className="max-w-6xl mx-auto">
        <Card className="shadow-xl">
          <CardHeader>
            <CardTitle className="text-2xl font-bold">
              Consent Submissions
            </CardTitle>
            {data?.spreadsheetId && (
              <p className="text-sm text-muted-foreground">
                Spreadsheet ID: {data.spreadsheetId}
              </p>
            )}
          </CardHeader>
          <CardContent>
            {isLoading && (
              <div className="space-y-2">
                <Skeleton className="h-10 w-full" />
                <Skeleton className="h-10 w-full" />
                <Skeleton className="h-10 w-full" />
              </div>
            )}

            {error && (
              <div className="flex items-center gap-2 text-red-600 dark:text-red-400">
                <AlertCircle className="h-5 w-5" />
                <p>Failed to load submissions. Please try again.</p>
              </div>
            )}

            {data && (
              <div className="space-y-4">
                <div className="flex items-center justify-between flex-wrap gap-3">
                  <div className="flex items-center gap-2 text-green-600 dark:text-green-400">
                    <CheckCircle2 className="h-5 w-5" />
                    <p className="font-medium">
                      {totalRows} submission{totalRows !== 1 ? "s" : ""} found
                    </p>
                  </div>
                  <div className="flex items-center gap-3 flex-wrap">
                    <div className="flex items-center gap-2" data-testid="select-per-page">
                      <span className="text-sm text-muted-foreground">Show:</span>
                      <Select
                        value={String(perPage)}
                        onValueChange={(val) => {
                          setPerPage(Number(val));
                          setPage(1);
                        }}
                      >
                        <SelectTrigger className="w-[75px] h-8 text-sm">
                          <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                          {PER_PAGE_OPTIONS.map((opt) => (
                            <SelectItem key={opt} value={String(opt)}>
                              {opt}
                            </SelectItem>
                          ))}
                        </SelectContent>
                      </Select>
                    </div>
                    {totalRows > 0 && (
                      <Button
                        asChild
                        variant="default"
                        size="sm"
                        data-testid="button-export-csv"
                      >
                        <a href="/api/consent/export" download>
                          <Download className="h-4 w-4 mr-2" />
                          Export to CSV
                        </a>
                      </Button>
                    )}
                  </div>
                </div>

                {visibleRows.length > 0 ? (
                  <>
                    <div className="overflow-x-auto">
                      <table className="w-full border-collapse">
                        <thead>
                          <tr className="border-b-2 border-gray-200 dark:border-gray-700">
                            {data.rows[0]?.map((header, index) => (
                              <th
                                key={index}
                                className="px-4 py-2 text-left font-semibold text-sm text-gray-700 dark:text-gray-300"
                                data-testid={`header-${index}`}
                              >
                                {header}
                              </th>
                            ))}
                          </tr>
                        </thead>
                        <tbody>
                          {visibleRows.map((row, rowIndex) => (
                            <tr
                              key={offset + rowIndex}
                              className="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50"
                              data-testid={`row-${rowIndex}`}
                            >
                              {row.map((cell, cellIndex) => (
                                <td
                                  key={cellIndex}
                                  className="px-4 py-2 text-sm"
                                  data-testid={`cell-${rowIndex}-${cellIndex}`}
                                >
                                  {cell}
                                </td>
                              ))}
                            </tr>
                          ))}
                        </tbody>
                      </table>
                    </div>

                    {totalPages > 1 && (
                      <div
                        className="flex items-center justify-between flex-wrap gap-3 pt-4 border-t border-gray-200 dark:border-gray-700"
                        data-testid="pagination"
                      >
                        <p className="text-xs text-muted-foreground">
                          Showing {offset + 1}–{Math.min(offset + perPage, totalRows)} of {totalRows}
                        </p>
                        <div className="flex items-center gap-1">
                          <Button
                            variant="outline"
                            size="sm"
                            disabled={currentPage <= 1}
                            onClick={() => setPage(currentPage - 1)}
                            data-testid="button-prev-page"
                            className="h-8 px-3 text-xs"
                          >
                            <ChevronLeft className="h-3.5 w-3.5 mr-1" />
                            Previous
                          </Button>

                          {pageNumbers.map((p, idx) =>
                            p === "ellipsis-start" || p === "ellipsis-end" ? (
                              <span
                                key={p}
                                className="w-8 h-8 flex items-center justify-content text-xs text-muted-foreground"
                              >
                                &hellip;
                              </span>
                            ) : (
                              <Button
                                key={p}
                                variant={p === currentPage ? "default" : "outline"}
                                size="sm"
                                onClick={() => setPage(p)}
                                data-testid={`page-${p}`}
                                className="h-8 w-8 p-0 text-xs"
                              >
                                {p}
                              </Button>
                            )
                          )}

                          <Button
                            variant="outline"
                            size="sm"
                            disabled={currentPage >= totalPages}
                            onClick={() => setPage(currentPage + 1)}
                            data-testid="button-next-page"
                            className="h-8 px-3 text-xs"
                          >
                            Next
                            <ChevronRight className="h-3.5 w-3.5 ml-1" />
                          </Button>
                        </div>
                      </div>
                    )}
                  </>
                ) : (
                  <p className="text-muted-foreground text-center py-8">
                    No submissions yet.
                  </p>
                )}
              </div>
            )}
          </CardContent>
        </Card>
      </div>
    </div>
  );
}
