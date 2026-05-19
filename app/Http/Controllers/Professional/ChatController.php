<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Mail\chat_message_notification;
use App\Models\Chat;
use App\Models\Project;
use App\Models\User;
use App\Services\ChatService;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function __construct(
        private ChatService $chat,
        private FileUploadService $fileUpload
    ) {}

    /**
     * A listing may store the owner as projects.professional_id and/or projects.user_id.
     * The website passes user_id as the lister's users.id — accept either column.
     */
    private function projectListerMatches(Project $project, int $listerUserId): bool
    {
        return (int) $project->professional_id === $listerUserId
            || (int) $project->user_id === $listerUserId;
    }

    private function projectOwnedBy(Project $project, int $userId): bool
    {
        return $this->projectListerMatches($project, $userId);
    }

    private function buyerHasChatThread(User $buyer, int $professionalId, int $projectId): bool
    {
        return Chat::query()
            ->where('user_id', $buyer->id)
            ->where('professional_id', $professionalId)
            ->where('project_id', $projectId)
            ->exists();
    }

    private function sellerHasChatThread(User $seller, int $buyerUserId, int $projectId): bool
    {
        return Chat::query()
            ->where('professional_id', $seller->id)
            ->where('user_id', $buyerUserId)
            ->where('project_id', $projectId)
            ->exists();
    }

    /**
     * Conversations safe to auto-open: listing lister matches the chat seller, or this buyer already
     * has a chat row for that pair (legacy projects where user_id / professional_id disagree).
     *
     * @param  Collection<int, Chat>  $conversations
     * @return Collection<int, Chat>
     */
    private function filterBuyerConversationsWithValidLister(Collection $conversations, User $buyer): Collection
    {
        return $conversations->filter(function (Chat $chat) use ($buyer) {
            if (! $chat->project) {
                return $this->buyerHasChatThread($buyer, (int) $chat->professional_id, (int) $chat->project_id);
            }

            return $this->projectListerMatches($chat->project, (int) $chat->professional_id)
                || $this->buyerHasChatThread($buyer, (int) $chat->professional_id, (int) $chat->project_id);
        })->values();
    }

    /**
     * Seller-side: project owned by seller, or an existing chat row for this seller + buyer + project.
     *
     * @param  Collection<int, Chat>  $conversations
     * @return Collection<int, Chat>
     */
    private function filterSellerConversationsWithOwnedProjects(Collection $conversations, User $seller): Collection
    {
        return $conversations->filter(function (Chat $chat) use ($seller) {
            if (! $chat->project) {
                return $this->sellerHasChatThread($seller, (int) $chat->user_id, (int) $chat->project_id);
            }

            return $this->projectOwnedBy($chat->project, (int) $seller->id)
                || $this->sellerHasChatThread($seller, (int) $chat->user_id, (int) $chat->project_id);
        })->values();
    }

    /**
     * @return list<UploadedFile>
     */
    private function validatedAttachmentFiles(Request $request): array
    {
        $raw = $request->file('attachments', []);
        if ($raw === null) {
            return [];
        }
        if (! is_array($raw)) {
            $raw = [$raw];
        }

        return array_values(array_filter(
            $raw,
            fn ($f) => $f instanceof UploadedFile && $f->isValid()
        ));
    }

    private function storeAttachmentsOnChat(Chat $chat, array $files): void
    {
        foreach ($files as $index => $file) {
            $this->fileUpload->upload($file, $chat, 'attachment', 'uploads/chats', $index);
        }
    }

    private function notifyChatRecipient(User $recipient, User $sender, Project $project, ?string $message, int $attachmentCount = 0): void
    {
        if (empty($recipient->email)) {
            return;
        }

        $senderName = trim((string) ($sender->full_name ?: $sender->name));
        $recipientName = trim((string) ($recipient->full_name ?: $recipient->name));
        $listingName = trim((string) $project->name);
        $messagePreview = trim((string) $message);

        if ($messagePreview === '' && $attachmentCount > 0) {
            $messagePreview = $attachmentCount === 1
                ? '1 attachment was sent.'
                : $attachmentCount.' attachments were sent.';
        }

        if ($messagePreview === '') {
            $messagePreview = 'You have received a new chat message.';
        }

        $detail = [
            'recipient_name' => $recipientName !== '' ? $recipientName : 'there',
            'sender_name' => $senderName !== '' ? $senderName : 'A MagnateHub member',
            'listing_name' => $listingName !== '' ? $listingName : 'Unknown listing',
            'message' => $messagePreview,
            'chat_url' => route('professional.chat', [
                'user_id' => $sender->id,
                'project_id' => $project->id,
            ]),
        ];

        try {
            Mail::to($recipient->email)->send(new chat_message_notification($detail));
        } catch (\Throwable $exception) {
            Log::warning('Chat message notification email failed', [
                'recipient_id' => $recipient->id,
                'recipient_email' => $recipient->email,
                'sender_id' => $sender->id,
                'project_id' => $project->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $routeParams
     */
    private function chatSendResponse(Request $request, array $routeParams, string $flashMessage = 'Message sent.'): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $flashMessage,
                'redirect' => route('professional.chat', $routeParams),
            ]);
        }

        return redirect()->route('professional.chat', $routeParams)->with('success', $flashMessage);
    }

    private function wantsChatPanel(Request $request): bool
    {
        return $request->ajax() && $request->boolean('panel');
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function chatPanelResponse(Request $request, array $payload): View|JsonResponse
    {
        if ($this->wantsChatPanel($request)) {
            return response()->json([
                'success' => true,
                'html' => view('professional.dashboard.partials.chat-panel-body', $payload)->render(),
            ]);
        }

        return view('professional.dashboard.chat', $payload);
    }

    private function chatPanelLoadFailed(Request $request, string $message, int $status = 422): RedirectResponse|JsonResponse
    {
        if ($this->wantsChatPanel($request)) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        return redirect()->route('professional.chat')
            ->withErrors(['error' => $message]);
    }

    public function index(Request $request): View|RedirectResponse|JsonResponse
    {
        $currentUser = Auth::user();
        $requestedProfessionalId = (int) $request->query('professional_id', 0);
        $requestedListerId = (int) $request->query('user_id', 0);
        $requestedProjectId = (int) $request->query('project_id', 0);

        // Buyers are stored as chats.user_id; sellers use chats.professional_id.
        if ($currentUser->isBuyer()) {
            return $this->userChat($request, $currentUser, $requestedProfessionalId ?: null);
        }

        if ($requestedListerId > 0 && $requestedProjectId > 0) {
            $project = Project::find($requestedProjectId);

            if (!$project) {
                return redirect()->route('professional.chat')
                    ->withErrors(['error' => 'Project not found.']);
            }

            // Website sends user_id as the project lister id.
            // If current user is not the lister, open buyer-side chat.
            if ($this->projectListerMatches($project, $requestedListerId) && (int) $currentUser->id !== $requestedListerId) {
                return $this->userChat($request, $currentUser, $requestedListerId);
            }
        }

        return $this->professionalChat($request, $currentUser);
    }

    private function professionalChat(Request $request, User $professional): View|RedirectResponse|JsonResponse
    {
        $conversations = Chat::with(['project.thumbnail', 'user.documents'])
            ->where('professional_id', $professional->id)
            ->latest('created_at')
            ->get()
            ->unique(fn (Chat $chat) => $chat->project_id . ':' . $chat->user_id)
            ->values();
        $openableConversations = $this->filterSellerConversationsWithOwnedProjects($conversations, $professional);

        $requestedUserId = $request->query('user_id');
        $requestedProjectId = $request->query('project_id');

        $selectedUserId = 0;
        $selectedProjectId = 0;

        if ($requestedUserId && $requestedProjectId) {
            $selectedUserId = (int) $requestedUserId;
            $selectedProjectId = (int) $requestedProjectId;
        } elseif ($openableConversations->isNotEmpty()) {
            $selectedUserId = (int) $openableConversations->first()->user_id;
            $selectedProjectId = (int) $openableConversations->first()->project_id;
        } elseif ($conversations->isNotEmpty()) {
            // Fallback for legacy rows so the latest thread still opens on page load.
            $selectedUserId = (int) $conversations->first()->user_id;
            $selectedProjectId = (int) $conversations->first()->project_id;
        }

        $messages = collect();
        $selectedConversation = null;
        $selectedUser = null;
        $selectedProject = null;

        if ($selectedUserId && $selectedProjectId) {
            $selectedProject = Project::with('thumbnail')->find($selectedProjectId);
            $selectedUser = User::with('documents')->find($selectedUserId);
            
            if (!$selectedProject) {
                return $this->chatPanelLoadFailed($request, 'Project not found.', 404);
            }
            
            if (!$selectedUser) {
                return $this->chatPanelLoadFailed($request, 'User not found.', 404);
            }
            
            if (! $this->projectOwnedBy($selectedProject, (int) $professional->id)
                && ! $this->sellerHasChatThread($professional, $selectedUserId, $selectedProjectId)) {
                return $this->chatPanelLoadFailed($request, 'You do not have access to this project.', 403);
            }

            $messages = $this->chat->getMessages($selectedUserId, $professional->id, $selectedProjectId)->load('user.documents', 'documents');
            
            if ($messages->isNotEmpty()) {
                $this->chat->markRead($selectedUserId, $professional->id, $selectedProjectId, 'professional');
            }
            
            $selectedConversation = $conversations->first(
                fn (Chat $chat) => (int) $chat->user_id === $selectedUserId && (int) $chat->project_id === $selectedProjectId
            );

            if (!$selectedConversation && $messages->isNotEmpty()) {
                $selectedConversation = $messages->first();
            }

            if (!$selectedConversation) {
                $selectedConversation = (object) [
                    'user_id' => $selectedUserId,
                    'project_id' => $selectedProjectId,
                    'professional_id' => $professional->id,
                    'user' => $selectedUser,
                    'project' => $selectedProject,
                ];
            }
        }

        return $this->chatPanelResponse($request, [
            'conversations' => $conversations,
            'messages' => $messages,
            'selectedConversation' => $selectedConversation,
            'selectedUserId' => $selectedUserId,
            'selectedProjectId' => $selectedProjectId,
            'isProfessional' => true,
        ]);
    }

    private function userChat(Request $request, User $user, ?int $forcedProfessionalId = null): View|RedirectResponse|JsonResponse
    {
        $conversations = Chat::with(['project.thumbnail', 'professional.documents'])
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->get()
            ->unique(fn (Chat $chat) => $chat->project_id . ':' . $chat->professional_id)
            ->values();
        $openableConversations = $this->filterBuyerConversationsWithValidLister($conversations, $user);

        $requestedProfessionalId = $forcedProfessionalId ?: $request->query('professional_id') ?: $request->query('user_id');
        $requestedProjectId = $request->query('project_id');

        $selectedProfessionalId = 0;
        $selectedProjectId = 0;

        if ($requestedProfessionalId && $requestedProjectId) {
            $selectedProfessionalId = (int) $requestedProfessionalId;
            $selectedProjectId = (int) $requestedProjectId;
        } elseif ($openableConversations->isNotEmpty()) {
            $selectedProfessionalId = (int) $openableConversations->first()->professional_id;
            $selectedProjectId = (int) $openableConversations->first()->project_id;
        } elseif ($conversations->isNotEmpty()) {
            // Fallback for legacy rows so the latest thread still opens on page load.
            $selectedProfessionalId = (int) $conversations->first()->professional_id;
            $selectedProjectId = (int) $conversations->first()->project_id;
        }

        $messages = collect();
        $selectedConversation = null;
        $selectedProfessional = null;
        $selectedProject = null;

        if ($selectedProfessionalId && $selectedProjectId) {
            $selectedProject = Project::with(['professional.documents', 'thumbnail'])->find($selectedProjectId);
            $selectedProfessional = User::with('documents')->find($selectedProfessionalId);
            
            if (!$selectedProject) {
                return $this->chatPanelLoadFailed($request, 'Project not found.', 404);
            }
            
            if (!$selectedProfessional) {
                return $this->chatPanelLoadFailed($request, 'Professional not found.', 404);
            }
            
            if (! $this->projectListerMatches($selectedProject, (int) $selectedProfessional->id)
                && ! $this->buyerHasChatThread($user, (int) $selectedProfessional->id, $selectedProjectId)) {
                return $this->chatPanelLoadFailed($request, 'This listing does not belong to that seller.', 403);
            }

            $messages = $this->chat->getMessages($user->id, $selectedProfessional->id, $selectedProjectId)->load('professional.documents', 'documents');
            
            if ($messages->isNotEmpty()) {
                $this->chat->markRead($user->id, $selectedProfessional->id, $selectedProjectId, 'user');
            }
            
            $selectedConversation = $conversations->first(
                fn (Chat $chat) => (int) $chat->professional_id === $selectedProfessionalId && (int) $chat->project_id === $selectedProjectId
            );

            if (!$selectedConversation && $messages->isNotEmpty()) {
                $selectedConversation = $messages->first();
            }

            if (!$selectedConversation) {
                $selectedConversation = (object) [
                    'user_id' => $user->id,
                    'professional_id' => $selectedProfessionalId,
                    'project_id' => $selectedProjectId,
                    'professional' => $selectedProfessional,
                    'project' => $selectedProject,
                ];
            }
        }

        return $this->chatPanelResponse($request, [
            'conversations' => $conversations,
            'messages' => $messages,
            'selectedConversation' => $selectedConversation,
            'selectedProfessionalId' => $selectedProfessionalId,
            'selectedProjectId' => $selectedProjectId,
            'isProfessional' => false,
        ]);
    }

    public function send(Request $request): RedirectResponse|JsonResponse
    {
        $currentUser = Auth::user();
        $sendingAsBuyer = $request->filled('professional_id');

        if (!$sendingAsBuyer) {
            $validated = $request->validate([
                'user_id'       => 'required|integer|exists:users,id',
                'project_id'    => 'required|integer|exists:projects,id',
                'message'       => 'nullable|string',
                'attachments'   => 'nullable|array|max:15',
                'attachments.*' => 'file|max:10240',
            ]);

            $files = $this->validatedAttachmentFiles($request);

            if (empty(trim((string) ($validated['message'] ?? ''))) && $files === []) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Write a message or attach at least one file.',
                        'errors' => ['message' => ['Write a message or attach at least one file.']],
                    ], 422);
                }

                return back()->withErrors(['message' => 'Write a message or attach at least one file.']);
            }

            $chat = $this->chat->sendMessage([
                'user_id'         => $validated['user_id'],
                'professional_id' => $currentUser->id,
                'project_id'      => $validated['project_id'],
                'message'         => $validated['message'] ?? null,
                'send'            => 0,
            ]);

            $this->storeAttachmentsOnChat($chat, $files);
            $recipient = User::find($validated['user_id']);
            $project = Project::find($validated['project_id']);
            if ($recipient && $project) {
                $this->notifyChatRecipient($recipient, $currentUser, $project, $validated['message'] ?? null, count($files));
            }

            return $this->chatSendResponse($request, [
                'user_id' => $validated['user_id'],
                'project_id' => $validated['project_id'],
            ]);
        }

        $validated = $request->validate([
            'professional_id' => 'required|integer|exists:users,id',
            'project_id'      => 'required|integer|exists:projects,id',
            'message'         => 'nullable|string',
            'attachments'     => 'nullable|array|max:15',
            'attachments.*'   => 'file|max:10240',
        ]);

        $files = $this->validatedAttachmentFiles($request);

        if (empty(trim((string) ($validated['message'] ?? ''))) && $files === []) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Write a message or attach at least one file.',
                    'errors' => ['message' => ['Write a message or attach at least one file.']],
                ], 422);
            }

            return back()->withErrors(['message' => 'Write a message or attach at least one file.']);
        }

        $chat = $this->chat->sendMessage([
            'user_id'         => $currentUser->id,
            'professional_id' => $validated['professional_id'],
            'project_id'      => $validated['project_id'],
            'message'         => $validated['message'] ?? null,
            'send'            => 1,
        ]);

        $this->storeAttachmentsOnChat($chat, $files);
        $recipient = User::find($validated['professional_id']);
        $project = Project::find($validated['project_id']);
        if ($recipient && $project) {
            $this->notifyChatRecipient($recipient, $currentUser, $project, $validated['message'] ?? null, count($files));
        }

        return $this->chatSendResponse($request, [
            'user_id' => $validated['professional_id'],
            'project_id' => $validated['project_id'],
        ]);
    }
}
