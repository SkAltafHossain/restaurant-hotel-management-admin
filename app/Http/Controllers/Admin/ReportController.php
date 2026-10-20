<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RoomBooking;
use App\Models\Expense;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function dailyReport(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        $orders = Order::whereDate('created_at', $date)->get();
        $roomBookings = RoomBooking::whereDate('created_at', $date)->get();
        $expenses = Expense::whereDate('expense_date', $date)->get();

        $totalFoodRevenue = $orders->where('payment_status', 'paid')->sum('final_amount');
        $totalRoomRevenue = $roomBookings->where('payment_status', 'paid')->sum('total_price');
        $totalExpenses = $expenses->sum('amount');
        $netRevenue = ($totalFoodRevenue + $totalRoomRevenue) - $totalExpenses;

        return view('admin.reports.daily', compact(
            'date',
            'orders',
            'roomBookings',
            'expenses',
            'totalFoodRevenue',
            'totalRoomRevenue',
            'totalExpenses',
            'netRevenue'
        ));
    }

    public function exportDailyReport(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        $orders = Order::whereDate('created_at', $date)->get();
        $roomBookings = RoomBooking::whereDate('created_at', $date)->get();
        $expenses = Expense::whereDate('expense_date', $date)->get();

        $totalFoodRevenue = $orders->where('payment_status', 'paid')->sum('final_amount');
        $totalRoomRevenue = $roomBookings->where('payment_status', 'paid')->sum('total_price');
        $totalExpenses = $expenses->sum('amount');
        $netRevenue = ($totalFoodRevenue + $totalRoomRevenue) - $totalExpenses;

        $pdf = Pdf::loadView('admin.reports.pdf-daily', compact(
            'date',
            'orders',
            'roomBookings',
            'expenses',
            'totalFoodRevenue',
            'totalRoomRevenue',
            'totalExpenses',
            'netRevenue'
        ));

        return $pdf->download("daily-report-{$date}.pdf");
    }

    public function monthlyReport(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $orders = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        $roomBookings = RoomBooking::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        $expenses = Expense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->get();

        $totalFoodRevenue = $orders->where('payment_status', 'paid')->sum('final_amount');
        $totalRoomRevenue = $roomBookings->where('payment_status', 'paid')->sum('total_price');
        $totalExpenses = $expenses->sum('amount');
        $netRevenue = ($totalFoodRevenue + $totalRoomRevenue) - $totalExpenses;

        return view('admin.reports.monthly', compact(
            'month',
            'year',
            'orders',
            'roomBookings',
            'expenses',
            'totalFoodRevenue',
            'totalRoomRevenue',
            'totalExpenses',
            'netRevenue'
        ));
    }
}
