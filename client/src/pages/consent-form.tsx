import { useState } from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { useMutation } from "@tanstack/react-query";
import { insertConsentSubmissionSchema, type InsertConsentSubmission } from "@shared/schema";
import { apiRequest } from "@/lib/queryClient";
import { useToast } from "@/hooks/use-toast";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Checkbox } from "@/components/ui/checkbox";
import { Card, CardContent } from "@/components/ui/card";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from "@/components/ui/dialog";
import { Shield, UserCheck, CheckCircle, TriangleAlert } from "lucide-react";

export default function ConsentForm() {
  const [showSuccessModal, setShowSuccessModal] = useState(false);
  const { toast } = useToast();

  const form = useForm<InsertConsentSubmission>({
    resolver: zodResolver(insertConsentSubmissionSchema),
    defaultValues: {
      firstName: "",
      lastName: "",
      email: "",
      mobile: "",
      sentinelNumber: "",
      consent: false,
    },
  });

  const submitConsentMutation = useMutation({
    mutationFn: async (data: InsertConsentSubmission) => {
      const response = await apiRequest("POST", "/api/consent", data);
      return response.json();
    },
    onSuccess: () => {
      setShowSuccessModal(true);
      form.reset();
    },
    onError: (error: any) => {
      console.error("Submission error:", error);
      toast({
        title: "Submission Failed",
        description: "There was an error submitting your consent. Please try again.",
        variant: "destructive",
      });
    },
  });

  const onSubmit = (data: InsertConsentSubmission) => {
    submitConsentMutation.mutate(data);
  };

  return (
    <div className="min-h-screen py-8 px-4 bg-background">
      <div className="max-w-lg mx-auto">
        {/* Header Section */}
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center w-16 h-16 bg-primary rounded-full mb-4">
            <UserCheck className="text-primary-foreground w-8 h-8" />
          </div>
          <h1 className="text-3xl font-bold text-foreground mb-2">Learner Consent Form</h1>
          <p className="text-muted-foreground">Help us keep you informed about recertification opportunities</p>
        </div>

        {/* Main Form Card */}
        <Card className="form-card">
          <CardContent className="p-8">
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
              {/* Marketing Message */}
              <div className="bg-primary/5 p-4 rounded-lg border border-primary/20">
                <h2 className="text-lg font-semibold text-foreground mb-2">Stay Informed with Confidence</h2>
                <p className="text-sm text-muted-foreground leading-relaxed">
                  By providing your email address and mobile number, you agree to receive updates about upcoming recertifications, assessment availability, and exclusive course offers from Absolute Training & Assessing Ltd. Your information will be kept secure in line with GDPR and will never be shared with third parties. You can opt out at any time by contacting us.
                </p>
              </div>

              {/* First Name Input */}
              <div className="space-y-2">
                <Label htmlFor="firstName">First Name *</Label>
                <Input
                  {...form.register("firstName")}
                  type="text"
                  id="firstName"
                  placeholder="Enter your first name"
                  data-testid="input-firstname"
                />
                {form.formState.errors.firstName && (
                  <div className="error-message">
                    <TriangleAlert className="w-4 h-4" />
                    <span>{form.formState.errors.firstName.message}</span>
                  </div>
                )}
              </div>

              {/* Last Name Input */}
              <div className="space-y-2">
                <Label htmlFor="lastName">Last Name *</Label>
                <Input
                  {...form.register("lastName")}
                  type="text"
                  id="lastName"
                  placeholder="Enter your last name"
                  data-testid="input-lastname"
                />
                {form.formState.errors.lastName && (
                  <div className="error-message">
                    <TriangleAlert className="w-4 h-4" />
                    <span>{form.formState.errors.lastName.message}</span>
                  </div>
                )}
              </div>

              {/* Email Input */}
              <div className="space-y-2">
                <Label htmlFor="email">Email Address *</Label>
                <Input
                  {...form.register("email")}
                  type="email"
                  id="email"
                  placeholder="your.email@example.com"
                  data-testid="input-email"
                />
                {form.formState.errors.email && (
                  <div className="error-message">
                    <TriangleAlert className="w-4 h-4" />
                    <span>{form.formState.errors.email.message}</span>
                  </div>
                )}
                <p className="helper-text">We'll use this to send you recertification reminders</p>
              </div>

              {/* Mobile Number Input */}
              <div className="space-y-2">
                <Label htmlFor="mobile">Mobile Number *</Label>
                <Input
                  {...form.register("mobile")}
                  type="tel"
                  id="mobile"
                  placeholder="07825633999"
                  data-testid="input-mobile"
                />
                {form.formState.errors.mobile && (
                  <div className="error-message">
                    <TriangleAlert className="w-4 h-4" />
                    <span>{form.formState.errors.mobile.message}</span>
                  </div>
                )}
                <p className="helper-text">Format: 07825633999</p>
              </div>

              {/* Sentinel Number Input */}
              <div className="space-y-2">
                <Label htmlFor="sentinelNumber">Sentinel Number *</Label>
                <Input
                  {...form.register("sentinelNumber")}
                  type="text"
                  id="sentinelNumber"
                  placeholder="Enter your Sentinel Number"
                  data-testid="input-sentinel"
                />
                {form.formState.errors.sentinelNumber && (
                  <div className="error-message">
                    <TriangleAlert className="w-4 h-4" />
                    <span>{form.formState.errors.sentinelNumber.message}</span>
                  </div>
                )}
              </div>

              {/* Marketing Consent Checkbox */}
              <div className="checkbox-group">
                <div className="flex items-start space-x-3">
                  <Checkbox
                    {...form.register("consent")}
                    id="consent"
                    checked={form.watch("consent")}
                    onCheckedChange={(checked) => form.setValue("consent", !!checked)}
                    data-testid="checkbox-consent"
                  />
                  <Label htmlFor="consent" className="text-sm cursor-pointer leading-relaxed">
                    <strong>I consent to receive marketing communications</strong><br />
                    I agree to receive SMS messages and emails from your organisation regarding course recertification reminders, training updates, and related marketing communications. I understand that I will be contacted approximately 2 years from now to book my recertification event. I can withdraw my consent at any time by contacting you directly.
                  </Label>
                </div>
                {form.formState.errors.consent && (
                  <div className="error-message mt-2">
                    <TriangleAlert className="w-4 h-4" />
                    <span>{form.formState.errors.consent.message}</span>
                  </div>
                )}
              </div>

              {/* Privacy Notice */}
              <div className="bg-accent p-4 rounded-lg text-sm">
                <div className="flex gap-3">
                  <Shield className="text-primary w-5 h-5 mt-0.5 flex-shrink-0" />
                  <div>
                    <strong className="text-accent-foreground">Your Privacy Matters</strong>
                    <p className="text-muted-foreground mt-1">
                      Your data will be stored securely and used solely for the purpose of sending recertification reminders. We will never share your information with third parties. For more details, please review our privacy policy.
                    </p>
                  </div>
                </div>
              </div>

              {/* Submit Button */}
              <Button 
                type="submit" 
                className="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all"
                disabled={submitConsentMutation.isPending}
                data-testid="button-submit"
              >
                {submitConsentMutation.isPending ? (
                  <>
                    <div className="spinner" />
                    <span className="ml-2">Submitting...</span>
                  </>
                ) : (
                  "Submit Consent"
                )}
              </Button>

              {/* Required Fields Note */}
              <p className="text-center text-muted-foreground text-sm">
                * Required fields
              </p>
            </form>
          </CardContent>
        </Card>

        {/* Footer Information */}
        <div className="text-center mt-8 text-sm text-muted-foreground">
          <p>Questions? Contact us at <a href="mailto:info@absoluterail.co.uk" className="text-primary hover:underline">info@absoluterail.co.uk</a></p>
          <p className="mt-2">© 2025 Absolute Training & Assessing Ltd. All rights reserved.</p>
        </div>
      </div>

      {/* Success Modal */}
      <Dialog open={showSuccessModal} onOpenChange={setShowSuccessModal}>
        <DialogContent className="max-w-md text-center">
          <DialogHeader>
            <div className="success-icon mx-auto mb-4">
              <CheckCircle className="w-12 h-12 text-white" />
            </div>
            <DialogTitle className="text-2xl font-bold text-foreground">Thank You!</DialogTitle>
            <DialogDescription className="text-muted-foreground">
              Your consent has been recorded successfully. We'll keep you informed about your recertification opportunities.
            </DialogDescription>
          </DialogHeader>
          <Button 
            onClick={() => setShowSuccessModal(false)}
            className="w-full mt-4"
            data-testid="button-close-modal"
          >
            Close
          </Button>
        </DialogContent>
      </Dialog>
    </div>
  );
}
