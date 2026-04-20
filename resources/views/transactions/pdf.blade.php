<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Transaksi - {{ date('d F Y') }}</title>

    <style>
        @page {
            margin: 20mm 15mm;
            @bottom-center {
                content: "Halaman " counter(page) " dari " counter(pages);
                font-size: 10px;
                color: #666;
            }
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            font-size: 12px;
            background: #f8f9fa;
            color: #333;
        }

        .container {
            max-width: 210mm;
            margin: auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        /* 🔥 HEADER - DISAMAKAN */
        .header {
            background: #667eea;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
        }

        .header p {
            opacity: 0.9;
            margin: 10px 0;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            max-width: 450px;
            margin: auto;
            font-size: 13px;
        }

        /* 🔥 STATS - IDENTIK */
        .stats-row {
            display: table;
            width: 100%;
            padding: 0 30px;
            margin: 30px 0;
            border-spacing: 15px;
        }

        .stat-card {
            display: table-cell;
            padding: 20px;
            background: white;
            border-radius: 16px;
            text-align: center;
            border-top: 4px solid;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .stat-primary { border-color: #667eea; }
        .stat-success { border-color: #1cc88a; }
        .stat-warning { border-color: #f6c23e; }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }

        /* 🔥 TABLE - IDENTIK */
        .table-container {
            margin: 0 30px 40px;
        }

        .table-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        thead {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        th, td {
            padding: 14px;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        tbody tr:hover {
            background: #e3f2fd;
        }

        /* 🔥 AVATAR - SAMA */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* 🔥 STATUS BADGE - STYLE MEMBER */
        .status-lunas {
            background: #d4edda;
            color: #155724;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-belum {
            background: #fff3cd;
            color: #856404;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        /* 🔥 HARGA */
        .harga {
            font-weight: bold;
            color: #667eea;
        }

        /* 🔥 FOOTER */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }

    </style>
</head>

<body>
<div class="container">

    <div class="header">
        <h1>Laporan Transaksi</h1>
        <p>Sistem Manajemen Laundry Premium</p>

        <div class="report-info">
            <div>{{ date('d F Y H:i') }}</div>
            <div>{{ $transactions->count() }} Transaksi</div>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-card stat-primary">
            <div class="stat-number">{{ $transactions->count() }}</div>
            <div class="stat-label">Total</div>
        </div>

        <div class="stat-card stat-success">
            <div class="stat-number">{{ $transactions->where('status_bayar','lunas')->count() }}</div>
            <div class="stat-label">Lunas</div>
        </div>

        <div class="stat-card stat-warning">
            <div class="stat-number">
                Rp {{ number_format($transactions->sum('total'),0,',','.') }}
            </div>
            <div class="stat-label">Pendapatan</div>
        </div>
    </div>

    <div class="table-container">
        <h2 class="table-title">Daftar Transaksi</h2>

        <table>
            <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Customer</th>
                <th>Tanggal</th>
                <th>Service</th>
                <th>Berat</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
            </thead>

            <tbody>
            @foreach($transactions as $t)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $t->kode_transaksi }}</strong></td>

                <td>
                    <div style="display:flex;align-items:center;">
                        <div class="avatar">
                            {{ Str::substr($t->customer->nama ?? 'N',0,1) }}
                        </div>
                        <div style="margin-left:10px;">
                            {{ $t->customer->nama ?? 'Walk-in' }}
                        </div>
                    </div>
                </td>

                <td>{{ $t->tanggal_masuk->format('d/m/Y') }}</td>
                <td>{{ $t->service->nama_service ?? '-' }}</td>
                <td>{{ $t->berat }} kg</td>

                <td class="harga">
                    Rp {{ number_format($t->total,0,',','.') }}
                </td>

                <td>
                    @if($t->status_bayar == 'lunas')
                        <span class="status-lunas"> Lunas</span>
                    @else
                        <span class="status-belum">{{ ucfirst($t->status_bayar) }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dicetak {{ date('d F Y H:i:s') }}
    </div>

</div>
</body>
</html>