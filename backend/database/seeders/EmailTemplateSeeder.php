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
    }
}
