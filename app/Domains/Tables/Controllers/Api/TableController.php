<?php

namespace App\Domains\Tables\Controllers\Api;

use App\Http\Controllers\Api\AdminApiController;
use App\Domains\Tables\Models\RestaurantTable;
use App\Domains\Tables\Requests\StoreTableRequest;
use App\Domains\Tables\Requests\UpdateTableRequest;
use App\Domains\Tables\Resources\TableResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TableController extends AdminApiController
{
    public function index(): AnonymousResourceCollection
    {
        $tables = $this->restaurant()->tables()->latest()->get();

        return TableResource::collection($tables);
    }

    public function store(StoreTableRequest $request): JsonResponse
    {
        $table = $this->restaurant()->tables()->create($request->validated());

        return (new TableResource($table))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        $table = $this->restaurant()->tables()->find($id);

        return $table
            ? (new TableResource($table))->response()
            : $this->notFound();
    }

    public function update(UpdateTableRequest $request, int $id): JsonResponse
    {
        $table = $this->restaurant()->tables()->find($id);

        if (!$table) return $this->notFound();

        $table->update($request->validated());

        return (new TableResource($table))->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $table = $this->restaurant()->tables()->find($id);

        if (!$table) return $this->notFound();

        $table->delete();

        return response()->json(['message' => 'Table deleted.']);
    }

    public function updateStatus(int $id, string $status): JsonResponse
    {
        $allowed = ['disponible', 'ocupada', 'reservada', 'mantenimiento'];

        if (!in_array($status, $allowed)) {
            return response()->json(['message' => 'Invalid status.'], 422);
        }

        $table = $this->restaurant()->tables()->find($id);

        if (!$table) return $this->notFound();

        $table->update(['status' => $status]);

        return (new TableResource($table))->response();
    }
}
