<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PesertaPkl;
use App\Models\Absensi;
use App\Models\LaporanHarian;
use App\Models\Tugas;
use Carbon\Carbon;

class PembimbingDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $pembimbing = Auth::user()->pembimbing;

        if (!$pembimbing) {
            abort(403, 'User belum terhubung ke pembimbing');
        }

        $pembimbingId = $pembimbing->id;

        // ===============================
        // TOTAL PESERTA
        // ===============================
        $totalPeserta = PesertaPkl::where('pembimbing_id', $pembimbingId)->count();

        // ===============================
        // ABSENSI
        // ===============================
        $absensiQuery = Absensi::whereDate('tanggal', $today)
            ->whereHas('pesertaPkl', function ($q) use ($pembimbingId) {
                $q->where('pembimbing_id', $pembimbingId);
            });

        $hadir = (clone $absensiQuery)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();

        $terlambat = (clone $absensiQuery)
            ->where('status', 'terlambat')
            ->count();

        $izin = (clone $absensiQuery)
            ->where('status', 'izin')
            ->count();

        $sakit = (clone $absensiQuery)
            ->where('status', 'sakit')
            ->count();

        // BELUM ABSEN
        $belumAbsen = PesertaPkl::where('pembimbing_id', $pembimbingId)
            ->whereDoesntHave('absensi', function ($q) use ($today) {
                $q->whereDate('tanggal', $today);
            })
            ->count();

        // ===============================
        // LAPORAN (STATISTIK)
        // ===============================
        $laporanQuery = LaporanHarian::whereHas('pesertaPkl', function ($q) use ($pembimbingId) {
            $q->where('pembimbing_id', $pembimbingId);
        });

        $menunggu = (clone $laporanQuery)->where('status', 'menunggu')->count();
        $disetujui = (clone $laporanQuery)->where('status', 'disetujui')->count();
        $revisi = (clone $laporanQuery)->where('status', 'revisi')->count();

        // ===============================
        // TUGAS
        // ===============================
        $tugas = Tugas::whereHas('pesertaPkl', function ($q) use ($pembimbingId) {
            $q->where('pembimbing_id', $pembimbingId);
        })->where('status', '!=', 'selesai')->count();

        // ===============================
        // DATA CHART TREN LAPORAN (7 HARI TERAKHIR)
        // ===============================
        $weeklyLabels = [];
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyLabels[] = $date->translatedFormat('D');
            $weeklyData[] = LaporanHarian::whereHas('pesertaPkl', function($q) use ($pembimbingId) {
                $q->where('pembimbing_id', $pembimbingId);
            })->whereDate('tanggal', $date->toDateString())->count();
        }

        // ===============================
        // DAFTAR PESERTA BIMBINGAN
        // ===============================
        $pesertaBimbingan = PesertaPkl::with(['user', 'divisi'])
            ->where('pembimbing_id', $pembimbingId)
            ->get();

        // ===============================
        // LAPORAN TERBARU
        // ===============================
        $laporan = LaporanHarian::with(['pesertaPkl.user'])
            ->whereHas('pesertaPkl', function ($q) use ($pembimbingId) {
                $q->where('pembimbing_id', $pembimbingId);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('pembimbing.dashboard', compact(
            'totalPeserta',
            'hadir',
            'belumAbsen',
            'izin',
            'sakit',
            'terlambat',
            'menunggu',
            'disetujui',
            'revisi',
            'tugas',
            'laporan',
            'weeklyLabels',
            'weeklyData',
            'pesertaBimbingan'
        ));
    }
}
