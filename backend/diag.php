<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\User;
use App\Models\VisaType;
use App\Models\Profile;

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = User::first();
if (!$user) {
    echo "No user found\n";
    exit;
}

echo "User: {$user->email}\n";
$profile = $user->profile;
if ($profile) {
    echo "Profile found:\n";
    echo "- Education: {$profile->education_level}\n";
    echo "- Funds: {$profile->funds_range}\n";
    echo "- Exp: {$profile->work_experience_years}\n";
    echo "- IELTS: {$profile->ielts_status}\n";
} else {
    echo "No profile found\n";
}

$visas = VisaType::all();
echo "Total visas: " . $visas->count() . "\n";
foreach ($visas as $visa) {
    if ($visa->min_funds_required || $visa->min_education_level || $visa->min_work_experience_years || $visa->min_ielts_score) {
        $score = app(\App\Services\RecommendationService::class)->calculateMatchScore($profile, $visa);
        echo "Visa: {$visa->name} (ID: {$visa->id}) -> Match Score: {$score}%\n";
        echo "- Min Funds: {$visa->min_funds_required}\n";
        echo "- Min Edu: {$visa->min_education_level}\n";
        echo "- Min Exp: {$visa->min_work_experience_years}\n";
        echo "- Min IELTS: {$visa->min_ielts_score}\n";
        echo "-------------------\n";
    }
}
