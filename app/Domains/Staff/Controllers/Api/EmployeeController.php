<?php

namespace App\Domains\Staff\Controllers\Api;

use App\Http\Controllers\Api\AdminApiController;
use App\Domains\Staff\Models\Employee;
use App\Domains\Staff\Requests\StoreEmployeeRequest;
use App\Domains\Staff\Requests\UpdateEmployeeRequest;
use App\Domains\Staff\Resources\EmployeeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployeeController extends AdminApiController
{
    public function index(): AnonymousResourceCollection
    {
        $employees = $this->restaurant()
            ->employees()
            ->with('user')
            ->latest()
            ->paginate(20);

        return EmployeeResource::collection($employees);
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = $this->restaurant()->employees()->create($request->validated());

        return (new EmployeeResource($employee->load('user')))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id): JsonResponse
    {
        $employee = $this->restaurant()->employees()->with('user')->find($id);

        return $employee
            ? (new EmployeeResource($employee))->response()
            : $this->notFound();
    }

    public function update(UpdateEmployeeRequest $request, int $id): JsonResponse
    {
        $employee = $this->restaurant()->employees()->find($id);

        if (!$employee) return $this->notFound();

        $employee->update($request->validated());

        return (new EmployeeResource($employee->load('user')))->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $employee = $this->restaurant()->employees()->find($id);

        if (!$employee) return $this->notFound();

        $employee->delete();

        return response()->json(['message' => 'Employee deleted.']);
    }
}
