<?php

namespace App\Http\Controllers\Admin;

use App\Models\Divisi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Divisi::withCount(['pembimbing', 'pesertaPkl']);

        if ($request->search) {
            $query->where('nama_divisi', 'like', '%' . $request->search . '%');
        }

        $divisis = $query->latest()->paginate(12)->withQueryString();

        return view('admin.divisi.index', compact('divisis'));
    }

    public function create()
    {
        return view('admin.divisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required|max:255',
        ]);

        Divisi::create([
            'nama_divisi' => $request->nama_divisi
        ]);

        return redirect()
            ->route('admin.divisi.index')
            ->with('success', 'Divisi berhasil ditambahkan');
    }

    public function show($uuid)
{
    $divisi = \App\Models\Divisi::with([
        'pesertaPkl.user',
        'pembimbing.user'
    ])->where('uuid', $uuid)->firstOrFail();

    return view('admin.divisi.show', compact('divisi'));
}
    public function edit($uuid)
    {
        $divisi = Divisi::where('uuid', $uuid)->firstOrFail();

        return view('admin.divisi.edit', compact('divisi'));
    }

    public function update(Request $request, $uuid)
    {
        $request->validate([
            'nama_divisi' => 'required|max:255',
        ]);

        $divisi = Divisi::where('uuid', $uuid)->firstOrFail();

        $divisi->update([
            'nama_divisi' => $request->nama_divisi
        ]);

        return redirect()
            ->route('admin.divisi.index')
            ->with('success', 'Divisi berhasil diperbarui');
    }

    public function destroy($uuid)
    {
        $divisi = Divisi::where('uuid', $uuid)->firstOrFail();
        $divisi->delete();

        return back()->with('success', 'Divisi berhasil dihapus');
    }
}
