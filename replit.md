# Overview

This is a full-stack web application built with React (frontend) and Express (backend) that collects learner consent submissions through a form interface. The application stores submissions both in-memory and syncs them to Google Sheets for persistent storage. It uses a modern TypeScript stack with shadcn/ui components for the interface and Drizzle ORM for database schema management.

# User Preferences

Preferred communication style: Simple, everyday language.

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

**Design Rationale**: The frontend architecture prioritizes developer experience with TypeScript, type-safe forms, and reusable UI components. The lightweight routing and efficient caching strategies improve performance while maintaining simplicity.

## Backend Architecture

**Server Framework**: Express.js with TypeScript, running in ESM module mode. The server handles API routes and serves the built frontend in production.

**Storage Strategy**: Dual-storage approach using in-memory storage for fast access and Google Sheets for persistent, shareable data storage. The `MemStorage` class implements an `IStorage` interface, allowing for easy migration to a database-backed implementation later.

**API Design**: RESTful API with a single POST endpoint (`/api/consent`) for consent form submissions. Request validation uses Zod schemas shared between frontend and backend.

**Development Setup**: Custom Vite middleware integration for hot module replacement during development. Production builds bundle the server separately using esbuild.

**Design Rationale**: The in-memory + Google Sheets approach was chosen to avoid database setup complexity while still providing data persistence. The interface-based storage design allows seamless migration to PostgreSQL when needed without changing business logic.

## Data Schema

**ORM**: Drizzle ORM configured for PostgreSQL dialect. Schema definitions are shared between client and server through a `shared/schema.ts` file.

**Tables**:
- `users`: Basic user authentication (id, username, password)
- `consent_submissions`: Learner consent records (id, firstName, lastName, email, mobile, sentinelNumber, consent boolean, timestamp)

**Validation**: Zod schemas generated from Drizzle schemas using `drizzle-zod`, ensuring consistency between database schema and runtime validation. Custom refinements add business logic validation (email format, phone format, consent required).

**Design Rationale**: While the database isn't currently active, the schema is defined and ready for future use. The shared schema approach prevents type drift between frontend and backend.

## External Dependencies

**Google Sheets Integration**: Custom client (`googleSheetClient.ts`) that handles OAuth token management and API calls. Uses the official `googleapis` package. Access tokens are retrieved from Replit Connectors and automatically refreshed when expired. The spreadsheet ID is persisted to a `.spreadsheet_id` file to prevent duplicate spreadsheet creation across server restarts. All dates are formatted in UK format (DD/MM/YYYY) when written to Google Sheets.

**Session Management**: `connect-pg-simple` is included for PostgreSQL-backed session storage (currently unused but ready for implementation).

**Replit-Specific Features**: 
- Replit Connectors for Google Sheets OAuth
- Vite plugins for error overlays, code cartography, and dev banners
- Environment-based token authentication for connector access

**Font Loading**: Google Fonts (Inter, Architects Daughter, DM Sans, Fira Code, Geist Mono) loaded from CDN.

**Design Rationale**: The Google Sheets integration allows non-technical users to access and analyze data using familiar spreadsheet tools. Replit Connectors simplify OAuth setup without requiring credential management.