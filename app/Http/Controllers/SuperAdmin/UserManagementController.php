<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::with(['role', 'division'])
            ->whereIn('role_id', [1, 2, 3]) // Super Admin, HR, Interviewer only
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $roles = Role::whereIn('id', [1, 2, 3])->get();
        $divisions = Division::all();

        return view('superadmin.users.index', compact('users', 'roles', 'divisions'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::whereIn('id', [1, 2, 3])->get();
        $divisions = Division::all();

        return view('superadmin.users.create', compact('roles', 'divisions'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'division_id' => $validated['division_id'],
            'phone' => $validated['phone'],
            'is_active' => true,
            'is_verified' => true,
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $roles = Role::whereIn('id', [1, 2, 3])->get();
        $divisions = Division::all();

        return view('superadmin.users.edit', compact('user', 'roles', 'divisions'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role_id' => ['required', 'exists:roles,id'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ]);

        $user->update($validated);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return back()->with('success', 'Password berhasil direset');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return back()->with('success', "Pengguna berhasil {$status}");
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }
}
