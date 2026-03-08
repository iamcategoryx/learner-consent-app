<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $db = getDb();

    $stmt = $db->query(
        'SELECT id, first_name, last_name, email, mobile, sentinel_number, consent, submitted_at
         FROM consent_submissions
         ORDER BY submitted_at DESC'
    );

    $rows = $stmt->fetchAll();

    $formatted = array_map(function ($row) {
        $date = new DateTime($row['submitted_at']);
        return [
            'id'             => (int)$row['id'],
            'date'           => $date->format('d/m/Y'),
            'firstName'      => $row['first_name'],
            'lastName'       => $row['last_name'],
            'email'          => $row['email'],
            'mobile'         => $row['mobile'],
            'sentinelNumber' => $row['sentinel_number'],
            'consent'        => (bool)$row['consent'],
        ];
    }, $rows);

    echo json_encode([
        'success' => true,
        'count'   => count($formatted),
        'data'    => $formatted,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to read submissions']);
}
