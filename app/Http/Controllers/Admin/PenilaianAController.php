<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\PesertaPkl;
use Illuminate\Http\Request;

class PenilaianAController extends Controller
{
    public function index(Request $request)
    {
        $query = PesertaPkl::with(['user', 'penilaian', 'pembimbing']);

        // 🔍 Filter nama peserta
        if ($request->nama) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->nama . '%');
            });
        }

        // 🔍 Filter institusi
        if ($request->institusi) {
            $query->where('asal_institusi', 'like', '%' . $request->institusi . '%');
        }

        // 🔍 Filter predikat
        if ($request->predikat) {
            $query->whereHas('penilaian', function ($q) use ($request) {
                $q->where('predikat', $request->predikat);
            });
        }

        $pesertas = $query->latest()->paginate(15)->withQueryString();

        return view('admin.penilaian.index', compact('pesertas'));
    }
}
