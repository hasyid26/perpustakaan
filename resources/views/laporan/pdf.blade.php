<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman Buku</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 12px;
            color: #666;
        }
        
        .info {
            margin-bottom: 20px;
        }
        
        .info table {
            width: 100%;
        }
        
        .info td {
            padding: 3px 0;
        }
        
        .info td:first-child {
            width: 150px;
            font-weight: bold;
        }
        
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table.data th {
            background-color: #333;
            color: white;
            padding: 10px 5px;
            text-align: left;
            font-size: 11px;
        }
        
        table.data td {
            padding: 8px 5px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        
        table.data tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }
        
        .status-dipinjam {
            background-color: #DBEAFE;
            color: #1E40AF;
        }
        
        .status-dikembalikan {
            background-color: #D1FAE5;
            color: #065F46;
        }
        
        .status-ditolak {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PEMINJAMAN BUKU</h1>
        <p>Sistem Informasi Perpustakaan</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td>Tanggal Cetak</td>
                <td>: {{ $tanggal_cetak }}</td>
            </tr>
            @if($filters['tanggal_awal'] && $filters['tanggal_akhir'])
            <tr>
                <td>Periode</td>
                <td>: {{ \Carbon\Carbon::parse($filters['tanggal_awal'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($filters['tanggal_akhir'])->format('d/m/Y') }}</td>
            </tr>
            @endif
            @if($filters['status'])
            <tr>
                <td>Filter Status</td>
                <td>: {{ ucfirst($filters['status']) }}</td>
            </tr>
            @endif
            <tr>
                <td>Total Data</td>
                <td>: {{ $laporan->count() }} transaksi</td>
            </tr>
        </table>
    </div>

    @if($laporan->isEmpty())
        <div class="no-data">
            <p>Tidak ada data laporan yang tersedia</p>
        </div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="10%">Kode</th>
                    <th width="15%">Peminjam</th>
                    <th width="22%">Buku</th>
                    <th width="10%">Tgl Pinjam</th>
                    <th width="10%">Tgl Kembali</th>
                    <th width="15%">Petugas</th>
                    <th width="8%">Denda</th>
                    <th width="7%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laporan as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->kode_peminjaman }}</td>
                    <td>{{ $data->user->name }}</td>
                    <td>{{ $data->buku->judul }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->tanggal_peminjaman)->format('d/m/Y') }}</td>
                    <td>
                        @if($data->tanggal_pengembalian_aktual)
                            {{ \Carbon\Carbon::parse($data->tanggal_pengembalian_aktual)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($data->petugasPinjam)
                            {{ $data->petugasPinjam->name }}
                        @else
                            -
                        @endif
                    </td>
                    <td>Rp {{ number_format($data->denda, 0, ',', '.') }}</td>
                    <td>
                        <span class="status status-{{ $data->status }}">
                            {{ ucfirst($data->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p><strong>Total Denda:</strong> Rp {{ number_format($laporan->sum('denda'), 0, ',', '.') }}</p>
        </div>
    @endif
</body>
</html>