<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public function baseAttributes(): array
    {
        return [
            'id' => $this->id,
            'deleted_at' => optional($this->deleted_at)->toISOString(),
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString()
        ];
    }
}