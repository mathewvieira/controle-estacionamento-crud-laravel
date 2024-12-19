<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    public function toArray(Request $request = null): array
    {
        $pagination = [
            'total' => $this->total(),
            'per_page' => $this->perPage(),
            'last_page' => $this->lastPage(),
            'has_pages' => $this->hasPages(),
            'on_first_page' => $this->onFirstPage(),
            'has_more_pages' => $this->hasMorePages(),
            'current_page' => $this->currentPage(),
            'previous_page_url' => $this->previousPageUrl(),
            'next_page_url' => $this->nextPageUrl(),
            'first_item' => $this->firstItem(),
            'last_item' => $this->lastItem(),
        ];

        $meta = $this->total() > 0 ? ['meta' => ['pagination' => $pagination]] : [];

        return array_merge(['data' => $this->collection->toArray()], $meta);
    }
}