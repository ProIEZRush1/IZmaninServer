<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function index()
    {
        $privacyPolicy = [
            'title' => 'Privacy Policy - Zmanim App',
            'last_updated' => '2025-01-08',
            'content' => [
                [
                    'section' => 'Introduction',
                    'text' => 'Welcome to Zmanim App. We respect your privacy and are committed to protecting your personal data. This privacy policy explains how we handle your information when you use our Jewish prayer times application.'
                ],
                [
                    'section' => 'Information We Collect',
                    'text' => 'We collect the following types of information:',
                    'items' => [
                        'Location Data: We use your geographic location (latitude and longitude) solely to calculate accurate prayer times for your area. This data is processed in real-time and is not stored on our servers.',
                        'Language Preference: We store your selected language preference locally on your device to provide a personalized experience.',
                        'Theme Preference: Your chosen theme (light/dark mode) is stored locally on your device.'
                    ]
                ],
                [
                    'section' => 'How We Use Your Information',
                    'text' => 'We use the collected information exclusively for:',
                    'items' => [
                        'Calculating accurate Zmanim (Jewish prayer times) based on your location',
                        'Displaying the app interface in your preferred language',
                        'Providing the visual theme you prefer'
                    ]
                ],
                [
                    'section' => 'Data Storage and Security',
                    'text' => 'Your privacy is our priority:',
                    'items' => [
                        'Location data is NOT stored on our servers - it is only used for real-time calculations',
                        'All preferences are stored locally on your device',
                        'We do not create user accounts or profiles',
                        'We do not track your usage patterns or behavior',
                        'We use secure HTTPS connections for all API communications'
                    ]
                ],
                [
                    'section' => 'Third-Party Services',
                    'text' => 'We use the following third-party services:',
                    'items' => [
                        'Hebcal API: For accurate Zmanim calculations. They receive only location coordinates and date, no personal information.',
                        'No analytics or tracking services are used',
                        'No advertising networks are integrated'
                    ]
                ],
                [
                    'section' => 'Your Rights',
                    'text' => 'You have complete control over your data:',
                    'items' => [
                        'You can use custom coordinates instead of GPS location',
                        'You can clear all local data by uninstalling the app',
                        'You can change your location method at any time',
                        'No account or personal information is required to use the app'
                    ]
                ],
                [
                    'section' => 'Children\'s Privacy',
                    'text' => 'Our app does not collect personal information from anyone, including children under 13. The app is suitable for all ages as it only provides prayer time information.'
                ],
                [
                    'section' => 'Changes to This Policy',
                    'text' => 'We may update this privacy policy from time to time. Any changes will be reflected with an updated "Last Updated" date. Continued use of the app after changes constitutes acceptance of the updated policy.'
                ],
                [
                    'section' => 'Contact Us',
                    'text' => 'If you have any questions or concerns about this privacy policy or our practices, please contact us through the app\'s support channels.'
                ],
                [
                    'section' => 'Data Deletion',
                    'text' => 'Since we don\'t store any personal data on our servers, there is no data to delete. All locally stored preferences are removed when you uninstall the app.'
                ]
            ]
        ];

        return response()->json($privacyPolicy);
    }

    public function html()
    {
        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Zmanim App</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
        }
        .last-updated {
            color: #7f8c8d;
            font-style: italic;
            margin-bottom: 30px;
        }
        ul {
            padding-left: 20px;
        }
        li {
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 25px;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Privacy Policy - Zmanim App</h1>
        <p class="last-updated">Last Updated: January 8, 2025</p>
        
        <div class="section">
            <h2>Introduction</h2>
            <p>Welcome to Zmanim App. We respect your privacy and are committed to protecting your personal data. This privacy policy explains how we handle your information when you use our Jewish prayer times application.</p>
        </div>

        <div class="section">
            <h2>Information We Collect</h2>
            <p>We collect the following types of information:</p>
            <ul>
                <li><strong>Location Data:</strong> We use your geographic location (latitude and longitude) solely to calculate accurate prayer times for your area. This data is processed in real-time and is not stored on our servers.</li>
                <li><strong>Language Preference:</strong> We store your selected language preference locally on your device to provide a personalized experience.</li>
                <li><strong>Theme Preference:</strong> Your chosen theme (light/dark mode) is stored locally on your device.</li>
            </ul>
        </div>

        <div class="section">
            <h2>How We Use Your Information</h2>
            <p>We use the collected information exclusively for:</p>
            <ul>
                <li>Calculating accurate Zmanim (Jewish prayer times) based on your location</li>
                <li>Displaying the app interface in your preferred language</li>
                <li>Providing the visual theme you prefer</li>
            </ul>
        </div>

        <div class="section">
            <h2>Data Storage and Security</h2>
            <p>Your privacy is our priority:</p>
            <ul>
                <li>Location data is NOT stored on our servers - it is only used for real-time calculations</li>
                <li>All preferences are stored locally on your device</li>
                <li>We do not create user accounts or profiles</li>
                <li>We do not track your usage patterns or behavior</li>
                <li>We use secure HTTPS connections for all API communications</li>
            </ul>
        </div>

        <div class="section">
            <h2>Third-Party Services</h2>
            <p>We use the following third-party services:</p>
            <ul>
                <li><strong>Hebcal API:</strong> For accurate Zmanim calculations. They receive only location coordinates and date, no personal information.</li>
                <li>No analytics or tracking services are used</li>
                <li>No advertising networks are integrated</li>
            </ul>
        </div>

        <div class="section">
            <h2>Your Rights</h2>
            <p>You have complete control over your data:</p>
            <ul>
                <li>You can use custom coordinates instead of GPS location</li>
                <li>You can clear all local data by uninstalling the app</li>
                <li>You can change your location method at any time</li>
                <li>No account or personal information is required to use the app</li>
            </ul>
        </div>

        <div class="section">
            <h2>Children\'s Privacy</h2>
            <p>Our app does not collect personal information from anyone, including children under 13. The app is suitable for all ages as it only provides prayer time information.</p>
        </div>

        <div class="section">
            <h2>Changes to This Policy</h2>
            <p>We may update this privacy policy from time to time. Any changes will be reflected with an updated "Last Updated" date. Continued use of the app after changes constitutes acceptance of the updated policy.</p>
        </div>

        <div class="section">
            <h2>Contact Us</h2>
            <p>If you have any questions or concerns about this privacy policy or our practices, please contact us through the app\'s support channels.</p>
        </div>

        <div class="section">
            <h2>Data Deletion</h2>
            <p>Since we don\'t store any personal data on our servers, there is no data to delete. All locally stored preferences are removed when you uninstall the app.</p>
        </div>
    </div>
</body>
</html>';

        return response($html)->header('Content-Type', 'text/html');
    }
}
