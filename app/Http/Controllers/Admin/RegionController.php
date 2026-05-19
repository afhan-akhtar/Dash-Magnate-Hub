<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegionController extends Controller
{
    public function index(): View
    {
        $regions = Region::with('location')->orderBy('name')->get();

        return view('admin.regions.index', compact('regions'));
    }

    public function create(): View
    {
        $locations = Location::orderBy('name')->get();

        return view('admin.regions.create', compact('locations'));
    }

    public function edit(int $id): View
    {
        $region = Region::findOrFail($id);
        $locations = Location::orderBy('name')->get();

        return view('admin.regions.edit', compact('region', 'locations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location_id' => 'required|integer|exists:locations,id',
        ]);

        Region::create($request->only(['name', 'location_id']));

        return redirect()->route('admin.regions.index')->with('success', 'Region created.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location_id' => 'required|integer|exists:locations,id',
        ]);

        Region::findOrFail($id)->update($request->only(['name', 'location_id']));

        return redirect()->route('admin.regions.index')->with('success', 'Region updated.');
    }

    public function destroy(int $id): RedirectResponse
    {
        Region::findOrFail($id)->delete();

        return redirect()->route('admin.regions.index')->with('success', 'Region deleted.');
    }
}
