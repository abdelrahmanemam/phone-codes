<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneNumberFilterRequest;
use App\Services\PhoneNumberService;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function __construct(private readonly PhoneNumberService $service) {}

    public function index(PhoneNumberFilterRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $result = $this->service->listPhoneNumbers($filters);

        return response()->json($result);
    }
}
