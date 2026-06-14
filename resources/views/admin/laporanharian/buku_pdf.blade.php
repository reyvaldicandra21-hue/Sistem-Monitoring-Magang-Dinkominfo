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
        
        /* Laporan Table Style */
        .table-laporan {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            table-layout: fixed;
        }
        .table-laporan thead {
            display: table-header-group;
        }
        .table-laporan th {
            background-color: #0056b3;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            border: 1px solid #dee2e6;
            padding: 8px 6px;
            font-size: 11px;
        }
        .table-laporan td {
            border: 1px solid #dee2e6;
            padding: 8px;
            font-size: 11px;
            vertical-align: top;
            word-wrap: break-word;
        }
        .table-laporan tr {
            page-break-inside: avoid;
        }
        .text-center {
            text-align: center;
        }
        .text-muted {
            color: #6c757d;
        }
        .img-wrapper {
            margin-bottom: 5px;
        }
        .img-wrapper img {
            max-width: 100%;
            max-height: 80px;
            border: 1px solid #ccc;
            border-radius: 3px;
            display: block;
            margin: 0 auto;
        }
        .no-doc {
            color: #bbb;
            font-style: italic;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
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
        <table class="table-laporan">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Hari / Tanggal</th>
                    <th style="width: 45%;">Kegiatan Harian</th>
                    <th style="width: 18%;">Dokumentasi</th>
                    <th style="width: 12%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporans as $index => $laporan)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>Hari Ke-{{ $index + 1 }}</strong><br>
                            <span class="text-muted" style="font-size: 10px;">{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('l') }}</span><br>
                            <span style="font-size: 10px;">{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d M Y') }}</span>
                        </td>
                        <td>
                            {!! nl2br(e($laporan->kegiatan)) !!}
                        </td>
                        <td class="text-center">
                            @if($laporan->dokumentasi && $laporan->dokumentasi->count() > 0)
                                @foreach($laporan->dokumentasi->take(2) as $dok)
                                    @php
                                        $imageData = '';
                                        if(!empty($dok->file)) {
                                            $imagePath = storage_path('app/public/' . $dok->file);
                                            if(is_file($imagePath)) {
                                                $type = pathinfo($imagePath, PATHINFO_EXTENSION);
                                                $data = file_get_contents($imagePath);
                                                $imageData = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                            }
                                        }
                                    @endphp
                                    
                                    @if($imageData)
                                        <div class="img-wrapper">
                                            <img src="{{ $imageData }}" alt="Dokumentasi">
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <span class="no-doc">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="status-badge status-{{ strtolower($laporan->status) }}">
                                {{ strtoupper($laporan->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="text-align: center; margin-top: 50px;">
            <h3>Belum ada laporan harian yang diisi oleh peserta.</h3>
        </div>
    @endif

</body>
</html>
