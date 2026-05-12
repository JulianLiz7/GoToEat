<?php

namespace App\Domains\Finance\Controllers\Api;

use App\Http\Controllers\Api\AdminApiController;
use App\Domains\Finance\Models\Expense;
use App\Domains\Finance\Requests\StoreExpenseRequest;
use App\Domains\Finance\Resources\ExpenseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExpenseController extends AdminApiController
{
    public function index(): AnonymousResourceCollection
    {
        $expenses = $this->restaurant()
            ->expenses()
            ->latest('expense_date')
            ->paginate(30);

        return ExpenseResource::collection($expenses);
    }

    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $expense = $this->restaurant()->expenses()->create($request->validated());

        return (new ExpenseResource($expense))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        $expense = $this->restaurant()->expenses()->find($id);

        return $expense
            ? (new ExpenseResource($expense))->response()
            : $this->notFound();
    }

    public function update(StoreExpenseRequest $request, int $id): JsonResponse
    {
        $expense = $this->restaurant()->expenses()->find($id);

        if (!$expense) return $this->notFound();

        $expense->update($request->validated());

        return (new ExpenseResource($expense))->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $expense = $this->restaurant()->expenses()->find($id);

        if (!$expense) return $this->notFound();

        $expense->delete();

        return response()->json(['message' => 'Expense deleted.']);
    }
}
