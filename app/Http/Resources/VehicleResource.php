<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class VehicleResource extends BaseResource
{
    public function toArray(Request $request = null): array
    {
        return array_merge($this->baseAttributes(), [
            'plate_number' => $this->plateNumber,
            'spot_number' => $this->spotNumber,
            'model' => $this->model,
            'color' => $this->color,
            'entry_at' => optional($this->entry_at)->toISOString(),
            'exit_at' => optional($this->exit_at)->toISOString()
        ]);
    }
}