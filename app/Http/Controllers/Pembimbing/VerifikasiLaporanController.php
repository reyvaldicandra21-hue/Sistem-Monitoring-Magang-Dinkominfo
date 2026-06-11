<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\LaporanHarian;
use App\Models\LogVerifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VerifikasiLaporanController extends Controller
{
    /**
     * Daftar laporan menunggu verifikasi
     */
    public function index()
    {
        $pembimbingId = Auth::user()->pembimbing->id;

$laporan = LaporanHarian::with([
        'pesertaPkl',
        'verifikasiTerakhir'
    ])
    ->where('status', 'menunggu')
    ->whereHas('pesertaPkl', function ($q) use ($pembimbingId) {
        $q->where('pembimbing_id', $pembimbingId);
    })
    ->orderBy('tanggal', 'desc')
    ->get();

        return view('pembimbing.verifikasi.index', compact('laporan'));
    }

    /**
     * Detail laporan + histori verifikasi
     */
    public function show($id)
    {
        $laporan = LaporanHarian::with([
            'pesertaPkl',
            'verifikasis'
        ])->findOrFail($id);

        return view('pembimbing.verifikasi.show', compact('laporan'));
    }

    /**
     * Simpan verifikasi (setujui / revisi)
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'status'  => 'required|in:disetujui,revisi',
            'catatan' => 'required_if:status,revisi|nullable|string',
        ], [
            'catatan.required_if' => 'Catatan revisi wajib diisi jika Anda memilih opsi Revisi.'
        ]);

        $laporan = LaporanHarian::findOrFail($id);

        // simpan log verifikasi
        LogVerifikasi::create([
            'laporan_harian_id' => $laporan->id,
            'pembimbing_id'     => Auth::user()->pembimbing->id,
            'status'            => $request->status,
            'catatan_pembimbing'=> $request->catatan,
            'verified_at'       => Carbon::now(),
        ]);

        // update status laporan utama
        $laporan->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->route('pembimbing.verifikasi.index')
            ->with('success', 'Laporan berhasil diverifikasi');
    }
}
