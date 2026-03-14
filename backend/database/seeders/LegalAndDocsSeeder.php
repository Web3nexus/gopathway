<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class LegalAndDocsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            [
                'key' => 'privacy_policy_content',
                'value' => "<h1>Privacy Policy</h1>
<p>Last Updated: March 14, 2026</p>
<p>GoPathway (\"we\", \"our\", or \"us\") is committed to protecting your privacy. This Privacy Policy explains how your personal information is collected, used, and disclosed by GoPathway.</p>

<h2>1. Information We Collect</h2>
<p>We collect information about you in several ways when you use our services:</p>
<ul>
    <li><strong>Personal Data:</strong> While using our Service, we may ask you to provide us with certain personally identifiable information that can be used to contact or identify you (\"Personal Data\"). This includes, but is not limited to, Email address, First name and last name, Phone number, and Address.</li>
    <li><strong>Usage Data:</strong> We may also collect information on how the Service is accessed and used (\"Usage Data\"). This may include information such as your computer's IP address, browser type, browser version, the pages of our Service that you visit, and other diagnostic data.</li>
    <li><strong>Relocation Data:</strong> Information regarding your immigration targets, document uploads, and pathway progress.</li>
</ul>

<h2>2. Use of Data</h2>
<p>GoPathway uses the collected data for various purposes:</p>
<ul>
    <li>To provide and maintain our Service</li>
    <li>To notify you about changes to our Service</li>
    <li>To allow you to participate in interactive features of our Service</li>
    <li>To provide customer support</li>
    <li>To gather analysis or valuable information so that we can improve our Service</li>
    <li>To monitor the usage of our Service</li>
    <li>To detect, prevent and address technical issues</li>
</ul>

<h2>3. Transfer of Data</h2>
<p>Your information, including Personal Data, may be transferred to — and maintained on — computers located outside of your state, province, country or other governmental jurisdiction where the data protection laws may differ than those from your jurisdiction.</p>

<h2>4. Disclosure of Data</h2>
<p>We may disclose your personal information in the good faith belief that such action is necessary to: comply with a legal obligation, protect and defend the rights or property of GoPathway, or prevent or investigate possible wrongdoing in connection with the Service.</p>

<h2>5. Security of Data</h2>
<p>The security of your data is important to us, but remember that no method of transmission over the Internet, or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your Personal Data, we cannot guarantee its absolute security.</p>

<h2>6. Your Data Protection Rights</h2>
<p>Under certain circumstances, you have rights under data protection laws in relation to your personal data, including the right to request access, correction, erasure, restriction, and portability of your personal data.</p>

<h2>7. Contact Us</h2>
<p>If you have any questions about this Privacy Policy, please contact us at support@gopathway.net.</p>",
                'group' => 'legal',
                'type' => 'text',
                'label' => 'Privacy Policy Content',
                'description' => 'The full content of the Privacy Policy page (HTML allowed).',
            ],
            [
                'key' => 'terms_service_content',
                'value' => "<h1>Terms of Service</h1>
<p>Last Updated: March 14, 2026</p>
<p>Please read these Terms of Service (\"Terms\", \"Terms of Service\") carefully before using the GoPathway website (the \"Service\") operated by GoPathway (\"us\", \"we\", or \"our\").</p>

<h2>1. Acceptance of Terms</h2>
<p>By accessing or using the Service you agree to be bound by these Terms. If you disagree with any part of the terms then you may not access the Service. These Terms apply to all visitors, users and others who access or use the Service.</p>

<h2>2. Accounts</h2>
<p>When you create an account with us, you must provide us information that is accurate, complete, and current at all times. Failure to do so constitutes a breach of the Terms, which may result in immediate termination of your account on our Service.</p>

<h2>3. Subscription and Billing</h2>
<p>Some parts of the Service are billed on a subscription basis (\"Subscription(s)\"). You will be billed in advance on a recurring and periodic basis (\"Billing Cycle\"). At the end of each Billing Cycle, your Subscription will automatically renew under the exact same conditions unless you cancel it or GoPathway cancels it.</p>

<h2>4. Content</h2>
<p>Our Service allows you to post, link, store, share and otherwise make available certain information, text, graphics, videos, or other material (\"Content\"). You are responsible for the Content that you post to the Service, including its legality, reliability, and appropriateness.</p>

<h2>5. Intellectual Property</h2>
<p>The Service and its original content (excluding Content provided by users), features and functionality are and will remain the exclusive property of GoPathway and its licensors. The Service is protected by copyright, trademark, and other laws.</p>

<h2>6. Links To Other Web Sites</h2>
<p>Our Service may contain links to third-party web sites or services that are not owned or controlled by GoPathway. GoPathway has no control over, and assumes no responsibility for, the content, privacy policies, or practices of any third party web sites or services.</p>

<h2>7. Limitation Of Liability</h2>
<p>In no event shall GoPathway, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses.</p>

<h2>8. Governing Law</h2>
<p>These Terms shall be governed and construed in accordance with the laws of the jurisdiction in which the company is registered, without regard to its conflict of law provisions.</p>

<h2>9. Changes</h2>
<p>We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material we will try to provide at least 30 days notice prior to any new terms taking effect.</p>

<h2>10. Contact Us</h2>
<p>If you have any questions about these Terms, please contact us.</p>",
                'group' => 'legal',
                'type' => 'text',
                'label' => 'Terms of Service Content',
                'description' => 'The full content of the Terms of Service page (HTML allowed).',
            ],
            [
                'key' => 'documentation_content',
                'value' => "<h1>System Documentation & User Guide</h1>
<p>Welcome to the GoPathway user manual. This guide is designed to help you navigate our platform and make the most of our relocation intelligence tools.</p>

<h3>1. Your Dashboard</h3>
<p>Upon logging in, the Dashboard provides a high-level overview of your relocation progress. You can see your active pathways, upcoming tasks from your relocation kit, and quick links to your most-used tools. The dashboard is personalized based on your target country and visa type.</p>

<h3>2. Setting Up Your Profile</h3>
<p>To get the most accurate recommendations, ensure your profile is complete. Navigate to <strong>Settings</strong> to update your current location, target destination, and relocation preferences. This data drives our recommendation engine, which suggests the best visa strategies and cost estimates for your profile.</p>

<h3>3. Exploring Destinations</h3>
<p>Use the <strong>Countries</strong> explorer to research target destinations. Each country page contains detailed information on:</p>
<ul>
    <li><strong>Visa Requirements:</strong> A list of available visa types and eligibility criteria.</li>
    <li><strong>Cost of Living:</strong> Real-time estimates for housing, food, and transport.</li>
    <li><strong>Residency Pathways:</strong> Steps required to gain permanent residency or citizenship.</li>
</ul>
<p>You can compare countries side-by-side to determine which best fits your career and lifestyle goals.</p>

<h3>4. Using the Relocation Kit</h3>
<p>Your Relocation Kit is a curated checklist of every step required for your move. Each item includes:</p>
<ul>
    <li>Detailed instructions on the application process.</li>
    <li>A list of mandatory documents.</li>
    <li>Links to official government submission portals.</li>
    <li>Expected processing times and fees.</li>
</ul>
<p>Mark items as complete as you progress to track your real-time readiness score.</p>

<h3>5. Financial Planning</h3>
<p>The <strong>Cost Planner</strong> tool allows you to build a comprehensive budget for your move. It includes templates for:</p>
<ul>
    <li><strong>Pre-arrival:</strong> Flights, visa fees, document translation, and medical checks.</li>
    <li><strong>Post-arrival:</strong> Rental deposit, furniture, initial groceries, and local transport passes.</li>
</ul>

<h3>6. Professional Assistance</h3>
<p>If you need specialized help, our <strong>Expert Marketplace</strong> connects you with verified professionals. You can find experts in Immigration Law, Real Estate, and Career Coaching. Message experts safely and securely through our internal <strong>Inbox</strong>.</p>

<h3>7. Document Vault</h3>
<p>The <strong>Document Vault</strong> is your secure digital safe. Upload and organize copies of your essential identity and qualification documents. This ensures they are available for download whenever you need to submit an application during your journey.</p>

<h3>8. Messaging & Support</h3>
<p>Stay in touch with experts and our support team through the integrated chat system. If you encounter technical issues, use the <strong>Support</strong> portal to lodge a ticket with our 24/7 help desk.</p>

<p><em>Thank you for choosing GoPathway for your global relocation. Our mission is to make your move as seamless and informed as possible.</em></p>",
                'group' => 'system',
                'type' => 'text',
                'label' => 'Documentation Content',
                'description' => 'The full content of the Documentation/Help page (HTML allowed).',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
