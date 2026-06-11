<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PesertaPKL;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiPController extends Controller
{

public function index(Request $request)
{
    $pembimbing = Auth::user()->pembimbing;

    $bulan = $request->bulan ?? now()->format('Y-m');

    try {
        $start = Carbon::parse($bulan)->startOfMonth();
        $end   = Carbon::parse($bulan)->endOfMonth();
        $bulan = $start->format('Y-m');
    } catch (\Exception $e) {
        $start = now()->startOfMonth();
        $end   = now()->endOfMonth();
        $bulan = $start->format('Y-m');
    }

    $days = $start->daysInMonth;
    $today = Carbon::today();

    $pesertas = PesertaPKL::with(['user','divisi'])
        ->where('pembimbing_id', $pembimbing->id)
        ->get()
        ->map(function($peserta) use ($start,$end,$days,$today){

            $absensi = Absensi::where('peserta_pkl_id',$peserta->id)
                ->whereBetween('tanggal',[$start,$end])
                ->get();

            $hadir = $absensi->where('status','hadir')->count();
            $terlambat = $absensi->where('status','terlambat')->count();
            $izin = $absensi->where('status','izin')->count();
            $sakit = $absensi->where('status','sakit')->count();

            $alpha = 0;
            $totalHariAktif = 0;

            for($i=1; $i <= $days; $i++){

                $tgl = $start->copy()->day($i);

                if($tgl->gt($today)) continue;

                $totalHariAktif++;

                $ada = $absensi->first(function($a) use ($tgl){
                    return Carbon::parse($a->tanggal)->isSameDay($tgl);
                });

                if(!$ada){
                    $alpha++;
                }
            }

            $masuk = $hadir + $terlambat;

            $persen = $totalHariAktif > 0
                ? round(($masuk / $totalHariAktif) * 100)
                : 0;

            $peserta->hadir = $hadir + $terlambat;
            $peserta->izin = $izin;
            $peserta->sakit = $sakit;
            $peserta->alpha = $alpha;
            $peserta->persen = $persen;

            return $peserta;
        });

    // STATS RINGKAS UNTUK WIDGET
    $totalPeserta = $pesertas->count();
    $hadirHariIni = Absensi::whereIn('peserta_pkl_id', $pesertas->pluck('id'))
        ->whereDate('tanggal', Carbon::today())
        ->whereIn('status', ['hadir','terlambat'])
        ->count();
    $izinHariIni = Absensi::whereIn('peserta_pkl_id', $pesertas->pluck('id'))
        ->whereDate('tanggal', Carbon::today())
        ->whereIn('status', ['izin','sakit'])
        ->count();

    return view('pembimbing.absensi.index', compact(
        'pesertas','start','end','days','bulan', 'totalPeserta', 'hadirHariIni', 'izinHariIni'
    ));
}

// ================= KALENDER =================
public function kalender($id, $bulan)
{
    $pembimbing = Auth::user()->pembimbing;

    try {
        $start = Carbon::parse($bulan)->startOfMonth();
        $end   = Carbon::parse($bulan)->endOfMonth();
    } catch (\Exception $e) {
        $start = now()->startOfMonth();
        $end   = now()->endOfMonth();
    }

    $peserta = PesertaPKL::where('id',$id)
        ->where('pembimbing_id',$pembimbing->id)
        ->firstOrFail();

    $data = Absensi::where('peserta_pkl_id', $peserta->id)
        ->whereBetween('tanggal', [$start, $end])
        ->get();

    return response()->json($data);
}

// ================= DETAIL =================
public function detail($peserta,$tanggal)
{
    $pembimbing = Auth::user()->pembimbing;

    $cek = PesertaPKL::where('id',$peserta)
        ->where('pembimbing_id',$pembimbing->id)
        ->exists();

    if(!$cek){
        abort(403);
    }

    $absensi = Absensi::where('peserta_pkl_id',$peserta)
        ->whereDate('tanggal',$tanggal)
        ->first();

    if(!$absensi){
        return response()->json(null);
    }

    return response()->json([
        'status' => $absensi->status,
        'jam_masuk' => $absensi->jam_masuk,
        'jam_pulang' => $absensi->jam_pulang,
        'foto' => $absensi->foto,
        'bukti' => $absensi->bukti ?? null,
        'latitude' => $absensi->latitude,
        'longitude' => $absensi->longitude,
        'alasan' => $absensi->alasan ?? null
    ]);
}

}
