<?php

namespace App\Http\Controllers\PesertaPKL;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PesertaPkl;
use App\Models\Absensi;
use App\Models\LaporanHarian;
use App\Models\Tugas;
use Carbon\Carbon;

class PesertaDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Ambil data peserta (bisa null)
        $peserta = PesertaPkl::where('user_id', $user->id)->first();

        // ================= DEFAULT VALUE (ANTI ERROR) =================
        $statusAbsensi = 'Belum Absen';
        $jamMasuk = null;
        $statusLaporan = 'Belum dibuat';
        $tugas = collect();
        $laporan = collect();

        // ================= JIKA SUDAH PUNYA DATA PKL =================
        if ($peserta) {

            // ================= ABSENSI =================
            $absensi = Absensi::where('peserta_pkl_id', $peserta->id)
                        ->whereDate('tanggal', $today)
                        ->first();

            if ($absensi) {
                if ($absensi->status == 'terlambat') {
                    $statusAbsensi = 'Hadir (Terlambat)';
                } else {
                    $statusAbsensi = ucfirst($absensi->status);
                }

                $jamMasuk = $absensi->jam_masuk;
            }

            // ================= LAPORAN HARI INI =================
            $laporanHariIni = LaporanHarian::where('peserta_pkl_id', $peserta->id)
                                ->whereDate('tanggal', $today)
                                ->first();

            $statusLaporan = $laporanHariIni ? 'Sudah dibuat' : 'Belum dibuat';

            // ================= TUGAS =================
            $tugas = Tugas::with('pesertaPkl')
            ->whereHas('pesertaPkl', function ($q) use ($peserta) {
                $q->where('peserta_pkls.id', $peserta->id);
            })
            ->latest()
            ->take(5)
            ->get();

            // ================= LAPORAN TERBARU =================
            $laporan = LaporanHarian::where('peserta_pkl_id', $peserta->id)
                        ->latest()
                        ->take(5)
                        ->get();
        }

        return view('pesertapkl.dashboard', compact(
            'user',
            'statusAbsensi',
            'jamMasuk',
            'statusLaporan',
            'tugas',
            'laporan'
        ));
    }
}
