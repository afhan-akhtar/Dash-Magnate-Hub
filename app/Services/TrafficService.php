<?php

namespace App\Services;

use App\Models\TrafficLog;
use Illuminate\Http\Request;

class TrafficService
{
    public function log(Request $request): void
    {
        $ip = $request->ip();

        if ($this->alreadyLoggedToday($ip)) {
            return;
        }

        TrafficLog::create([
            'ip'    => $ip,
            'day'   => now()->format('d'),
            'month' => now()->format('m'),
            'year'  => now()->format('Y'),
            'code'  => md5(uniqid()),
        ]);
    }

    private function alreadyLoggedToday(string $ip): bool
    {
        return TrafficLog::where('ip', $ip)
            ->whereDate('created_at', today())
            ->exists();
    }

    public function getDailyStats(): array
    {
        return TrafficLog::selectRaw('day, month, year, COUNT(*) as count')
            ->groupBy('day', 'month', 'year')
            ->orderByRaw('year DESC, month DESC, day DESC')
            ->limit(30)
            ->get()
            ->toArray();
    }
}
