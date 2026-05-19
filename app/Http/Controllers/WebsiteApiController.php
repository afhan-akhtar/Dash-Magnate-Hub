<?php

namespace App\Http\Controllers;

use App\Helpers\SubscriptionHelper;
use App\Mail\account_otp;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Location;
use App\Models\ProfessionalQuestion;
use App\Models\ProfessionalQuestionAnswer;
use App\Models\Project;
use App\Models\Region;
use App\Models\SignupLead;
use App\Models\Subscriber;
use App\Models\Tag;
use App\Models\Transaction;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Stripe\Charge;
use Stripe\Stripe;

class WebsiteApiController extends Controller
{
    private const SIGNUP_EMAIL_CODE_TTL_MINUTES = 15;

    private const SIGNUP_VERIFICATION_TOKEN_TTL_MINUTES = 30;

    private const PASSWORD_RESET_EMAIL_CODE_TTL_MINUTES = 15;

    private const PASSWORD_RESET_TOKEN_TTL_MINUTES = 30;

    public function __construct(private SubscriptionService $subscriptionService) {}

    public function GetAllProjects(Request $request): JsonResponse
    {
        $query = Project::query()
            ->with($this->projectPublicApiEagerLoads())
            ->visibleOnWebsite()
            ->where('sold', 0)
            ->latest();

        if ($request->filled('id')) {
            $query->whereKey($request->id);
        } else {
            $this->applyProjectFilters($query, $request);
        }

        $perPage = (int) ($request->input('limit', $request->input('per_page', 12)));
        $perPage = $perPage > 0 ? min($perPage, 100) : 12;
        $direction = strtolower((string) $request->input('order_by', 'desc')) === 'asc' ? 'asc' : 'desc';

        $projects = $query
            ->orderBy('views', $direction)
            ->paginate($perPage)
            ->through(fn (Project $project) => $this->transformProject($project));

        return response()->json([
            'success' => true,
            'message' => 'Projects retrieved successfully.',
            'data' => $projects,
        ]);
    }

    public function GetProjectByUrl(string $url): JsonResponse
    {
        $project = Project::query()
            ->with($this->projectPublicApiEagerLoads())
            ->where('url', $url)
            ->visibleOnWebsite()
            ->where('sold', 0)
            ->first();

        if (! $project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found.',
            ], 404);
        }

        $project->increment('views');
        $project->refresh();
        $project->load($this->projectPublicApiEagerLoads());

        return response()->json([
            'success' => true,
            'message' => 'Project retrieved successfully.',
            'data' => $this->transformProject($project, true),
        ]);
    }

    public function GetSimilarProjects(Request $request): JsonResponse
    {
        if (! $request->filled('project_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Project ID (project_id) is required.',
            ], 400);
        }

        $project = Project::query()->find($request->project_id);

        if (! $project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found or incorrect project_id.',
            ], 404);
        }

        $similarProjects = Project::query()
            ->with(['category', 'documents'])
            ->where('category_id', $project->category_id)
            ->where('id', '!=', $project->id)
            ->visibleOnWebsite()
            ->where('sold', 0)
            ->orderByDesc('views')
            ->limit(5)
            ->get()
            ->map(fn (Project $item) => [
                'id' => $item->id,
                'name' => $item->name,
                'title_image' => $this->projectTitleImage($item),
                'images' => $this->projectImages($item),
                'url' => $item->url,
                'created_at' => optional($item->created_at)?->toDateTimeString(),
                'category_name' => $item->category?->name,
                'urgent_sale' => (int) ($item->urgent_sale ?? 0),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Similar projects retrieved successfully.',
            'data' => $similarProjects,
        ]);
    }

    public function GetAllProjectCategories(): JsonResponse
    {
        $categories = Category::query()
            ->where('active', 1)
            ->where('status', 0)
            ->with('thumbnail')
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'url' => $category->url,
                'image' => $category->thumbnail?->url,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully.',
            'data' => $categories,
        ]);
    }

    public function GetAllProjectLocations(): JsonResponse
    {
        $locations = Location::query()
            ->where('active', 1)
            ->where('status', 0)
            ->with('thumbnail')
            ->orderBy('name')
            ->get()
            ->map(fn (Location $location) => [
                'id' => $location->id,
                'name' => $location->name,
                'url' => $location->url,
                'image' => $location->thumbnail?->url,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Locations retrieved successfully.',
            'data' => $locations,
        ]);
    }

    public function GetAllRegions(Request $request): JsonResponse
    {
        $query = Region::query()->select(['id', 'location_id', 'name'])->orderBy('name');

        if ($request->filled('locationId')) {
            $query->where('location_id', $request->locationId);
        }

        $regions = $query->get()->map(fn (Region $region) => [
            'id' => $region->id,
            'locationId' => $region->location_id,
            'name' => $region->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Regions retrieved successfully.',
            'data' => $regions,
        ]);
    }

    public function GetAllProfessionalQuestions(): JsonResponse
    {
        $questions = ProfessionalQuestion::query()
            ->where('status', 1)
            ->with(['options' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('id')])
            ->orderBy('role')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (ProfessionalQuestion $question) => [
                'id' => $question->id,
                'role' => $question->role,
                'question' => $question->question,
                'type' => $question->type,
                'is_required' => (bool) $question->is_required,
                'sort_order' => $question->sort_order,
                'options' => $question->options->map(fn ($opt) => [
                    'id' => $opt->id,
                    'label' => $opt->label,
                    'value' => $opt->value,
                    'sort_order' => $opt->sort_order,
                ])->values(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Professional questions retrieved successfully.',
            'data' => $questions,
        ]);
    }

    public function GetProfessionalQuestionsByRole(string $role): JsonResponse
    {
        if (! in_array($role, ProfessionalQuestion::ROLES, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid professional role.',
            ], 422);
        }

        $questions = ProfessionalQuestion::query()
            ->where('status', 1)
            ->where('role', $role)
            ->with(['options' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')->orderBy('id')])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (ProfessionalQuestion $question) => [
                'id' => $question->id,
                'role' => $question->role,
                'question' => $question->question,
                'type' => $question->type,
                'is_required' => (bool) $question->is_required,
                'sort_order' => $question->sort_order,
                'options' => $question->options->map(fn ($opt) => [
                    'id' => $opt->id,
                    'label' => $opt->label,
                    'value' => $opt->value,
                    'sort_order' => $opt->sort_order,
                ])->values(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Professional questions retrieved successfully.',
            'data' => $questions,
        ]);
    }

    public function GetWebsitePlans(Request $request): JsonResponse
    {
        $role = $request->input('role');
        $plans = collect($this->availablePlanIdsForRole($role !== null ? (string) $role : null))
            ->filter(fn (int $planId) => $role === null || $this->isPlanAllowedForRole((string) $role, $planId))
            ->map(fn (int $planId) => $this->websitePlanConfig($planId))
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Plans retrieved successfully.',
            'data' => $plans,
        ]);
    }

    public function GetAllBlogs(Request $request, string $url = null): JsonResponse
    {
        if ($url !== null && $url !== '') {
            return $this->GetBlogByUrl($url);
        }

        $validated = $request->validate([
            'per_page' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:100'],
            'blog_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'search' => ['sometimes', 'nullable', 'string', 'max:200'],
            'category_id' => ['sometimes', 'nullable', 'integer', 'min:1', 'exists:blog_categories,id'],
            'category_slug' => ['sometimes', 'nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/i'],
            'tag_id' => ['sometimes', 'nullable', 'integer', 'min:1', 'exists:tags,id'],
            'tag_slug' => ['sometimes', 'nullable', 'string', 'max:140', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/i'],
        ]);

        $perPage = max(1, min((int) ($validated['per_page'] ?? $request->input('per_page', 12)), 100));

        $query = Blog::query()
            ->withCount(['comments' => fn (Builder $builder) => $builder->where('status', 0)])
            ->with('thumbnail')
            ->when(method_exists(Blog::class, 'tags'), fn ($q) => $q->with('tags'))
            ->when(method_exists(Blog::class, 'blogCategory'), fn ($q) => $q->with('blogCategory'))
            ->where('active', 1)
            ->orderByDesc('id');

        if (! empty($validated['blog_id'])) {
            $query->whereKey($validated['blog_id']);
        }

        if (! empty($validated['search'])) {
            $term = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], trim($validated['search']));
            $query->where('name', 'like', '%'.$term.'%');
        }

        if (! empty($validated['category_id'])) {
            $query->where('blog_category_id', (int) $validated['category_id']);
        } elseif (! empty($validated['category_slug']) && method_exists(Blog::class, 'blogCategory')) {
            $slug = Str::lower($validated['category_slug']);
            $query->whereHas('blogCategory', fn (Builder $q) => $q->where('slug', $slug)->where('status', 1));
        }

        $tagSlugs = $this->normalizeBlogTagSlugFilter($request, $validated);
        if ($tagSlugs !== [] && method_exists(Blog::class, 'tags')) {
            $query->whereHas('tags', fn (Builder $q) => $q->whereIn('slug', $tagSlugs));
        } elseif (! empty($validated['tag_id']) && method_exists(Blog::class, 'tags')) {
            $query->whereHas('tags', fn (Builder $q) => $q->whereKey((int) $validated['tag_id']));
        }

        $blogs = $query->paginate($perPage)->through(fn (Blog $blog) => $this->transformBlog($blog));

        return response()->json([
            'success' => true,
            'message' => 'Blogs retrieved successfully.',
            'data' => $blogs,
        ]);
    }

    /**
     * Public blog categories with how many active posts use each (for filters / nav).
     */
    public function GetBlogCategoriesForWebsite(): JsonResponse
    {
        $categories = BlogCategory::query()
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->withCount([
                'blogs' => fn (Builder $q) => $q->where('active', 1),
            ])
            ->get()
            ->map(fn (BlogCategory $c) => [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'blog_count' => (int) $c->blogs_count,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Blog categories retrieved successfully.',
            'data' => $categories,
        ]);
    }

    /**
     * Public blog tags with how many active posts use each (for filters / nav).
     */
    public function GetBlogTagsForWebsite(): JsonResponse
    {
        $tags = Tag::query()
            ->orderBy('name')
            ->withCount([
                'blogs' => fn (Builder $q) => $q->where('active', 1),
            ])
            ->get()
            ->map(fn (Tag $t) => [
                'id' => $t->id,
                'name' => $t->name,
                'slug' => $t->slug,
                'blog_count' => (int) $t->blogs_count,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Blog tags retrieved successfully.',
            'data' => $tags,
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return list<string>
     */
    private function normalizeBlogTagSlugFilter(Request $request, array $validated): array
    {
        $raw = $request->query('tag_slugs');
        $slugs = [];

        if (is_array($raw)) {
            $slugs = $raw;
        } elseif (is_string($raw) && $raw !== '') {
            $slugs = preg_split('/\s*,\s*/', $raw, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        }

        $slugs = array_map(static fn ($s) => Str::lower(trim((string) $s)), $slugs);
        $slugs = array_values(array_filter($slugs, static fn (string $s) => $s !== '' && preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $s)));

        if (! empty($validated['tag_slug'])) {
            $slugs[] = Str::lower($validated['tag_slug']);
        }

        return array_values(array_unique($slugs));
    }

    public function GetBlogByUrl(string $url): JsonResponse
    {
        $blog = Blog::query()
            ->with(['comments' => fn ($query) => $query->where('status', 0)->latest(), 'documents'])
            ->when(method_exists(Blog::class, 'tags'), fn ($q) => $q->with('tags'))
            ->when(method_exists(Blog::class, 'blogCategory'), fn ($q) => $q->with('blogCategory'))
            ->where('url', $url)
            ->where('active', 1)
            ->first();

        if (! $blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found.',
            ], 404);
        }

        $blog->increment('views');
        $blog->refresh();
        $blog->load(['comments' => fn ($query) => $query->where('status', 0)->latest(), 'documents']);
        if (method_exists(Blog::class, 'tags')) {
            $blog->load('tags');
        }
        if (method_exists(Blog::class, 'blogCategory')) {
            $blog->load('blogCategory');
        }

        return response()->json([
            'success' => true,
            'message' => 'Blog retrieved successfully.',
            'data' => [
                'blog' => $this->transformBlog($blog, true),
                'comments' => $blog->comments->map(fn (Comment $comment) => [
                    'id' => $comment->id,
                    'name' => $comment->name,
                    'email' => $comment->email,
                    'phone' => $comment->phone,
                    'message' => $comment->message,
                    'created_at' => optional($comment->created_at)?->toDateTimeString(),
                ]),
            ],
        ]);
    }

    public function GetAllRecentBlogs(): JsonResponse
    {
        $blogs = Blog::query()
            ->where('active', 1)
            ->with('thumbnail')
            ->when(method_exists(Blog::class, 'tags'), fn ($q) => $q->with('tags'))
            ->when(method_exists(Blog::class, 'blogCategory'), fn ($q) => $q->with('blogCategory'))
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (Blog $blog) => $this->transformBlog($blog));

        return response()->json([
            'success' => true,
            'message' => 'Recent blogs retrieved successfully.',
            'data' => $blogs,
        ]);
    }

    public function AddBlogComment(Request $request, int $id = null): JsonResponse
    {
        $validated = $request->validate([
            'blog_id' => ['nullable', 'integer', 'exists:blogs,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'message' => ['required', 'string'],
        ]);

        $blogId = $id ?? $validated['blog_id'] ?? null;

        if (! $blogId) {
            return response()->json([
                'success' => false,
                'message' => 'Blog ID is required.',
            ], 422);
        }

        $comment = Comment::query()->create([
            'blog_id' => $blogId,
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
            'status' => 0,
            'code' => uniqid('CMT-'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment submitted successfully and is pending approval.',
            'data' => [
                'id' => $comment->id,
            ],
        ], 201);
    }

    public function SaveContactForm(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:1000'],
            'subject' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $contact = Contact::query()->create([
            'type' => 'Contact Form',
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        $contact->update(['code' => md5((string) $contact->id)]);

        return response()->json([
            'status' => true,
            'message' => 'Form saved successfully!',
            'data' => $contact->fresh(),
        ], 201);
    }

    public function SaveNewsLetterForm(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('subscribers', 'email'),
            ],
        ], [
            'email.unique' => 'You have already subscribed for newsletter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $subscriber = Subscriber::query()->create([
            'email' => $request->email,
            'status' => 0,
            'code' => md5(strtolower($request->email).now()->timestamp),
        ]);

        Contact::query()->create([
            'type' => 'News Letter Form',
            'email' => $request->email,
            'code' => md5((string) $subscriber->id),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Form saved successfully!',
            'data' => $subscriber,
        ], 201);
    }

    public function RegisterNewUser(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'integer'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::query()->create([
            'role' => $this->mapLegacyTypeToRole((int) $request->input('type', 0)),
            'name' => trim($request->first_name.' '.$request->last_name),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'otp' => random_int(100000, 999999),
            'verified' => 0,
            'status' => 0,
            'is_deleted' => 0,
        ]);

        $user->update(['code' => md5((string) $user->id)]);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'data' => [
                'id' => $user->id,
                'role' => $user->role,
                'email' => $user->email,
                'otp' => $user->otp,
            ],
        ], 201);
    }

    public function WebsiteLogin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::query()
            ->where('email', $request->email)
            ->where('role', '!=', 'admin')
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if (! $user->verified) {
            return response()->json([
                'success' => false,
                'status' => false,
                'message' => 'Please verify your account first.',
            ], 403);
        }

        $token = $user->createToken('auth_token', ['role:'.$user->role])->plainTextToken;

        return response()->json([
            'success' => true,
            'status' => true,
            'message' => 'Login successful.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'role' => $user->role,
                    'name' => $user->full_name ?: $user->name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'verified' => (bool) $user->verified,
                ],
            ],
        ]);
    }

    public function WebsiteSignup(Request $request, SubscriptionService $subscriptionService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role' => ['required', 'string', Rule::in(User::PROFESSIONAL_ROLES)],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'plan_id' => ['nullable', 'integer', Rule::in(array_keys(self::WEBSITE_PLAN_PRICES))],
            'email_verification_code' => ['required', 'digits:6'],
            'stripe_token' => ['nullable', 'string'],
            'token' => ['nullable', 'string'],
            'billing_first_name' => ['nullable', 'string', 'max:255'],
            'billing_last_name' => ['nullable', 'string', 'max:255'],
            'billing_business_name' => ['nullable', 'string', 'max:255'],
            'billing_abn' => ['nullable', 'string', 'max:50'],
            'billing_email' => ['nullable', 'string', 'email', 'max:255'],
            'billing_phone' => ['nullable', 'string', 'max:50'],
            'billing_address' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $role = (string) $request->role;
        $planId = $request->filled('plan_id') ? (int) $request->plan_id : null;
        $stripeToken = (string) ($request->input('stripe_token') ?: $request->input('token', ''));
        $verificationCode = (string) $request->email_verification_code;

        if (! $this->hasValidSignupEmailCode($request->email, $verificationCode)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired email verification code.',
            ], 422);
        }

        if ($this->roleRequiresPlan($role) && $planId === null) {
            return response()->json([
                'success' => false,
                'message' => 'The selected role requires a plan.',
            ], 422);
        }

        if ($planId !== null && ! $this->isPlanAllowedForRole($role, $planId)) {
            return response()->json([
                'success' => false,
                'message' => 'The selected plan is not available for the chosen role.',
            ], 422);
        }

        $billingValidator = Validator::make(
            $request->all(),
            $this->billingDetailsRules($this->roleRequiresPlan($role))
        );
        if ($billingValidator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $billingValidator->errors(),
            ], 422);
        }

        $billingDetails = $this->extractBillingDetails($request);

        $planConfig = $planId !== null ? $this->websitePlanConfig($planId) : null;
        $amount = $planConfig['price'] ?? 0;
        $stripeCharge = null;
        $stripeCard = null;

        if ($amount > 0) {
            if ($stripeToken === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe token is required for paid plans.',
                ], 422);
            }

            if ((string) env('STRIPE_SECRET') === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe is not configured on the server.',
                ], 500);
            }

            try {
                Stripe::setApiKey((string) env('STRIPE_SECRET'));

                $retrievedToken = \Stripe\Token::retrieve($stripeToken);
                $stripeCard = $retrievedToken->card ?? null;

                $stripeCharge = Charge::create([
                    'amount' => (int) round($amount * 100),
                    'currency' => 'aud',
                    'source' => $stripeToken,
                    'description' => sprintf(
                        '%s website signup for %s',
                        $planConfig['name'] ?? 'Signup',
                        $request->email
                    ),
                    'metadata' => [
                        'role' => $role,
                        'plan_id' => $planId !== null ? (string) $planId : '',
                        'email' => $request->email,
                    ],
                ]);

                if (($stripeCharge->status ?? null) !== 'succeeded') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stripe payment could not be verified.',
                    ], 402);
                }
            } catch (\Throwable $exception) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                ], 402);
            }
        }

        $user = null;
        $plan = null;
        $transaction = null;

        DB::transaction(function () use (
            $request,
            $role,
            $planId,
            $planConfig,
            $billingDetails,
            $subscriptionService,
            $stripeCharge,
            $stripeCard,
            &$user,
            &$plan,
            &$transaction
        ): void {
            $user = User::query()->create([
                'role' => $role,
                'name' => trim($request->first_name.' '.$request->last_name),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'otp' => random_int(100000, 999999),
                'verified' => 1,
                'status' => 0,
                'is_deleted' => 0,
            ]);

            $user->update(['code' => md5((string) $user->id)]);

            if ($planId !== null && $planConfig !== null) {
                $plan = $subscriptionService->createPlan($user, [
                    'type' => $planId,
                    'name' => $planConfig['name'],
                    'slug' => $planConfig['slug'],
                    'price' => $planConfig['price'],
                    'listing_limit' => $planConfig['listing_limit'],
                    'duration_days' => $planConfig['duration_days'],
                    'timeframe_label' => $planConfig['timeframe_label'],
                    'premium_marking_limit' => $planConfig['premium_marking_limit'],
                    'inclusions' => $planConfig['inclusions'],
                    ...$billingDetails,
                ]);
            }

            if ($stripeCharge !== null) {
                $transaction = Transaction::query()->create([
                    'professional_id' => $user->id,
                    'plan_id' => $plan?->id,
                    'name' => $planConfig['name'] ?? null,
                    'number' => $stripeCard?->last4 ? '**** **** **** '.$stripeCard->last4 : $stripeCharge->id,
                    'expiry_date' => $stripeCard?->exp_month && $stripeCard?->exp_year
                        ? sprintf('%02d/%s', $stripeCard->exp_month, $stripeCard->exp_year)
                        : null,
                    'cvc' => null,
                    'code' => $stripeCharge->id,
                ]);
            }
        });

        $this->clearSignupEmailCode($request->email);

        return response()->json([
            'success' => true,
            'message' => 'Signup completed successfully.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'role' => $user->role,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                ],
                'plan' => $plan !== null && $planConfig !== null ? [
                    'id' => $plan->id,
                    'plan_id' => $planId,
                    'name' => $planConfig['name'],
                    'price' => $planConfig['price'],
                    'listing_limit' => $planConfig['listing_limit'],
                    'expiry' => optional($plan->expiry)?->format('Y-m-d'),
                    'billing_details' => $billingDetails,
                ] : null,
                'payment' => [
                    'required' => $amount > 0,
                    'verified' => $stripeCharge !== null ? ($stripeCharge->status === 'succeeded') : true,
                    'transaction_id' => $transaction?->id,
                    'stripe_charge_id' => $stripeCharge?->id,
                    'amount' => $amount,
                    'currency' => 'AUD',
                ],
            ],
        ], 201);
    }

    public function VerifyWebsiteSignupVerificationCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'email_verification_code' => ['required', 'digits:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $verificationCode = (string) $request->email_verification_code;

        if (! $this->hasValidSignupEmailCode($request->email, $verificationCode)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired email verification code.',
            ], 422);
        }

        $verificationToken = $this->storeSignupVerificationToken($request->email);

        return response()->json([
            'success' => true,
            'message' => 'Verification code confirmed successfully.',
            'data' => [
                'email' => $request->email,
                'verification_token' => $verificationToken,
                'expires_in_minutes' => self::SIGNUP_VERIFICATION_TOKEN_TTL_MINUTES,
            ],
        ]);
    }

    public function CreateWebsiteSignupLead(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role' => ['required', 'string', Rule::in(User::PROFESSIONAL_ROLES)],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'verification_token' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (! $this->hasValidSignupVerificationToken($request->email, (string) $request->verification_token)) {
            return response()->json([
                'success' => false,
                'message' => 'Email verification has expired. Please verify your email again.',
            ], 422);
        }

        $lead = SignupLead::query()->updateOrCreate(
            ['email' => $request->email],
            [
                'role' => $request->role,
                'name' => trim($request->first_name.' '.$request->last_name),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'code' => Str::random(40),
            ]
        );

        $this->clearSignupEmailCode($request->email);
        $this->clearSignupVerificationToken($request->email);

        return response()->json([
            'success' => true,
            'message' => 'Signup lead created successfully.',
            'data' => [
                'lead' => [
                    'id' => $lead->id,
                    'code' => $lead->code,
                    'role' => $lead->role,
                    'first_name' => $lead->first_name,
                    'last_name' => $lead->last_name,
                    'email' => $lead->email,
                    'email_verified_at' => optional($lead->email_verified_at)?->toDateTimeString(),
                ],
            ],
        ], 201);
    }

    public function CompleteWebsiteSignup(Request $request, SubscriptionService $subscriptionService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => ['required', 'integer', 'exists:signup_leads,id'],
            'lead_code' => ['required', 'string'],
            'plan_id' => ['nullable', 'integer', Rule::in($this->availablePlanIdsForRole(null))],
            'stripe_token' => ['nullable', 'string'],
            'token' => ['nullable', 'string'],
            'billing_first_name' => ['nullable', 'string', 'max:255'],
            'billing_last_name' => ['nullable', 'string', 'max:255'],
            'billing_business_name' => ['nullable', 'string', 'max:255'],
            'billing_abn' => ['nullable', 'string', 'max:50'],
            'billing_email' => ['nullable', 'string', 'email', 'max:255'],
            'billing_phone' => ['nullable', 'string', 'max:50'],
            'billing_address' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $lead = SignupLead::query()->findOrFail($request->lead_id);

        if (! hash_equals($lead->code, (string) $request->lead_code)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid lead credentials.',
            ], 422);
        }

        if (User::query()->where('email', $lead->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A user with this email already exists.',
            ], 422);
        }

        $role = $lead->role;
        $planId = $request->filled('plan_id') ? (int) $request->plan_id : null;
        $stripeToken = (string) ($request->input('stripe_token') ?: $request->input('token', ''));

        if ($this->roleRequiresPlan($role) && $planId === null) {
            return response()->json([
                'success' => false,
                'message' => 'The selected role requires a plan.',
            ], 422);
        }

        if ($planId !== null && ! $this->isPlanAllowedForRole($role, $planId)) {
            return response()->json([
                'success' => false,
                'message' => 'The selected plan is not available for the chosen role.',
            ], 422);
        }

        $billingValidator = Validator::make(
            $request->all(),
            $this->billingDetailsRules($this->roleRequiresPlan($role))
        );
        if ($billingValidator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $billingValidator->errors(),
            ], 422);
        }

        $billingDetails = $this->extractBillingDetails($request);

        $planConfig = $planId !== null ? $this->websitePlanConfig($planId) : null;
        $amount = $planConfig['price'] ?? 0;
        $stripeCharge = null;
        $stripeCard = null;

        if ($amount > 0) {
            if ($stripeToken === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe token is required for paid plans.',
                ], 422);
            }

            if ((string) env('STRIPE_SECRET') === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe is not configured on the server.',
                ], 500);
            }

            try {
                Stripe::setApiKey((string) env('STRIPE_SECRET'));

                $retrievedToken = \Stripe\Token::retrieve($stripeToken);
                $stripeCard = $retrievedToken->card ?? null;

                $stripeCharge = Charge::create([
                    'amount' => (int) round($amount * 100),
                    'currency' => 'aud',
                    'source' => $stripeToken,
                    'description' => sprintf(
                        '%s website signup for %s',
                        $planConfig['name'] ?? 'Signup',
                        $lead->email
                    ),
                    'metadata' => [
                        'role' => $role,
                        'plan_id' => $planId !== null ? (string) $planId : '',
                        'email' => $lead->email,
                        'lead_id' => (string) $lead->id,
                    ],
                ]);

                if (($stripeCharge->status ?? null) !== 'succeeded') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stripe payment could not be verified.',
                    ], 402);
                }
            } catch (\Throwable $exception) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                ], 402);
            }
        }

        $user = null;
        $plan = null;
        $transaction = null;

        DB::transaction(function () use (
            $lead,
            $role,
            $planId,
            $planConfig,
            $billingDetails,
            $subscriptionService,
            $stripeCharge,
            $stripeCard,
            &$user,
            &$plan,
            &$transaction
        ): void {
            $user = User::query()->create([
                'role' => $role,
                'name' => $lead->name,
                'first_name' => $lead->first_name,
                'last_name' => $lead->last_name,
                'email' => $lead->email,
                'password' => $lead->password,
                'otp' => random_int(100000, 999999),
                'verified' => 1,
                'status' => 0,
                'is_deleted' => 0,
            ]);

            $user->update(['code' => md5((string) $user->id)]);

            if ($planId !== null && $planConfig !== null) {
                $plan = $subscriptionService->createPlan($user, [
                    'type' => $planId,
                    'name' => $planConfig['name'],
                    'slug' => $planConfig['slug'],
                    'price' => $planConfig['price'],
                    'listing_limit' => $planConfig['listing_limit'],
                    'duration_days' => $planConfig['duration_days'],
                    'timeframe_label' => $planConfig['timeframe_label'],
                    'premium_marking_limit' => $planConfig['premium_marking_limit'],
                    'inclusions' => $planConfig['inclusions'],
                    ...$billingDetails,
                ]);
            }

            if ($stripeCharge !== null) {
                $transaction = Transaction::query()->create([
                    'professional_id' => $user->id,
                    'plan_id' => $plan?->id,
                    'name' => $planConfig['name'] ?? null,
                    'number' => $stripeCard?->last4 ? '**** **** **** '.$stripeCard->last4 : $stripeCharge->id,
                    'expiry_date' => $stripeCard?->exp_month && $stripeCard?->exp_year
                        ? sprintf('%02d/%s', $stripeCard->exp_month, $stripeCard->exp_year)
                        : null,
                    'cvc' => null,
                    'code' => $stripeCharge->id,
                ]);
            }

            $lead->delete();
        });

        $token = $user->createToken('auth_token', ['role:'.$user->role])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Signup completed successfully.',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'role' => $user->role,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                ],
                'plan' => $plan !== null && $planConfig !== null ? [
                    'id' => $plan->id,
                    'plan_id' => $planId,
                    'name' => $planConfig['name'],
                    'price' => $planConfig['price'],
                    'listing_limit' => $planConfig['listing_limit'],
                    'expiry' => optional($plan->expiry)?->format('Y-m-d'),
                    'billing_details' => $billingDetails,
                ] : null,
                'payment' => [
                    'required' => $amount > 0,
                    'verified' => $stripeCharge !== null ? ($stripeCharge->status === 'succeeded') : true,
                    'transaction_id' => $transaction?->id,
                    'stripe_charge_id' => $stripeCharge?->id,
                    'amount' => $amount,
                    'currency' => 'AUD',
                ],
            ],
        ], 201);
    }

    public function SubscribeWebsitePlan(Request $request, SubscriptionService $subscriptionService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'plan_id' => ['required', 'integer', Rule::in($this->availablePlanIdsForRole(null))],
            'stripe_token' => ['nullable', 'string'],
            'token' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::query()->findOrFail((int) $request->user_id);
        $planId = (int) $request->plan_id;
        $stripeToken = (string) ($request->input('stripe_token') ?: $request->input('token', ''));

        if (! $user->isProfessional()) {
            return response()->json([
                'success' => false,
                'message' => 'Only professional users can subscribe to plans.',
            ], 422);
        }

        if (! $this->isPlanAllowedForRole($user->role, $planId)) {
            return response()->json([
                'success' => false,
                'message' => 'The selected plan is not available for this user role.',
            ], 422);
        }

        $billingValidator = Validator::make($request->all(), $this->billingDetailsRules(true));
        if ($billingValidator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $billingValidator->errors(),
            ], 422);
        }

        $billingDetails = $this->extractBillingDetails($request);
        $planConfig = $this->websitePlanConfig($planId);
        $amount = $planConfig['price'] ?? 0;
        $stripeCharge = null;
        $stripeCard = null;

        if ($amount > 0) {
            if ($stripeToken === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe token is required for paid plans.',
                ], 422);
            }

            if ((string) env('STRIPE_SECRET') === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe is not configured on the server.',
                ], 500);
            }

            try {
                Stripe::setApiKey((string) env('STRIPE_SECRET'));

                $retrievedToken = \Stripe\Token::retrieve($stripeToken);
                $stripeCard = $retrievedToken->card ?? null;

                $stripeCharge = Charge::create([
                    'amount' => (int) round($amount * 100),
                    'currency' => 'aud',
                    'source' => $stripeToken,
                    'description' => sprintf(
                        '%s plan subscription for %s',
                        $planConfig['name'] ?? 'Plan',
                        $user->email
                    ),
                    'metadata' => [
                        'user_id' => (string) $user->id,
                        'role' => (string) $user->role,
                        'plan_id' => (string) $planId,
                        'email' => (string) $user->email,
                    ],
                ]);

                if (($stripeCharge->status ?? null) !== 'succeeded') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stripe payment could not be verified.',
                    ], 402);
                }
            } catch (\Throwable $exception) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                ], 402);
            }
        }

        $plan = null;
        $transaction = null;

        DB::transaction(function () use (
            $user,
            $planId,
            $planConfig,
            $billingDetails,
            $subscriptionService,
            $stripeCharge,
            $stripeCard,
            &$plan,
            &$transaction
        ): void {
            $plan = $subscriptionService->createPlan($user, [
                'type' => $planId,
                'name' => $planConfig['name'],
                'slug' => $planConfig['slug'],
                'price' => $planConfig['price'],
                'listing_limit' => $planConfig['listing_limit'],
                'duration_days' => $planConfig['duration_days'],
                'timeframe_label' => $planConfig['timeframe_label'],
                'premium_marking_limit' => $planConfig['premium_marking_limit'],
                'inclusions' => $planConfig['inclusions'],
                ...$billingDetails,
            ]);

            if ($stripeCharge !== null) {
                $transaction = Transaction::query()->create([
                    'professional_id' => $user->id,
                    'plan_id' => $plan?->id,
                    'name' => $planConfig['name'] ?? null,
                    'number' => $stripeCard?->last4 ? '**** **** **** '.$stripeCard->last4 : $stripeCharge->id,
                    'expiry_date' => $stripeCard?->exp_month && $stripeCard?->exp_year
                        ? sprintf('%02d/%s', $stripeCard->exp_month, $stripeCard->exp_year)
                        : null,
                    'cvc' => null,
                    'code' => $stripeCharge->id,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Plan subscribed successfully.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'role' => $user->role,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                ],
                'plan' => [
                    'id' => $plan->id,
                    'plan_id' => $planId,
                    'name' => $planConfig['name'],
                    'price' => $planConfig['price'],
                    'listing_limit' => $planConfig['listing_limit'],
                    'expiry' => optional($plan->expiry)?->format('Y-m-d'),
                    'billing_details' => $billingDetails,
                ],
                'payment' => [
                    'required' => $amount > 0,
                    'verified' => $stripeCharge !== null ? ($stripeCharge->status === 'succeeded') : true,
                    'transaction_id' => $transaction?->id,
                    'stripe_charge_id' => $stripeCharge?->id,
                    'amount' => $amount,
                    'currency' => 'AUD',
                ],
            ],
        ], 201);
    }

    public function DeleteWebsiteSignupLead(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => ['required', 'integer', 'exists:signup_leads,id'],
            'lead_code' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $lead = SignupLead::query()->findOrFail($request->lead_id);

        if (! hash_equals($lead->code, (string) $request->lead_code)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid lead credentials.',
            ], 422);
        }

        $lead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Signup lead deleted successfully.',
        ]);
    }

    public function SendPasswordResetCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = strtolower(trim($request->email));
        $user = User::query()
            ->where('email', $email)
            ->where('role', '!=', 'admin')
            ->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $otp = (string) random_int(100000, 999999);

        try {
            $this->storePasswordResetEmailCode($email, $otp);
            $this->clearPasswordResetToken($email);

            $emailSent = true;
            try {
                Mail::to($email)->send(new account_otp([
                    'otp' => $otp,
                    'email' => $email,
                ]));
            } catch (\Throwable $mailException) {
                $emailSent = false;
                Log::warning('Password reset email failed', [
                    'email' => $email,
                    'error' => $mailException->getMessage(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Password reset code generated'.($emailSent ? ' and emailed successfully.' : ' but email delivery failed.'),
                'data' => [
                    'email' => $email,
                    'otp' => $otp,
                    'expires_in_minutes' => self::PASSWORD_RESET_EMAIL_CODE_TTL_MINUTES,
                    'email_sent' => $emailSent,
                ],
            ]);
        } catch (\Throwable $exception) {
            $this->clearPasswordResetEmailCode($email);

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 400);
        }
    }

    public function VerifyPasswordResetCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
            'email_verification_code' => ['required', 'digits:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = strtolower(trim($request->email));
        $otp = (string) $request->email_verification_code;

        if (! $this->hasValidPasswordResetEmailCode($email, $otp)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired email verification code.',
            ], 422);
        }

        $verificationToken = $this->storePasswordResetToken($email);

        return response()->json([
            'success' => true,
            'message' => 'Verification code confirmed successfully.',
            'data' => [
                'email' => $email,
                'verification_token' => $verificationToken,
                'expires_in_minutes' => self::PASSWORD_RESET_TOKEN_TTL_MINUTES,
            ],
        ]);
    }

    public function ResetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
            'verification_token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = strtolower(trim($request->email));
        $token = (string) $request->verification_token;

        if (! $this->hasValidPasswordResetToken($email, $token)) {
            return response()->json([
                'success' => false,
                'message' => 'Password reset token is invalid or expired.',
            ], 422);
        }

        $user = User::query()
            ->where('email', $email)
            ->where('role', '!=', 'admin')
            ->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'otp' => 0,
        ]);

        $this->clearPasswordResetEmailCode($email);
        $this->clearPasswordResetToken($email);

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
        ]);
    }

    public function GetProfessionalQuestionAnswers(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $answers = ProfessionalQuestionAnswer::query()
            ->with('question.options')
            ->where('user_id', $user->id)
            ->get()
            ->map(fn (ProfessionalQuestionAnswer $answer) => [
                'id' => $answer->id,
                'professional_question_id' => $answer->professional_question_id,
                'question' => $answer->question?->question,
                'role' => $answer->question?->role,
                'type' => $answer->question?->type,
                'answer_text' => $answer->answer_text,
                'selected_option_ids' => $answer->selected_option_ids ?? [],
                'selected_options' => $answer->question?->options
                    ->whereIn('id', $answer->selected_option_ids ?? [])
                    ->map(fn ($opt) => ['id' => $opt->id, 'label' => $opt->label])
                    ->values(),
            ])
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Professional question answers retrieved successfully.',
            'data' => $answers,
        ]);
    }

    public function SubmitProfessionalQuestionAnswers(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.professional_question_id' => ['required', 'integer', 'exists:professional_questions,id'],
            // `answer` is accepted as a legacy/frontend alias for `answer_text`
            'answers.*.answer_text' => ['nullable', 'string'],
            'answers.*.answer' => ['nullable', 'string'],
            'answers.*.selected_option_ids' => ['nullable', 'array'],
            'answers.*.selected_option_ids.*' => ['integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        /** @var User $user */
        $user = User::query()->findOrFail((int) $request->input('user_id'));

        $questionIds = collect($request->input('answers', []))
            ->pluck('professional_question_id')
            ->map(fn ($id) => (int) $id)
            ->values();

        $questions = ProfessionalQuestion::query()
            ->where('status', 1)
            ->where('role', $user->role)
            ->whereIn('id', $questionIds)
            ->with(['options' => fn ($q) => $q->where('is_active', true)])
            ->get()
            ->keyBy('id');

        if ($questions->count() !== $questionIds->unique()->count()) {
            return response()->json([
                'success' => false,
                'message' => 'One or more questions do not belong to the authenticated user role.',
            ], 422);
        }

        try {
            $payloads = collect($request->input('answers', []))->map(function (array $payload) use ($questions) {
                $questionId = (int) $payload['professional_question_id'];
                /** @var ProfessionalQuestion|null $question */
                $question = $questions->get($questionId);

                if (! $question) {
                    return null;
                }

                $selectedOptionIds = collect($payload['selected_option_ids'] ?? [])
                    ->map(fn ($id) => (int) $id)
                    ->filter()
                    ->values();

                $freeText = trim((string) ($payload['answer_text'] ?? $payload['answer'] ?? ''));

                if ($question->type === 'single_choice' && $selectedOptionIds->isEmpty() && $freeText !== '') {
                    $match = $question->options->first(
                        fn ($opt) => strcasecmp(trim((string) $opt->label), $freeText) === 0
                    );
                    if ($match) {
                        $selectedOptionIds = collect([(int) $match->id]);
                    }
                }

                if ($question->type === 'single_choice') {
                    if ($selectedOptionIds->isEmpty() && ! $question->is_required) {
                        return [
                            'professional_question_id' => $questionId,
                            'answer_text' => null,
                            'selected_option_ids' => [],
                        ];
                    }

                    if ($selectedOptionIds->count() !== 1 || ! $question->options->whereIn('id', $selectedOptionIds)->count()) {
                        throw new \InvalidArgumentException('Invalid option selected for question ID '.$questionId);
                    }

                    return [
                        'professional_question_id' => $questionId,
                        'answer_text' => null,
                        'selected_option_ids' => $selectedOptionIds->toArray(),
                    ];
                }

                if ($question->type === 'multi_choice') {
                    if ($selectedOptionIds->isEmpty() && ! $question->is_required) {
                        return [
                            'professional_question_id' => $questionId,
                            'answer_text' => null,
                            'selected_option_ids' => [],
                        ];
                    }

                    if ($selectedOptionIds->isEmpty() || $question->options->whereIn('id', $selectedOptionIds)->count() !== $selectedOptionIds->count()) {
                        throw new \InvalidArgumentException('Invalid options selected for question ID '.$questionId);
                    }

                    return [
                        'professional_question_id' => $questionId,
                        'answer_text' => null,
                        'selected_option_ids' => $selectedOptionIds->toArray(),
                    ];
                }

                $text = $freeText;
                if ($text === '' && $question->is_required) {
                    throw new \InvalidArgumentException('Answer text is required for question ID '.$questionId);
                }

                return [
                    'professional_question_id' => $questionId,
                    'answer_text' => $text !== '' ? $text : null,
                    'selected_option_ids' => [],
                ];
            })->filter();

            $savedAnswers = $payloads->map(function (array $payload) use ($user) {
                return ProfessionalQuestionAnswer::query()->updateOrCreate(
                    [
                        'professional_question_id' => $payload['professional_question_id'],
                        'user_id' => $user->id,
                    ],
                    [
                        'answer_text' => $payload['answer_text'],
                        'selected_option_ids' => $payload['selected_option_ids'],
                    ]
                );
            })->values();
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Professional question answers submitted successfully.',
            'user_id' => $user->id,
            'data' => $savedAnswers->map(fn (ProfessionalQuestionAnswer $answer) => [
                'id' => $answer->id,
                'user_id' => $answer->user_id,
                'professional_question_id' => $answer->professional_question_id,
                'answer_text' => $answer->answer_text,
                'selected_option_ids' => $answer->selected_option_ids,
            ]),
        ]);
    }

    public function SendWebsiteSignupVerificationCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $otp = (string) random_int(100000, 999999);

        try {
            $this->storeSignupEmailCode($request->email, $otp);

            Mail::to($request->email)->send(new account_otp([
                'otp' => $otp,
                'email' => $request->email,
                'name' => $request->input('name', 'MagnateHub User'),
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent successfully.',
                'data' => [
                    'email' => $request->email,
                    'expires_in_minutes' => self::SIGNUP_EMAIL_CODE_TTL_MINUTES,
                ],
            ]);
        } catch (\Throwable $exception) {
            $this->clearSignupEmailCode($request->email);

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }
    }

    public function GetStripeToken(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string'],
            'card_number' => ['required', 'string'],
            'cvc' => ['required', 'string'],
            'exp_month' => ['required', 'numeric', 'min:1', 'max:12'],
            'exp_year' => ['required', 'numeric', 'min:'.date('Y')],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        try {
            Stripe::setApiKey((string) env('STRIPE_SECRET'));

            $token = \Stripe\Token::create([
                'card' => [
                    'number' => $request->card_number,
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,
                    'cvc' => $request->cvc,
                    'name' => $request->name,
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Token fetched successfully!',
                'token' => $token,
            ]);
        } catch (\Throwable $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 400);
        }
    }

    /**
     * Eager loads for public project payloads (listing owner may be on professional_id or legacy user_id;
     * broker team accounts may resolve company/logo from the parent user).
     *
     * @return list<string|array<string, mixed>>
     */
    private function projectPublicApiEagerLoads(): array
    {
        return [
            'location',
            'category',
            'region',
            'professional.documents',
            'professional.professional.documents',
            'user.documents',
            'documents',
        ];
    }

    /**
     * The user who owns this listing (session / portal account).
     */
    private function resolveProjectListingOwner(Project $project): ?User
    {
        if ((int) $project->professional_id > 0) {
            return $project->professional;
        }

        if ((int) ($project->user_id ?? 0) > 0) {
            return $project->user;
        }

        return null;
    }

    /**
     * User row used for company name, role, and company logo on the public API.
     * Broker sub-accounts (users.professional_id → parent broker) use the parent for brand fields.
     */
    private function resolvePublicListingBrandUser(?User $listingOwner): ?User
    {
        if (! $listingOwner) {
            return null;
        }

        if ($listingOwner->role !== 'broker' || (int) ($listingOwner->professional_id ?? 0) <= 0) {
            return $listingOwner;
        }

        if (filled(trim((string) $listingOwner->company_name))) {
            return $listingOwner;
        }

        $parent = $listingOwner->relationLoaded('professional')
            ? $listingOwner->professional
            : $listingOwner->professional()->with('documents')->first();

        if ($parent && $parent->isBroker()) {
            return $parent;
        }

        return $listingOwner;
    }

    private function applyProjectFilters(Builder $query, Request $request): void
    {
        if ($request->filled('postcode')) {
            $query->where('name', 'like', '%'.$request->postcode.'%');
        }

        if ($request->filled('businessId')) {
            $businessId = trim((string) $request->businessId);

            // Support external reference like "MGH-2026-2" by using trailing numeric project id.
            if (preg_match('/(\d+)\s*$/', $businessId, $matches)) {
                $query->whereKey((int) $matches[1]);
            } elseif (is_numeric($businessId)) {
                $query->whereKey((int) $businessId);
            } else {
                $keyword = $businessId;
                $textFields = [
                    'name',
                    'trading',
                    'earning_type',
                    'stock_level',
                    'summary',
                    'location_information',
                    'description',
                    'code',
                    'skills',
                    'potential',
                    'hours',
                    'staff',
                    'lease',
                    'business_established',
                    'training',
                    'awards',
                    'reason_for_sale',
                    'seeking_investment',
                    'reported_sales',
                    'run_rate_sales',
                    'ebitda_margin',
                    'industry',
                    'assets_or_collateral',
                    'interested_to_connect_with_advisors',
                    'business_overview',
                    'products_and_services_overview',
                    'assets_overview',
                    'facilities_overview',
                    'capitalization_overview',
                ];

                $query->where(function (Builder $keywordQuery) use ($keyword, $textFields) {
                    foreach ($textFields as $index => $field) {
                        $method = $index === 0 ? 'where' : 'orWhere';
                        $keywordQuery->{$method}($field, 'like', '%'.$keyword.'%');
                    }
                });
            }
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('state')) {
            $query->where('location_id', $request->state);
        }

        if ($request->filled('region')) {
            $query->where('region_id', $request->region);
        }

        if ($request->filled('min') && is_numeric($request->min)) {
            $query->where('price', '>=', $request->min);
        }

        if ($request->filled('max') && is_numeric($request->max)) {
            $query->where('price', '<=', $request->max);
        }

        if ($request->boolean('franchise')) {
            $query->where('franchise', 1);
        }

        if ($request->boolean('premium')) {
            $query->where('premium', 1);
        }

        if ($request->boolean('urgent_sale')) {
            $query->where('urgent_sale', 1);
        }

        if ($request->filled('type') && $request->type !== '0') {
            $query->where('type', (int) $request->type);
        } else {
            $query->where('type', '!=', 3);
        }
    }

    private function transformProject(Project $project, bool $detailed = false): array
    {
        $listingOwner = $this->resolveProjectListingOwner($project);
        $brandUser = $this->resolvePublicListingBrandUser($listingOwner) ?? $listingOwner;
        $brandDocuments = $brandUser?->documents;
        $ownerDocuments = $listingOwner?->documents;
        $projectAttributes = collect($project->attributesToArray())
            ->except([
                // Internal reference not needed on public API.
                'code',
            ])
            ->all();

        $data = array_merge($projectAttributes, [
            'project_id' => $project->id,
            'listing_owner_id' => $listingOwner?->id,
            'active' => (bool) ($projectAttributes['active'] ?? $project->active),
            'status' => (int) ($projectAttributes['status'] ?? $project->status),
            'deleted_at' => optional($project->deleted_at)?->toDateTimeString(),
            'location_name' => $project->location?->name,
            'category' => $project->category?->name,
            'category_name' => $project->category?->name,
            'region_name' => $project->region?->name,
            'user_first_name' => $listingOwner?->first_name,
            'user_last_name' => $listingOwner?->last_name,
            'user_company_name' => $brandUser?->company_name,
            'user_company_logo' => optional($brandDocuments?->firstWhere('collection', 'company_logo'))->url,
            'user_profile' => optional($ownerDocuments?->firstWhere('collection', 'profile'))->url,
            'user_type' => $brandUser?->role,
            'urgent_sale' => (int) ($project->urgent_sale ?? 0),
            'title_image' => $this->projectTitleImage($project),
            'images' => $this->projectImages($project),
            'created_at' => optional($project->created_at)?->toDateTimeString(),
            'updated_at' => optional($project->updated_at)?->toDateTimeString(),
        ]);

        if ($detailed) {
            $data = array_merge($data, [
                'earning_type' => $project->earning_type,
                'stock_level' => $project->stock_level,
                'location_information' => $project->location_information,
                'skills' => $project->skills,
                'potential' => $project->potential,
                'hours' => $project->hours,
                'staff' => $project->staff,
                'lease' => $project->lease,
                'business_established' => $project->business_established,
                'training' => $project->training,
                'awards' => $project->awards,
                'reason_for_sale' => $project->reason_for_sale,
                'seeking_investment' => $project->seeking_investment,
                'reported_sales' => $project->reported_sales,
                'run_rate_sales' => $project->run_rate_sales,
                'ebitda_margin' => $project->ebitda_margin,
                'industry' => $project->industry,
                'assets_or_collateral' => $project->assets_or_collateral,
                'interested_to_connect_with_advisors' => $project->interested_to_connect_with_advisors,
                'business_overview' => $project->business_overview,
                'products_and_services_overview' => $project->products_and_services_overview,
                'assets_overview' => $project->assets_overview,
                'facilities_overview' => $project->facilities_overview,
                'capitalization_overview' => $project->capitalization_overview,
            ]);
        }

        return $data;
    }

    private function transformBlog(Blog $blog, bool $detailed = false): array
    {
        $titleImage = $this->blogTitleImage($blog);
        $images = $this->blogImages($blog);
        $tags = $this->blogTags($blog);

        $data = [
            'id' => $blog->id,
            'name' => $blog->name,
            'description' => $blog->description,
            'writer_name' => $blog->writer_name,
            'url' => $blog->url,
            'views' => $blog->views,
            'comments_count' => $blog->comments_count ?? null,
            'image' => $titleImage,
            'title_image' => $titleImage,
            'images' => $images,
            'writer_image' => $blog->writer_image,
            'date' => optional($blog->created_at)?->format('jS F Y'),
            'time' => optional($blog->created_at)?->format('h:i A'),
            'created_at' => optional($blog->created_at)?->toDateTimeString(),
            'updated_at' => optional($blog->updated_at)?->toDateTimeString(),
            'tags' => $tags,
            'category' => ($blog->relationLoaded('blogCategory') && $blog->blogCategory)
                ? [
                    'id' => $blog->blogCategory->id,
                    'name' => $blog->blogCategory->name,
                    'slug' => $blog->blogCategory->slug,
                ]
                : null,
        ];

        if ($detailed) {
            $data['content'] = $blog->content;
        }

        return $data;
    }

    private function projectCardImage(Project $project): ?string
    {
        $cardDocument = $project->documents()->where('collection', 'card')->first();

        return $cardDocument?->url ?: $project->card;
    }

    private function projectTitleImage(Project $project): ?string
    {
        return $this->projectCardImage($project) ?: ($this->projectImages($project)[0] ?? null);
    }

    private function projectImages(Project $project): array
    {
        $gallery = $project->documents()->where('collection', 'gallery')->orderBy('sort_order')->pluck('url')->filter()->values();

        $images = [];

        $titleImage = $this->projectCardImage($project);
        if ($titleImage) {
            $images[] = $titleImage;
        }

        if ($gallery->isNotEmpty()) {
            $images = array_values(array_unique(array_merge($images, $gallery->all())));

            return $images;
        }

        if (! empty($project->images)) {
            $decoded = json_decode($project->images, true);
            if (is_array($decoded)) {
                $images = array_values(array_unique(array_merge($images, array_values(array_filter($decoded)))));

                return $images;
            }
        }

        return $images;
    }

    private function blogTitleImage(Blog $blog): ?string
    {
        $cardDocument = $blog->relationLoaded('thumbnail')
            ? $blog->thumbnail
            : $blog->documents()->where('collection', 'card')->first();

        return $cardDocument?->url ?: $blog->card;
    }

    private function blogImages(Blog $blog): array
    {
        $images = [];
        $titleImage = $this->blogTitleImage($blog);

        if ($titleImage) {
            $images[] = $titleImage;
        }

        $gallery = $blog->documents()->where('collection', 'gallery')->orderBy('sort_order')->pluck('url')->filter()->values()->all();

        if ($gallery !== []) {
            $images = array_values(array_unique(array_merge($images, $gallery)));
        }

        return $images;
    }

    private function blogTags(Blog $blog): array
    {
        if (! method_exists($blog, 'tags')) {
            return [];
        }

        if (! $blog->relationLoaded('tags')) {
            $blog->loadMissing('tags');
        }

        $tags = $blog->getRelation('tags');

        if (! $tags instanceof Collection) {
            return [];
        }

        return $tags->map(static fn ($tag) => [
            'id' => (int) $tag->id,
            'name' => (string) $tag->name,
            'slug' => (string) $tag->slug,
        ])->values()->all();
    }

    private function mapLegacyTypeToRole(int $type): string
    {
        return match ($type) {
            1 => 'buyer',
            2 => 'seller',
            3 => 'capital_raiser',
            4 => 'broker',
            default => 'user',
        };
    }

    private function websitePlanConfig(int $planId): array
    {
        $package = $this->subscriptionService->getPackageByType($planId);
        $info = SubscriptionHelper::getPlanInfo($planId);

        return [
            'id' => $planId,
            'name' => $package?->name ?? ($info['name'] ?? 'Unknown'),
            'slug' => $package?->slug,
            'listing_limit' => $package?->listing_limit ?? SubscriptionHelper::getListingLimit($planId),
            'price' => (float) ($package?->price ?? 0),
            'duration_days' => $package?->duration_days,
            'timeframe_label' => $package?->timeframe_label ?? ($info['duration'] ?? null),
            'premium_marking_limit' => (int) ($package?->premium_marking_limit ?? 0),
            'inclusions' => $package?->inclusions ?? [],
        ];
    }

    private function billingDetailsRules(bool $required): array
    {
        $requiredRule = $required ? 'required' : 'nullable';

        return [
            'billing_first_name' => [$requiredRule, 'string', 'max:255'],
            'billing_last_name' => [$requiredRule, 'string', 'max:255'],
            'billing_business_name' => ['nullable', 'string', 'max:255'],
            'billing_abn' => ['nullable', 'string', 'max:50'],
            'billing_email' => [$requiredRule, 'string', 'email', 'max:255'],
            'billing_phone' => [$requiredRule, 'string', 'max:50'],
            'billing_address' => [$requiredRule, 'string', 'max:1000'],
        ];
    }

    private function extractBillingDetails(Request $request): array
    {
        return [
            'billing_first_name' => $request->input('billing_first_name'),
            'billing_last_name' => $request->input('billing_last_name'),
            'billing_business_name' => $request->input('billing_business_name'),
            'billing_abn' => $request->input('billing_abn'),
            'billing_email' => $request->input('billing_email'),
            'billing_phone' => $request->input('billing_phone'),
            'billing_address' => $request->input('billing_address'),
        ];
    }

    private function passwordResetEmailCodeCacheKey(string $email): string
    {
        return 'website_password_reset_email_code:'.sha1(strtolower(trim($email)));
    }

    private function storePasswordResetEmailCode(string $email, string $otp): void
    {
        Cache::put(
            $this->passwordResetEmailCodeCacheKey($email),
            ['otp' => $otp],
            now()->addMinutes(self::PASSWORD_RESET_EMAIL_CODE_TTL_MINUTES)
        );
    }

    private function hasValidPasswordResetEmailCode(string $email, string $otp): bool
    {
        $payload = Cache::get($this->passwordResetEmailCodeCacheKey($email));

        if (! is_array($payload) || ! isset($payload['otp'])) {
            return false;
        }

        return hash_equals((string) $payload['otp'], $otp);
    }

    private function clearPasswordResetEmailCode(string $email): void
    {
        Cache::forget($this->passwordResetEmailCodeCacheKey($email));
    }

    private function passwordResetTokenCacheKey(string $email): string
    {
        return 'website_password_reset_token:'.sha1(strtolower(trim($email)));
    }

    private function storePasswordResetToken(string $email): string
    {
        $token = Str::random(64);

        Cache::put(
            $this->passwordResetTokenCacheKey($email),
            ['token' => $token],
            now()->addMinutes(self::PASSWORD_RESET_TOKEN_TTL_MINUTES)
        );

        return $token;
    }

    private function hasValidPasswordResetToken(string $email, string $token): bool
    {
        $payload = Cache::get($this->passwordResetTokenCacheKey($email));

        if (! is_array($payload) || ! isset($payload['token'])) {
            return false;
        }

        return hash_equals((string) $payload['token'], $token);
    }

    private function clearPasswordResetToken(string $email): void
    {
        Cache::forget($this->passwordResetTokenCacheKey($email));
    }

    private function isPlanAllowedForRole(string $role, int $planId): bool
    {
        if ($role === 'buyer') {
            return false;
        }

        if ($role === 'capital_raiser') {
            return in_array($planId, [
                SubscriptionHelper::PLAN_FREE,
                SubscriptionHelper::PLAN_CAPITAL_RAISE,
            ], true);
        }

        return in_array($planId, [
            SubscriptionHelper::PLAN_FREE,
            SubscriptionHelper::PLAN_ESSENTIALS,
            SubscriptionHelper::PLAN_PREMIUM,
            SubscriptionHelper::PLAN_BROKER_PRO,
        ], true);
    }

    private function availablePlanIdsForRole(?string $role): array
    {
        if ($role === 'buyer') {
            return [];
        }

        return $this->subscriptionService->getAvailablePackageTypeIds();
    }

    private function roleRequiresPlan(string $role): bool
    {
        return $role !== 'buyer';
    }

    private function signupEmailCodeCacheKey(string $email): string
    {
        return 'website_signup_email_code:'.sha1(strtolower(trim($email)));
    }

    private function storeSignupEmailCode(string $email, string $otp): void
    {
        Cache::put(
            $this->signupEmailCodeCacheKey($email),
            ['otp' => $otp],
            now()->addMinutes(self::SIGNUP_EMAIL_CODE_TTL_MINUTES)
        );
    }

    private function hasValidSignupEmailCode(string $email, string $otp): bool
    {
        $payload = Cache::get($this->signupEmailCodeCacheKey($email));

        if (! is_array($payload) || ! isset($payload['otp'])) {
            return false;
        }

        return hash_equals((string) $payload['otp'], $otp);
    }

    private function clearSignupEmailCode(string $email): void
    {
        Cache::forget($this->signupEmailCodeCacheKey($email));
    }

    private function signupVerificationTokenCacheKey(string $email): string
    {
        return 'website_signup_verification_token:'.sha1(strtolower(trim($email)));
    }

    private function storeSignupVerificationToken(string $email): string
    {
        $token = Str::random(64);

        Cache::put(
            $this->signupVerificationTokenCacheKey($email),
            ['token' => $token],
            now()->addMinutes(self::SIGNUP_VERIFICATION_TOKEN_TTL_MINUTES)
        );

        return $token;
    }

    private function hasValidSignupVerificationToken(string $email, string $token): bool
    {
        $payload = Cache::get($this->signupVerificationTokenCacheKey($email));

        if (! is_array($payload) || ! isset($payload['token'])) {
            return false;
        }

        return hash_equals((string) $payload['token'], $token);
    }

    private function clearSignupVerificationToken(string $email): void
    {
        Cache::forget($this->signupVerificationTokenCacheKey($email));
    }
}
