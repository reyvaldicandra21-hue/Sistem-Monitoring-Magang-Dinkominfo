<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tugas;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TugasAController extends Controller
{
    // =========================
    // LIST TUGAS (SEMUA)
    // =========================
 public function index(Request $request)
{
    $query = Tugas::with([
            'pesertaPkl',
            'pengumpulan'
        ]);

    if ($request->judul) {
        $query->where('judul', 'like', '%' . $request->judul . '%');
    }

    if ($request->deadline) {
        $query->whereDate('deadline', $request->deadline);
    }

    if ($request->peserta) {
        $query->whereHas('pesertaPkl.user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->peserta . '%');
        });
    }

    $tugas = $query->latest()
        ->paginate(10)
        ->withQueryString();

    $tugas->getCollection()->transform(function ($item) {
        $item->total_peserta = $item->pesertaPkl->count();
        $item->total_kumpul  = $item->pengumpulan->count();
        $item->status_tugas  = $item->status;
        return $item;
    });

    return view('admin.tugas.index', compact('tugas'));
}

    public function hasil($uuid)
    {
        $tugas = Tugas::with([
                'pesertaPkl.user',
                'pengumpulan.pesertaPkl.user'
            ])
            ->where('uuid', $uuid)->firstOrFail();

        return view('admin.tugas.hasil', compact('tugas'));
    }
}
