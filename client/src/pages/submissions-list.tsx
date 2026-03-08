import { useQuery } from "@tanstack/react-query";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Skeleton } from "@/components/ui/skeleton";
import { AlertCircle, CheckCircle2 } from "lucide-react";

export default function SubmissionsList() {
  const { data, isLoading, error } = useQuery<{
    success: boolean;
    spreadsheetId: string;
    rows: string[][];
    count: number;
  }>({
    queryKey: ["/api/consent/list"],
  });

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
                <div className="flex items-center gap-2 text-green-600 dark:text-green-400">
                  <CheckCircle2 className="h-5 w-5" />
                  <p className="font-medium">
                    {data.count} submission{data.count !== 1 ? "s" : ""} found
                  </p>
                </div>

                {data.rows.length > 0 ? (
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
                        {data.rows.slice(1).map((row, rowIndex) => (
                          <tr
                            key={rowIndex}
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
