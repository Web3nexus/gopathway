<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insertOrIgnore([
            [
                'key' => 'mail_mailer',
                'value' => 'smtp',
                'type' => 'string',
                'label' => 'Mail Mailer',
                'description' => 'The mailer driver to use (e.g., smtp, mailgun, sendgrid).',
                'group' => 'Email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.mailtrap.io',
                'type' => 'string',
                'label' => 'Mail Host',
                'description' => 'SMTP server host.',
                'group' => 'Email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'mail_port',
                'value' => '2525',
                'type' => 'string',
                'label' => 'Mail Port',
                'description' => 'SMTP server port.',
                'group' => 'Email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'mail_username',
                'value' => '',
                'type' => 'string',
                'label' => 'Mail Username',
                'description' => 'SMTP server username.',
                'group' => 'Email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'mail_password',
                'value' => '',
                'type' => 'encrypted_string',
                'label' => 'Mail Password',
                'description' => 'SMTP server password.',
                'group' => 'Email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'type' => 'string',
                'label' => 'Mail Encryption',
                'description' => 'SMTP server encryption (tls, ssl, or null).',
                'group' => 'Email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'hello@example.com',
                'type' => 'string',
                'label' => 'Mail From Address',
                'description' => 'The sender e-mail address.',
                'group' => 'Email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'GoPathway',
                'type' => 'string',
                'label' => 'Mail From Name',
                'description' => 'The sender name.',
                'group' => 'Email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('group', 'Email')->delete();
    }
};
