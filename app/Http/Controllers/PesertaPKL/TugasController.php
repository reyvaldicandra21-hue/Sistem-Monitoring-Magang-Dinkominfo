<?php

namespace App\Http\Controllers\PesertaPKL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\TugasPengumpulan;
use App\Models\PesertaPKL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index()
    {
        $peserta = PesertaPKL::where('user_id', Auth::id())->first();

        if (!$peserta) {
            return redirect('pesertapkl.lengkapidata');
        }

        $tugas = $peserta->tugas()
            ->with([
                'pengumpulan' => function ($q) use ($peserta) {
                    $q->where('peserta_pkl_id', $peserta->id);
                }
            ])
            ->latest()
            ->get();

        return view('pesertapkl.tugas.index', compact('tugas', 'peserta'));
    }

    public function create($uuid)
{
    $peserta = PesertaPKL::where('user_id', Auth::id())->first();

    if (!$peserta) {
        return redirect('/lengkapi-data');
    }

    $tugas = $peserta->tugas()->where('uuid', $uuid)->firstOrFail();

    return view('pesertapkl.tugas.create', compact('tugas'));
}

    // =========================
    // SHOW
    // =========================
    public function show($uuid)
    {
        $peserta = PesertaPKL::where('user_id', Auth::id())->first();

        if (!$peserta) {
            return redirect('/lengkapi-data');
        }

        $tugas = $peserta->tugas()
            ->with('pengumpulan')
            ->where('uuid', $uuid)->firstOrFail();

        $pengumpulan = $tugas->pengumpulan
            ->where('peserta_pkl_id', $peserta->id)
            ->first();

        // 🔥 URL FILE TUGAS (dari pembimbing)
        $fileTugas = $tugas->file
            ? asset('storage/' . $tugas->file)
            : null;

        // 🔥 URL FILE PENGUMPULAN
        $filePengumpulan = $pengumpulan && $pengumpulan->file
            ? asset('storage/' . $pengumpulan->file)
            : null;

        return view('pesertapkl.tugas.show', compact(
            'tugas',
            'peserta',
            'pengumpulan',
            'fileTugas',
            'filePengumpulan'
        ));
    }

    // =========================
    // KUMPUL
    // =========================
    public function kumpul(Request $request, $uuid)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,jpg,png|max:5120',
            'catatan' => 'nullable'
        ]);

        $peserta = PesertaPKL::where('user_id', Auth::id())->first();

        if (!$peserta) {
            return redirect('pesertapkl.lengkapidata');
        }

        $tugas = $peserta->tugas()->where('uuid', $uuid)->firstOrFail();

        $existing = TugasPengumpulan::where('tugas_id', $tugas->id)
            ->where('peserta_pkl_id', $peserta->id)
            ->first();

        $path = $existing->file ?? null;

        // =========================
        // UPLOAD FILE (SYMLINK)
        // =========================
        if ($request->hasFile('file')) {

            // hapus file lama
            if ($existing && $existing->file && Storage::disk('public')->exists($existing->file)) {
                Storage::disk('public')->delete($existing->file);
            }

            // simpan ke storage/app/public/tugas
            $path = $request->file('file')->store('tugas', 'public');
        }

        // =========================
        // STATUS
        // =========================
        $status = now()->format('Y-m-d') > $tugas->deadline
            ? 'terlambat'
            : 'dikumpulkan';

        // =========================
        // SIMPAN
        // =========================
        TugasPengumpulan::updateOrCreate(
            [
                'tugas_id' => $tugas->id,
                'peserta_pkl_id' => $peserta->id,
            ],
            [
                'file' => $path,
                'catatan' => $request->catatan,
                'tanggal_kumpul' => now(),
                'status' => $status
            ]
        );

        // Update parent status
        $totalPeserta = $tugas->pesertaPkl()->count();
        $totalKumpul = $tugas->pengumpulan()->count();

        if ($totalKumpul == 0) {
            $tugas->update(['status' => 'belum']);
        } elseif ($totalKumpul < $totalPeserta) {
            $tugas->update(['status' => 'sebagian']);
        } else {
            $tugas->update(['status' => 'selesai']);
        }

        return redirect()->route('pesertapkl.tugas.index')
            ->with('success', 'Tugas berhasil disimpan');
    }
}
