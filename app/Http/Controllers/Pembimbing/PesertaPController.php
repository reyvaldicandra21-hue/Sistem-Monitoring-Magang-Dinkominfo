<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\PesertaPkl;
use App\Models\Tugas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PesertaPController extends Controller
{
    public function index(Request $request)
    {
        $pembimbing = Auth::user()->pembimbing;

        $query = PesertaPkl::with(['user', 'divisi'])
            ->where('pembimbing_id', $pembimbing->id);

        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $peserta = $query->paginate(12)->withQueryString();

        return view('pembimbing.peserta.index', compact('peserta'));
    }

public function show($id)
{
    $pembimbing = Auth::user()->pembimbing;

    $peserta = PesertaPkl::with([
        'user',
        'divisi',
        'pembimbing',
        'absensi' => function ($q) {
            $q->latest();
        },
        'laporanHarian' => function ($q) {
            $q->latest();
        }
    ])
    ->where('pembimbing_id', $pembimbing->id)
    ->findOrFail($id);

    // 🔥 AMBIL TUGAS KHUSUS PESERTA INI
    $tugas = Tugas::where('pembimbing_id', $pembimbing->id)
        ->latest()
        ->get();

    return view('pembimbing.peserta.show', compact('peserta', 'tugas'));
}
}
