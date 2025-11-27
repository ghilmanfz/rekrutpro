<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\Position;
use App\Models\Location;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    /**
     * Display master data management page
     */
    public function index()
    {
        $divisions = Division::withCount('users')->orderBy('name')->get();
        $positions = Position::withCount('jobPostings')->orderBy('name')->get();
        $locations = Location::withCount('jobPostings')->orderBy('name')->get();

        return view('superadmin.master-data.index', compact('divisions', 'positions', 'locations'));
    }

    // ============ DIVISIONS ============

    public function storeDivision(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:divisions',
            'description' => 'nullable|string',
        ]);

        Division::create($validated);

        return back()->with('success', 'Divisi berhasil ditambahkan');
    }

    public function updateDivision(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:divisions,name,' . $division->id,
            'description' => 'nullable|string',
        ]);

        $division->update($validated);

        return back()->with('success', 'Divisi berhasil diperbarui');
    }

    public function destroyDivision(Division $division)
    {
        if ($division->users()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus divisi yang masih memiliki pengguna');
        }

        $division->delete();

        return back()->with('success', 'Divisi berhasil dihapus');
    }

    // ============ POSITIONS ============

    public function storePosition(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:positions',
            'description' => 'nullable|string',
        ]);

        Position::create($validated);

        return back()->with('success', 'Posisi berhasil ditambahkan');
    }

    public function updatePosition(Request $request, Position $position)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:positions,name,' . $position->id,
            'description' => 'nullable|string',
        ]);

        $position->update($validated);

        return back()->with('success', 'Posisi berhasil diperbarui');
    }

    public function destroyPosition(Position $position)
    {
        if ($position->jobPostings()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus posisi yang masih memiliki lowongan');
        }

        $position->delete();

        return back()->with('success', 'Posisi berhasil dihapus');
    }

    // ============ LOCATIONS ============

    public function storeLocation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations',
            'address' => 'nullable|string',
        ]);

        Location::create($validated);

        return back()->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function updateLocation(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
            'address' => 'nullable|string',
        ]);

        $location->update($validated);

        return back()->with('success', 'Lokasi berhasil diperbarui');
    }

    public function destroyLocation(Location $location)
    {
        if ($location->jobPostings()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus lokasi yang masih memiliki lowongan');
        }

        $location->delete();

        return back()->with('success', 'Lokasi berhasil dihapus');
    }
}
