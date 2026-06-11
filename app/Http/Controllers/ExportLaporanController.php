<?php

namespace App\Http\Controllers;

use App\Models\PesertaPKL;
use App\Models\LaporanHarian;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExportLaporanController extends Controller
{
    /**
     * Cetak laporan harian ke PDF
     */
    public function laporanPdf($pesertaId)
    {
        $peserta = PesertaPKL::with([
            'laporanHarian.verifikasiTerakhir'
        ])->findOrFail($pesertaId);

        $pdf = Pdf::loadView(
            'export.laporan_pdf',
            compact('peserta')
        )->setPaper('A4', 'portrait');

        return $pdf->download(
            'Laporan_PKL_' . $peserta->nama . '.pdf'
        );
    }

    /**
     * Export absensi bulanan ke CSV (Excel)
     */
    public function absensiBulananCsv(Request $request, $pesertaId)
    {
        $bulan = $request->bulan ?? now()->format('m');
        $tahun = $request->tahun ?? now()->format('Y');

        $peserta = PesertaPKL::findOrFail($pesertaId);

        $absensi = Absensi::where('peserta_pkl_id', $pesertaId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')
            ->get();

        $filename = "Absensi_{$peserta->nama}_{$bulan}_{$tahun}.csv";

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($absensi) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Tanggal',
                'Jam Masuk',
                'Jam Pulang',
                'Status'
            ]);

            foreach ($absensi as $a) {
                fputcsv($handle, [
                    $a->tanggal,
                    $a->jam_masuk,
                    $a->jam_pulang,
                    ucfirst($a->status)
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
