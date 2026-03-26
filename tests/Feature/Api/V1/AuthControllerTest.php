<?php

declare(strict_types=1);

use App\Models\SalesRepresentative;

use function Pest\Laravel\postJson;

describe('POST /api/v1/auth/login', function (): void {

    it('returns a token for valid credentials', function (): void {
        $salesRep = SalesRepresentative::factory()->create([
            'cpf' => '12345678901',
            'password' => 'secret-password',
        ]);

        $response = postJson(route('api.v1.auth.login'), [
            'cpf' => '12345678901',
            'password' => 'secret-password',
            'device_name' => 'test-device',
        ]);

        $response
            ->assertSuccessful()
            ->assertJsonStructure([
                'sales_representative' => ['id', 'name', 'email', 'cpf'],
                'token',
            ])
            ->assertJsonMissing(['password'])
            ->assertJsonPath('sales_representative.id', $salesRep->id);

        expect($response->json('token'))->toBeString()->not->toBeEmpty();
    });

    it('returns 422 for non-existent cpf', function (): void {
        postJson(route('api.v1.auth.login'), [
            'cpf' => '00000000000',
            'password' => 'any-password',
            'device_name' => 'test-device',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['cpf']);
    });

    it('returns 422 for wrong password', function (): void {
        SalesRepresentative::factory()->create([
            'cpf' => '12345678901',
            'password' => 'correct-password',
        ]);

        postJson(route('api.v1.auth.login'), [
            'cpf' => '12345678901',
            'password' => 'wrong-password',
            'device_name' => 'test-device',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['cpf']);
    });

    it('returns 422 when required fields are missing', function (array $payload): void {
        postJson(route('api.v1.auth.login'), $payload)
            ->assertUnprocessable();
    })->with([
        'missing cpf' => [['password' => 'pass', 'device_name' => 'device']],
        'missing password' => [['cpf' => '12345678901', 'device_name' => 'device']],
        'missing device_name' => [['cpf' => '12345678901', 'password' => 'pass']],
    ]);

    it('revokes previous tokens for the same device on login', function (): void {
        $salesRep = SalesRepresentative::factory()->create([
            'cpf' => '12345678901',
            'password' => 'secret-password',
        ]);

        postJson(route('api.v1.auth.login'), [
            'cpf' => '12345678901',
            'password' => 'secret-password',
            'device_name' => 'my-phone',
        ])->assertSuccessful();

        postJson(route('api.v1.auth.login'), [
            'cpf' => '12345678901',
            'password' => 'secret-password',
            'device_name' => 'my-phone',
        ])->assertSuccessful();

        expect($salesRep->tokens()->where('name', 'my-phone')->count())->toBe(1);
    });

    it('issues a token that can access protected routes', function (): void {
        SalesRepresentative::factory()->create([
            'cpf' => '12345678901',
            'password' => 'secret-password',
        ]);

        $loginResponse = postJson(route('api.v1.auth.login'), [
            'cpf' => '12345678901',
            'password' => 'secret-password',
            'device_name' => 'test-device',
        ]);

        $token = $loginResponse->json('token');

        $this->getJson(route('api.v1.user'), [
            'Authorization' => "Bearer {$token}",
        ])->assertSuccessful();
    });

});
