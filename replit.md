# Overview

This is a full-stack web application built with React (frontend) and Express (backend) that collects learner consent submissions through a form interface. The application stores submissions both in-memory and syncs them to Google Sheets for persistent storage. It uses a modern TypeScript stack with shadcn/ui components for the interface and Drizzle ORM for database schema management.

A **PHP 8.3 + MySQL version** is also available in the `php-app/` folder for deployment on traditional hosting.

# User Preferences

Preferred communication style: Simple, everyday language.
All changes must be made in both the Node.js/React version AND the PHP 8.3 version (`php-app/`).
All changes must be pushed to the GitHub repo (`https://github.com/iamcategoryx/learner-consent-app`) after each update.

# System Architecture

## Frontend Architecture

**Framework & Build Tool**: React with Vite for development and production builds. The frontend uses TypeScript with JSX preservation and follows a component-based architecture.

**UI Component Library**: shadcn/ui (New York style) with Radix UI primitives. This provides accessible, customizable components with consistent styling through CSS variables and Tailwind CSS.

**Routing**: Wouter for client-side routing, chosen for its lightweight footprint compared to React Router.

**State Management**: 
- TanStack Query (React Query) for server state management and data fetching
- React Hook Form with Zod validation for form state and validation
- Local component state with React hooks

**Styling**: Tailwind CSS with a custom configuration using CSS variables for theming. Supports both light and dark modes through class-based theme switching.

## Backend Architecture

**Server Framework**: Express.js with TypeScript, running in ESM module mode. The server handles API routes and serves the built frontend in production.

**Storage Strategy**: Dual-storage approach using in-memory storage for fast access and Google Sheets for persistent, shareable data storage. The `MemStorage` class implements an `IStorage` interface, allowing for easy migration to a database-backed implementation later.

**API Design**: RESTful API with POST endpoint (`/api/consent`) for consent form submissions and GET endpoint (`/api/consent/list`) for listing submissions. Request validation uses Zod schemas shared between frontend and backend.

## Data Schema

**Tables**:
- `consent_submissions`: Learner consent records (id, firstName, lastName, email, mobile, sentinelNumber, consent boolean, timestamp)

**Validation**: Zod schemas generated from Drizzle schemas using `drizzle-zod`, ensuring consistency between database schema and runtime validation. Custom refinements add business logic validation (email format, UK phone format 07xxxxxxxxx, consent required).

## External Dependencies

**Google Sheets Integration**: Custom client (`server/googleSheetClient.ts`) that handles OAuth token management and API calls. Uses the official `googleapis` package. Access tokens are retrieved from Replit Connectors and automatically refreshed when expired. The spreadsheet ID is persisted to a `.spreadsheet_id` file to prevent duplicate spreadsheet creation across server restarts. All dates are formatted in UK format (DD/MM/YYYY) when written to Google Sheets.

**SendGrid Integration**: Custom client (`server/sendgridClient.ts`) for sending confirmation emails to learners after they submit consent. Uses the Replit Connectors SDK for API key management. Sends a branded HTML email with consent summary, privacy notice, and next steps information.

**GitHub Integration**: Connected via Replit Connectors for pushing code to the repository at `https://github.com/iamcategoryx/learner-consent-app`.

## PHP 8.3 + MySQL Version

A standalone PHP version lives in `php-app/` with the following structure:
- `index.php` - Consent form (main page)
- `submissions.php` - View all submissions
- `database.sql` - MySQL schema export (run first to create database)
- `includes/config.php` - Database credentials, SendGrid API key, site settings
- `includes/db.php` - MySQL PDO connection
- `includes/email.php` - SendGrid email sending via cURL
- `api/submit.php` - Handles form POST submissions
- `api/list.php` - Returns submissions as JSON
- `css/style.css` - All styling
- `.htaccess` - Apache URL rewriting

**Note**: The PHP version uses MySQL for primary data storage. Google Sheets integration is available via a Google Service Account - place the `google-credentials.json` key file in the `php-app/` root and set `GOOGLE_SPREADSHEET_ID` in `includes/config.php`. SendGrid credentials must also be set manually in `includes/config.php`.

## Company Details

- **Company**: Absolute Training & Assessing Ltd
- **Email**: info@absoluterail.co.uk
- **Year**: 2025
