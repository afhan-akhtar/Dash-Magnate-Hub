<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Location;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'professionals' => User::professionals()->where('is_deleted', 0)->count(),
            'projects' => Project::query()->count(),
            'blogs_published' => Blog::query()->where('status', 1)->count(),
            'contacts_inbox' => Contact::query()
                ->whereIn('type', ['Contact Form', 'contact'])
                ->where('is_deleted', 0)
                ->count(),
            'categories' => Category::query()->count(),
            'locations' => Location::query()->count(),
        ];

        $statCards = $this->buildStatCards($stats);

        $monthBuckets = $this->monthBuckets();
        $chartLabels = array_column($monthBuckets, 'label');

        $chartGrowth = [
            [
                'name' => 'New members',
                'data' => $this->countsPerBuckets(
                    $monthBuckets,
                    User::query()->where('role', '!=', 'admin')->where('is_deleted', 0)
                ),
            ],
            [
                'name' => 'New listings',
                'data' => $this->countsPerBuckets($monthBuckets, Project::query()),
            ],
        ];

        $contactFormTypes = ['Contact Form', 'contact'];
        $newsletterType = 'News Letter Form';

        $chartAcquisition = [
            [
                'name' => 'Contact form',
                'data' => $this->countsPerBuckets(
                    $monthBuckets,
                    Contact::query()->whereIn('type', $contactFormTypes)->where('is_deleted', 0)
                ),
            ],
            [
                'name' => 'Newsletter',
                'data' => $this->countsPerBuckets(
                    $monthBuckets,
                    Contact::query()->where('type', $newsletterType)->where('is_deleted', 0)
                ),
            ],
            [
                'name' => 'Other',
                'data' => $this->countsPerBuckets(
                    $monthBuckets,
                    Contact::query()
                        ->where('is_deleted', 0)
                        ->whereNotIn('type', array_merge($contactFormTypes, [$newsletterType]))
                ),
            ],
        ];

        $recentMembers = User::query()
            ->where('role', '!=', 'admin')
            ->where('is_deleted', 0)
            ->with('thumbnail')
            ->latest('id')
            ->take(6)
            ->get();

        $recentProjects = Project::query()
            ->with(['professional'])
            ->latest('id')
            ->take(6)
            ->get();

        $recentContacts = Contact::query()
            ->where('is_deleted', 0)
            ->latest('id')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'statCards',
            'chartLabels',
            'chartGrowth',
            'chartAcquisition',
            'recentMembers',
            'recentProjects',
            'recentContacts'
        ));
    }

    /**
     * @param  array<int, array{label: string, start: Carbon, end: Carbon}>  $buckets
     */
    private function countsPerBuckets(array $buckets, Builder $base): array
    {
        $out = [];
        foreach ($buckets as $bucket) {
            $out[] = (clone $base)
                ->whereBetween('created_at', [$bucket['start'], $bucket['end']])
                ->count();
        }

        return $out;
    }

    /**
     * @return array<int, array{label: string, start: Carbon, end: Carbon}>
     */
    private function monthBuckets(): array
    {
        $out = [];
        for ($i = 11; $i >= 0; $i--) {
            $start = now()->copy()->subMonths($i)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $out[] = [
                'label' => $start->format('M/y'),
                'start' => $start,
                'end' => $end,
            ];
        }

        return $out;
    }

    private function buildStatCards(array $stats): array
    {
        $days = 14;
        $startDate = now()->startOfDay()->subDays($days - 1);

        $buildDaily = function (Builder $query) use ($startDate, $days) {
            $rows = (clone $query)
                ->where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
                ->groupBy('day')
                ->pluck('total', 'day');

            $series = [];
            for ($i = 0; $i < $days; $i++) {
                $date = $startDate->copy()->addDays($i);
                $key = $date->toDateString();
                $series[] = [
                    'date'  => $date->format('j/n/y'),
                    'full'  => $date->format('j/n/y'),
                    'count' => (int) ($rows[$key] ?? 0),
                ];
            }
            return $series;
        };

        $professionalsDaily = $buildDaily(User::professionals()->where('is_deleted', 0));
        $projectsDaily      = $buildDaily(Project::query());
        $blogsDaily         = $buildDaily(Blog::query()->where('status', 1));
        $contactsDaily      = $buildDaily(
            Contact::query()->whereIn('type', ['Contact Form', 'contact'])->where('is_deleted', 0)
        );
        $categoriesDaily    = $buildDaily(Category::query());
        $locationsDaily     = $buildDaily(Location::query());

        return [
            [
                'key'        => 'professionals',
                'label'      => 'Professionals',
                'value'      => str_pad((string) $stats['professionals'], 2, '0', STR_PAD_LEFT),
                'icon'       => 'feather-briefcase',
                'tone'       => 'warning',
                'color'      => '#d97706',
                'gradient'   => '#fbbf24',
                'highlight'  => $stats['professionals'].' total',
                'sub'        => 'All professionals',
                'daily'      => $professionalsDaily,
            ],
            [
                'key'        => 'listings',
                'label'      => 'Listings',
                'value'      => str_pad((string) $stats['projects'], 2, '0', STR_PAD_LEFT),
                'icon'       => 'feather-airplay',
                'tone'       => 'info',
                'color'      => '#0ea5e9',
                'gradient'   => '#22d3ee',
                'highlight'  => $stats['projects'].' total',
                'sub'        => 'Projects in directory',
                'daily'      => $projectsDaily,
            ],
            [
                'key'        => 'blogs',
                'label'      => 'Published Blogs',
                'value'      => str_pad((string) $stats['blogs_published'], 2, '0', STR_PAD_LEFT),
                'icon'       => 'feather-edit-3',
                'tone'       => 'success',
                'color'      => '#16a34a',
                'gradient'   => '#4ade80',
                'highlight'  => $stats['blogs_published'].' live',
                'sub'        => 'Posts marked published',
                'daily'      => $blogsDaily,
            ],
            [
                'key'        => 'contacts',
                'label'      => 'Contact Leads',
                'value'      => str_pad((string) $stats['contacts_inbox'], 2, '0', STR_PAD_LEFT),
                'icon'       => 'feather-mail',
                'tone'       => 'danger',
                'color'      => '#ef4444',
                'gradient'   => '#f87171',
                'highlight'  => $stats['contacts_inbox'].' inbox',
                'sub'        => 'Contact form submissions',
                'daily'      => $contactsDaily,
            ],
            [
                'key'        => 'categories',
                'label'      => 'Categories',
                'value'      => str_pad((string) $stats['categories'], 2, '0', STR_PAD_LEFT),
                'icon'       => 'feather-layers',
                'tone'       => 'primary',
                'color'      => '#5b2cd7',
                'gradient'   => '#8b5cf6',
                'highlight'  => $stats['categories'].' total',
                'sub'        => 'All categories',
                'daily'      => $categoriesDaily,
            ],
            [
                'key'        => 'locations',
                'label'      => 'Locations',
                'value'      => str_pad((string) $stats['locations'], 2, '0', STR_PAD_LEFT),
                'icon'       => 'feather-map-pin',
                'tone'       => 'info',
                'color'      => '#0ea5e9',
                'gradient'   => '#22d3ee',
                'highlight'  => $stats['locations'].' total',
                'sub'        => 'All locations',
                'daily'      => $locationsDaily,
            ],
        ];
    }
}
