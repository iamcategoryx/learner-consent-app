import { google } from 'googleapis';
import { readFileSync, writeFileSync, existsSync } from 'fs';
import { join } from 'path';

let connectionSettings: any;
let cachedSpreadsheetId: string | null = null;

const SPREADSHEET_ID_FILE = join(process.cwd(), '.spreadsheet_id');

async function getAccessToken() {
  if (connectionSettings && connectionSettings.settings.expires_at && new Date(connectionSettings.settings.expires_at).getTime() > Date.now()) {
    return connectionSettings.settings.access_token;
  }
  
  const hostname = process.env.REPLIT_CONNECTORS_HOSTNAME
  const xReplitToken = process.env.REPL_IDENTITY 
    ? 'repl ' + process.env.REPL_IDENTITY 
    : process.env.WEB_REPL_RENEWAL 
    ? 'depl ' + process.env.WEB_REPL_RENEWAL 
    : null;

  if (!xReplitToken) {
    throw new Error('X_REPLIT_TOKEN not found for repl/depl');
  }

  connectionSettings = await fetch(
    'https://' + hostname + '/api/v2/connection?include_secrets=true&connector_names=google-sheet',
    {
      headers: {
        'Accept': 'application/json',
        'X_REPLIT_TOKEN': xReplitToken
      }
    }
  ).then(res => res.json()).then(data => data.items?.[0]);

  const accessToken = connectionSettings?.settings?.access_token || connectionSettings.settings?.oauth?.credentials?.access_token;

  if (!connectionSettings || !accessToken) {
    throw new Error('Google Sheet not connected');
  }
  return accessToken;
}

// WARNING: Never cache this client.
// Access tokens expire, so a new client must be created each time.
// Always call this function again to get a fresh client.
export async function getUncachableGoogleSheetClient() {
  const accessToken = await getAccessToken();

  const oauth2Client = new google.auth.OAuth2();
  oauth2Client.setCredentials({
    access_token: accessToken
  });

  return google.sheets({ version: 'v4', auth: oauth2Client });
}

export async function getSpreadsheetId(): Promise<string> {
  if (cachedSpreadsheetId) {
    return cachedSpreadsheetId;
  }
  
  if (existsSync(SPREADSHEET_ID_FILE)) {
    const savedId = readFileSync(SPREADSHEET_ID_FILE, 'utf-8').trim();
    if (savedId) {
      cachedSpreadsheetId = savedId;
      console.log(`Using saved spreadsheet ID: ${savedId}`);
      return savedId;
    }
  }
  
  await getAccessToken();
  
  let spreadsheetId = connectionSettings?.settings?.spreadsheet_id;
  
  if (!spreadsheetId) {
    const sheets = await getUncachableGoogleSheetClient();
    const response = await sheets.spreadsheets.create({
      requestBody: {
        properties: {
          title: 'Learner Consent Submissions'
        }
      }
    });
    spreadsheetId = response.data.spreadsheetId!;
    console.log(`Created new Google Spreadsheet: ${spreadsheetId}`);
  }
  
  writeFileSync(SPREADSHEET_ID_FILE, spreadsheetId, 'utf-8');
  console.log(`Saved spreadsheet ID to file: ${spreadsheetId}`);
  
  cachedSpreadsheetId = spreadsheetId;
  return spreadsheetId;
}
