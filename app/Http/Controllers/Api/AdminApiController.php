<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Domains\Restaurant\Models\Restaurant;
use Illuminate\Http\JsonResponse;

abstract class AdminApiController extends Controller
{
    protected function restaurant(): Restaurant
    {
        $restaurant = auth()->user()->ownedRestaurants()->first();

        abort_unless($restaurant, 403, 'No restaurant associated with this account.');

        return $restaurant;
    }

    protected function successResponse(mixed $data, string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json(['data' => $data, 'message' => $message], $status);
    }

    protected function notFound(string $message = 'Resource not found.'): JsonResponse
    {
        return response()->json(['message' => $message], 404);
    }
}
