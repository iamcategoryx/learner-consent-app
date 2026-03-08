<?php

require_once __DIR__ . '/config.php';

function sendConsentConfirmationEmail(
    string $toEmail,
    string $firstName,
    string $lastName,
    string $sentinelNumber,
    string $sendgridApiKey,
    string $fromEmail
): bool {
    $subject = 'Consent Confirmation - Absolute Training & Assessing Ltd';

    $html = '
    <div style="font-family: \'Inter\', Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #f8f9fb; padding: 32px 16px;">
        <div style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.08); overflow: hidden;">
            <div style="background: #0284c7; padding: 32px; text-align: center;">
                <h1 style="color: #ffffff; font-size: 24px; margin: 0;">Thank You, ' . htmlspecialchars($firstName) . '!</h1>
                <p style="color: rgba(255,255,255,0.9); font-size: 14px; margin: 8px 0 0;">Your consent has been recorded</p>
            </div>
            <div style="padding: 32px;">
                <p style="color: #2c4a6e; font-size: 15px; line-height: 1.6; margin: 0 0 16px;">
                    Dear ' . htmlspecialchars($firstName) . ' ' . htmlspecialchars($lastName) . ',
                </p>
                <p style="color: #2c4a6e; font-size: 15px; line-height: 1.6; margin: 0 0 16px;">
                    We have successfully recorded your marketing consent. Here is a summary of what you agreed to:
                </p>
                <div style="background: #f0f7ff; border: 1px solid #d0e3f7; border-radius: 8px; padding: 20px; margin: 0 0 20px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 6px 0; color: #5a7a9e; font-size: 14px; width: 140px;">Name:</td>
                            <td style="padding: 6px 0; color: #2c4a6e; font-size: 14px; font-weight: 600;">' . htmlspecialchars($firstName) . ' ' . htmlspecialchars($lastName) . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; color: #5a7a9e; font-size: 14px;">Sentinel Number:</td>
                            <td style="padding: 6px 0; color: #2c4a6e; font-size: 14px; font-weight: 600;">' . htmlspecialchars($sentinelNumber) . '</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; color: #5a7a9e; font-size: 14px;">Consent:</td>
                            <td style="padding: 6px 0; color: #16a34a; font-size: 14px; font-weight: 600;">Granted</td>
                        </tr>
                    </table>
                </div>
                <p style="color: #2c4a6e; font-size: 15px; line-height: 1.6; margin: 0 0 16px;">
                    <strong>What happens next?</strong><br>
                    We will contact you approximately 2 years from now to remind you about your recertification. You may receive:
                </p>
                <ul style="color: #2c4a6e; font-size: 15px; line-height: 1.8; margin: 0 0 20px; padding-left: 20px;">
                    <li>SMS reminders about upcoming recertification dates</li>
                    <li>Email updates about training availability and course offers</li>
                    <li>Information about assessment booking</li>
                </ul>
                <div style="background: #f8f9fb; border-radius: 8px; padding: 16px; margin: 0 0 20px;">
                    <p style="color: #5a7a9e; font-size: 13px; line-height: 1.6; margin: 0;">
                        <strong style="color: #2c4a6e;">Your Privacy:</strong> Your data is stored securely in line with GDPR and will never be shared with third parties. You can withdraw your consent at any time by contacting us.
                    </p>
                </div>
                <p style="color: #2c4a6e; font-size: 15px; line-height: 1.6; margin: 0;">
                    If you have any questions, please don\'t hesitate to contact us.
                </p>
            </div>
            <div style="background: #f8f9fb; padding: 24px 32px; text-align: center; border-top: 1px solid #e8ecf1;">
                <p style="color: #5a7a9e; font-size: 13px; margin: 0 0 4px;">
                    <a href="mailto:info@absoluterail.co.uk" style="color: #0284c7; text-decoration: none;">info@absoluterail.co.uk</a>
                </p>
                <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                    &copy; 2025 Absolute Training &amp; Assessing Ltd. All rights reserved.
                </p>
            </div>
        </div>
    </div>';

    $payload = [
        'personalizations' => [
            [
                'to' => [['email' => $toEmail]],
                'subject' => $subject,
            ]
        ],
        'from' => [
            'email' => $fromEmail,
            'name'  => SITE_NAME,
        ],
        'content' => [
            [
                'type'  => 'text/html',
                'value' => $html,
            ]
        ],
    ];

    $ch = curl_init('https://api.sendgrid.com/v3/mail/send');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $sendgridApiKey,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode >= 200 && $httpCode < 300;
}
