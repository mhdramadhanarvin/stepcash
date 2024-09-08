import React from "react";

const PrivacyPolicy: React.FC = () => {
    return (
        <div className="container mx-auto p-4">
            <h1 className="text-3xl font-bold mb-4">Privacy Policy</h1>
            <p className="mb-2">Effective Date: 1 September 2024</p>

            <h2 className="text-2xl font-semibold mt-6 mb-2">
                1. Introduction
            </h2>
            <p className="mb-4">
                Welcome to StepCash ("we", "our", "us"). We value your privacy
                and are committed to protecting your personal information.
            </p>

            <h2 className="text-2xl font-semibold mt-6 mb-2">
                2. Information We Collect
            </h2>
            <ul className="list-disc list-inside">
                <li>
                    Personal Information: We collect your name, email address,
                    and other contact information.
                </li>
                <li>
                    Fitness Data: We collect steps, distance, and other fitness
                    activity from Google Fit.
                </li>
            </ul>

            <h2 className="text-2xl font-semibold mt-6 mb-2">
                3. How We Use Your Information
            </h2>
            <ul className="list-disc list-inside">
                <li>To Provide Services</li>
                <li>To Improve Our Services</li>
                <li>To Communicate with You</li>
            </ul>

            <h2 className="text-2xl font-semibold mt-6 mb-2">
                4. How We Share Your Information
            </h2>
            <p className="mb-4">
                We do not sell or transfer your information except to provide
                services or comply with legal obligations.
            </p>

            <h2 className="text-2xl font-semibold mt-6 mb-2">
                5. Data Security
            </h2>
            <p className="mb-4">
                We use security measures to protect your data, though we cannot
                guarantee absolute security.
            </p>

            <h2 className="text-2xl font-semibold mt-6 mb-2">6. Your Rights</h2>
            <p className="mb-4">
                You can access, correct, or request deletion of your data by
                contacting us.
            </p>

            <h2 className="text-2xl font-semibold mt-6 mb-2">
                7. Changes to This Policy
            </h2>
            <p className="mb-4">
                We may update this policy. Changes will be posted with the
                updated effective date.
            </p>

            <h2 className="text-2xl font-semibold mt-6 mb-2">8. Contact Us</h2>
            <p>
                If you have any questions, contact us at mramadhan687@gmail.com.
            </p>
        </div>
    );
};

export default PrivacyPolicy;
