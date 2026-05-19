<?php

namespace App\Services;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Collection;

class ChatService
{
    public function getConversations(int $userId, string $role = 'user'): Collection
    {
        $query = Chat::with(['project', 'documents']);

        if ($role === 'user') {
            $query->where('user_id', $userId);
        } else {
            $query->where('professional_id', $userId);
        }

        return $query->select('project_id', 'professional_id', 'user_id')
            ->groupBy('project_id', 'professional_id', 'user_id')
            ->get();
    }

    public function getMessages(int $userId, int $professionalId, int $projectId): Collection
    {
        return Chat::with(['documents'])
            ->where('user_id', $userId)
            ->where('professional_id', $professionalId)
            ->where('project_id', $projectId)
            ->orderBy('created_at')
            ->get();
    }

    public function sendMessage(array $data): Chat
    {
        return Chat::create(array_merge($data, ['code' => md5(uniqid())]));
    }

    public function markRead(int $userId, int $professionalId, int $projectId, string $role = 'user'): void
    {
        $query = Chat::where('project_id', $projectId);

        if ($role === 'user') {
            $query->where('professional_id', $professionalId)
                ->where('send', 0);
        } else {
            $query->where('user_id', $userId)
                ->where('send', 1);
        }

        $query->update(['status' => 1]);
    }
}
