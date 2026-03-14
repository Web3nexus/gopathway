<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            [
                'key' => 'site_meta_title',
                'value' => 'GoPathway - Relocation & Immigration Made Easy',
                'type' => 'string',
                'label' => 'Site Meta Title',
                'description' => 'Default meta title for the website.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_meta_description',
                'value' => 'Discover your pathway to a new country with GoPathway. Find visas, compare countries, and chat with experts.',
                'type' => 'text',
                'label' => 'Site Meta Description',
                'description' => 'Default meta description for SEO.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_og_image',
                'value' => '',
                'type' => 'file',
                'label' => 'Open Graph Image',
                'description' => 'Default image used when sharing site links on social media.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_logo',
                'value' => '',
                'type' => 'file',
                'label' => 'Site Logo',
                'description' => 'The main logo for the application.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'site_favicon',
                'value' => '',
                'type' => 'file',
                'label' => 'Site Favicon',
                'description' => 'The favicon displayed in the browser tab.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('settings')->insertOrIgnore($settings);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'site_meta_title',
            'site_meta_description',
            'site_og_image',
            'site_logo',
            'site_favicon',
        ])->delete();
    }
};
