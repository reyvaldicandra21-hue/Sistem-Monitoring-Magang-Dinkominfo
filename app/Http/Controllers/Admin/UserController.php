<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = User::roles();

        return view('admin.user.index', compact('users', 'roles'));
    }

    /**
     * Form tambah user
     */
    public function create()
    {
        $roles = User::roles();
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:' . implode(',', array_keys(User::roles())),
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            // Sync Profile
            if ($user->role === User::ROLE_PEMBIMBING) {
                \App\Models\Pembimbing::updateOrCreate(['user_id' => $user->id], ['nama' => $user->name]);
            } elseif ($user->role === User::ROLE_PESERTA) {
                \App\Models\PesertaPkl::updateOrCreate(['user_id' => $user->id], ['status' => 'pending']);
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Form edit user
     */
    public function edit($uuid)
    {
        $user  = User::where('uuid', $uuid)->firstOrFail();
        $roles = User::roles();

        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update data user
     */
    public function update(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:' . implode(',', array_keys(User::roles())),
        ]);

        DB::transaction(function () use ($request, $user) {
            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
                'role'  => $request->role,
            ]);

            // Sync Profile
            if ($user->role === User::ROLE_PEMBIMBING) {
                \App\Models\Pembimbing::updateOrCreate(['user_id' => $user->id], ['nama' => $user->name]);
            } elseif ($user->role === User::ROLE_PESERTA) {
                \App\Models\PesertaPkl::updateOrCreate(['user_id' => $user->id], ['status' => 'pending']);
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    /**
     * Reset password user
     */
    public function resetPassword(Request $request, $uuid)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('uuid', $uuid)->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Password berhasil direset');
    }

    /**
     * Hapus user
     */
    public function destroy($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        // Jangan izinkan admin menghapus akunnya sendiri
        if (Auth::id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
    /**
     * Ubah status aktif/nonaktif user
     */
    public function toggleStatus($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        // Jangan izinkan admin menonaktifkan dirinya sendiri
        if (Auth::id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Akun berhasil {$status}");
    }
}
