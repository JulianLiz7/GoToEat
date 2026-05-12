<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminFinanceController extends Controller
{
    public function index()
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();

        if (!$restaurant) return redirect()->route('onboarding.step1');

        $rid   = $restaurant->id;
        $month = now()->month;
        $year  = now()->year;

        $monthRevenue  = DB::table('orders')
            ->where('restaurant_id', $rid)
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->sum('total') ?? 0;

        $monthExpenses = DB::table('expenses')
            ->where('restaurant_id', $rid)
            ->whereMonth('expense_date', $month)->whereYear('expense_date', $year)
            ->sum('amount') ?? 0;

        $netProfit = $monthRevenue - $monthExpenses;

        $expenses = $restaurant->expenses()
            ->whereMonth('expense_date', $month)->whereYear('expense_date', $year)
            ->latest('expense_date')->get();

        // Liquidación de propinas (Ley 1935)
        $tipLiquidation = DB::table('tips')
            ->join('employees', 'tips.employee_id', '=', 'employees.id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->where('tips.restaurant_id', $rid)
            ->whereMonth('tips.date', $month)->whereYear('tips.date', $year)
            ->selectRaw('
                employees.id as employee_id,
                users.name as employee_name,
                employees.position,
                SUM(tips.amount) as total_tips,
                SUM(CASE WHEN tips.payment_method = "cash" THEN tips.amount ELSE 0 END) as cash_tips,
                SUM(CASE WHEN tips.payment_method IN ("card","transfer") THEN tips.amount ELSE 0 END) as transfer_pending
            ')
            ->groupBy('employees.id', 'users.name', 'employees.position')
            ->get();

        return view('admin.finance', compact(
            'restaurant', 'monthRevenue', 'monthExpenses', 'netProfit',
            'expenses', 'tipLiquidation'
        ));
    }
}
