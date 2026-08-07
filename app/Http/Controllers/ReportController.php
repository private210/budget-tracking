<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Salary;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = (string) $request->query('month', now()->format('Y-m'));
        $startDate = now()->startOfMonth()->setDate(
            explode('-', $month)[0],
            explode('-', $month)[1],
            1
        );
        $endDate = $startDate->copy()->endOfMonth();

        $salary = Salary::where('received_at', '>=', $startDate)
            ->where('received_at', '<=', $endDate)
            ->first();

        $categoryBreakdown = Expense::select('category_id', DB::raw('SUM(amount) as total'))
            ->whereBetween('spent_at', [$startDate, $endDate])
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $totalExpenses = $categoryBreakdown->sum('total');

        $dailyExpenses = Expense::select(DB::raw('DATE(spent_at) as date'), DB::raw('SUM(amount) as total'))
            ->whereBetween('spent_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topExpenses = Expense::with('category')
            ->whereBetween('spent_at', [$startDate, $endDate])
            ->orderByDesc('amount')
            ->limit(10)
            ->get();

        return view('reports.index', compact(
            'salary',
            'categoryBreakdown',
            'totalExpenses',
            'dailyExpenses',
            'topExpenses',
            'month'
        ));
    }

    public function export(Request $request, string $format)
    {
        app()->setLocale('id');
        $month = (string) $request->query('month', now()->format('Y-m'));
        $startDate = now()->startOfMonth()->setDate(
            explode('-', $month)[0],
            explode('-', $month)[1],
            1
        );
        $endDate = $startDate->copy()->endOfMonth();

        $expenses = Expense::with('category')
            ->whereBetween('spent_at', [$startDate, $endDate])
            ->orderBy('spent_at')
            ->get();

        $total = $expenses->sum('amount');

        $categoryBreakdown = Expense::select('category_id', DB::raw('SUM(amount) as total'))
            ->whereBetween('spent_at', [$startDate, $endDate])
            ->groupBy('category_id')
            ->with('category')
            ->orderByDesc('total')
            ->get();

        $dailyExpenses = Expense::select(DB::raw('DATE(spent_at) as date'), DB::raw('SUM(amount) as total'))
            ->whereBetween('spent_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dayTotals = $dailyExpenses->mapWithKeys(fn ($d) => [(int) substr((string) $d->date, 8, 2) => (int) $d->total])->all();
        $days = [];
        for ($d = 1; $d <= $startDate->daysInMonth; $d++) {
            $days[$d] = $dayTotals[$d] ?? 0;
        }

        $charts = [
            'salary' => Salary::where('received_at', '>=', $startDate)
                ->where('received_at', '<=', $endDate)
                ->value('amount') ?? 0,
            'categoryBreakdown' => $categoryBreakdown,
            'days' => $days,
        ];

        $filename = 'laporan-pengeluaran-'.$month;

        if ($format === 'xlsx') {
            return $this->exportExcel($expenses, $total, $startDate, $endDate, $filename, $charts);
        }

        return $this->exportPdf($expenses, $total, $startDate, $endDate, $month, $filename, $charts);
    }

    private function exportPdf($expenses, $total, $startDate, $endDate, $month, $filename, $charts)
    {
        $pdf = Pdf::loadView('reports.pdf', compact(
            'expenses',
            'total',
            'startDate',
            'endDate',
            'month',
            'charts'
        ));

        return $pdf->download($filename.'.pdf');
    }

    private function exportExcel($expenses, $total, $startDate, $endDate, $filename, $charts)
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan');

        $rows = [
            ['Laporan Pengeluaran'],
            [$startDate->translatedFormat('d F Y').' — '.$endDate->translatedFormat('d F Y')],
            [],
            ['Tanggal', 'Kategori', 'Deskripsi', 'Jumlah'],
        ];

        foreach ($expenses as $e) {
            $rows[] = [
                $e->spent_at->format('d/m/Y'),
                ($e->category->icon ?? '').' '.$e->category->name,
                $e->description,
                $e->amount,
            ];
        }

        $rows[] = [];
        $rows[] = ['TOTAL', '', '', $total];

        $sheet->fromArray($rows, null, 'A1');

        $sheet->getStyle('A4:D4')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('D4:D'.(count($expenses) + 6))->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(15);

        $ringkasan = $spreadsheet->createSheet();
        $ringkasan->setTitle('Ringkasan');
        $ringkasan->setCellValue('A1', 'Ringkasan Bulan Ini');
        $ringkasan->setCellValue('A2', $startDate->translatedFormat('d F Y').' — '.$endDate->translatedFormat('d F Y'));

        $eco = $charts['categoryBreakdown'];
        $totalExpenses = (int) $eco->sum('total');
        $salaryAmount = (int) $charts['salary'];

        $ringkasan->setCellValue('A4', 'Gaji');
        $ringkasan->setCellValue('B4', $salaryAmount);
        $ringkasan->setCellValue('A5', 'Pengeluaran');
        $ringkasan->setCellValue('B5', $totalExpenses);
        $ringkasan->setCellValue('A6', 'Sisa');
        $ringkasan->setCellValue('B6', max($salaryAmount - $totalExpenses, 0));

        $ringkasan->setCellValue('A8', 'Kategori');
        $ringkasan->setCellValue('B8', 'Pengeluaran');

        $catRows = $eco->map(fn ($c) => [$c->category->icon.' '.$c->category->name, (int) $c->total])->all();
        $ringkasan->fromArray($catRows, null, 'A9');

        $ringkasan->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $ringkasan->getColumnDimension('A')->setWidth(28);
        $ringkasan->getColumnDimension('B')->setWidth(16);
        $ringkasan->getStyle('A4:B6')->getFont()->setBold(true);
        $ringkasan->getStyle('A8:B8')->getFont()->setBold(true);
        $ringkasan->getStyle('B4:B'.(8 + count($catRows)))->getNumberFormat()->setFormatCode('#,##0');

        $catCount = count($catRows);
        if ($catCount > 0) {
            $lastRow = 8 + $catCount;
            $plotLabel = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Ringkasan!$A$8', null, 1)];
            $plotCategory = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Ringkasan!$A$9:$A$'.$lastRow, null, $catCount)];
            $plotValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Ringkasan!$B$9:$B$'.$lastRow, null, $catCount)];

            $series = new DataSeries(
                DataSeries::TYPE_BARCHART,
                DataSeries::GROUPING_CLUSTERED,
                range(0, $catCount - 1),
                $plotLabel,
                $plotCategory,
                $plotValues,
                DataSeries::DIRECTION_HORIZONTAL
            );

            $chart = new Chart(
                'Kategori',
                new Title('Pengeluaran per Kategori (Rp)'),
                null,
                new PlotArea(null, [$series])
            );
            $chart->setTopLeftPosition('D2')->setBottomRightPosition('N24');
            $ringkasan->addChart($chart);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);

        $response = response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename.'.xlsx', ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

        return $response;
    }
}
