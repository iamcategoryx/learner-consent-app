<?php

require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

try {
    $db = getDb();

    $stmt = $db->query(
        'SELECT first_name, last_name, email, mobile, sentinel_number, consent, submitted_at
         FROM consent_submissions
         ORDER BY submitted_at DESC'
    );

    $rows = $stmt->fetchAll();

    $filename = 'consent_submissions_' . date('Y-m-d') . '.csv';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, no-store, must-revalidate');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Date', 'First Name', 'Last Name', 'Email', 'Mobile', 'Sentinel Number', 'Consent']);

    foreach ($rows as $row) {
        $date = new DateTime($row['submitted_at']);
        fputcsv($output, [
            $date->format('d/m/Y'),
            $row['first_name'],
            $row['last_name'],
            $row['email'],
            $row['mobile'],
            $row['sentinel_number'],
            $row['consent'] ? 'TRUE' : 'FALSE',
        ]);
    }

    fclose($output);
} catch (PDOException $e) {
    http_response_code(500);
    echo 'Failed to export submissions';
}
