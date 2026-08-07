<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pengeluaran</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1f2937; font-size: 11px; }
        .header { border-bottom: 3px solid #1BA37A; padding-bottom: 8px; margin-bottom: 16px; }
        .header h1 { font-size: 18px; color: #1BA37A; margin-bottom: 4px; }
        .header .sub { color: #6b7280; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #1BA37A; color: #fff; padding: 7px 8px; text-align: left; font-size: 10px; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        .amt { text-align: right; white-space: nowrap; }
        .total-row td { font-weight: bold; border-top: 2px solid #1BA37A; }
        .footer { margin-top: 20px; font-size: 10px; color: #9ca3af; text-align: center; }
        .empty { text-align: center; padding: 30px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pengeluaran</h1>
        <div class="sub">{{ $startDate->translatedFormat('d F Y') }} — {{ $endDate->translatedFormat('d F Y') }}</div>
    </div>

    @if($expenses->count() === 0)
        <p class="empty">Tidak ada pengeluaran pada periode ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:14%">Tanggal</th>
                    <th style="width:22%">Kategori</th>
                    <th>Deskripsi</th>
                    <th style="width:18%" class="amt">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $e)
                    <tr>
                        <td>{{ $e->spent_at->translatedFormat('d M Y') }}</td>
                        <td>{{ ($e->category->icon ?? '') . ' ' . $e->category->name }}</td>
                        <td>{{ $e->description }}</td>
                        <td class="amt">Rp {{ number_format($e->amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td></td>
                    <td></td>
                    <td>TOTAL</td>
                    <td class="amt">Rp {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="footer">
        Dihasilkan {{ now()->translatedFormat('d F Y H:i') }} • Titik Simpan — Budget Tracker
    </div>
</body>
</html>