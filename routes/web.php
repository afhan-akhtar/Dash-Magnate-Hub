<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\ProfessionalQuestionController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Professional\ChatController;
use App\Http\Controllers\Professional\OnboardingController;
use App\Http\Controllers\Professional\PlanController;
use App\Http\Controllers\Professional\PortalController;
use App\Http\Controllers\Professional\ProjectController;
use App\Http\Controllers\Professional\SettingsController;
use App\Http\Controllers\WebsiteApiController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Admin Panel — Session-Based Routes
|--------------------------------------------------------------------------
*/

// Maintenance
Route::get('maintenance/migrate', function () {
    Artisan::call('migrate');

    $output = trim(Artisan::output()) ?: 'Migrations executed successfully.';

    return response("<pre>{$output}</pre>", 200)
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('maintenance.migrate');

// Route::get('maintenance/migrate-new', function () {
//     Artisan::call('migrate:fresh --seed');

//     $output = trim(Artisan::output()) ?: 'Migrations executed successfully.';

//     return response("<pre>{$output}</pre>", 200)
//         ->header('Content-Type', 'text/html; charset=UTF-8');
// })->name('maintenance.migrate');

Route::get('maintenance/seeder', function () {
    Artisan::call('db:seed');

    $output = trim(Artisan::output()) ?: 'Seeding executed successfully.';

    return response("<pre>{$output}</pre>", 200)
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('maintenance.seeder');

Route::get('maintenance/clear-cache', function () {
    Artisan::call('optimize:clear');

    $output = trim(Artisan::output()) ?: 'Application caches cleared successfully.';

    return response("<pre>{$output}</pre>", 200)
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('maintenance.clear-cache');

Route::get('maintenance/storage-link', function () {
    Artisan::call('storage:link');

    $output = trim(Artisan::output()) ?: 'Storage link created successfully.';

    return response("<pre>{$output}</pre>", 200)
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('maintenance.storage-link');

/*
| When public/storage is not linked (or the symlink is broken), Apache sends the
| request to Laravel and static files 404/500. This route serves files from the
| public disk safely so uploads (categories, documents, etc.) still load.
*/
Route::get('storage/{path}', function (string $path) {
    $path = str_replace("\0", '', $path);
    $path = str_replace(['..', '\\'], ['', '/'], $path);
    $path = ltrim($path, '/');

    if ($path === '' || ! Storage::disk('public')->exists($path)) {
        abort(404);
    }

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('storage.serve');

// Legacy public website API compatibility routes
Route::get('GetAllProjects', [WebsiteApiController::class, 'GetAllProjects']);
Route::get('GetProject/{url}', [WebsiteApiController::class, 'GetProjectByUrl']);
Route::get('GetSimilarProjects', [WebsiteApiController::class, 'GetSimilarProjects']);
Route::get('GetAllProjectCategories', [WebsiteApiController::class, 'GetAllProjectCategories']);
Route::get('GetAllProjectLocations', [WebsiteApiController::class, 'GetAllProjectLocations']);
Route::get('GetAllRegions', [WebsiteApiController::class, 'GetAllRegions']);
Route::get('GetAllBlogs/{url?}', [WebsiteApiController::class, 'GetAllBlogs']);
Route::get('GetAllRecentBlogs', [WebsiteApiController::class, 'GetAllRecentBlogs']);
Route::post('GetAllBlogs/comment', [WebsiteApiController::class, 'AddBlogComment']);
Route::post('SaveContactForm', [WebsiteApiController::class, 'SaveContactForm']);
Route::post('SaveNewsLetterForm', [WebsiteApiController::class, 'SaveNewsLetterForm']);
Route::post('RegisterNewUser', [WebsiteApiController::class, 'RegisterNewUser']);
Route::post('WebsiteSignup/SendCode', [WebsiteApiController::class, 'SendWebsiteSignupVerificationCode']);
Route::post('WebsiteSignup', [WebsiteApiController::class, 'WebsiteSignup']);
Route::post('GetStripeToken', [WebsiteApiController::class, 'GetStripeToken']);
Route::get('GetAllProfessionalQuestions', [WebsiteApiController::class, 'GetAllProfessionalQuestions']);
Route::get('GetProfessionalQuestionsByRole/{role}', [WebsiteApiController::class, 'GetProfessionalQuestionsByRole']);

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest-only routes
    Route::middleware('admin.guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'login'])->name('login.post');
    });

    // Authenticated admin routes
    Route::middleware('admin.auth')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');

        Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('profile', [Admin\ProfileController::class, 'index'])->name('profile');
        Route::put('profile', [Admin\ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [Admin\ProfileController::class, 'changePassword'])->name('profile.password');

        // Accounts
        Route::get('accounts', [Admin\AccountController::class, 'index'])->name('accounts.index');
        Route::post('accounts', [Admin\AccountController::class, 'store'])->name('accounts.store');
        Route::delete('accounts/{id}', [Admin\AccountController::class, 'destroy'])->name('accounts.destroy');

        // Categories
        Route::get('categories', [Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/create', [Admin\CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{id}/edit', [Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{id}', [Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{id}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

        // Locations
        Route::get('locations', [Admin\LocationController::class, 'index'])->name('locations.index');
        Route::get('locations/create', [Admin\LocationController::class, 'create'])->name('locations.create');
        Route::post('locations', [Admin\LocationController::class, 'store'])->name('locations.store');
        Route::get('locations/{id}/edit', [Admin\LocationController::class, 'edit'])->name('locations.edit');
        Route::put('locations/{id}', [Admin\LocationController::class, 'update'])->name('locations.update');
        Route::delete('locations/{id}', [Admin\LocationController::class, 'destroy'])->name('locations.destroy');

        // Regions
        Route::get('regions', [Admin\RegionController::class, 'index'])->name('regions.index');
        Route::get('regions/create', [Admin\RegionController::class, 'create'])->name('regions.create');
        Route::post('regions', [Admin\RegionController::class, 'store'])->name('regions.store');
        Route::get('regions/{id}/edit', [Admin\RegionController::class, 'edit'])->name('regions.edit');
        Route::put('regions/{id}', [Admin\RegionController::class, 'update'])->name('regions.update');
        Route::delete('regions/{id}', [Admin\RegionController::class, 'destroy'])->name('regions.destroy');

        // Documents
        Route::get('documents', [Admin\DocumentController::class, 'index'])->name('documents.index');
        Route::delete('documents/{id}', [Admin\DocumentController::class, 'destroy'])->name('documents.destroy');

        // Blogs
        Route::get('blogs', [Admin\BlogController::class, 'index'])->name('blogs.index');
        Route::get('blogs/create', [Admin\BlogController::class, 'create'])->name('blogs.create');
        Route::post('blogs', [Admin\BlogController::class, 'store'])->name('blogs.store');
        Route::get('blogs/{id}/edit', [Admin\BlogController::class, 'edit'])->name('blogs.edit');
        Route::put('blogs/{id}', [Admin\BlogController::class, 'update'])->name('blogs.update');
        Route::delete('blogs/{id}', [Admin\BlogController::class, 'destroy'])->name('blogs.destroy');

        Route::get('blog-categories', [Admin\BlogCategoryController::class, 'index'])->name('blog-categories.index');
        Route::get('blog-categories/create', [Admin\BlogCategoryController::class, 'create'])->name('blog-categories.create');
        Route::post('blog-categories', [Admin\BlogCategoryController::class, 'store'])->name('blog-categories.store');
        Route::get('blog-categories/{id}', [Admin\BlogCategoryController::class, 'show'])->name('blog-categories.show');
        Route::get('blog-categories/{id}/edit', [Admin\BlogCategoryController::class, 'edit'])->name('blog-categories.edit');
        Route::put('blog-categories/{id}', [Admin\BlogCategoryController::class, 'update'])->name('blog-categories.update');
        Route::delete('blog-categories/{id}', [Admin\BlogCategoryController::class, 'destroy'])->name('blog-categories.destroy');

        Route::get('blog-tags', [Admin\BlogTagController::class, 'index'])->name('blog-tags.index');
        Route::get('blog-tags/create', [Admin\BlogTagController::class, 'create'])->name('blog-tags.create');
        Route::post('blog-tags', [Admin\BlogTagController::class, 'store'])->name('blog-tags.store');
        Route::get('blog-tags/{id}', [Admin\BlogTagController::class, 'show'])->name('blog-tags.show');
        Route::get('blog-tags/{id}/edit', [Admin\BlogTagController::class, 'edit'])->name('blog-tags.edit');
        Route::put('blog-tags/{id}', [Admin\BlogTagController::class, 'update'])->name('blog-tags.update');
        Route::delete('blog-tags/{id}', [Admin\BlogTagController::class, 'destroy'])->name('blog-tags.destroy');

        // Projects
        Route::get('projects', [Admin\ProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/{id}', [Admin\ProjectController::class, 'show'])->name('projects.show');
        Route::put('projects/{id}', [Admin\ProjectController::class, 'update'])->name('projects.update');
        Route::patch('projects/{id}/status', [Admin\ProjectController::class, 'updateStatus'])->name('projects.update-status');
        Route::delete('projects/{id}', [Admin\ProjectController::class, 'destroy'])->name('projects.destroy');

        // Users
        Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('users/{id}', [Admin\UserController::class, 'show'])->name('users.show');
        Route::put('users/{id}', [Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Plans
        Route::get('plans', [Admin\PlanController::class, 'index'])->name('plans.index');
        Route::put('plans/{id}', [Admin\PlanController::class, 'update'])->name('plans.update');
        Route::delete('plans/{id}', [Admin\PlanController::class, 'destroy'])->name('plans.destroy');

        // Professionals
        Route::get('professionals', [Admin\ProfessionalController::class, 'index'])->name('professionals.index');
        Route::get('professionals/{id}', [Admin\ProfessionalController::class, 'show'])->name('professionals.show');
        Route::put('professionals/{id}', [Admin\ProfessionalController::class, 'update'])->name('professionals.update');
        Route::patch('professionals/{id}/status', [Admin\ProfessionalController::class, 'updateStatus'])->name('professionals.update-status');
        Route::delete('professionals/{id}', [Admin\ProfessionalController::class, 'destroy'])->name('professionals.destroy');

        // Professional Questions
        Route::get('professional-questions', [ProfessionalQuestionController::class, 'index'])->name('professional-questions.index');
        Route::get('professional-questions/create', [ProfessionalQuestionController::class, 'create'])->name('professional-questions.create');
        Route::post('professional-questions', [ProfessionalQuestionController::class, 'store'])->name('professional-questions.store');
        Route::get('professional-questions/{id}/edit', [ProfessionalQuestionController::class, 'edit'])->name('professional-questions.edit');
        Route::put('professional-questions/{id}', [ProfessionalQuestionController::class, 'update'])->name('professional-questions.update');
        Route::delete('professional-questions/{id}', [ProfessionalQuestionController::class, 'destroy'])->name('professional-questions.destroy');

        // Contacts
        Route::get('contacts', [Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::get('contacts/{id}', [Admin\ContactController::class, 'show'])->name('contacts.show');
        Route::delete('contacts/{id}', [Admin\ContactController::class, 'destroy'])->name('contacts.destroy');

        // Traffic
        Route::get('traffic', [Admin\TrafficController::class, 'index'])->name('traffic.index');

        // Subscribers
        Route::get('subscribers', [Admin\SubscriberController::class, 'index'])->name('subscribers.index');
        Route::delete('subscribers/{id}', [Admin\SubscriberController::class, 'destroy'])->name('subscribers.destroy');

        // Email Templates (edit-only)
        Route::get('email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
        Route::get('email-templates/{template}/edit', [EmailTemplateController::class, 'edit'])->name('email-templates.edit');
        Route::put('email-templates/{template}', [EmailTemplateController::class, 'update'])->name('email-templates.update');

    });
});

Route::prefix('professionals')->name('professional.')->group(function () {
    Route::middleware('professional.guest')->group(function () {
        Route::get('login', [PortalController::class, 'showLogin'])->name('login');
        Route::post('login', [PortalController::class, 'login'])->name('login.post');
    });
});

Route::prefix('professionals/dashboard')
    ->name('professional.')
    ->group(function () {
        // Chat routes - accessible to authenticated portal users
        // Route::middleware('professional.auth')->group(function () {
        // });
        
        // Professional-only routes
        Route::middleware(['professional.auth', 'questions.answered', 'professional.redirect_buyer_from_seller'])->group(function () {
            // Onboarding (exempt handled inside middleware via routeIs check)
            Route::get('onboarding', [OnboardingController::class, 'show'])->name('onboarding');
            Route::post('onboarding', [OnboardingController::class, 'submit'])->name('onboarding.submit');

            Route::get('/', [PortalController::class, 'dashboard'])->name('dashboard');
            Route::get('plan', [PlanController::class, 'index'])->name('plans.index');
            Route::get('listings', [ProjectController::class, 'index'])->name('listings.index');
            Route::get('listings/create', [ProjectController::class, 'create'])->name('listings.create');
            Route::get('listings/{id}', [ProjectController::class, 'show'])->name('listings.show');
            Route::get('listings/{id}/edit', [ProjectController::class, 'edit'])->name('listings.edit');
            Route::post('listings', [ProjectController::class, 'store'])->name('listings.store');
            Route::put('listings/{id}', [ProjectController::class, 'update'])->name('listings.update');
            Route::patch('listings/{id}/status', [ProjectController::class, 'updateStatus'])->name('listings.update-status');
            Route::post('listings/{id}/mark-premium', [ProjectController::class, 'markPremium'])->name('listings.mark-premium');
            Route::delete('listings/{id}', [ProjectController::class, 'destroy'])->name('listings.destroy');
            Route::redirect('project', 'listings');
            Route::redirect('project/create', 'listings/create');
            Route::get('project/{id}', fn (int $id) => redirect()->route('professional.listings.show', $id));
            Route::get('setting', [SettingsController::class, 'index'])->name('settings');
            Route::put('setting', [SettingsController::class, 'updateProfile'])->name('settings.update');
            Route::put('setting/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
            Route::post('setting/avatar', [SettingsController::class, 'updateAvatar'])->name('settings.avatar');
            Route::post('setting/company-logo', [SettingsController::class, 'updateCompanyLogo'])->name('settings.logo');
            Route::get('chat', [ChatController::class, 'index'])->name('chat');
            Route::post('chat/send', [ChatController::class, 'send'])->name('chat.send');
        });
    });

Route::post('professionals/logout', [PortalController::class, 'logout'])
    ->middleware('professional.auth')
    ->name('professional.logout');

Route::redirect('login/professionals', '/professionals/login');
Route::redirect('login/professionals/', '/professionals/login');
Route::redirect('professional/login', '/professionals/login');
Route::redirect('professional/login/', '/professionals/login');
Route::get('dashboard/professionals/{path?}', function (Illuminate\Http\Request $request, string $path = null) {
    $target = '/professionals/dashboard'.($path ? '/'.ltrim($path, '/') : '');
    $query = $request->getQueryString();

    return redirect($query ? $target.'?'.$query : $target);
})->where('path', '.*');

// Universal Chat Entry Point - works for both users and professionals
Route::get('chat', function (Illuminate\Http\Request $request) {
    if (! \Illuminate\Support\Facades\Auth::check()) {
        return redirect('/')
            ->withErrors(['error' => 'Please login to access the chat.'])
            ->with('intended_url', $request->fullUrl());
    }

    return redirect()->route('professional.chat', $request->query());
})->name('chat');

// Redirect root to professional login
Route::get('/', fn () => redirect()->route('professional.login'));
