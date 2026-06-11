<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\PesertaPKL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{

    /**
     * DAFTAR PESERTA
     */
    public function index()
    {
        $pembimbing = Auth::user()->pembimbing;

        $pesertas = PesertaPKL::with(['user','penilaian'])
            ->where('pembimbing_id',$pembimbing->id)
            ->paginate(10);

        return view('pembimbing.penilaian.index', compact('pesertas'));
    }

    /**
     * FORM INPUT NILAI
     */
    public function edit($id)
    {
        $peserta = PesertaPKL::with('user')->findOrFail($id);

        $penilaian = Penilaian::firstOrCreate([
            'peserta_pkl_id' => $peserta->id
        ]);

        return view('pembimbing.penilaian.edit', compact('peserta','penilaian'));
    }

    /**
     * SIMPAN / UPDATE NILAI
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'disiplin' => 'required|numeric|min:0|max:100',
            'tanggung_jawab' => 'required|numeric|min:0|max:100',
            'kerjasama' => 'required|numeric|min:0|max:100',
            'etika' => 'required|numeric|min:0|max:100',
            'inisiatif' => 'required|numeric|min:0|max:100',
        ]);

        $peserta = PesertaPKL::findOrFail($id);

        // 🔥 HITUNG NILAI AKHIR
        $nilaiAkhir = (
            $request->disiplin +
            $request->tanggung_jawab +
            $request->kerjasama +
            $request->etika +
            $request->inisiatif
        ) / 5;

        // 🔥 PREDIKAT
        $predikat = $this->predikat($nilaiAkhir);

        // 🔥 SIMPAN
        Penilaian::updateOrCreate(
            ['peserta_pkl_id' => $peserta->id],
            [
                'disiplin' => $request->disiplin,
                'tanggung_jawab' => $request->tanggung_jawab,
                'kerjasama' => $request->kerjasama,
                'etika' => $request->etika,
                'inisiatif' => $request->inisiatif,
                'nilai_akhir' => $nilaiAkhir,
                'predikat' => $predikat,
                'catatan' => $request->catatan
            ]
        );

        return redirect()->route('pembimbing.penilaian.index')
            ->with('success','Penilaian berhasil disimpan');
    }

    /**
     * PREDIKAT
     */
    private function predikat($nilai)
    {
        if($nilai >= 86) return "A";
        if($nilai >= 76) return "B";
        if($nilai >= 66) return "C";
        return "D";
    }
}
