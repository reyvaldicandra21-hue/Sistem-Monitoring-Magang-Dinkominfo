<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Harian PKL</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 8px 0;
            vertical-align: top;
        }
        .info-table td.label {
            width: 150px;
            color: #666;
            font-weight: bold;
        }
        .content-section {
            margin-bottom: 25px;
        }
        .content-section h3 {
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
            color: #2c3e50;
            font-size: 16px;
        }
        .content-box {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            min-height: 50px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-disetujui { background-color: #d4edda; color: #155724; }
        .status-menunggu { background-color: #fff3cd; color: #856404; }
        .status-revisi { background-color: #f8d7da; color: #721c24; }
        
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 12px;
            color: #777;
        }
        .dokumentasi-grid {
            margin-top: 15px;
        }
        .dokumentasi-item {
            display: inline-block;
            width: 30%;
            margin-right: 2%;
            margin-bottom: 10px;
        }
        .dokumentasi-item img {
            width: 100%;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Harian PKL</h1>
        <p>Sistem Manajemen PKL Terintegrasi</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Peserta</td>
            <td>: {{ $laporan->pesertaPkl->user->name }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal PKL</td>
            <td>: {{ $laporan->pesertaPkl->tanggal_mulai }} - {{ $laporan->pesertaPkl->tanggal_selesai }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Laporan</td>
            <td>: {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>: 
                <span class="status-badge status-{{ $laporan->status }}">
                    {{ ucfirst($laporan->status) }}
                </span>
            </td>
        </tr>
    </table>

    <div class="content-section">
        <h3>Kegiatan</h3>
        <div class="content-box">
            {{ $laporan->kegiatan }}
        </div>
    </div>

    <div class="content-section">
        <h3>Hasil</h3>
        <div class="content-box">
            {{ $laporan->hasil ?? '-' }}
        </div>
    </div>

    <div class="content-section">
        <h3>Kendala</h3>
        <div class="content-box">
            {{ $laporan->kendala ?? '-' }}
        </div>
    </div>

    @if($laporan->dokumentasi->count())
    <div class="content-section">
        <h3>Dokumentasi</h3>
        <div class="dokumentasi-grid">
            @foreach($laporan->dokumentasi as $foto)
            <div class="dokumentasi-item">
                <img src="{{ public_path('storage/' . $foto->file) }}" alt="Dokumentasi">
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
