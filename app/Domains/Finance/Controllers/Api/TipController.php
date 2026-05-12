<?php

namespace App\Domains\Finance\Controllers\Api;

use App\Http\Controllers\Api\AdminApiController;
use App\Domains\Finance\Resources\TipResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TipController extends AdminApiController
{
    public function index(): AnonymousResourceCollection
    {
        $tips = $this->restaurant()
            ->tips()
            ->with('employee.user')
            ->latest('date')
            ->paginate(30);

        return TipResource::collection($tips);
    }

    /**
     * Liquidación de propinas por empleado (Ley 1935 de 2018).
     * Diferencia entre efectivo (recibido directamente) y tarjeta/transferencia
     * (el empleador debe transferir al trabajador).
     */
    public function liquidation(): JsonResponse
    {
        $restaurant = $this->restaurant();
        $month      = request('month', now()->month);
        $year       = request('year', now()->year);

        $tips = $restaurant->tips()
            ->with('employee.user')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $liquidation = $tips
            ->groupBy('employee_id')
            ->map(function ($employeeTips) {
                $employee = $employeeTips->first()->employee;
                return [
                    'employee_id'         => $employee?->id,
                    'employee_name'       => $employee?->user?->name ?? 'Sin asignar',
                    'position'            => $employee?->position,
                    'total_tips'          => (float) $employeeTips->sum('amount'),
                    'cash_tips'           => (float) $employeeTips->where('payment_method', 'cash')->sum('amount'),
                    'transfer_pending'    => (float) $employeeTips->whereIn('payment_method', ['card', 'transfer'])->sum('amount'),
                    'tip_count'           => $employeeTips->count(),
                ];
            })
            ->values();

        return response()->json([
            'data'   => $liquidation,
            'period' => ['month' => $month, 'year' => $year],
            'note'   => 'Ley 1935/2018: propinas son del trabajador. Las de tarjeta/transferencia deben ser giradas por el empleador.',
        ]);
    }
}
