<?php

namespace Tests\Feature;

use App\Helpers\SubscriptionHelper;
use App\Http\Controllers\WebsiteApiController;
use App\Models\ProfessionalQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WebsiteSignupFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_complete_lead_based_signup_and_submit_answers(): void
    {
        Mail::fake();
        $controller = $this->app->make(WebsiteApiController::class);
        $subscriptionService = $this->app->make(\App\Services\SubscriptionService::class);

        $question = ProfessionalQuestion::query()->create([
            'role' => 'buyer',
            'question' => 'What type of businesses are you looking for?',
            'answer' => '',
            'sort_order' => 1,
            'status' => 1,
            'code' => md5('buyer-question'),
        ]);

        $email = 'buyer@example.com';

        $sendCodeResponse = $controller->SendWebsiteSignupVerificationCode(Request::create('/api/website/signup/send-code', 'POST', [
            'email' => $email,
        ]));
        $this->assertSame(200, $sendCodeResponse->getStatusCode());

        $otp = Cache::get('website_signup_email_code:' . sha1(strtolower(trim($email))))['otp'];

        $verifyResponse = $controller->VerifyWebsiteSignupVerificationCode(Request::create('/api/website/signup/verify-code', 'POST', [
            'email' => $email,
            'email_verification_code' => $otp,
        ]));
        $this->assertSame(200, $verifyResponse->getStatusCode());

        $verificationToken = $verifyResponse->getData(true)['data']['verification_token'];

        $leadResponse = $controller->CreateWebsiteSignupLead(Request::create('/api/website/signup/lead', 'POST', [
            'role' => 'buyer',
            'first_name' => 'Lead',
            'last_name' => 'Buyer',
            'email' => $email,
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'verification_token' => $verificationToken,
        ]));
        $this->assertSame(201, $leadResponse->getStatusCode());

        $leadPayload = $leadResponse->getData(true)['data']['lead'];
        $leadId = $leadPayload['id'];
        $leadCode = $leadPayload['code'];

        $completeResponse = $controller->CompleteWebsiteSignup(
            Request::create('/api/website/signup/complete', 'POST', [
            'lead_id' => $leadId,
            'lead_code' => $leadCode,
            ]),
            $subscriptionService
        );
        $this->assertSame(201, $completeResponse->getStatusCode());

        $completePayload = $completeResponse->getData(true)['data'];
        $token = $completePayload['token'];
        $userId = $completePayload['user']['id'];

        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'email' => $email,
            'role' => 'buyer',
            'verified' => 1,
        ]);

        $this->assertDatabaseMissing('plans', [
            'professional_id' => $userId,
        ]);

        $this->assertDatabaseMissing('signup_leads', [
            'id' => $leadId,
        ]);

        $questionResponse = $controller->GetProfessionalQuestionsByRole('buyer');
        $this->assertSame(200, $questionResponse->getStatusCode());
        $this->assertSame($question->id, $questionResponse->getData(true)['data'][0]['id']);

        $plansResponse = $controller->GetWebsitePlans(Request::create('/api/website/plans', 'GET', [
            'role' => 'buyer',
        ]));
        $this->assertSame([], $plansResponse->getData(true)['data']);

        $answerRequest = Request::create('/api/website/professional-question-answers', 'POST', [
            'answers' => [
                [
                    'professional_question_id' => $question->id,
                    'answer' => 'I am looking for service-based businesses.',
                ],
            ],
        ]);
        $answerRequest->headers->set('Authorization', 'Bearer ' . $token);
        $answerRequest->setUserResolver(fn () => \App\Models\User::query()->findOrFail($userId));

        $answerResponse = $controller->SubmitProfessionalQuestionAnswers($answerRequest);
        $this->assertSame(200, $answerResponse->getStatusCode());
        $this->assertSame(
            $question->id,
            $answerResponse->getData(true)['data'][0]['professional_question_id']
        );

        $this->assertDatabaseHas('professional_question_answers', [
            'professional_question_id' => $question->id,
            'user_id' => $userId,
            'answer' => 'I am looking for service-based businesses.',
        ]);
    }
}
