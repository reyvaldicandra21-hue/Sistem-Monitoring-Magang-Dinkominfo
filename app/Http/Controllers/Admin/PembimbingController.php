<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembimbing;
use App\Models\User;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PembimbingController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembimbing::with(['user', 'divisi']);

        if ($request->search) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('email', 'like', '%' . $request->search . '%');
                  });
        }

        $pembimbings = $query->latest()->paginate(12)->withQueryString();

        return view('admin.pembimbing.index', compact('pembimbings'));
    }

    public function create()
    {
        $divisi = Divisi::all(); // ✅ ambil divisi

        return view('admin.pembimbing.create', compact('divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'jabatan'   => 'nullable|string|max:255',
            'divisi_id' => 'nullable|exists:divisi,id',
        ]);

        DB::transaction(function () use ($request) {

            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'pembimbing',
            ]);

            Pembimbing::create([
                'user_id'   => $user->id,
                'nama'      => $request->nama,
                'jabatan'   => $request->jabatan,
                'divisi_id' => $request->divisi_id, // ✅ relasi
            ]);
        });

        return redirect()
            ->route('admin.pembimbing.index')
            ->with('success', 'Pembimbing berhasil dibuat.');
    }

    public function show(Pembimbing $pembimbing)
    {
        $pembimbing->load(['user', 'divisi', 'pesertaPkls']);

        return view('admin.pembimbing.show', compact('pembimbing'));
    }

    public function edit(Pembimbing $pembimbing)
    {
        $divisi = Divisi::all();

        return view('admin.pembimbing.edit', compact('pembimbing', 'divisi'));
    }

    public function update(Request $request, Pembimbing $pembimbing)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $pembimbing->user->id,
            'password'  => 'nullable|min:6',
            'jabatan'   => 'nullable|string|max:255',
            'divisi_id' => 'nullable|exists:divisi,id',
        ]);

        DB::transaction(function () use ($request, $pembimbing) {

            // update pembimbing
            $pembimbing->update([
                'nama'      => $request->nama,
                'jabatan'   => $request->jabatan,
                'divisi_id' => $request->divisi_id,
            ]);

            // update user
            $userData = [
                'name'  => $request->nama,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $pembimbing->user->update($userData);
        });

        return redirect()
            ->route('admin.pembimbing.index')
            ->with('success', 'Data pembimbing berhasil diperbarui.');
    }

    public function destroy(Pembimbing $pembimbing)
    {
        if ($pembimbing->pesertaPkls()->exists()) {
            return back()->with('error', 'Pembimbing masih memiliki peserta PKL.');
        }

        DB::transaction(function () use ($pembimbing) {
            $pembimbing->user->delete();
            $pembimbing->delete();
        });

        return redirect()
            ->route('admin.pembimbing.index')
            ->with('success', 'Pembimbing berhasil dihapus.');
    }
}
