<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrafficLog;
use App\Services\TrafficService;
use Illuminate\View\View;

class TrafficController extends Controller
{
    public function __construct(private TrafficService $traffic)
    {
    }

    public function index(): View
    {
        $logs = TrafficLog::latest()->get();
        $stats = $this->traffic->getDailyStats();

        return view('admin.traffic.index', compact('logs', 'stats'));
    }
}
