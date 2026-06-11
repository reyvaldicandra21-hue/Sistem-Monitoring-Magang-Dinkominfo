<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesertaPkl;
use App\Models\LaporanHarian;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Divisi;
use App\Models\Pembimbing;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    // 🔥 API REALTIME
    public function data()
    {
        try {

            $today = now()->toDateString();

            // 🔥 DATA LAPORAN TERBARU
            $laporan = LaporanHarian::latest()
                ->take(5)
                ->get()
                ->map(function ($l) {
                    return [
                        'nama' => $l->user->name ?? 'User',
                        'judul' => $l->kegiatan ?? '-',
                        'created_at' => $l->created_at
                            ? $l->created_at->format('d M Y')
                            : '-'
                    ];
                });

            // 🔥 DATA ABSENSI HARI INI
            $todayCarbon = today();

            $pesertaAktifHariIni = PesertaPkl::whereDate('tanggal_mulai', '<=', $todayCarbon)
                ->whereDate('tanggal_selesai', '>=', $todayCarbon)
                ->pluck('id');
            $hadir = Absensi::whereIn('peserta_pkl_id', $pesertaAktifHariIni)->whereDate('tanggal', $today)->where('status', 'hadir')->count();
            $terlambat = Absensi::whereIn('peserta_pkl_id', $pesertaAktifHariIni)->whereDate('tanggal', $today)->where('status', 'terlambat')->count();
            $izin = Absensi::whereIn('peserta_pkl_id', $pesertaAktifHariIni)->whereDate('tanggal', $today)->where('status', 'izin')->count();
            $sakit = Absensi::whereIn('peserta_pkl_id', $pesertaAktifHariIni)->whereDate('tanggal', $today)->where('status', 'sakit')->count();

            $totalPeserta = PesertaPkl::count();
            $nextMonth = $todayCarbon->copy()->addMonth();

            $monthlyCurrentActive = PesertaPkl::whereDate('tanggal_mulai', '<=', $todayCarbon)
                ->whereDate('tanggal_selesai', '>=', $todayCarbon)
                ->count();

            $monthlyCurrentPending = PesertaPkl::whereMonth('tanggal_mulai', $todayCarbon->month)
                ->whereYear('tanggal_mulai', $todayCarbon->year)
                ->whereDate('tanggal_mulai', '>', $todayCarbon)
                ->count();

            // 🔥 ALPHA
           $totalPesertaAktif = $pesertaAktifHariIni->count();

            $alpha = max(
                0,
                $totalPesertaAktif - (
                    $hadir +
                    $terlambat +
                    $izin +
                    $sakit
                )
            );

            // 🔥 DATA CHART TREN LAPORAN (7 HARI TERAKHIR)
            $weeklyLabels = [];
            $weeklyData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $weeklyLabels[] = $date->translatedFormat('D');
                $weeklyData[] = LaporanHarian::whereDate('tanggal', $date->toDateString())->count();
            }

$todayCarbon = today();

$divisi = Divisi::withCount([
    'pesertaPkl as peserta_aktif_pending_count' => function ($query) use ($todayCarbon) {
        $query->where(function ($q) use ($todayCarbon) {

            // AKTIF
            $q->where(function ($aktif) use ($todayCarbon) {
                $aktif->whereDate('tanggal_mulai', '<=', $todayCarbon)
                      ->whereDate('tanggal_selesai', '>=', $todayCarbon);
            })

            // ATAU PENDING
            ->orWhere(function ($pending) use ($todayCarbon) {
                $pending->whereDate('tanggal_mulai', '>', $todayCarbon);
            });

        });
    }
])->get();

$divisiData = $divisi->map(function ($d) {
    return [
        'nama' => $d->nama_divisi,
        'jumlah' => $d->peserta_aktif_pending_count
    ];
});

            // 🔥 DATA PESERTA TERBARU (SEMINGGU TERAKHIR)
            $pesertaList = PesertaPkl::with(['user', 'divisi'])
                ->where('created_at', '>=', now()->subDays(7))
                ->latest()
                ->take(5) // Limit to 5 to keep it compact as requested
                ->get()
                ->map(function($p) {
                    return [
                        'nama' => $p->user->name ?? '-',
                        'divisi' => $p->divisi->nama_divisi ?? '-',
                        'sekolah' => $p->asal_institusi ?? '-', // Consistent with other views
                        'status' => 'Aktif'
                    ];
                });

            // 🔥 HITUNG PESERTA BELUM DITEMPATKAN
            $unassignedCount = PesertaPkl::whereNull('divisi_id')
                ->orWhereNull('pembimbing_id')
                ->count();

            // 🔥 RESPONSE JSON
            return response()->json([
                'total_user' => User::count(),
                'unassigned_count' => $unassignedCount,
                'total_peserta' => $totalPeserta,
                'total_laporan' => LaporanHarian::count(),

                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
                'total_peserta_aktif_hari_ini' => $totalPesertaAktif,
                'monthly_current_active' => $monthlyCurrentActive,
                'monthly_current_pending' => $monthlyCurrentPending,
                'monthly_current_label' => $todayCarbon->translatedFormat('F'),
                'monthly_next_label' => $nextMonth->translatedFormat('F'),

                'laporan' => $laporan,
                'divisi_list' => $divisiData,
                'peserta_list' => $pesertaList,

                // 🔥 CHART DATA
                'chart_weekly' => [
                    'labels' => $weeklyLabels,
                    'data' => $weeklyData,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}
