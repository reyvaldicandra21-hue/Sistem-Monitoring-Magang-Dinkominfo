<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanHarian;
use App\Models\LaporanDokumentasi;
use App\Models\LogVerifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanHarianAController extends Controller
{
    /**
     * LIST SEMUA LAPORAN
     */
    public function index(Request $request)
    {
        $query = LaporanHarian::with([
            'pesertaPkl.user',
            'verifikasiTerakhir'
        ]);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->peserta) {
            $query->whereHas('pesertaPkl.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->peserta . '%');
            });
        }

        $laporans = $query->latest()->paginate(10)->withQueryString();

        $pesertaList = \App\Models\PesertaPKL::with('user')->get();

        return view('admin.laporanharian.index', compact('laporans', 'pesertaList'));
    }

    /**
     * DETAIL LAPORAN
     */
    public function show($id)
    {
        $laporan = LaporanHarian::with([
            'pesertaPkl.user',
            'verifikasis.pembimbing',
            'dokumentasi' // ✅ relasi benar
        ])->findOrFail($id);

        return view('admin.laporanharian.show', compact('laporan'));
    }

    /**
     * DOWNLOAD PDF
     */
    public function download($id)
    {
        $laporan = LaporanHarian::with([
            'pesertaPkl.user',
            'verifikasis',
            'dokumentasi'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('admin.laporanharian.pdf', compact('laporan'));
        
        $filename = 'Laporan_' . str_replace(' ', '_', $laporan->pesertaPkl->user->name) . '_' . $laporan->tanggal . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * DOWNLOAD BUKU LAPORAN (SEMUA LAPORAN PESERTA)
     */
    public function downloadBuku($peserta_id)
    {
        $peserta = \App\Models\PesertaPKL::with(['user', 'pembimbing', 'divisi'])
            ->findOrFail($peserta_id);

        $laporans = LaporanHarian::with(['dokumentasi'])
            ->where('peserta_pkl_id', $peserta_id)
            ->orderBy('tanggal', 'asc')
            ->get();

        $pdf = Pdf::loadView('admin.laporanharian.buku_pdf', compact('peserta', 'laporans'));
        
        $filename = 'Buku_Laporan_' . str_replace(' ', '_', $peserta->user->name) . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * VERIFIKASI
     */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,revisi',
            'catatan' => 'required_if:status,revisi|nullable|string'
        ], [
            'catatan.required_if' => 'Catatan revisi wajib diisi jika Anda memilih opsi Revisi.'
        ]);

        $laporan = LaporanHarian::findOrFail($id);

        $laporan->update([
            'status' => $request->status
        ]);

        LogVerifikasi::create([
            'laporan_harian_id' => $laporan->id,
            'pembimbing_id' => $laporan->pesertaPkl->pembimbing_id,
            'status' => $request->status,
            'catatan_pembimbing' => $request->catatan ? '[Verifikasi Admin] ' . $request->catatan : '[Verifikasi Admin]',
        ]);

        return back()->with('success','Laporan berhasil diverifikasi');
    }
}