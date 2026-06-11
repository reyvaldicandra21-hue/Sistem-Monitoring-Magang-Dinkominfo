<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesertaPkl;
use App\Models\User;
use App\Models\Pembimbing;
use App\Models\HistoryDivisi;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class PesertaPklController extends Controller
{
    // ================= LIST =================
    public function index(Request $request)
    {
        $query = PesertaPkl::with([
            'user',
            'pembimbing',
            'divisi'
        ]);

        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $today = now()->toDateString();
            if ($request->status == 'pending') {
                $query->where('tanggal_mulai', '>', $today);
            } elseif ($request->status == 'aktif') {
                $query->where('tanggal_mulai', '<=', $today)
                      ->where('tanggal_selesai', '>=', $today);
            } elseif ($request->status == 'selesai') {
                $query->where('tanggal_selesai', '<', $today);
            } elseif ($request->status == 'unassigned') {
                $query->where(function($q) {
                    $q->whereNull('divisi_id')
                      ->orWhereNull('pembimbing_id');
                });
            }
        }

        $pesertas = $query->latest()->paginate(12)->withQueryString();

        $today = now()->toDateString();
        $totalPeserta = PesertaPkl::count();
        $pendingCount = PesertaPkl::where('tanggal_mulai', '>', $today)->count();
        $aktifCount = PesertaPkl::where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->count();
        $selesaiCount = PesertaPkl::where('tanggal_selesai', '<', $today)->count();

        return view('admin.pesertapkl.index', compact(
            'pesertas',
            'totalPeserta',
            'pendingCount',
            'aktifCount',
            'selesaiCount'
        ));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.pesertapkl.create', [
            'pembimbings' => Pembimbing::all(),
            'divisi' => Divisi::all(),
        ]);
    }

    // ================= STORE =================
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'jenis' => 'required|in:siswa,mahasiswa',

        // 🔥 tambahan
        'tanggal_mulai' => 'nullable|date',
        'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',

        // Assign fields
        'pembimbing_id' => 'nullable|exists:pembimbings,id',

        'divisi_id' => 'nullable|exists:divisi,id',
    ]);

    DB::transaction(function () use ($request) {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pesertapkl',
        ]);

        PesertaPkl::create([
            'user_id' => $user->id,
            'jenis' => $request->jenis,
            'asal_institusi' => $request->asal_institusi,
            'jurusan' => $request->jurusan,
            'no_hp' => $request->no_hp,

            // 🔥 inti sistem
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,

            // Assign fields
            'pembimbing_id' => $request->pembimbing_id,

            'divisi_id' => $request->divisi_id,
        ]);
    });

    return redirect()->route('admin.pesertapkl.index')
        ->with('success', 'Peserta berhasil ditambahkan.');
}

    // ================= DETAIL =================
public function show($id)
{
    $pesertapkl = PesertaPkl::with([
        'user',
        'pembimbing',
        'divisi',
        'absensi',
        'laporanHarian' => function($q) {
            $q->with('verifikasiTerakhir.pembimbing')->latest('tanggal');
        },
        'tugas' => function($q) use ($id) {
            $q->with(['pembimbing', 'pengumpulan' => function($pq) use ($id) {
                $pq->where('peserta_pkl_id', $id);
            }])->latest();
        },
        'historyDivisi' => function($q) {
            $q->with(['divisiLama', 'divisiBaru']);
        }
    ])->findOrFail($id);

    return view('admin.pesertapkl.show', compact('pesertapkl'));
}

    // ================= EDIT =================
    public function edit($id)
    {
        $pesertapkl = PesertaPkl::with(['user', 'historyDivisi'])->findOrFail($id);

        return view('admin.pesertapkl.edit', [
            'peserta'      => $pesertapkl,
            'pembimbings'  => Pembimbing::orderBy('nama')->get(),
            'divisi'       => Divisi::orderBy('nama_divisi')->get(),
        ]);
    }

    // ================= UPDATE =================
public function update(Request $request, $id)
{
    $pesertapkl = PesertaPkl::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $pesertapkl->user_id,
        'jenis' => 'required|in:siswa,mahasiswa',

        // 🔥 tambahan
        'tanggal_mulai' => 'nullable|date',
        'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',

        // Assign fields
        'pembimbing_id' => 'nullable|exists:pembimbings,id',

        'divisi_id' => 'nullable|exists:divisi,id',
    ]);

  DB::transaction(function () use ($request, $pesertapkl) {

    $pesertapkl->user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    if ($request->filled('password')) {
        $pesertapkl->user->update([
            'password' => Hash::make($request->password),
        ]);
    }

    // Simpan divisi lama sebelum diubah
    $divisiLama = $pesertapkl->divisi_id;

    $pesertapkl->update([
        'jenis' => $request->jenis,
        'asal_institusi' => $request->asal_institusi,
        'jurusan' => $request->jurusan,
        'no_hp' => $request->no_hp,

        'tanggal_mulai' => $request->tanggal_mulai,
        'tanggal_selesai' => $request->tanggal_selesai,

        'pembimbing_id' => $request->pembimbing_id,
        'divisi_id' => $request->divisi_id,
    ]);

    // Jika divisi berubah, simpan ke history
    if ($divisiLama != $request->divisi_id) {

        HistoryDivisi::create([
            'peserta_pkl_id'     => $pesertapkl->id,
            'divisi_id_lama'     => $divisiLama,
            'divisi_id_baru'     => $request->divisi_id,
            'tanggal_perubahan'  => now(),
            'keterangan'         => 'Perubahan divisi oleh admin',
        ]);
    }
});

    return redirect()->route('admin.pesertapkl.index')
        ->with('success', 'Peserta berhasil diperbarui.');
}
    // ================= DELETE =================
    public function destroy($id)
    {
        $pesertapkl = PesertaPkl::findOrFail($id);

        DB::transaction(function () use ($pesertapkl) {
            $pesertapkl->delete();
            $pesertapkl->user->delete();
        });

        return redirect()->route('admin.pesertapkl.index')
            ->with('success', 'Peserta berhasil dihapus.');
    }

    public function statistikTahunan()
    {
        $tahun = now()->year;

        $bulanList = [];

        $endOfYear = Carbon::create($tahun, 12, 31)->endOfMonth();

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $startOfMonth = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $endOfMonth = Carbon::create($tahun, $bulan, 1)->endOfMonth();

            // Count active + pending (registered on/before this month, not completed before this month)
            $total = PesertaPkl::where('status', '!=', 'ditolak')
                ->where('created_at', '<=', $endOfMonth)
                ->where('tanggal_mulai', '<=', $endOfYear)
                ->where(function($query) use ($startOfMonth) {
                    $query->where('tanggal_selesai', '>=', $startOfMonth)
                          ->orWhereNull('tanggal_selesai')
                          ->orWhereNull('tanggal_mulai');
                })
                ->count();

            $bulanList[] = [
                'bulan' => $bulan,
                'nama' => Carbon::createFromDate($tahun, $bulan, 1)
                        ->translatedFormat('F'),
                'total' => $total
            ];
        }

        return response()->json($bulanList);
    }

    public function statistikBulanan($bulan)
    {
        $tahun = now()->year;

        $startOfMonth = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $endOfMonth = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        // Only count participants registered on or before this month
        $aktif = PesertaPkl::where('status', '!=', 'ditolak')
            ->where('created_at', '<=', $endOfMonth)
            ->whereNotNull('tanggal_mulai')
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_mulai', '<=', $endOfMonth)
            ->where('tanggal_selesai', '>=', $startOfMonth)
            ->count();

        $pending = PesertaPkl::where('status', '!=', 'ditolak')
            ->where('created_at', '<=', $endOfMonth)
            ->where(function($query) use ($endOfMonth) {
                $query->where('tanggal_mulai', '>', $endOfMonth)
                      ->orWhereNull('tanggal_mulai')
                      ->orWhereNull('tanggal_selesai');
            })
            ->count();

        $selesai = PesertaPkl::where('status', '!=', 'ditolak')
            ->where('created_at', '<=', $endOfMonth)
            ->whereNotNull('tanggal_selesai')
            ->whereBetween('tanggal_selesai', [$startOfMonth, $endOfMonth])
            ->count();

        $events = [];

        $peserta = PesertaPkl::with([
            'user',
            'divisi'
        ])
        ->where('created_at', '<=', $endOfMonth)
        ->where(function ($q) use ($bulan) {
            $q->whereMonth('tanggal_mulai', $bulan)
              ->orWhereMonth('tanggal_selesai', $bulan);
        })
        ->get();

        foreach ($peserta as $p) {

            $events[] = [
                'title' => $p->user->name . ' mulai PKL',
                'start' => $p->tanggal_mulai,
                'color' => '#22c55e',

                'extendedProps' => [
                    'status' => 'Mulai PKL',
                    'nama' => $p->user->name,
                    'divisi' => $p->divisi->nama_divisi ?? '-'
                ]
            ];

            $events[] = [
                'title' => $p->user->name . ' selesai PKL',
                'start' => $p->tanggal_selesai,
                'color' => '#ef4444',

                'extendedProps' => [
                    'status' => 'Selesai PKL',
                    'nama' => $p->user->name,
                    'divisi' => $p->divisi->nama_divisi ?? '-'
                ]
            ];
        }

        return response()->json([
            'aktif' => $aktif,
            'pending' => $pending,
            'selesai' => $selesai,
            'events' => $events
        ]);
    }
}
