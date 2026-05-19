<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Category;
use App\Models\Location;
use App\Models\Project;
use App\Models\Region;
use App\Services\FileUploadService;
use App\Services\ProfessionalPortalSessionService;
use App\Services\ProjectService;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(
        private ProjectService $projects,
        private FileUploadService $fileUpload,
        private SubscriptionService $subscription,
        private ProfessionalPortalSessionService $portalSession
    ) {}

    public function index(): View
    {
        /** @var \App\Models\User $professional */
        $professional = Auth::user();
        $activePlan = $this->subscription->getActivePlan($professional);

        $projects = Project::with(['category', 'location', 'thumbnail'])
            ->where('professional_id', $professional->id)
            ->latest()
            ->paginate(12);

        return view('professional.dashboard.listings.index', [
            'projects' => $projects,
            'hasNoPlan' => !$activePlan,
            'hasExpiredPlan' => !$activePlan && $professional->planPurchases()->exists(),
            'remainingPremiumMarks' => $this->subscription->remainingPremiumMarks($professional),
        ]);
    }

    public function create(Request $request): View
    {
        /** @var \App\Models\User $professional */
        $professional = Auth::user();
        $this->portalSession->sync($request, $professional);
        $activePlan = $this->subscription->getActivePlan($professional);

        return view('professional.dashboard.listings.create', [
            'categories' => Category::orderBy('name')->get(),
            'locations' => Location::with('regions')->orderBy('name')->get(),
            'regions' => Region::orderBy('name')->get(),
            'hasQuota' => $this->subscription->hasListingQuota($professional),
            'hasNoPlan' => !$activePlan,
            'activePlan' => $activePlan,
            'listingType' => (int) session()->get('type', 0),
        ]);
    }

    public function show(int $id): View
    {
        /** @var \App\Models\User $professional */
        $professional = Auth::user();
        $activePlan = $this->subscription->getActivePlan($professional);

        $project = Project::with(['category', 'location', 'region', 'documents', 'thumbnail'])
            ->where('professional_id', $professional->id)
            ->findOrFail($id);

        return view('professional.dashboard.listings.show', [
            'project' => $project,
            'gallery' => $project->documentCollection('gallery')->get(),
            'hasQuota' => $this->subscription->hasListingQuota($professional),
            'hasNoPlan' => !$activePlan,
        ]);
    }

    public function edit(Request $request, int $id): View
    {
        /** @var \App\Models\User $professional */
        $professional = Auth::user();
        $this->portalSession->sync($request, $professional);

        $project = Project::with(['thumbnail', 'documents'])
            ->where('professional_id', $professional->id)
            ->findOrFail($id);
        $projectType = (int) $project->type;
        if (! in_array($projectType, [1, 2, 3, 4], true)) {
            $projectType = (int) session()->get('type', 0);
        }
        if (! in_array($projectType, [1, 2, 3, 4], true)) {
            $projectType = match ($professional->role) {
                'buyer' => 1,
                'seller' => 2,
                'capital_raiser' => 3,
                'broker' => 4,
                default => 2,
            };
        }

        return view('professional.dashboard.listings.edit', [
            'project' => $project,
            'categories' => Category::orderBy('name')->get(),
            'locations' => Location::with('regions')->orderBy('name')->get(),
            'regions' => Region::orderBy('name')->get(),
            'projectType' => $projectType,
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $professional */
        $professional = Auth::user();

        if (!$this->subscription->hasListingQuota($professional)) {
            $hasNoPlan = !$this->subscription->getActivePlan($professional);
            $errorMsg = $hasNoPlan
                ? 'You need an active plan before you can create a listing.'
                : 'Listing limit reached. Please upgrade your plan before creating another listing.';

            return redirect()
                ->route('professional.plans.index')
                ->withErrors(['name' => $errorMsg]);
        }

        $validated = $this->prepareSellerListingPayload($request->validated(), (int) session()->get('type', 0));
        $validated['type'] = (int) session()->get('type', 0);
        $validated = array_merge($validated, $this->normalizeListingTags([
            'franchise' => $request->boolean('franchise'),
            'multiple_locations' => $request->boolean('multiple_locations'),
            'urgent_sale' => $request->boolean('urgent_sale'),
            'sold' => $request->boolean('sold'),
            'under_offer' => $request->boolean('under_offer'),
        ]));

        $project = $this->projects->createProject($validated, $professional->id);

        if ($request->hasFile('card')) {
            $this->fileUpload->upload(
                $request->file('card'),
                $project,
                'card',
                'uploads/project/card',
                0,
                false
            );
        }

        if ($request->hasFile('gallery')) {
            $this->fileUpload->uploadMany(
                $request->file('gallery'),
                $project,
                'gallery',
                'uploads/project/images',
                false
            );
        }

        return redirect()
            ->route('professional.listings.index')
            ->with('success', 'Listing created successfully.');
    }

    public function update(StoreProjectRequest $request, int $id): RedirectResponse
    {
        /** @var \App\Models\User $professional */
        $professional = Auth::user();
        $project = Project::with(['documents', 'thumbnail'])
            ->where('professional_id', $professional->id)
            ->findOrFail($id);

        $validated = $this->prepareSellerListingPayload(
            $request->validated(),
            (int) ($request->input('type') ?: $project->type)
        );
        unset($validated['card'], $validated['gallery'], $validated['remove_card'], $validated['remove_gallery']);
        $validated = array_merge($validated, $this->normalizeListingTags([
            'franchise' => $request->boolean('franchise'),
            'multiple_locations' => $request->boolean('multiple_locations'),
            'urgent_sale' => $request->boolean('urgent_sale'),
            'sold' => $request->boolean('sold'),
            'under_offer' => $request->boolean('under_offer'),
        ]));

        $this->projects->updateProject($project, $validated);

        if ($request->boolean('remove_card')) {
            $existingCard = $project->document('card')->first();
            if ($existingCard) {
                $this->fileUpload->deleteDocument($existingCard);
            }
        }

        $removeGalleryIds = collect($request->input('remove_gallery', []))
            ->filter(fn ($value) => is_numeric($value))
            ->map(fn ($value) => (int) $value)
            ->values();

        if ($removeGalleryIds->isNotEmpty()) {
            $project->documentCollection('gallery')
                ->whereIn('id', $removeGalleryIds->all())
                ->get()
                ->each(fn ($document) => $this->fileUpload->deleteDocument($document));
        }

        if ($request->hasFile('card')) {
            $this->fileUpload->replaceDocument(
                $request->file('card'),
                $project,
                'card',
                'uploads/project/card',
                false
            );
        }

        if ($request->hasFile('gallery')) {
            $this->fileUpload->uploadMany(
                $request->file('gallery'),
                $project,
                'gallery',
                'uploads/project/images',
                false
            );
        }

        return redirect()
            ->route('professional.listings.show', $project->id)
            ->with('success', 'Listing updated successfully.');
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        /** @var \App\Models\User $professional */
        $professional = Auth::user();
        $project = Project::where('professional_id', $professional->id)->findOrFail($id);

        if ($project->isDeleted()) {
            return redirect()
                ->route('professional.listings.index')
                ->withErrors(['listing' => 'Deleted listings cannot be activated or inactivated.']);
        }

        $data = $request->validate([
            'field' => ['required', 'in:active,under_offer'],
            'value' => ['required', 'in:0,1'],
        ]);

        $nextValue = (int) $data['value'];

        if ($data['field'] === 'under_offer' && $nextValue === 1) {
            $project->update($this->normalizeListingTags([
                'franchise' => (int) $project->franchise === 1,
                'multiple_locations' => (int) $project->multiple_locations === 1,
                'urgent_sale' => (int) $project->urgent_sale === 1,
                'sold' => (int) $project->sold === 1,
                'under_offer' => $data['field'] === 'under_offer',
            ]));
        } else {
            $project->update([
                $data['field'] => $nextValue,
            ]);
        }

        $messages = [
            'active' => 'Listing visibility updated.',
            'under_offer' => 'Listing under offer status updated.',
        ];

        return redirect()
            ->route('professional.listings.index')
            ->with('success', $messages[$data['field']] ?? 'Listing status updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        /** @var \App\Models\User $professional */
        $professional = Auth::user();
        $project = Project::where('professional_id', $professional->id)->findOrFail($id);

        $this->projects->deleteProject($project);

        return redirect()
            ->route('professional.listings.index')
            ->with('success', 'Listing deleted successfully.');
    }

    public function markPremium(int $id): RedirectResponse
    {
        /** @var \App\Models\User $professional */
        $professional = Auth::user();
        $project = Project::where('professional_id', $professional->id)->findOrFail($id);

        if ($this->subscription->applyPremiumMark($professional, $project)) {
            return redirect()
                ->route('professional.listings.index')
                ->with('success', 'Listing marked as premium successfully.');
        }

        return redirect()
            ->route('professional.listings.index')
            ->withErrors(['listing' => 'You do not have a premium mark credit for this listing or the listing is not eligible.']);
    }

    private function normalizeListingTags(array $tagInput): array
    {
        $normalized = [
            'franchise' => !empty($tagInput['franchise']) ? 1 : 0,
            'multiple_locations' => !empty($tagInput['multiple_locations']) ? 1 : 0,
            'urgent_sale' => !empty($tagInput['urgent_sale']) ? 1 : 0,
            'sold' => !empty($tagInput['sold']) ? 1 : 0,
            'under_offer' => !empty($tagInput['under_offer']) ? 1 : 0,
        ];

        if ($normalized['sold'] === 1) {
            return [
                'franchise' => 0,
                'multiple_locations' => 0,
                'urgent_sale' => 0,
                'sold' => 1,
                'under_offer' => 0,
            ];
        }

        if ($normalized['under_offer'] === 1) {
            return [
                'franchise' => 0,
                'multiple_locations' => 0,
                'urgent_sale' => 0,
                'sold' => 0,
                'under_offer' => 1,
            ];
        }

        return [
            'franchise' => $normalized['franchise'],
            'multiple_locations' => $normalized['multiple_locations'],
            'urgent_sale' => $normalized['urgent_sale'],
            'sold' => 0,
            'under_offer' => 0,
        ];
    }

    private function prepareSellerListingPayload(array $validated, int $type): array
    {
        if (! in_array($type, [2, 4], true)) {
            return $validated;
        }

        $validated['name'] = trim((string) ($validated['name'] ?? '')) ?: 'Listing';
        $validated['description'] = (string) ($validated['description'] ?? '');
        $validated['summary'] = $validated['summary'] ?? null;

        return $validated;
    }
}

