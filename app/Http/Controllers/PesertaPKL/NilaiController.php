<?php

namespace App\Http\Controllers\PesertaPKL;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PesertaPKL;
use App\Models\Penilaian;

class NilaiController extends Controller
{
    public function index()
    {
        $peserta = PesertaPKL::where('user_id', Auth::id())->first();

        $penilaian = Penilaian::where('peserta_pkl_id', $peserta->id)->first();

        return view('pesertapkl.nilai.index', compact('penilaian'));
    }
}
