import { type User, type InsertUser, type ConsentSubmission, type InsertConsentSubmission } from "@shared/schema";
import { randomUUID } from "crypto";

export interface IStorage {
  getUser(id: string): Promise<User | undefined>;
  getUserByUsername(username: string): Promise<User | undefined>;
  createUser(user: InsertUser): Promise<User>;
  createConsentSubmission(submission: InsertConsentSubmission): Promise<ConsentSubmission>;
  getAllConsentSubmissions(): Promise<ConsentSubmission[]>;
}

export class MemStorage implements IStorage {
  private users: Map<string, User>;
  private consentSubmissions: Map<string, ConsentSubmission>;

  constructor() {
    this.users = new Map();
    this.consentSubmissions = new Map();
  }

  async getUser(id: string): Promise<User | undefined> {
    return this.users.get(id);
  }

  async getUserByUsername(username: string): Promise<User | undefined> {
    return Array.from(this.users.values()).find(
      (user) => user.username === username,
    );
  }

  async createUser(insertUser: InsertUser): Promise<User> {
    const id = randomUUID();
    const user: User = { ...insertUser, id };
    this.users.set(id, user);
    return user;
  }

  async createConsentSubmission(insertSubmission: InsertConsentSubmission): Promise<ConsentSubmission> {
    const id = randomUUID();
    const submission: ConsentSubmission = {
      id,
      firstName: insertSubmission.firstName,
      lastName: insertSubmission.lastName,
      email: insertSubmission.email,
      mobile: insertSubmission.mobile,
      sentinelNumber: insertSubmission.sentinelNumber,
      consent: insertSubmission.consent,
      submittedAt: new Date(),
    };
    this.consentSubmissions.set(id, submission);
    return submission;
  }

  async getAllConsentSubmissions(): Promise<ConsentSubmission[]> {
    return Array.from(this.consentSubmissions.values());
  }
}

export const storage = new MemStorage();
