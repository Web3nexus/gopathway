<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Country;
use App\Models\BlogPost; // Assuming you have a BlogPost model, adjust as needed

class SeoController extends Controller
{
    public function publicSettings()
    {
        // Only return specific public settings to avoid leaking sensitive admin configs
        $keys = [
            'site_meta_title',
            'site_meta_description',
            'site_og_image',
            'site_logo',
            'site_favicon',
            'support_email',
            'google_analytics_id',
            'cookie_consent_enabled',
            'cookie_consent_message',
            'privacy_policy_url',
            'terms_service_url',
            'turnstile_site_key',
        ];

        $settings = \App\Models\Setting::whereIn('key', $keys)->pluck('value', 'key');
        
        return response()->json(['data' => $settings]);
    }

    public function sitemap()
    {
        $baseUrl = config('app.url', 'https://gopathway.net');

        // Static routes
        $urls = [
            '/',
            '/pathway',
            '/cost',
            '/pricing',
            '/experts',
            '/school-explorer',
            '/relocation-hub',
            '/compare',
            '/job-search',
            '/residency',
            '/support',
        ];

        // Dynamic Routes - Countries
        $countries = Country::where('is_active', true)->get();
        foreach ($countries as $country) {
            $urls[] = '/compare?country=' . $country->id;
            $urls[] = '/relocation-hub/' . $country->id;
        }

        // Generate XML
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        foreach ($urls as $url) {
            $urlElement = $xml->addChild('url');
            // Remove trailing slash from baseUrl just in case, but keep the leading slash on $url
            $fullLoc = rtrim($baseUrl, '/') . $url;
            $urlElement->addChild('loc', $fullLoc);
            $urlElement->addChild('lastmod', now()->toAtomString());
            $urlElement->addChild('changefreq', 'daily');
            $urlElement->addChild('priority', '0.8');
        }

        return Response::make($xml->asXML(), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    public function robots()
    {
        $baseUrl = config('app.url', 'https://gopathway.net');
        $content = "User-agent: *\nDisallow: /admin/\nDisallow: /api/\nAllow: /\n\nSitemap: {$baseUrl}/api/v1/sitemap.xml\n";

        return Response::make($content, 200, [
            'Content-Type' => 'text/plain'
        ]);
    }
}
