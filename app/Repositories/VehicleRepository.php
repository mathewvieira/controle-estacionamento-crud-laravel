<?php

namespace App\Repositories;

use App\Http\Resources\BaseCollection;
use App\Interfaces\VehicleRepositoryInterface;
use App\Models\Vehicle;

class VehicleRepository implements VehicleRepositoryInterface
{
    public function findAll(int $perPage = 5): BaseCollection
    {
        return new BaseCollection(Vehicle::paginate($perPage));
    }

    public function find(string $id): Vehicle
    {
        return Vehicle::findOrFail($id);
    }

    public function create(array $data): Vehicle
    {
        return Vehicle::firstOrCreate($data);
    }

    public function update(string $id, array $data): Vehicle
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update($data);
        return $vehicle;
    }

    public function delete(string $id): bool
    {
        $vehicle = Vehicle::findOrFail($id);
        return $vehicle->delete();
    }
}