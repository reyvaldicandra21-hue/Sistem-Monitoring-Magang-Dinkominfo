<?php

namespace App\Http\Controllers\Pembimbing;

use App\Models\Tugas;
use App\Models\PesertaPkl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TugasPController extends Controller
{
    public function index(Request $request)
    {
        $pembimbing = Auth::user()->pembimbing;

        $query = Tugas::with([
                'pesertaPkl.user',
                'pengumpulan' // 🔥 penting untuk hitung status
            ])
            ->whereHas('pesertaPkl', function ($q) use ($pembimbing) {
                $q->where('pembimbing_id', $pembimbing->id);
            });

        // Filter Judul
        if ($request->judul) {
            $query->where('judul', 'like', '%' . $request->judul . '%');
        }

        // Filter Deadline
        if ($request->deadline) {
            $query->whereDate('deadline', $request->deadline);
        }

        // Filter Peserta
        if ($request->peserta) {
            $query->whereHas('pesertaPkl.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->peserta . '%');
            });
        }

        $tugas = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // =========================
        // TRANSFORM DATA
        // =========================
        $tugas->getCollection()->transform(function ($item) {
            $item->total_peserta = $item->pesertaPkl->count();
            $item->total_kumpul = $item->pengumpulan->count();
            return $item;
        });

        // ✅ RETURN DI LUAR TRANSFORM
        $stats = [
            'total' => $tugas->total(),
            'selesai' => $query->get()->where('status', 'selesai')->count(),
            'berjalan' => $query->get()->where('status', '!=', 'selesai')->count(),
        ];

        return view('pembimbing.tugas.index', compact('tugas', 'stats'));
    }

    public function create()
    {
        $pembimbing = Auth::user()->pembimbing;
        $today = now()->toDateString();

        $peserta = PesertaPkl::where('pembimbing_id', $pembimbing->id)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->get();

        return view('pembimbing.tugas.create', compact('peserta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'required|max:10000',
            'deadline' => 'required|date',
            'peserta' => 'required|array|min:1',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,jpg,png|max:10240'
        ]);

        $pembimbing = Auth::user()->pembimbing;

        // upload file
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tugas_file', 'public');
        }

        $tugas = Tugas::create([
            'pembimbing_id' => $pembimbing->id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
            'file' => $filePath
        ]);

        // =========================
        // PESERTA
        // =========================
        $today = now()->toDateString();
        if ($request->has('all_peserta') || (is_array($request->peserta) && in_array('all', $request->peserta))) {
            $pesertaIds = PesertaPkl::where('pembimbing_id', $pembimbing->id)
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->pluck('id')
                ->toArray();
        } else {
            $pesertaIds = PesertaPkl::whereIn('id', $request->peserta ?? [])
                ->where('pembimbing_id', $pembimbing->id)
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->pluck('id')
                ->toArray();
        }

        $tugas->pesertaPkl()->sync($pesertaIds);

        return redirect()
            ->route('pembimbing.tugas.index')
            ->with('success', 'Tugas berhasil dibuat');
    }

    public function edit($id)
    {
        $pembimbing = Auth::user()->pembimbing;
        $today = now()->toDateString();

        $tugas = Tugas::whereHas('pesertaPkl', function ($q) use ($pembimbing) {
                $q->where('pembimbing_id', $pembimbing->id);
            })
            ->with('pesertaPkl')
            ->findOrFail($id);

        $peserta = PesertaPkl::where('pembimbing_id', $pembimbing->id)
            ->where(function($q) use ($today, $id) {
                $q->whereDate('tanggal_mulai', '<=', $today)
                  ->whereDate('tanggal_selesai', '>=', $today)
                  ->orWhereHas('tugas', function($tQuery) use ($id) {
                      $tQuery->where('tugas_id', $id);
                  });
            })
            ->get();

        return view('pembimbing.tugas.edit', compact('tugas', 'peserta'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'deskripsi' => 'required|max:10000',
            'deadline' => 'required|date',
            'peserta' => 'nullable|array',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,jpg,png|max:5120'
        ]);

        $pembimbing = Auth::user()->pembimbing;

        $tugas = Tugas::findOrFail($id);

        $tugas->judul = $request->judul;
        $tugas->deskripsi = $request->deskripsi;
        $tugas->deadline = $request->deadline;

        // file baru
        if ($request->hasFile('file')) {

            if ($tugas->file && Storage::disk('public')->exists($tugas->file)) {
                Storage::disk('public')->delete($tugas->file);
            }

            $tugas->file = $request->file('file')->store('tugas_file', 'public');
        }

        $tugas->save();

        // =========================
        // PESERTA
        // =========================
        $today = now()->toDateString();
        if ($request->has('all_peserta')) {
            $pesertaIds = PesertaPkl::where('pembimbing_id', $pembimbing->id)
                ->where(function($q) use ($today, $id) {
                    $q->whereDate('tanggal_mulai', '<=', $today)
                      ->whereDate('tanggal_selesai', '>=', $today)
                      ->orWhereHas('tugas', function($tQuery) use ($id) {
                          $tQuery->where('tugas_id', $id);
                      });
                })
                ->pluck('id')
                ->toArray();
        } else {
            $pesertaIds = PesertaPkl::whereIn('id', $request->peserta ?? [])
                ->where('pembimbing_id', $pembimbing->id)
                ->where(function($q) use ($today, $id) {
                    $q->whereDate('tanggal_mulai', '<=', $today)
                      ->whereDate('tanggal_selesai', '>=', $today)
                      ->orWhereHas('tugas', function($tQuery) use ($id) {
                          $tQuery->where('tugas_id', $id);
                      });
                })
                ->pluck('id')
                ->toArray();
        }

        $tugas->pesertaPkl()->sync($pesertaIds);

        // Recalculate status in case assigned participants changed
        $totalPeserta = count($pesertaIds);
        $totalKumpul = $tugas->pengumpulan()->count();

        if ($totalKumpul == 0) {
            $tugas->status = 'belum';
        } elseif ($totalKumpul < $totalPeserta) {
            $tugas->status = 'sebagian';
        } else {
            $tugas->status = 'selesai';
        }
        $tugas->save();

        return redirect()
            ->route('pembimbing.tugas.index')
            ->with('success', 'Tugas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);

        if ($tugas->file && Storage::disk('public')->exists($tugas->file)) {
            Storage::disk('public')->delete($tugas->file);
        }

        $tugas->delete();

        return back()->with('success', 'Tugas dihapus');
    }

    public function hasil($id)
    {
        $tugas = Tugas::with('pengumpulan.pesertaPkl.user')->findOrFail($id);

        return view('pembimbing.tugas.hasil', compact('tugas'));
    }
}
