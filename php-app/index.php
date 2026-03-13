<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learner Consent Form | Absolute Training & Assessing Ltd</title>
    <meta name="description" content="Provide your consent to receive recertification reminders from Absolute Training & Assessing Ltd.">
    <meta property="og:title" content="Learner Consent Form | Absolute Training & Assessing Ltd">
    <meta property="og:description" content="Help us keep you informed about recertification opportunities.">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
            </div>
            <h1>Learner Consent Form</h1>
            <p>Help us keep you informed about recertification opportunities</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="consentForm" method="POST" novalidate>
                    <div class="info-box">
                        <h2>Stay Informed with Confidence</h2>
                        <p>By providing your email address and mobile number, you agree to receive updates about upcoming recertifications, assessment availability, and exclusive course offers from Absolute Training &amp; Assessing Ltd. Your information will be kept secure in line with GDPR and will never be shared with third parties. You can opt out at any time by contacting us.</p>
                    </div>

                    <div class="form-group">
                        <label for="firstName">First Name *</label>
                        <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" data-testid="input-firstname">
                        <div class="error-text" id="error-firstName" style="display:none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            <span></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lastName">Last Name *</label>
                        <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" data-testid="input-lastname">
                        <div class="error-text" id="error-lastName" style="display:none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            <span></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" placeholder="your.email@example.com" data-testid="input-email">
                        <div class="error-text" id="error-email" style="display:none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            <span></span>
                        </div>
                        <p class="helper-text">We'll use this to send you recertification reminders</p>
                    </div>

                    <div class="form-group">
                        <label for="mobile">Mobile Number *</label>
                        <input type="tel" id="mobile" name="mobile" placeholder="07825633999" data-testid="input-mobile">
                        <div class="error-text" id="error-mobile" style="display:none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            <span></span>
                        </div>
                        <p class="helper-text">Format: 07825633999</p>
                    </div>

                    <div class="form-group">
                        <label for="sentinelNumber">Sentinel Number *</label>
                        <input type="text" id="sentinelNumber" name="sentinelNumber" placeholder="Enter your Sentinel Number" data-testid="input-sentinel">
                        <div class="error-text" id="error-sentinelNumber" style="display:none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            <span></span>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <div class="checkbox-row">
                            <input type="checkbox" id="consent" name="consent" data-testid="checkbox-consent">
                            <label for="consent">
                                <strong>I consent to receive marketing communications</strong>
                                I agree to receive SMS messages and emails from your organisation regarding course recertification reminders, training updates, and related marketing communications. I understand that I will be contacted approximately 2 years from now to book my recertification event. I can withdraw my consent at any time by contacting you directly.
                            </label>
                        </div>
                    </div>

                    <div class="privacy-box">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                        <div>
                            <strong>Your Privacy Matters</strong>
                            <p>Your data will be stored securely and used solely for the purpose of sending recertification reminders. We will never share your information with third parties. For more details, please review our privacy policy.</p>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmit" data-testid="button-submit">Submit Consent</button>

                    <p class="required-note">* Required fields</p>
                </form>
            </div>
        </div>

        <div class="footer">
            <p>Questions? Contact us at <a href="mailto:info@absoluterail.co.uk">info@absoluterail.co.uk</a></p>
            <p>&copy; 2025 Absolute Training &amp; Assessing Ltd. All rights reserved.</p>
        </div>
    </div>

    <div class="modal-overlay" id="successModal">
        <div class="modal">
            <div class="success-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h2>Thank You!</h2>
            <p>Your consent has been recorded successfully. We'll keep you informed about your recertification opportunities.</p>
            <button class="btn-close" id="btnCloseModal" data-testid="button-close-modal">Close</button>
        </div>
    </div>

    <script>
    (function () {
        const form = document.getElementById('consentForm');
        const btn  = document.getElementById('btnSubmit');
        const modal = document.getElementById('successModal');
        const btnClose = document.getElementById('btnCloseModal');

        const fields = ['firstName', 'lastName', 'email', 'mobile', 'sentinelNumber', 'consent'];

        function showError(field, msg) {
            const el = document.getElementById('error-' + field);
            const input = document.getElementById(field);
            if (el) { el.style.display = 'flex'; el.querySelector('span').textContent = msg; }
            if (input && input.type !== 'checkbox') input.classList.add('is-invalid');
        }

        function clearErrors() {
            fields.forEach(function (f) {
                const el = document.getElementById('error-' + f);
                const input = document.getElementById(f);
                if (el) el.style.display = 'none';
                if (input) input.classList.remove('is-invalid');
            });
        }

        function validate() {
            clearErrors();
            var ok = true;
            var v = {};
            fields.forEach(function (f) { v[f] = document.getElementById(f); });

            if (!v.firstName.value.trim()) { showError('firstName', 'First name is required'); ok = false; }
            if (!v.lastName.value.trim()) { showError('lastName', 'Last name is required'); ok = false; }

            var emailVal = v.email.value.trim();
            if (!emailVal || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
                showError('email', 'Please enter a valid email address'); ok = false;
            }

            if (!/^07\d{9}$/.test(v.mobile.value.trim())) {
                showError('mobile', 'Please enter a valid UK mobile number (e.g., 07825633999)'); ok = false;
            }

            if (!v.sentinelNumber.value.trim()) { showError('sentinelNumber', 'Sentinel Number is required'); ok = false; }

            return ok;
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!validate()) return;

            btn.disabled = true;
            btn.textContent = 'Submitting...';

            var payload = {
                firstName: document.getElementById('firstName').value.trim(),
                lastName: document.getElementById('lastName').value.trim(),
                email: document.getElementById('email').value.trim(),
                mobile: document.getElementById('mobile').value.trim(),
                sentinelNumber: document.getElementById('sentinelNumber').value.trim(),
                consent: document.getElementById('consent').checked
            };

            fetch('api/submit.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                btn.disabled = false;
                btn.textContent = 'Submit Consent';

                if (data.success) {
                    form.reset();
                    clearErrors();
                    modal.classList.add('active');
                } else if (data.errors) {
                    data.errors.forEach(function (err) { showError(err.field, err.message); });
                } else {
                    alert('There was an error submitting your consent. Please try again.');
                }
            })
            .catch(function () {
                btn.disabled = false;
                btn.textContent = 'Submit Consent';
                alert('There was an error submitting your consent. Please try again.');
            });
        });

        btnClose.addEventListener('click', function () { modal.classList.remove('active'); });
        modal.addEventListener('click', function (e) { if (e.target === modal) modal.classList.remove('active'); });
    })();
    </script>
</body>
</html>
