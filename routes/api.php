<?php

use App\Http\Controllers\WebsiteApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes — Website
|--------------------------------------------------------------------------
*/
Route::prefix('website')->group(function () {
    Route::get('projects', [WebsiteApiController::class, 'GetAllProjects']);
    Route::get('projects/{url}', [WebsiteApiController::class, 'GetProjectByUrl']);
    Route::get('GetSimilarProjects', [WebsiteApiController::class, 'GetSimilarProjects']);
    Route::get('blogs', [WebsiteApiController::class, 'GetAllBlogs']);
    Route::get('blog-categories', [WebsiteApiController::class, 'GetBlogCategoriesForWebsite']);
    Route::get('blog-tags', [WebsiteApiController::class, 'GetBlogTagsForWebsite']);
    Route::get('GetAllRecentBlogs', [WebsiteApiController::class, 'GetAllRecentBlogs']);
    Route::get('blogs/{url}', [WebsiteApiController::class, 'GetBlogByUrl']);
    Route::post('blogs/{id}/comment', [WebsiteApiController::class, 'AddBlogComment']);
    Route::get('categories', [WebsiteApiController::class, 'GetAllProjectCategories']);
    Route::get('locations', [WebsiteApiController::class, 'GetAllProjectLocations']);
    Route::get('regions', [WebsiteApiController::class, 'GetAllRegions']);
    Route::get('plans', [WebsiteApiController::class, 'GetWebsitePlans']);
    Route::get('professional-questions', [WebsiteApiController::class, 'GetAllProfessionalQuestions']);
    Route::get('professional-questions/{role}', [WebsiteApiController::class, 'GetProfessionalQuestionsByRole']);
    Route::post('login', [WebsiteApiController::class, 'WebsiteLogin']);
    Route::post('signup/send-code', [WebsiteApiController::class, 'SendWebsiteSignupVerificationCode']);
    Route::post('signup/verify-code', [WebsiteApiController::class, 'VerifyWebsiteSignupVerificationCode']);
    Route::post('signup/lead', [WebsiteApiController::class, 'CreateWebsiteSignupLead']);
    Route::post('signup/complete', [WebsiteApiController::class, 'CompleteWebsiteSignup']);
    Route::post('plans/subscribe', [WebsiteApiController::class, 'SubscribeWebsitePlan']);
    Route::post('signup/lead/delete', [WebsiteApiController::class, 'DeleteWebsiteSignupLead']);
    Route::post('signup', [WebsiteApiController::class, 'WebsiteSignup']);
    Route::post('RegisterNewUser', [WebsiteApiController::class, 'RegisterNewUser']);
    Route::post('password/forgot', [WebsiteApiController::class, 'SendPasswordResetCode']);
    Route::post('password/resend-code', [WebsiteApiController::class, 'SendPasswordResetCode']);
    Route::post('password/verify-code', [WebsiteApiController::class, 'VerifyPasswordResetCode']);
    Route::post('password/reset', [WebsiteApiController::class, 'ResetPassword']);
    Route::post('contact', [WebsiteApiController::class, 'SaveContactForm']);
    Route::post('subscribe', [WebsiteApiController::class, 'SaveNewsLetterForm']);
    Route::post('GetStripeToken', [WebsiteApiController::class, 'GetStripeToken']);
    Route::get('professional-question-answers', [WebsiteApiController::class, 'GetProfessionalQuestionAnswers']);
    Route::post('professional-question-answers', [WebsiteApiController::class, 'SubmitProfessionalQuestionAnswers']);

});
