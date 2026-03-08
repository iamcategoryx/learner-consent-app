import sgMail from '@sendgrid/mail';

let connectionSettings: any;

async function getCredentials() {
  const hostname = process.env.REPLIT_CONNECTORS_HOSTNAME;
  const xReplitToken = process.env.REPL_IDENTITY
    ? 'repl ' + process.env.REPL_IDENTITY
    : process.env.WEB_REPL_RENEWAL
    ? 'depl ' + process.env.WEB_REPL_RENEWAL
    : null;

  if (!xReplitToken) {
    throw new Error('X-Replit-Token not found for repl/depl');
  }

  connectionSettings = await fetch(
    'https://' + hostname + '/api/v2/connection?include_secrets=true&connector_names=sendgrid',
    {
      headers: {
        'Accept': 'application/json',
        'X-Replit-Token': xReplitToken
      }
    }
  ).then(res => res.json()).then(data => data.items?.[0]);

  if (!connectionSettings || (!connectionSettings.settings.api_key || !connectionSettings.settings.from_email)) {
    throw new Error('SendGrid not connected');
  }
  return { apiKey: connectionSettings.settings.api_key, email: connectionSettings.settings.from_email };
}

export async function getUncachableSendGridClient() {
  const { apiKey, email } = await getCredentials();
  sgMail.setApiKey(apiKey);
  return {
    client: sgMail,
    fromEmail: email
  };
}

export async function sendConsentConfirmationEmail(
  toEmail: string,
  firstName: string,
  lastName: string,
  sentinelNumber: string
) {
  const { client, fromEmail } = await getUncachableSendGridClient();

  const msg = {
    to: toEmail,
    from: {
      email: fromEmail,
      name: 'Absolute Training & Assessing Ltd'
    },
    subject: 'Consent Confirmation - Absolute Training & Assessing Ltd',
    html: `
      <div style="font-family: 'Inter', Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #f8f9fb; padding: 32px 16px;">
        <div style="background: #ffffff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.08); overflow: hidden;">
          <div style="background: #0284c7; padding: 32px; text-align: center;">
            <h1 style="color: #ffffff; font-size: 24px; margin: 0;">Thank You, ${firstName}!</h1>
            <p style="color: rgba(255,255,255,0.9); font-size: 14px; margin: 8px 0 0;">Your consent has been recorded</p>
          </div>
          <div style="padding: 32px;">
            <p style="color: #2c4a6e; font-size: 15px; line-height: 1.6; margin: 0 0 16px;">
              Dear ${firstName} ${lastName},
            </p>
            <p style="color: #2c4a6e; font-size: 15px; line-height: 1.6; margin: 0 0 16px;">
              We have successfully recorded your marketing consent. Here is a summary of what you agreed to:
            </p>
            <div style="background: #f0f7ff; border: 1px solid #d0e3f7; border-radius: 8px; padding: 20px; margin: 0 0 20px;">
              <table style="width: 100%; border-collapse: collapse;">
                <tr>
                  <td style="padding: 6px 0; color: #5a7a9e; font-size: 14px; width: 140px;">Name:</td>
                  <td style="padding: 6px 0; color: #2c4a6e; font-size: 14px; font-weight: 600;">${firstName} ${lastName}</td>
                </tr>
                <tr>
                  <td style="padding: 6px 0; color: #5a7a9e; font-size: 14px;">Sentinel Number:</td>
                  <td style="padding: 6px 0; color: #2c4a6e; font-size: 14px; font-weight: 600;">${sentinelNumber}</td>
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
              If you have any questions, please don't hesitate to contact us.
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
      </div>
    `
  };

  await client.send(msg);
  console.log(`Confirmation email sent to ${toEmail}`);
}
