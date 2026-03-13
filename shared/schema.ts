import { sql } from "drizzle-orm";
import { pgTable, text, varchar, boolean, timestamp } from "drizzle-orm/pg-core";
import { createInsertSchema } from "drizzle-zod";
import { z } from "zod";

export const users = pgTable("users", {
  id: varchar("id").primaryKey().default(sql`gen_random_uuid()`),
  username: text("username").notNull().unique(),
  password: text("password").notNull(),
});

export const consentSubmissions = pgTable("consent_submissions", {
  id: varchar("id").primaryKey().default(sql`gen_random_uuid()`),
  firstName: text("first_name").notNull(),
  lastName: text("last_name").notNull(),
  email: text("email").notNull(),
  mobile: text("mobile").notNull(),
  sentinelNumber: text("sentinel_number").notNull(),
  consent: boolean("consent").notNull().default(true),
  submittedAt: timestamp("submitted_at").notNull().defaultNow(),
});

export const insertUserSchema = createInsertSchema(users).pick({
  username: true,
  password: true,
});

export const insertConsentSubmissionSchema = createInsertSchema(consentSubmissions).omit({
  id: true,
  submittedAt: true,
}).extend({
  firstName: z.string().min(1, "First name is required"),
  lastName: z.string().min(1, "Last name is required"),
  email: z.string().email("Please enter a valid email address"),
  mobile: z.string().regex(/^07\d{9}$/, "Please enter a valid UK mobile number (e.g., 07825633999)"),
  sentinelNumber: z.string().min(1, "Sentinel Number is required"),
  consent: z.boolean(),
});

export type InsertUser = z.infer<typeof insertUserSchema>;
export type User = typeof users.$inferSelect;
export type ConsentSubmission = typeof consentSubmissions.$inferSelect;
export type InsertConsentSubmission = z.infer<typeof insertConsentSubmissionSchema>;
