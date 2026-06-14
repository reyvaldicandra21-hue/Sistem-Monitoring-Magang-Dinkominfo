<?php

namespace App\Http\Controllers\PesertaPKL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\PesertaPkl;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{

    public function index()
    {

        $peserta = PesertaPkl::where('user_id',Auth::id())->first();

        $today = Carbon::today();

        $absensi = Absensi::where('peserta_pkl_id',$peserta->id)
                    ->whereDate('tanggal',$today)
                    ->first();

        return view('pesertapkl.absensi.index',compact('absensi'));

    }



public function absenMasuk(Request $request)
{
    // ================= VALIDASI =================
    $request->validate([
        'foto' => 'required',
        'latitude' => 'required',
        'longitude' => 'required'
    ]);

    $peserta = PesertaPkl::where('user_id', Auth::id())->first();

    if(!$peserta){
        return back()->with('error','Data peserta tidak ditemukan');
    }

    $today = Carbon::today();

    // ================= CEK SUDAH ABSEN =================
    $cek = Absensi::where('peserta_pkl_id', $peserta->id)
            ->whereDate('tanggal', $today)
            ->first();

    if($cek){
        return back()->with('error','Anda sudah absen hari ini');
    }

    // ================= SIMPAN FOTO =================
    $imageName = null;

    if($request->foto){

        $image = $request->foto;

        $image = str_replace('data:image/png;base64,','',$image);
        $image = str_replace(' ','+',$image);

        $imageName = 'absensi_'.time().'.png';

        Storage::disk('public')->put(
            'absensi/'.$imageName,
            base64_decode($image)
        );

        $imageName = 'absensi/'.$imageName;
    }

    // ================= STATUS ABSEN =================
    // Logika terlambat dihapus, semua absen masuk dianggap 'hadir'
    $now = Carbon::now();
    $status = 'hadir';

    // ================= SIMPAN DATA =================
    Absensi::create([
        'user_id' => Auth::id(),
        'peserta_pkl_id' => $peserta->id,
        'tanggal' => $today,
        'jam_masuk' => $now->format('H:i:s'),
        'status' => $status,
        'foto' => $imageName,

        // 🔥 GPS WAJIB
        'latitude' => $request->latitude,
        'longitude' => $request->longitude
    ]);

    return back()->with('success','Absen masuk berhasil');
}



    public function absenPulang()
    {

        $peserta = PesertaPkl::where('user_id',Auth::id())->first();

        $today = Carbon::today();

        $absensi = Absensi::where('peserta_pkl_id',$peserta->id)
                    ->whereDate('tanggal',$today)
                    ->first();

        if(!$absensi){
            return back()->with('error','Anda belum absen masuk');
        }

        if($absensi->jam_pulang){
            return back()->with('error','Anda sudah absen pulang');
        }

        $absensi->update([
            'jam_pulang'=>Carbon::now()->format('H:i:s')
        ]);

        return back()->with('success','Absen pulang berhasil');

    }

public function formIzin()
{
    $peserta = PesertaPkl::where('user_id', Auth::id())->first();
    $today = Carbon::today();
    
    $absensi = Absensi::where('peserta_pkl_id', $peserta->id)
                ->whereDate('tanggal', $today)
                ->first();

    if ($absensi) {
        return redirect()->route('pesertapkl.absensi.index')
            ->with('error', 'Anda sudah mengisi absensi hari ini.');
    }

    return view('pesertapkl.absensi.izin');
}

public function izin(Request $request)
{
    $request->validate([
        'tanggal' => 'required|date',
        'jenis' => 'required',
        'alasan' => 'required'
    ]);

    $peserta = PesertaPkl::where('user_id', Auth::id())->first();

    // Cek apakah sudah ada absensi pada tanggal tersebut
    $cek = Absensi::where('peserta_pkl_id', $peserta->id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

    if ($cek) {
        return redirect()->route('pesertapkl.absensi.index')
            ->with('error', 'Anda sudah melakukan absensi pada tanggal tersebut.');
    }

    $bukti = null;

    if ($request->file('bukti')) {
        // 🔥 WAJIB pakai store
        $bukti = $request->file('bukti')->store('bukti_izin', 'public');
    }

    Absensi::create([
        'user_id' => Auth::id(),
        'peserta_pkl_id' => $peserta->id,
        'tanggal' => $request->tanggal,
        'status' => $request->jenis,
        'alasan' => $request->alasan,
        'bukti' => $bukti,
        'status_verifikasi' => 'menunggu'
    ]);

    return redirect()
        ->route('pesertapkl.absensi.index')
        ->with('success', 'Pengajuan izin berhasil dikirim');
}



    public function riwayat(Request $request)
    {

        $peserta = PesertaPkl::where('user_id',Auth::id())->first();

        $bulan = $request->bulan ?? now()->format('Y-m');

        $absensis = Absensi::where('peserta_pkl_id',$peserta->id)
            ->whereMonth('tanggal',Carbon::parse($bulan)->month)
            ->whereYear('tanggal',Carbon::parse($bulan)->year)
            ->orderBy('tanggal','desc')
            ->get();

        return view('pesertapkl.absensi.riwayat',compact('absensis','bulan'));

    }

}
