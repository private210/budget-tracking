<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pengeluaran</title>
<style>
        @page { margin: 20px 20px; }
        html { margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1f2937; font-size: 11px; margin: 0; padding: 0; }
        h1, h2, h3, p { margin: 0; }

        .brand { display: inline-flex; align-items: center; gap: 7px; }
        .brand-mark { display: inline-block; width: 22px; height: 22px; background: #1BA37A; border-radius: 6px; color: #fff; font-weight: bold; font-size: 12px; text-align: center; line-height: 22px; }
        .brand-name { font-weight: bold; font-size: 13px; color: #111827; }
        .brand-name .green { color: #1BA37A; }
        .header { border-bottom: 3px solid #1BA37A; padding-bottom: 10px; margin-bottom: 18px; }
        .header h1 { font-size: 19px; color: #111827; margin: 4px 0 3px; }
        .header .sub { color: #6b7280; font-size: 11px; }

        .section { margin-bottom: 16px; }
        .section > h2 { font-size: 12.5px; color: #111827; border-left: 4px solid #1BA37A; padding-left: 8px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }

        table.summary { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin: 0 -6px 8px; }
        table.summary td { background: #f0fdf7; border: 1px solid #bde0d2; border-radius: 8px; padding: 10px 12px; width: 33%; }
        table.summary .lbl { font-size: 9.5px; color: #047857; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        table.summary .val { font-size: 15px; font-weight: bold; color: #1f2937; }
        table.summary .val.neg { color: #dc2626; }
        table.summary .val.muted { color: #9ca3af; }

        .chart-row { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin: 0 -6px 12px; page-break-inside: avoid; }
        .chart-row td { width: 50%; vertical-align: top; padding: 0; border: 1px solid #e5e7eb; border-radius: 10px; background: #fff; }
        .chart-head { padding: 10px 12px 0; }
        .chart-head .dot { display: inline-block; width: 9px; height: 9px; border-radius: 50%; background: #1BA37A; vertical-align: middle; margin-right: 6px; }
        .chart-head h3 { display: inline-block; font-size: 12px; font-weight: bold; color: #111827; vertical-align: middle; }
        .chart-body { padding: 10px 12px 12px; }

        .cat-row { width: 100%; margin-bottom: 7px; }
        .cat-name { display: inline-block; width: 30%; vertical-align: middle; font-size: 10px; white-space: nowrap; overflow: hidden; }
        .cat-name .swatch { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 4px; }
        .cat-track { display: inline-block; width: 42%; vertical-align: middle; height: 10px; background: #f3f4f6; border-radius: 5px; overflow: hidden; }
        .cat-bar { height: 10px; border-radius: 5px; }
        .cat-val { display: inline-block; width: 26%; text-align: right; vertical-align: middle; font-size: 10px; font-weight: bold; white-space: nowrap; }
        .cat-pct { color: #9ca3af; font-weight: normal; }

        table.daily { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.daily td { width: 3.3%; text-align: center; vertical-align: bottom; padding: 0; }
        .bar-zone { height: 70px; background: #f9fafb; border-bottom: 1px solid #d1d5db; }
        .day-bar { margin: 0 auto; width: 55%; background: #1BA37A; border-radius: 2px 2px 0 0; }
        .day-lbl { font-size: 6.5px; color: #6b7280; padding-top: 3px; }

        table.data { width: 100%; border-collapse: collapse; page-break-inside: auto; }
        thead { display: table-header-group; }
        thead th { background: #1BA37A; color: #fff; padding: 7px 8px; text-align: left; font-size: 10px; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        .amt { text-align: right; white-space: nowrap; }
        .total-row td { font-weight: bold; border-top: 2px solid #1BA37A; background: #f0fdf4 !important; }
        .empty { text-align: center; padding: 30px; color: #9ca3af; }

        .page-footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #374151; border-top: 2px solid #1BA37A; background: #f9fafb; padding: 9px 12px 8px; }
        .page-footer .green { color: #1BA37A; font-weight: bold; }
        .page-footer .pg { font-weight: bold; color: #1BA37A; }
        .page-number::after { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">
            <span class="brand-mark">T</span>
            <span class="brand-name">Titik <span class="green">Simpan</span></span>
        </div>
        <h1>Laporan Pengeluaran</h1>
        <div class="sub">{{ $startDate->translatedFormat('d F Y') }} — {{ $startDate->copy()->endOfMonth()->translatedFormat('d F Y') }} • Dihasilkan {{ now()->translatedFormat('d F Y H:i') }}</div>
    </div>

    @php
        $catTotal = $charts['categoryBreakdown']->sum('total');
        $days = $charts['days'];
        $maxDay = max($days) ?: 1;
        $sisa = $charts['salary'] - $total;
    @endphp

    <div class="section">
        <table class="summary">
            <tr>
                <td>
                    <div class="lbl">Gaji Bulan Ini</div>
                    <div class="val {{ $charts['salary'] ? '' : 'muted' }}">Rp {{ number_format($charts['salary'], 0, ',', '.') }}</div>
                </td>
                <td>
                    <div class="lbl">Total Pengeluaran</div>
                    <div class="val">Rp {{ number_format($total, 0, ',', '.') }}</div>
                </td>
                <td>
                    <div class="lbl">Sisa Saldo</div>
                    <div class="val {{ $sisa < 0 ? 'neg' : '' }}">Rp {{ number_format(max($sisa, 0), 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    @if($expenses->count() === 0)
        <p class="empty">Tidak ada pengeluaran pada periode ini.</p>
    @else
        <div class="section">
            <h2>Grafik Pengeluaran</h2>
            <table class="chart-row">
                <tr>
                    <td>
                        <div class="chart-head"><span class="dot"></span><h3>Per Kategori</h3></div>
                        <div class="chart-body">
                            @foreach($charts['categoryBreakdown'] as $c)
                                @php
                                    $pct = $catTotal > 0 ? round($c->total / $catTotal * 100, 1) : 0;
                                    $barW = $catTotal > 0 ? max(round($c->total / $catTotal * 100), 3) : 0;
                                    $color = $c->category->color ?: '#1BA37A';
                                @endphp
                                <div class="cat-row">
                                    <span class="cat-name"><span class="swatch" style="background: {{ $color }};"></span>{{ $c->category->icon }} {{ $c->category->name }}</span>
                                    <span class="cat-track"><span class="cat-bar" style="display:block; width: {{ $barW }}%; background: {{ $color }};"></span></span>
                                    <span class="cat-val">Rp {{ number_format($c->total, 0, ',', '.') }} <span class="cat-pct">({{ $pct }}%)</span></span>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <div class="chart-head"><span class="dot"></span><h3>Pengeluaran Harian</h3></div>
                        <div class="chart-body">
                            <table class="daily">
                                <tr>
                                    @foreach($days as $day => $amount)
                                        <td>
                                            <div class="bar-zone">
                                                <div class="day-bar" style="height: {{ $amount > 0 ? max(round($amount / $maxDay * 68), 3) : 2 }}px;"></div>
                                            </div>
                                            <div class="day-lbl">{{ $day }}</div>
                                        </td>
                                    @endforeach
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2>Rincian Pengeluaran</h2>
            <table class="data">
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
        </div>
    @endif

    <div class="page-footer">
        <div>© {{ now()->year }} <span class="green">Titik Simpan</span> - Ard Production &nbsp;•&nbsp; Halaman <span class="pg page-number"></span></div>
    </div>
</body>
</html>
