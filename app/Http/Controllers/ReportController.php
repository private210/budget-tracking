<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Salary;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $filename = 'laporan-pengeluaran-'.$month;

        if ($format === 'xlsx') {
            return $this->exportExcel($expenses, $total, $startDate, $endDate, $filename);
        }

        return $this->exportPdf($expenses, $total, $startDate, $endDate, $month, $filename);
    }

    private function exportPdf($expenses, $total, $startDate, $endDate, $month, $filename)
    {
        $pdf = Pdf::loadView('reports.pdf', compact(
            'expenses',
            'total',
            'startDate',
            'endDate',
            'month'
        ));

        return $pdf->download($filename.'.pdf');
    }

    private function exportExcel($expenses, $total, $startDate, $endDate, $filename)
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
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(15);

        $writer = new Xlsx($spreadsheet);

        $response = response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename.'.xlsx', ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);

        return $response;
    }
}
