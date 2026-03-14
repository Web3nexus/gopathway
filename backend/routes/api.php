<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PathwayController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimelineController;
use App\Http\Controllers\VisaTypeController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\GoScoreController;
use App\Http\Controllers\RiskAnalysisController;
use App\Http\Controllers\Api\CountryScoreController;
use App\Http\Controllers\Admin\FeatureController as AdminFeatureController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\CountryController as AdminCountryController;
use App\Http\Controllers\Admin\VisaTypeController as AdminVisaTypeController;
use App\Http\Controllers\Admin\DocumentTypeController;
use App\Http\Controllers\Admin\TimelineStepTemplateController;
use App\Http\Controllers\Admin\CostItemController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Admin\SupportManagementController;
use App\Http\Controllers\Api\ExpertPaymentController;
use App\Http\Controllers\Api\SeoController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────────────────────
//  API v1
// ──────────────────────────────────────────────────────────────
Route::group(['prefix' => 'v1'], function () {

    // Public Settings
    Route::get('/settings', [SeoController::class , 'publicSettings']);

    // Public — Countries & Visa Types
    Route::get('/countries', [CountryController::class , 'index']);
    Route::get('/countries/scores', [CountryScoreController::class , 'index']);
    Route::get('/countries/{country}', [CountryController::class , 'show']);
    Route::get('/countries/{country}/visa-types', [VisaTypeController::class , 'byCountry']);
    Route::get('/plans', [SubscriptionController::class , 'plans']);
    Route::get('/blog', [BlogController::class , 'index']);
    
    // SEO & Sitemap
    Route::get('/sitemap.xml', [SeoController::class , 'sitemap']);
    Route::get('/robots.txt', [SeoController::class , 'robots']);
    Route::get('/blog/{slug}', [BlogController::class , 'show']);

    // Auth (Sanctum stateful handled by bootstrap/app.php middleware)
    Route::post('/auth/register', [AuthController::class , 'register'])->middleware('throttle:register');
    Route::post('/auth/login', [AuthController::class , 'login'])->middleware('throttle:login');

    Route::post('/referral/track/{code}', [\App\Http\Controllers\Api\ReferralController::class , 'trackClick']);

    Route::post('/webhooks/paystack', [WebhookController::class , 'handle']);
    Route::post('/webhooks/flutterwave', [WebhookController::class , 'handleFlutterwave']);

    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {

            // Auth helpers
            Route::post('/auth/logout', [AuthController::class , 'logout']);
            Route::get('/auth/me', [AuthController::class , 'me']);
            Route::put('/auth/password', [AuthController::class , 'changePassword']);

            // Profile
            Route::get('/profile', [ProfileController::class , 'show']);
            Route::put('/profile', [ProfileController::class , 'update']);
            Route::patch('/profile/budget', [ProfileController::class , 'updateBudget']);

            // Employability
            Route::get('/user/employability-score', [\App\Http\Controllers\Api\EmployabilityController::class , 'getScore']);
            Route::get('/occupations', [\App\Http\Controllers\Api\EmployabilityController::class , 'getOccupations']);

            // Dashboard
            Route::get('/dashboard/summary', [DashboardController::class , 'summary']);

            // Pathway
            Route::get('/pathways', [PathwayController::class , 'index']);
            Route::get('/pathway', [PathwayController::class , 'show']);
            Route::put('/pathway/savings', [PathwayController::class , 'updateSavings']);
            Route::post('/pathway/select', [PathwayController::class , 'select']);
            Route::post('/pathway/deactivate', [PathwayController::class , 'deactivate']);
            Route::get('/pathways/compare', [\App\Http\Controllers\Api\ComparisonController::class , 'compare']);
            Route::get('/recommendations', [RecommendationController::class , 'index']);

            // Relocation Kits
            Route::get('/countries/{country}/relocation-kits', [\App\Http\Controllers\Api\RelocationKitsController::class , 'index']);

            // Schools & Study Pathway
            Route::get('/countries/{country}/schools', [\App\Http\Controllers\Api\SchoolController::class , 'byCountry']);
            Route::get('/schools/{school}/programs', [\App\Http\Controllers\Api\SchoolController::class , 'programs']);
            Route::get('/countries/{country}/student-visa', [\App\Http\Controllers\Api\SchoolController::class , 'studentVisa']);
            Route::get('/school-applications', [\App\Http\Controllers\Api\SchoolController::class , 'myApplications']);
            Route::post('/school-applications', [\App\Http\Controllers\Api\SchoolController::class , 'saveApplication']);
            Route::delete('/school-applications/{application}', [\App\Http\Controllers\Api\SchoolController::class , 'destroyApplication']);

            // Finance
            Route::get('/finance/recommendations', [\App\Http\Controllers\Api\FinanceController::class , 'recommendations']);

            // Timeline
            Route::get('/timeline', [TimelineController::class , 'index']);
            Route::post('/timeline/{step}/complete', [TimelineController::class , 'complete']);

            // Subscriptions
            Route::get('/billing/current', [SubscriptionController::class , 'current']);
            Route::post('/billing/subscribe', [SubscriptionController::class , 'subscribe']);
            Route::get('/billing/verify', [SubscriptionController::class , 'verify']);
            Route::get('/billing/history', [SubscriptionController::class , 'history']);
            Route::get('/billing/history/download', [SubscriptionController::class , 'downloadInvoices']);

            // Documents
            Route::get('/documents', [DocumentController::class , 'index']);
            Route::get('/documents/required-types', [DocumentController::class , 'requiredTypes']);
            Route::post('/documents/upload', [DocumentController::class , 'upload'])->middleware('subscribed');

            // Costs (Premium only)
            Route::get('/costs/templates', [CostController::class , 'index'])->middleware('subscribed');

            // Professionals
            Route::post('/professionals/apply', [ProfessionalController::class , 'apply']);
            Route::get('/professionals/status', [ProfessionalController::class , 'status']);
            Route::put('/professionals/profile', [ProfessionalController::class , 'updateProfile']);

            // Marketplace & Bookings
            Route::get('/marketplace', [BookingController::class , 'marketplace']);
            Route::get('/bookings', [BookingController::class , 'index']);
            Route::post('/bookings', [BookingController::class , 'store']);
            Route::patch('/bookings/{booking}/status', [BookingController::class , 'updateStatus']);

            // Notifications
            Route::get('/notifications', [NotificationController::class , 'index']);
            Route::get('/notifications/stats', [NotificationController::class , 'stats']);
            Route::post('/notifications/{id}/read', [NotificationController::class , 'markRead']);
            Route::post('/notifications/read-all', [NotificationController::class , 'markAllRead']);

            // Features
            Route::get('/features', [FeatureController::class , 'index']);

            // GoScore
            Route::get('/go-score', [GoScoreController::class , 'show']);
            Route::post('/go-score/calculate', [GoScoreController::class , 'calculate']);

            // Risk Analysis
            Route::get('/pathways/{pathway}/risk-analysis', [RiskAnalysisController::class , 'show']);
            Route::post('/pathways/{pathway}/risk-analysis/calculate', [RiskAnalysisController::class , 'calculate']);

            // Country Competitiveness Index
            Route::get('/countries/compare', [CountryScoreController::class , 'compare']);

            // Post-Arrival Settlement (available to all authenticated users)
            Route::get('/settlement/steps', [\App\Http\Controllers\Api\SettlementController::class , 'index']);
            Route::post('/settlement/steps/{step}/toggle', [\App\Http\Controllers\Api\SettlementController::class , 'toggle']);

            // Currency & Exchange
            Route::get('/currency/rates', [\App\Http\Controllers\Api\CurrencyController::class , 'rates']);
            Route::put('/currency/preference', [\App\Http\Controllers\Api\CurrencyController::class , 'updatePreference'])->middleware('auth:sanctum');

            // Referral System
            Route::get('/referral/stats', [\App\Http\Controllers\Api\ReferralController::class , 'index']);
            Route::get('/referral/history', [\App\Http\Controllers\Api\ReferralController::class , 'history']);
            Route::put('/referral/payout', [\App\Http\Controllers\Api\ReferralController::class , 'updatePayout']);

            // SOP Builder (Premium only)
            Route::post('/sop/start', [\App\Http\Controllers\Api\SopController::class , 'start'])->middleware('subscribed');
            Route::put('/sop/{sopDraft}/save', [\App\Http\Controllers\Api\SopController::class , 'save'])->middleware('subscribed');
            Route::post('/sop/{sopDraft}/generate', [\App\Http\Controllers\Api\SopController::class , 'generate'])->middleware('subscribed');
            Route::post('/sop/review', [\App\Http\Controllers\Api\SopController::class , 'review'])->middleware('subscribed');

            // Support Messages
            Route::post('/support', [SupportController::class , 'store']);

            // Messaging
            Route::get('/messages', [MessageController::class , 'index']);
            Route::get('/messages/{id}', [MessageController::class , 'show']);
            Route::post('/messages', [MessageController::class , 'store']);

            // Expert Payments
            Route::prefix('expert-payments')->group(function () {
                Route::post('/initialize', [ExpertPaymentController::class, 'initializePayment']);
                Route::get('/verify', [ExpertPaymentController::class, 'verifyPayment']);
                Route::get('/stats', [ExpertPaymentController::class, 'expertStats']);
                Route::post('/withdraw', [ExpertPaymentController::class, 'requestWithdrawal']);
            });

            // AI Chat
            Route::get('/ai-chats', [\App\Http\Controllers\Api\AiChatController::class , 'index']);
            Route::post('/ai-chats', [\App\Http\Controllers\Api\AiChatController::class , 'store']);
            Route::get('/ai-chats/{aiChat}', [\App\Http\Controllers\Api\AiChatController::class , 'show']);
            Route::post('/ai-chats/{aiChat}/send', [\App\Http\Controllers\Api\AiChatController::class , 'sendMessage'])->middleware('throttle:ai-chat');
            Route::delete('/ai-chats/{aiChat}', [\App\Http\Controllers\Api\AiChatController::class , 'destroy']);

            // Post-Arrival Residency & Career
            Route::get('/residency-rules/{country}', [\App\Http\Controllers\Api\ResidencyController::class , 'getRules']);
            Route::get('/residency-tracking', [\App\Http\Controllers\Api\ResidencyController::class , 'getTracking']);
            Route::post('/residency-tracking', [\App\Http\Controllers\Api\ResidencyController::class , 'saveTracking']);

            Route::get('/job-platforms/{country}', [\App\Http\Controllers\Api\CareerController::class , 'getJobPlatforms']);
            Route::get('/cv-templates/{country}', [\App\Http\Controllers\Api\CareerController::class , 'getCvTemplates']);
            Route::get('/my-cvs', [\App\Http\Controllers\Api\CareerController::class , 'getMyCvs']);
            Route::post('/cv-builder/generate', [\App\Http\Controllers\Api\CareerController::class , 'saveCv']);
            Route::delete('/cv-builder/{cv}', [\App\Http\Controllers\Api\CareerController::class , 'deleteCv']);
            Route::post('/cover-letter/generate', [\App\Http\Controllers\Api\CareerController::class , 'generateCoverLetter']);


            // Impersonation (needs to be outside EnsureUserIsAdmin because the current user is not an admin)
            Route::post('admin/leave-impersonation', [\App\Http\Controllers\Admin\UserController::class , 'leaveImpersonation']);

            // ──────────────────────────────────────────────────────────────
            //  Admin Management (role-protected)
            // ──────────────────────────────────────────────────────────────
            Route::group(['prefix' => 'admin', 'middleware' => [EnsureUserIsAdmin::class]], function () {
                    Route::apiResource('countries', AdminCountryController::class);
                    Route::apiResource('countries.visa-types', AdminVisaTypeController::class)->shallow();
                    Route::apiResource('document-types', DocumentTypeController::class);
                    Route::apiResource('timeline-templates', TimelineStepTemplateController::class);
                    Route::apiResource('cost-items', CostItemController::class);
                    Route::apiResource('subscription-plans', \App\Http\Controllers\Admin\SubscriptionPlanController::class)->except(['show']);

                    // Relocation Kits
                    Route::apiResource('relocation-kits', \App\Http\Controllers\Admin\RelocationKitController::class);
                    Route::post('relocation-kits/{relocation_kit}/items', [\App\Http\Controllers\Admin\RelocationKitController::class , 'storeItem']);
                    Route::put('relocation-kits/{relocation_kit}/items/{item}', [\App\Http\Controllers\Admin\RelocationKitController::class , 'updateItem']);
                    Route::delete('relocation-kits/{relocation_kit}/items/{item}', [\App\Http\Controllers\Admin\RelocationKitController::class , 'destroyItem']);
                    Route::apiResource('settlement-steps', \App\Http\Controllers\Admin\SettlementStepController::class);
                    Route::get('schools', [\App\Http\Controllers\Admin\SchoolController::class , 'index']);
                    Route::post('schools', [\App\Http\Controllers\Admin\SchoolController::class , 'store']);
                    Route::put('schools/{school}', [\App\Http\Controllers\Admin\SchoolController::class , 'update']);
                    Route::delete('schools/{school}', [\App\Http\Controllers\Admin\SchoolController::class , 'destroy']);
                    Route::post('schools/{school}/programs', [\App\Http\Controllers\Admin\SchoolController::class , 'storeProgram']);
                    Route::put('schools/{school}/programs/{program}', [\App\Http\Controllers\Admin\SchoolController::class , 'updateProgram']);
                    Route::delete('schools/{school}/programs/{program}', [\App\Http\Controllers\Admin\SchoolController::class , 'destroyProgram']);
                    Route::post('student-visa-requirements', [\App\Http\Controllers\Admin\SchoolController::class , 'storeVisaRequirement']);
                    // User Management
                    Route::get('users', [\App\Http\Controllers\Admin\UserController::class , 'index']);
                    Route::post('users/{user}/grant-premium', [\App\Http\Controllers\Admin\UserController::class , 'grantPremium']);
                    Route::post('users/{user}/remove-premium', [\App\Http\Controllers\Admin\UserController::class , 'removePremium']);
                    Route::post('users/{user}/impersonate', [\App\Http\Controllers\Admin\UserController::class , 'impersonate']);

                    // Admin Dashboard Stats
                    Route::get('dashboard/stats', [AdminDashboardController::class , 'stats']);

                    // Professional Verifications
                    Route::get('verifications', [AdminVerificationController::class , 'index']);
                    Route::post('verifications/{id}/review', [AdminVerificationController::class , 'review']);

                    // Expert Withdrawals
                    Route::get('expert-withdrawals', [\App\Http\Controllers\Admin\AdminExpertWithdrawalController::class , 'index']);
                    Route::post('expert-withdrawals/{id}/review', [\App\Http\Controllers\Admin\AdminExpertWithdrawalController::class , 'review']);

                    // Feature Management
                    Route::get('features', [AdminFeatureController::class , 'index']);
                    Route::put('features/{feature}', [AdminFeatureController::class , 'update']);
                    Route::get('platform-features', [AdminFeatureController::class , 'indexPlatformFeatures']);
                    Route::put('platform-features/{id}', [AdminFeatureController::class , 'togglePlatformFeature']);

                    // General Settings
                    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class , 'index']);
                    Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class , 'update']);
                    Route::post('settings/reveal', [\App\Http\Controllers\Admin\SettingController::class , 'reveal']);

                    // Email Templates
                    Route::apiResource('email-templates', \App\Http\Controllers\Admin\EmailTemplateController::class)->only(['index', 'show', 'update']);

                    // Mail Settings
                    Route::post('mail-settings/test', [\App\Http\Controllers\Admin\MailSettingsController::class, 'testConnection']);


                    // Referral Management
                    Route::get('referrals', [\App\Http\Controllers\Admin\ReferralController::class , 'index']);
                    Route::put('users/{user}/commission-rate', [\App\Http\Controllers\Admin\ReferralController::class , 'updateRate']);
                    Route::get('referrals/commissions', [\App\Http\Controllers\Admin\ReferralController::class , 'commissions']);
                    Route::post('referrals/commissions/{commission}/pay', [\App\Http\Controllers\Admin\ReferralController::class , 'markAsPaid']);
                    Route::get('bookings', [\App\Http\Controllers\BookingController::class , 'adminIndex']);

                    // Post-Arrival Career & Residency Management
                    Route::post('job-platforms', [\App\Http\Controllers\Admin\CareerController::class , 'storeJobPlatform']);
                    Route::put('job-platforms/{platform}', [\App\Http\Controllers\Admin\CareerController::class , 'updateJobPlatform']);
                    Route::delete('job-platforms/{platform}', [\App\Http\Controllers\Admin\CareerController::class , 'destroyJobPlatform']);

                    Route::post('residency-rules/{country}', [\App\Http\Controllers\Admin\CareerController::class , 'updateResidencyRules']);

                    Route::post('cv-templates', [\App\Http\Controllers\Admin\CareerController::class , 'storeCvTemplate']);
                    Route::put('cv-templates/{template}', [\App\Http\Controllers\Admin\CareerController::class , 'updateCvTemplate']);
                    Route::delete('cv-templates/{template}', [\App\Http\Controllers\Admin\CareerController::class , 'destroyCvTemplate']);

                    // Blog Management
                    Route::get('blog', [BlogController::class , 'adminIndex']);
                    Route::post('blog', [BlogController::class , 'store']);
                    Route::put('blog/{blogPost}', [BlogController::class , 'update']);
                    Route::delete('blog/{blogPost}', [BlogController::class , 'destroy']);

                    // Support Management
                    Route::get('support', [SupportManagementController::class , 'index']);
                    Route::get('support/{id}', [SupportManagementController::class , 'show']);
                    Route::post('support/{id}/reply', [SupportManagementController::class , 'reply']);
                }
                );
            }
            );
        });