import type { Express } from "express";
import { createServer, type Server } from "http";
import { storage } from "./storage";
import { insertConsentSubmissionSchema } from "@shared/schema";
import { getUncachableGoogleSheetClient, getSpreadsheetId } from "./googleSheetClient";

export async function registerRoutes(app: Express): Promise<Server> {
  // Submit consent form
  app.post("/api/consent", async (req, res) => {
    try {
      // Validate request body
      const validatedData = insertConsentSubmissionSchema.parse(req.body);
      
      // Store in memory
      const submission = await storage.createConsentSubmission(validatedData);
      
      // Save to Google Sheets
      try {
        const sheets = await getUncachableGoogleSheetClient();
        const spreadsheetId = await getSpreadsheetId();
        
        // Always update headers to ensure correct format (Date not Timestamp)
        await sheets.spreadsheets.values.update({
          spreadsheetId,
          range: 'A1:G1',
          valueInputOption: 'RAW',
          requestBody: {
            values: [['Date', 'First Name', 'Last Name', 'Email', 'Mobile', 'Sentinel Number', 'Consent']]
          }
        });
        
        // Format date as UK format (DD/MM/YYYY)
        const date = submission.submittedAt;
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const ukDate = `${day}/${month}/${year}`;
        
        // Append the new submission
        await sheets.spreadsheets.values.append({
          spreadsheetId,
          range: 'A:G',
          valueInputOption: 'RAW',
          requestBody: {
            values: [[
              ukDate,
              submission.firstName,
              submission.lastName,
              submission.email,
              submission.mobile,
              submission.sentinelNumber,
              submission.consent.toString()
            ]]
          }
        });
        
        console.log(`Consent submission saved to Google Sheets: ${spreadsheetId}`);
      } catch (sheetsError) {
        console.error('Failed to save to Google Sheets:', sheetsError);
        // Continue even if Google Sheets fails, we have it in memory
      }
      
      res.json({ 
        success: true, 
        message: "Consent submitted successfully",
        id: submission.id 
      });
    } catch (error: any) {
      console.error('Consent submission error:', error);
      
      if (error.name === 'ZodError') {
        return res.status(400).json({ 
          success: false,
          message: "Validation failed",
          errors: error.errors 
        });
      }
      
      res.status(500).json({ 
        success: false,
        message: "Failed to submit consent" 
      });
    }
  });

  // Get all submissions from Google Sheets
  app.get("/api/consent/list", async (req, res) => {
    try {
      const sheets = await getUncachableGoogleSheetClient();
      const spreadsheetId = await getSpreadsheetId();
      
      const response = await sheets.spreadsheets.values.get({
        spreadsheetId,
        range: 'A:G'
      });
      
      const rows = response.data.values || [];
      
      // Log raw data to console for debugging
      console.log('Google Sheet raw data:', JSON.stringify(rows, null, 2));
      
      res.json({
        success: true,
        spreadsheetId,
        rows,
        count: Math.max(0, rows.length - 1) // Subtract header row
      });
    } catch (error: any) {
      console.error('Failed to read from Google Sheets:', error);
      res.status(500).json({
        success: false,
        message: "Failed to read submissions"
      });
    }
  });

  const httpServer = createServer(app);
  return httpServer;
}
