<?php

namespace App\Services;

use App\Http\Resources\BaseCollection;
use App\Interfaces\VehicleRepositoryInterface;
use App\Models\Vehicle;
use App\Traits\ApiResponseTrait;

class VehicleService
{
    use ApiResponseTrait;

    protected VehicleRepositoryInterface $vehicleRepository;

    public function __construct(VehicleRepositoryInterface $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function findAllVehicles(int $perPage = 5): BaseCollection
    {
        return $this->vehicleRepository->findAll(
            max(1, min($perPage, 35))
        );
    }

    public function findVehicleById($id): Vehicle
    {
        return $this->vehicleRepository->find($id);
    }

    public function createVehicle(array $data): Vehicle
    {
        return $this->vehicleRepository->create($data);
    }

    public function updateVehicle($id, array $data): Vehicle
    {
        return $this->vehicleRepository->update($id, $data);
    }

    public function deleteVehicle($id): bool
    {
        return $this->vehicleRepository->delete($id);
    }
}