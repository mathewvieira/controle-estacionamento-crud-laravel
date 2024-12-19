<?php

namespace Tests\Feature\Api;

use App\Constants\VehicleMessages;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Vehicle;
use App\Models\User;
use App\Services\VehicleService;
use Symfony\Component\HttpFoundation\Response;

class VehiclesApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        Vehicle::factory()->count(10)->create();
    }

    protected function generateToken(): string
    {
        return User::factory()->create()->createToken('TestToken')->plainTextToken;
    }

    protected function performAuthorizedRequest(string $method, string $url, array $data = [])
    {
        return $this->withHeaders(['Authorization' => 'Bearer ' . $this->generateToken()])
            ->json($method, $url, $data);
    }

    public function test_can_list_vehicles_with_pagination()
    {
        $response = $this->performAuthorizedRequest('GET', '/api/vehicles?perPage=5');

        $response->assertSuccessful()
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'success',
                'status',
                'message',
                'data' => ['*' => ['id', 'plate_number', 'spot_number', 'model', 'color', 'entry_at', 'exit_at', 'deleted_at', 'created_at', 'updated_at']],
                'meta' => ['pagination' => ['total', 'per_page', 'last_page', 'current_page']]
            ]);
    }

    public function test_can_create_vehicle()
    {
        $data = [
            'plate_number' => 'XYZ-1234',
            'spot_number' => 50,
            'model' => 'Sedan',
            'color' => 'Blue',
            'entry_at' => '2024-12-18 14:30:00'
        ];

        $response = $this->performAuthorizedRequest('POST', '/api/vehicles', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonPath('message', VehicleMessages::ACTIONS['created'])
            ->assertJsonStructure([
                'success',
                'status',
                'message',
                'data' => [
                    'id',
                    'plate_number',
                    'spot_number',
                    'model',
                    'color',
                    'entry_at',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_cannot_create_vehicle_with_invalid_data()
    {
        $data = [
            'plate_number' => '',
            'spot_number' => '',
            'model' => '',
            'color' => '',
        ];

        $response = $this->performAuthorizedRequest('POST', '/api/vehicles', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonPath('message', 'Validation Error.')
            ->assertJsonStructure(['errors']);
    }

    public function test_can_update_vehicle()
    {
        $vehicle = Vehicle::factory()->create();
        $data = [
            'model' => 'Updated Model',
            'color' => 'Red',
        ];

        $response = $this->performAuthorizedRequest('PUT', "/api/vehicles/{$vehicle->id}", $data);

        $response->assertSuccessful()
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('message', VehicleMessages::ACTIONS['updated']);
    }

    public function test_cannot_update_nonexistent_vehicle()
    {
        $data = [
            'model' => 'Updated Model',
            'color' => 'Red',
        ];

        $response = $this->performAuthorizedRequest('PUT', '/api/vehicles/9999', $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonPath('message', VehicleMessages::ACTIONS['not_found']);
    }

    public function test_cannot_update_vehicle_with_invalid_data()
    {
        $vehicle = Vehicle::factory()->create();
        $data = [
            'plate_number' => '',
            'spot_number' => -10,
        ];

        $response = $this->performAuthorizedRequest('PUT', "/api/vehicles/{$vehicle->id}", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['errors']);
    }

    public function test_can_delete_vehicle()
    {
        $vehicle = Vehicle::factory()->create();

        $response = $this->performAuthorizedRequest('DELETE', "/api/vehicles/{$vehicle->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_cannot_delete_nonexistent_vehicle()
    {
        $response = $this->performAuthorizedRequest('DELETE', '/api/vehicles/9999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonPath('message', VehicleMessages::ACTIONS['not_found']);
    }

    public function test_can_get_vehicle_by_id()
    {
        $vehicle = Vehicle::factory()->create();

        $response = $this->performAuthorizedRequest('GET', "/api/vehicles/{$vehicle->id}");

        $response->assertSuccessful()
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('message', VehicleMessages::ACTIONS['retrieved'])
            ->assertJsonStructure([
                'success',
                'status',
                'message',
                'data' => ['id', 'plate_number', 'spot_number', 'model', 'color', 'entry_at', 'exit_at', 'created_at', 'updated_at']
            ]);
    }

    public function test_cannot_get_nonexistent_vehicle()
    {
        $response = $this->performAuthorizedRequest('GET', '/api/vehicles/9999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonPath('message', VehicleMessages::ACTIONS['not_found']);
    }

    public function test_can_handle_server_error()
    {
        $this->mock(VehicleService::class, function ($mock) {
            $mock->shouldReceive('findAllVehicles')->andThrow(new \Exception('Simulated server error'));
        });

        $response = $this->performAuthorizedRequest('GET', '/api/vehicles');

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJsonPath('message', VehicleMessages::ACTIONS['error']);
    }
}