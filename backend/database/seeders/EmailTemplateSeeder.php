<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailTemplate::updateOrCreate(
            ['key' => 'support_notification_admin'],
            [
                'subject' => '[Support] New Message: {{subject}}',
                'content' => "# New Support Message\n\nHello Admin,\n\nA new support message has been received from **{{user_name}}** ({{user_email}}).\n\n**Subject:** {{subject}}\n\n**Message:**\n{{message_body}}\n\n[View Message]({{button_url}})",
                'variables' => [
                    'user_name' => 'Name of the user who sent the message',
                    'user_email' => 'Email of the user',
                    'subject' => 'The subject of the support ticket',
                    'message_body' => 'The actual message content',
                    'button_url' => 'Link to view the message in admin panel'
                ],
            ]
        );

        EmailTemplate::updateOrCreate(
            ['key' => 'support_notification_user'],
            [
                'subject' => 'Re: [Support] {{subject}}',
                'content' => "# New Reply from Support\n\nHello {{user_name}},\n\nYou have received a reply from the GoPathway support team regarding your ticket: **{{subject}}**.\n\n**Message:**\n{{message_body}}\n\n[View Message]({{button_url}})",
                'variables' => [
                    'user_name' => 'Name of the user receiving the reply',
                    'subject' => 'The subject of the support ticket',
                    'message_body' => 'The support team\'s reply content',
                    'button_url' => 'Link to view the message in user dashboard'
                ],
            ]
        );

        EmailTemplate::updateOrCreate(
            ['key' => 'welcome_email'],
            [
                'subject' => 'Welcome to GoPathway, {{user_name}}!',
                'content' => "# Welcome to GoPathway!\n\nHello {{user_name}},\n\nWe're excited to have you on board! GoPathway is here to help you navigate your global migration journey with ease.\n\nYou can now start exploring destinations, tracking your pathway, and managing your documents.\n\n[Go to Dashboard]({{dashboard_url}})",
                'variables' => [
                    'user_name' => 'Name of the user',
                    'dashboard_url' => 'URL to the user dashboard'
                ],
            ]
        );

        EmailTemplate::updateOrCreate(
            ['key' => 'password_reset'],
            [
                'subject' => 'Reset Your Password',
                'content' => "# Password Reset Request\n\nHello {{user_name}},\n\nYou are receiving this email because we received a password reset request for your account.\n\n[Reset Password]({{reset_url}})\n\nIf you did not request a password reset, no further action is required.",
                'variables' => [
                    'user_name' => 'Name of the user',
                    'reset_url' => 'URL to reset the password'
                ],
            ]
        );

        EmailTemplate::updateOrCreate(
            ['key' => 'subscription_confirmation'],
            [
                'subject' => 'Subscription Confirmed: {{plan_name}}',
                'content' => "# Subscription Confirmed!\n\nHello {{user_name}},\n\nYour subscription to the **{{plan_name}}** plan has been successfully processed.\n\nYou now have full access to all premium features, including advanced relocation tools and priority support.\n\n[Manage Billing]({{billing_url}})",
                'variables' => [
                    'user_name' => 'Name of the user',
                    'plan_name' => 'Name of the subscription plan',
                    'billing_url' => 'URL to the billing settings'
                ],
            ]
        );

        EmailTemplate::updateOrCreate(
            ['key' => 'subscription_reminder'],
            [
                'subject' => 'Your Subscription Expires Soon',
                'content' => "# Subscription Reminder\n\nHello {{user_name}},\n\nThis is a friendly reminder that your subscription to **{{plan_name}}** will expire in 3 days on **{{expiry_date}}**.\n\nTo ensure uninterrupted access to GoPathway's premium features, please make sure your payment details are up to date.\n\n[Renew Now]({{billing_url}})",
                'variables' => [
                    'user_name' => 'Name of the user',
                    'plan_name' => 'Name of the subscription plan',
                    'expiry_date' => 'The date the subscription expires',
                    'billing_url' => 'URL to the billing settings'
                ],
            ]
        );

        EmailTemplate::updateOrCreate(
            ['key' => 'new_update'],
            [
                'subject' => 'New Update: {{update_title}}',
                'content' => "# {{update_title}}\n\nHello {{user_name}},\n\nWe've made some exciting updates to GoPathway:\n\n{{update_content}}\n\nStay tuned for more updates as we continue to improve your relocation experience!",
                'variables' => [
                    'user_name' => 'Name of the user',
                    'update_title' => 'The title of the update',
                    'update_content' => 'The content/details of the update'
                ],
            ]
        );

        EmailTemplate::updateOrCreate(
            ['key' => 'login_notification'],
            [
                'subject' => 'New Login Detected',
                'content' => "# New Login Notification\n\nHello {{user_name}},\n\nWe noticed a new login to your GoPathway account from {{device}} on {{time}}.\n\nIf this was you, you can ignore this email. If not, please change your password immediately to secure your account.\n\n[Go to Dashboard]({{dashboard_url}})",
                'variables' => [
                    'user_name' => 'Name of the user',
                    'device' => 'The device or browser used',
                    'time' => 'The time of login',
                    'dashboard_url' => 'URL to the dashboard'
                ],
            ]
        );
    }
}
