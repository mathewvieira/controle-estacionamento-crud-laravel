<?php

namespace App\Interfaces;

use App\Http\Resources\BaseCollection;
use App\Models\Vehicle;

interface VehicleRepositoryInterface
{
    public function findAll(int $perPage = 5): BaseCollection;
    public function find(string $id): Vehicle;
    public function create(array $data): Vehicle;
    public function update(string $id, array $data): Vehicle;
    public function delete(string $id): bool;
}