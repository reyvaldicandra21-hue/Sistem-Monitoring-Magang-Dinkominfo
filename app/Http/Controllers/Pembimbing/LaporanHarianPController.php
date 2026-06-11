<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\LaporanHarian;
use App\Models\LogVerifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanHarianPController extends Controller
{
    /**
     * LIST LAPORAN PESERTA BIMBINGAN
     */
    public function index(Request $request)
{
    $pembimbing = Auth::user()->pembimbing;

    $query = LaporanHarian::with([
        'pesertaPkl.user',
        'verifikasiTerakhir'
    ])->whereHas('pesertaPkl', function ($q) use ($pembimbing) {
        $q->where('pembimbing_id', $pembimbing->id);
    });

    // Filter status
    if ($request->status) {
        $query->where('status', $request->status);
    }

    // Filter tanggal
    if ($request->tanggal) {
        $query->whereDate('tanggal', $request->tanggal);
    }

    // Filter peserta
    if ($request->peserta) {
        $query->whereHas('pesertaPkl.user', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->peserta . '%');
        });
    }

    $laporans = $query->latest()->paginate(10)->withQueryString();

    return view('pembimbing.laporanharian.index', compact('laporans'));
}

    /**
     * DETAIL LAPORAN
     */
    public function show($id)
    {
        $pembimbing = Auth::user()->pembimbing;

        $laporan = LaporanHarian::with([
            'pesertaPkl.user',
            'verifikasis.pembimbing',
            'dokumentasi'
        ])->findOrFail($id);

        // Pastikan hanya peserta bimbingannya
        if ($laporan->pesertaPkl->pembimbing_id !== $pembimbing->id) {
            abort(403);
        }

        return view('pembimbing.laporanharian.show', compact('laporan'));
    }

    /**
     * APPROVE LAPORAN
     */
    public function approve($id)
    {
        $laporan = LaporanHarian::findOrFail($id);
        $pembimbing = Auth::user()->pembimbing;

        if ($laporan->pesertaPkl->pembimbing_id !== $pembimbing->id) {
            abort(403);
        }

        if ($laporan->status !== 'menunggu') {
            return back()->with('error', 'Laporan sudah diverifikasi.');
        }

        LogVerifikasi::create([
            'laporan_harian_id' => $laporan->id,
            'pembimbing_id'     => $pembimbing->id,
            'status'            => 'disetujui',
            'catatan_pembimbing'=> null,
        ]);

        $laporan->update([
            'status' => 'disetujui'
        ]);

        return back()->with('success', 'Laporan berhasil disetujui.');
    }

    /**
     * REJECT LAPORAN
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string'
        ]);

        $laporan = LaporanHarian::findOrFail($id);
        $pembimbing = Auth::user()->pembimbing;

        if ($laporan->pesertaPkl->pembimbing_id !== $pembimbing->id) {
            abort(403);
        }

        if ($laporan->status !== 'menunggu') {
            return back()->with('error', 'Laporan sudah diverifikasi.');
        }

        LogVerifikasi::create([
            'laporan_harian_id' => $laporan->id,
            'pembimbing_id'     => $pembimbing->id,
            'status'            => 'revisi',
            'catatan_pembimbing'=> $request->catatan,
        ]);

        $laporan->update([
            'status' => 'revisi'
        ]);

        return back()->with('success', 'Laporan berhasil dikembalikan untuk direvisi.');
    }

    public function verifikasi(Request $request, $id)
    {
    $request->validate([
        'status' => 'required|in:disetujui,revisi',
        'catatan' => 'required_if:status,revisi|nullable|string'
    ], [
        'catatan.required_if' => 'Catatan revisi wajib diisi jika Anda memilih opsi Revisi.'
    ]);

    $laporan = LaporanHarian::findOrFail($id);

    // update status laporan
    $laporan->update([
        'status' => $request->status
    ]);

    // simpan riwayat verifikasi
    LogVerifikasi::create([
        'laporan_harian_id' => $laporan->id,
        'pembimbing_id' => Auth::user()->pembimbing->id,
        'status' => $request->status,
        'catatan_pembimbing' => $request->catatan,
    ]);

    return back()->with('success','Laporan berhasil diverifikasi');
    }
}
