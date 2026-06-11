<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Laporan PKL - {{ $peserta->user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .page-break {
            page-break-after: always;
        }
        .cover {
            text-align: center;
            margin-top: 100px;
        }
        .cover h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .cover h2 {
            font-size: 18px;
            color: #555;
            margin-bottom: 50px;
        }
        .cover .info {
            font-size: 14px;
            margin-top: 50px;
            text-align: left;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
        }
        .cover .info table {
            width: 100%;
        }
        .cover .info td {
            padding: 8px 0;
        }
        .cover .info td:first-child {
            width: 40%;
            font-weight: bold;
        }
        
        /* Laporan Style */
        .laporan-header {
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .laporan-header h3 {
            margin: 0;
            color: #0056b3;
        }
        .laporan-header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 11px;
        }
        .content-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .content-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #444;
        }
        .dokumentasi {
            margin-top: 20px;
        }
        .dokumentasi img {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            display: block;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-disetujui { background: #d4edda; color: #155724; }
        .status-menunggu { background: #fff3cd; color: #856404; }
        .status-revisi { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

    <!-- HALAMAN SAMPUL -->
    <div class="cover page-break">
        <h1>BUKU LAPORAN HARIAN</h1>
        <h2>PRAKTIK KERJA LAPANGAN (PKL)</h2>
        
        <div style="margin-top: 80px; margin-bottom: 80px;">
            <!-- Jika ada logo instansi, bisa ditaruh di sini -->
        </div>

        <div class="info">
            <table>
                <tr>
                    <td>Nama Peserta</td>
                    <td>: {{ $peserta->user->name }}</td>
                </tr>
                <tr>
                    <td>Asal Institusi</td>
                    <td>: {{ $peserta->asal_institusi }}</td>
                </tr>
                <tr>
                    <td>Jurusan</td>
                    <td>: {{ $peserta->jurusan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Divisi Penempatan</td>
                    <td>: {{ $peserta->divisi->nama_divisi ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Pembimbing Industri</td>
                    <td>: {{ $peserta->pembimbing->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Pembimbing Sekolah</td>
                    <td>: {{ $peserta->pembimbingSekolah->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Periode PKL</td>
                    <td>: {{ $peserta->tanggal_mulai ? \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('d M Y') : '-' }} s.d {{ $peserta->tanggal_selesai ? \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d M Y') : '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ISI LAPORAN -->
    @if($laporans->count() > 0)
        @foreach($laporans as $index => $laporan)
            <div class="{{ !$loop->last ? 'page-break' : '' }}">
                <div class="laporan-header">
                    <h3>Laporan Hari Ke-{{ $index + 1 }}</h3>
                    <p>{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('l, d F Y') }}</p>
                    <div style="margin-top: 5px;">
                        <span class="status-badge status-{{ strtolower($laporan->status) }}">
                            Status: {{ strtoupper($laporan->status) }}
                        </span>
                    </div>
                </div>

                <div class="content-box">
                    <div class="content-title">Kegiatan Harian</div>
                    <p>{!! nl2br(e($laporan->kegiatan)) !!}</p>
                </div>

                @if($laporan->dokumentasi && $laporan->dokumentasi->count() > 0)
                    <div class="dokumentasi">
                        <div class="content-title">Dokumentasi</div>
                        @foreach($laporan->dokumentasi as $dok)
                            @php
                                $imageData = '';
                                if(!empty($dok->foto_path)) {
                                    $imagePath = storage_path('app/public/' . $dok->foto_path);
                                    if(is_file($imagePath)) {
                                        $type = pathinfo($imagePath, PATHINFO_EXTENSION);
                                        $data = file_get_contents($imagePath);
                                        $imageData = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                    }
                                }
                            @endphp
                            
                            @if($imageData)
                                <img src="{{ $imageData }}" alt="Dokumentasi">
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div style="text-align: center; margin-top: 50px;">
            <h3>Belum ada laporan harian yang diisi oleh peserta.</h3>
        </div>
    @endif

</body>
</html>
