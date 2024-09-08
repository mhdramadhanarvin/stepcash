import React from "react";

const TermsOfService: React.FC = () => {
    return (
        <div className="container mx-auto p-4">
            <h1 className="text-3xl font-bold mb-4">Terms of Service</h1>
            <p className="mb-2">Effective Date: 1 September 2024</p>

            <h2 className="text-2xl font-semibold mt-6 mb-2">
                1. Acceptance of Terms
            </h2>
            <p className="mb-4">
                By using StepCash, you agree to these terms. If you do not
                agree, please discontinue use.
            </p>

            <h2 className="text-2xl font-semibold mt-6 mb-2">
                2. Use of Our Services
            </h2>
            <ul className="list-disc list-inside">
                <li>
                    Eligibility: You must be at least 18 years old to use our
                    app.
                </li>
                <li>
                    Account Registration: Provide accurate information when
                    creating an account.
                </li>
                <li>
                    User Conduct: Use the app in compliance with all applicable
                    laws.
                </li>
            </ul>

            <h2 className="text-2xl font-semibold mt-6 mb-2">
                3. Fitness Data
            </h2>
            <p className="mb-4">
                By connecting to Google Fit, you allow us to use your fitness
                data as described in our Privacy Policy.
            </p>

            <h2 className="text-2xl font-semibold mt-6 mb-2">
                4. Intellectual Property
            </h2>
            <p className="mb-4">
                All content on this app is the property of StepCash or its
                licensors.
            </p>

            <h2 className="text-2xl font-semibold mt-6 mb-2">
                5. Limitation of Liability
            </h2>
            <p className="mb-4">
                We are not liable for any indirect or consequential damages
                arising from your use of our app.
            </p>

            <h2 className="text-2xl font-semibold mt-6 mb-2">6. Termination</h2>
            <p className="mb-4">
                We reserve the right to suspend or terminate your access to the
                app at any time.
            </p>

            {/*
            <h2 className="text-2xl font-semibold mt-6 mb-2">
                7. Governing Law
            </h2>
            <p className="mb-4">
                These terms are governed by the laws of [Your Jurisdiction].
            </p>
            */}

            <h2 className="text-2xl font-semibold mt-6 mb-2">
                7. Changes to These Terms
            </h2>
            <p className="mb-4">
                We may update these terms. The new terms will be posted with the
                updated effective date.
            </p>

            <h2 className="text-2xl font-semibold mt-6 mb-2">8. Contact Us</h2>
            <p>
                If you have any questions, contact us at mramadhan687@gmail.com.
            </p>
        </div>
    );
};

export default TermsOfService;
