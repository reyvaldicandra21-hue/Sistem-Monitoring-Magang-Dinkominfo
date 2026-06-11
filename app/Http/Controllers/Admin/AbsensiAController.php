<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PesertaPKL;
use App\Models\Absensi;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AbsensiAController extends Controller
{

public function index(Request $request)
{
    $bulan = $request->bulan ?? now()->format('Y-m');

    try {
        $start = Carbon::parse($bulan)->startOfMonth();
        $end   = Carbon::parse($bulan)->endOfMonth();
        // Normalize $bulan for the view and further use
        $bulan = $start->format('Y-m');
    } catch (\Exception $e) {
        $start = now()->startOfMonth();
        $end   = now()->endOfMonth();
        $bulan = $start->format('Y-m');
    }

    $days = $start->daysInMonth;
    $today = Carbon::today();

    $pesertas = PesertaPKL::with(['user'])
        ->latest()
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

            // inject ke object
            $peserta->hadir = $hadir;
            $peserta->terlambat = $terlambat;
            $peserta->izin = $izin;
            $peserta->sakit = $sakit;
            $peserta->alpha = $alpha;
            $peserta->persen = $persen;

            return $peserta;
        });

    return view('admin.absensis.index', compact(
        'pesertas','start','end','days','bulan'
    ));
}

public function kalender($id, $bulan)
{
    try {
        $start = Carbon::parse($bulan)->startOfMonth();
        $end   = Carbon::parse($bulan)->endOfMonth();
    } catch (\Exception $e) {
        $start = now()->startOfMonth();
        $end   = now()->endOfMonth();
    }

    $data = Absensi::where('peserta_pkl_id', $id)
        ->whereBetween('tanggal', [$start, $end])
        ->get();

    return response()->json($data);
}

public function detail($peserta,$tanggal)
{
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


public function export()
{
    $spreadsheet = new Spreadsheet();

    $bulanInput = request('bulan') ?? now()->format('Y-m');
    $parsedDate = Carbon::parse($bulanInput);
    $bulanValue = $parsedDate->month;
    $tahunValue = $parsedDate->year;

    // Ambil semua absensi bulan ini
    $absensi = \App\Models\Absensi::with('pesertaPkl.user')
        ->whereMonth('tanggal', $bulanValue)
        ->whereYear('tanggal', $tahunValue)
        ->get();

    $jumlahHari = $parsedDate->daysInMonth;

    $sheet1 = $spreadsheet->getActiveSheet();
    $sheet1->setTitle('Rekap Absensi');

/*
========================================
HEADER
========================================
*/

// Kolom terakhir
$lastCol = Coordinate::stringFromColumnIndex($jumlahHari + 2);

// Row 1
$sheet1->setCellValue('A1', 'NO');
$sheet1->setCellValue('B1', 'Nama');

// Merge "Bulan"
$sheet1->mergeCells("C1:{$lastCol}1");
$sheet1->setCellValue('C1', strtoupper($parsedDate->translatedFormat('F Y')));

// Row 2 (tanggal)
for ($i = 1; $i <= $jumlahHari; $i++) {
    $col = Coordinate::stringFromColumnIndex($i + 2);
    $sheet1->setCellValue($col . '2', $i);
}

// Merge NO & Nama ke bawah
$sheet1->mergeCells('A1:A2');
$sheet1->mergeCells('B1:B2');

// Styling header
$headerStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4F46E5'], // Indigo theme
    ],
    'borders' => [
        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
    ],
];
$sheet1->getStyle("A1:{$lastCol}2")->applyFromArray($headerStyle);

/*
========================================
DATA — ambil SEMUA peserta, bukan dari absensi
========================================
*/

// Kelompokkan absensi berdasarkan peserta_pkl_id untuk lookup cepat
$absensiByPeserta = $absensi->groupBy('peserta_pkl_id');

// Ambil SEMUA peserta PKL beserta user-nya (termasuk yang belum pernah absen)
$semuaPeserta = PesertaPKL::with('user')->latest()->get();

$row = 3;
$no  = 1;

foreach ($semuaPeserta as $peserta) {

    $nama  = optional($peserta->user)->name ?? 'Tidak diketahui';
    $items = $absensiByPeserta->get($peserta->id, collect());

    $sheet1->setCellValue("A$row", $no++);
    $sheet1->setCellValue("B$row", $nama);

    // Mapping tanggal → kode status (H/T/I/S/A)
    $dataTanggal = [];
    foreach ($items as $item) {
        $tgl = Carbon::parse($item->tanggal)->day;
        $dataTanggal[$tgl] = strtoupper(substr($item->status, 0, 1));
    }

    // Isi kolom per tanggal (kosong = '-')
    for ($i = 1; $i <= $jumlahHari; $i++) {
        $col = Coordinate::stringFromColumnIndex($i + 2);
        $sheet1->setCellValue($col . $row, $dataTanggal[$i] ?? '-');
    }

    $row++;
}

// Borders for all data
$lastRow = $row - 1;
$sheet1->getStyle("A1:{$lastCol}{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
$sheet1->getStyle("A3:{$lastCol}{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet1->getStyle("B3:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

for ($i = 1; $i <= $jumlahHari + 2; $i++) {
    $col = Coordinate::stringFromColumnIndex($i);
    $sheet1->getColumnDimension($col)->setAutoSize(true);
}

/*
========================================
SHEET PER PESERTA (FULL TANGGAL) — semua peserta
========================================
*/
foreach ($semuaPeserta as $peserta) {

    $nama  = optional($peserta->user)->name ?? 'Tidak diketahui';
    $items = $absensiByPeserta->get($peserta->id, collect());

    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle(substr($nama, 0, 25));

    // Header
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Tanggal');
    $sheet->setCellValue('C1', 'Status');
    $sheet->setCellValue('D1', 'Jam Masuk');
    $sheet->setCellValue('E1', 'Jam Keluar');

    $sheet->getStyle('A1:E1')->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '4F46E5'],
        ],
    ]);

    $sheetRow = 2;
    $no       = 1;

    // Mapping data berdasarkan tanggal untuk peserta ini
    $map = [];
    foreach ($items as $item) {
        $tgl = Carbon::parse($item->tanggal)->format('Y-m-d');
        $map[$tgl] = $item;
    }

    // Loop semua tanggal dalam bulan
    for ($i = 1; $i <= $jumlahHari; $i++) {

        $tanggal   = Carbon::create($tahunValue, $bulanValue, $i)->format('Y-m-d');
        $tglFormat = Carbon::create($tahunValue, $bulanValue, $i)->format('d-m-Y');

        $data = $map[$tanggal] ?? null;

        $sheet->setCellValue("A$sheetRow", $no++);
        $sheet->setCellValue("B$sheetRow", $tglFormat);
        $sheet->setCellValue("C$sheetRow", $data ? ucfirst($data->status) : '-');
        $sheet->setCellValue("D$sheetRow", $data->jam_masuk  ?? '-');
        $sheet->setCellValue("E$sheetRow", $data->jam_keluar ?? '-');

        $sheetRow++;
    }

    // Auto width & Styling
    $lastSheetRow = $sheetRow - 1;
    $sheet->getStyle("A1:E{$lastSheetRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyle("A2:E{$lastSheetRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    foreach (range('A','E') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
}

    /*
    ========================================
    DOWNLOAD
    ========================================
    */
    $writer = new Xlsx($spreadsheet);
    $fileName = 'Laporan_Absensi.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}
}
