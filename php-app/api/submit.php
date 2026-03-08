<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/email.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    $input = $_POST;
}

$firstName      = trim($input['firstName'] ?? '');
$lastName       = trim($input['lastName'] ?? '');
$email          = trim($input['email'] ?? '');
$mobile         = trim($input['mobile'] ?? '');
$sentinelNumber = trim($input['sentinelNumber'] ?? '');
$consent        = !empty($input['consent']);

$errors = [];

if ($firstName === '') {
    $errors[] = ['field' => 'firstName', 'message' => 'First name is required'];
}

if ($lastName === '') {
    $errors[] = ['field' => 'lastName', 'message' => 'Last name is required'];
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = ['field' => 'email', 'message' => 'Please enter a valid email address'];
}

if (!preg_match('/^07\d{9}$/', $mobile)) {
    $errors[] = ['field' => 'mobile', 'message' => 'Please enter a valid UK mobile number (e.g., 07825633999)'];
}

if ($sentinelNumber === '') {
    $errors[] = ['field' => 'sentinelNumber', 'message' => 'Sentinel Number is required'];
}

if (!$consent) {
    $errors[] = ['field' => 'consent', 'message' => 'You must provide consent to continue'];
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Validation failed', 'errors' => $errors]);
    exit;
}

try {
    $db = getDb();

    $stmt = $db->prepare(
        'INSERT INTO consent_submissions (first_name, last_name, email, mobile, sentinel_number, consent, submitted_at)
         VALUES (:first_name, :last_name, :email, :mobile, :sentinel_number, :consent, NOW())'
    );

    $stmt->execute([
        ':first_name'      => $firstName,
        ':last_name'       => $lastName,
        ':email'           => $email,
        ':mobile'          => $mobile,
        ':sentinel_number' => $sentinelNumber,
        ':consent'         => $consent ? 1 : 0,
    ]);

    $id = $db->lastInsertId();

    if (SENDGRID_API_KEY !== '' && SENDGRID_FROM_EMAIL !== '') {
        try {
            sendConsentConfirmationEmail(
                $email,
                $firstName,
                $lastName,
                $sentinelNumber,
                SENDGRID_API_KEY,
                SENDGRID_FROM_EMAIL
            );
        } catch (Exception $emailErr) {
            error_log('Failed to send confirmation email: ' . $emailErr->getMessage());
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Consent submitted successfully',
        'id'      => (int)$id,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to submit consent']);
}
