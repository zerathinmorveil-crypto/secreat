<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Customer - {{ date('d F Y') }}</title>
    <style>
        @page {
            margin: 20mm 15mm;
            @bottom-center {
                content: "Halaman " counter(page) " dari " counter(pages);
                font-size: 10px;
                color: #666;
            }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        /* HEADER */
        .header {
            background: #667eea;
            color: white;
            padding: 30px;
            text-align: center;
        }

/* HAPUS .header::before sepenuhnya */
        
        .logo {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 12px;
        }
        
        .report-info {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            max-width: 400px;
            margin: 0 auto;
        }
        
        /* STATS */
        .stats-row {
            width: 100%;
            display: table;
            table-layout: fixed;
            margin: 30px 0;
            padding: 0 30px;
            border-spacing: 15px 0;
        }
        
        .stat-card {
            display: table-cell;
            background: white;
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            border-top: 4px solid;
            width: 33%;
        }
        
        .stat-primary { border-top-color: #667eea; }
        .stat-success { border-top-color: #1cc88a; }
        .stat-warning { border-top-color: #f6c23e; }
        
        .stat-number {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 13px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        /* TABLE */
        .table-container {
            margin: 0 30px 40px;
        }
        
        .table-title {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid #667eea;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            font-size: 11px;
        }
        
        thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 18px 12px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 10px;
        }
        
        tbody td {
            padding: 16px 12px;
            border-bottom: 1px solid #f0f3f5;
            vertical-align: middle;
        }
        
        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        tbody tr:hover {
            background: #e3f2fd;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-aktif {
            background: #d4edda;
            color: #155724;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .status-nonaktif {
            background: #fff3cd;
            color: #856404;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .no-data i {
            font-size: 64px;
            opacity: 0.3;
            display: block;
            margin-bottom: 20px;
        }
        
        /* FOOTER */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 25px 30px;
            text-align: center;
            margin-top: 40px;
        }
        
        .footer p {
            margin: 0;
            font-size: 13px;
            opacity: 0.8;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .container { box-shadow: none; margin: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- HEADER --}}
        <div class="header">
            <h1>Laporan Data Customer</h1>
            <p>Sistem Manajemen Customer</p>
            <div class="report-info">
                <div><strong>Tanggal:</strong> {{ date('d F Y H:i') }}</div>
                <div><strong>Total:</strong> {{ $customers->total() }} Customer</div>
            </div>
        </div>

        {{-- STATS CARDS --}}
        <div class="stats-row">
            <div class="stat-card stat-primary">
                <div class="stat-number">{{ $customers->total() }}</div>
                <div class="stat-label">Total Customer</div>
            </div>
            <div class="stat-card stat-success">
                <div class="stat-number">{{ $customers->where('status', 'aktif')->count() }}</div>
                <div class="stat-label">Status Aktif</div>
            </div>
            <div class="stat-card stat-warning">
                <div class="stat-number">{{ $customers->where('status', 'nonaktif')->count() }}</div>
                <div class="stat-label">Status Nonaktif</div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-container">
            <h2 class="table-title">Daftar Customer</h2>
            
            @if($customers->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th width="8%">No</th>
                        <th width="8%">Foto</th>
                        <th>Nama Customer</th>
                        <th width="14%">No. HP</th>
                        <th width="22%">Email</th>
                        <th width="10%">Status</th>
                        <th width="18%">Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $index => $customer)
                    <tr>
                        <td>
                            <strong>{{ $loop->iteration }}</strong>
                        </td>
                        <td>
                            <div class="avatar">
                                {{ Str::substr($customer->nama, 0, 1) }}
                            </div>
                        </td>
                        <td>
                            <strong>{{ $customer->nama }}</strong>
                        </td>
                        <td>{{ $customer->no_hp }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>
                            @if($customer->status == 'aktif')
                                <span class="status-aktif">
                                    <i class="fas fa-check-circle"></i>
                                    Aktif
                                </span>
                            @else
                                <span class="status-nonaktif">
                                    <i class="fas fa-pause-circle"></i>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td>{{ Str::limit($customer->alamat, 50) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="no-data">
                <i class="fas fa-users-slash"></i>
                <h3>Belum Ada Data Customer</h3>
                <p>Tidak ada customer yang terdaftar dalam sistem.</p>
            </div>
            @endif
        </div>

        {{-- FOOTER --}}
        <div class="footer">
            <p>
                <strong>Dicetak pada:</strong> {{ date('d F Y H:i:s') }} | 
                <strong>Halaman:</strong> {{ date('d F Y') }}
            </p>
            <p style="margin-top: 8px; font-size: 11px; opacity: 0.7;">
                Sistem Manajemen Customer &copy; {{ date('Y') }}
            </p>
        </div>
    </div>
</body>
</html>