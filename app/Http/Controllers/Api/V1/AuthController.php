<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginSalesRepresentativeRequest;
use App\Models\SalesRepresentative;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(LoginSalesRepresentativeRequest $request): JsonResponse
    {
        $salesRepresentative = SalesRepresentative::query()
            ->where('cpf', $request->string('cpf'))
            ->first();

        if (! $salesRepresentative || ! Hash::check($request->string('password')->value(), $salesRepresentative->password)) {
            throw ValidationException::withMessages([
                'cpf' => [__('auth.failed')],
            ]);
        }

        $salesRepresentative->tokens()
            ->where('name', $request->string('device_name'))
            ->delete();

        $token = $salesRepresentative->createToken(
            $request->string('device_name')->value(),
            ['*'],
            now()->addDays(7),
        );

        return response()->json([
            'sales_representative' => $salesRepresentative,
            'token' => $token->plainTextToken,
        ]);
    }
}
