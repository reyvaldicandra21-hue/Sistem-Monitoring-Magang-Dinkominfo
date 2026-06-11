<?php

namespace App\Http\Controllers\PesertaPKL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaporanHarian;
use App\Models\LaporanDokumentasi;
use App\Models\PesertaPKL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanHarianController extends Controller
{
    public function index()
    {
        $peserta = PesertaPKL::where('user_id', Auth::id())->first();

        $laporans = LaporanHarian::with(['dokumentasi', 'verifikasiTerakhir'])
            ->where('peserta_pkl_id', $peserta->id)
            ->latest()
            ->get();

        return view('pesertapkl.laporanharian.index', compact('laporans'));
    }

    public function create()
    {
        return view('pesertapkl.laporanharian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan' => 'required|max:10000',
            'hasil' => 'nullable|max:10000',
            'kendala' => 'nullable|max:5000',
            'dokumentasi' => 'nullable|array|max:5',
            'dokumentasi.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $peserta = PesertaPKL::where('user_id', Auth::id())->first();

        $laporan = LaporanHarian::create([
            'user_id' => Auth::id(),
            'peserta_pkl_id' => $peserta->id,
            'tanggal' => now(),
            'kegiatan' => $request->kegiatan,
            'hasil' => $request->hasil,
            'kendala' => $request->kendala,
            'status' => 'menunggu'
        ]);

        // simpan dokumentasi
        if ($request->hasFile('dokumentasi')) {
            foreach ($request->file('dokumentasi') as $file) {

                $path = $file->store('laporan_dokumentasi', 'public');

                LaporanDokumentasi::create([
                    'laporan_harian_id' => $laporan->id,
                    'file' => $path
                ]);
            }
        }

        return redirect()->route('pesertapkl.laporanharian.index')
            ->with('success', 'Laporan berhasil dikirim');
    }

    public function show($id)
    {
        $peserta = PesertaPKL::where('user_id', Auth::id())->first();

        $laporan = LaporanHarian::with([
            'dokumentasi',
            'verifikasis.pembimbing'
        ])
        ->where('peserta_pkl_id', $peserta->id)
        ->findOrFail($id);

        return view('pesertapkl.laporanharian.show', compact('laporan'));
    }

    public function edit($id)
    {
        $peserta = PesertaPKL::where('user_id', Auth::id())->first();

        $laporan = LaporanHarian::with(['dokumentasi', 'verifikasiTerakhir'])
            ->where('peserta_pkl_id', $peserta->id)
            ->where('status', 'revisi')
            ->findOrFail($id);

        return view('pesertapkl.laporanharian.edit', compact('laporan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kegiatan' => 'required|max:10000',
            'hasil' => 'nullable|max:10000',
            'kendala' => 'nullable|max:5000',
            'dokumentasi' => 'nullable|array|max:5',
            'dokumentasi.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $peserta = PesertaPKL::where('user_id', Auth::id())->first();

        $laporan = LaporanHarian::where('peserta_pkl_id', $peserta->id)
            ->findOrFail($id);

        // ================= UPDATE DATA =================
        $laporan->update([
            'kegiatan' => $request->kegiatan,
            'hasil' => $request->hasil,
            'kendala' => $request->kendala,
            'status' => 'menunggu'
        ]);

        // ================= HAPUS DOKUMENTASI =================
        if ($request->has('hapus_dokumentasi')) {

            foreach ($request->hapus_dokumentasi as $idFoto) {

                $foto = LaporanDokumentasi::where('id', $idFoto)
                    ->where('laporan_harian_id', $laporan->id)
                    ->first();

                if ($foto) {

                    // hapus file fisik
                    if (Storage::disk('public')->exists($foto->file)) {
                        Storage::disk('public')->delete($foto->file);
                    }

                    // hapus database
                    $foto->delete();
                }
            }
        }

        // ================= TAMBAH FOTO BARU =================
        if ($request->hasFile('dokumentasi')) {
            foreach ($request->file('dokumentasi') as $file) {

                $path = $file->store('laporan_dokumentasi', 'public');

                LaporanDokumentasi::create([
                    'laporan_harian_id' => $laporan->id,
                    'file' => $path
                ]);
            }
        }

        return redirect()->route('pesertapkl.laporanharian.index')
            ->with('success', 'Laporan revisi berhasil dikirim');
    }
}