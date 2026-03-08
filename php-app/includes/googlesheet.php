<?php

require_once __DIR__ . '/config.php';

function getGoogleAccessToken(): ?string
{
    $keyFile = GOOGLE_SERVICE_ACCOUNT_KEY_FILE;

    if (!file_exists($keyFile)) {
        error_log('Google service account key file not found: ' . $keyFile);
        return null;
    }

    $serviceAccount = json_decode(file_get_contents($keyFile), true);

    if (!$serviceAccount || empty($serviceAccount['client_email']) || empty($serviceAccount['private_key'])) {
        error_log('Invalid Google service account key file');
        return null;
    }

    $header = base64url_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));

    $now = time();
    $claimSet = base64url_encode(json_encode([
        'iss'   => $serviceAccount['client_email'],
        'scope' => 'https://www.googleapis.com/auth/spreadsheets',
        'aud'   => 'https://oauth2.googleapis.com/token',
        'exp'   => $now + 3600,
        'iat'   => $now,
    ]));

    $signatureInput = $header . '.' . $claimSet;

    openssl_sign($signatureInput, $signature, $serviceAccount['private_key'], 'SHA256');
    $jwt = $signatureInput . '.' . base64url_encode($signature);

    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        error_log('Failed to get Google access token: ' . $response);
        return null;
    }

    $data = json_decode($response, true);
    return $data['access_token'] ?? null;
}

function base64url_encode(string $data): string
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function appendToGoogleSheet(
    string $firstName,
    string $lastName,
    string $email,
    string $mobile,
    string $sentinelNumber,
    bool $consent
): bool {
    $spreadsheetId = GOOGLE_SPREADSHEET_ID;

    if (empty($spreadsheetId)) {
        error_log('Google Spreadsheet ID not configured');
        return false;
    }

    $accessToken = getGoogleAccessToken();

    if (!$accessToken) {
        return false;
    }

    $date = date('d/m/Y');

    $headers = [['Date', 'First Name', 'Last Name', 'Email', 'Mobile', 'Sentinel Number', 'Consent']];
    $headerUrl = sprintf(
        'https://sheets.googleapis.com/v4/spreadsheets/%s/values/A1:G1?valueInputOption=RAW',
        urlencode($spreadsheetId)
    );

    $ch = curl_init($headerUrl);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST  => 'PUT',
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS     => json_encode(['values' => $headers]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
    ]);
    curl_exec($ch);
    curl_close($ch);

    $rowData = [[$date, $firstName, $lastName, $email, $mobile, $sentinelNumber, $consent ? 'true' : 'false']];
    $appendUrl = sprintf(
        'https://sheets.googleapis.com/v4/spreadsheets/%s/values/A:G:append?valueInputOption=RAW&insertDataOption=INSERT_ROWS',
        urlencode($spreadsheetId)
    );

    $ch = curl_init($appendUrl);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS     => json_encode(['values' => $rowData]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
        error_log('Consent submission saved to Google Sheets');
        return true;
    }

    error_log('Failed to append to Google Sheets: ' . $response);
    return false;
}
