<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class VehicleRequest extends FormRequest
{
    use ApiResponseTrait;

    public function rules(): array
    {
        return match ($this->method()) {
            'POST' => $this->store(),
            'PUT', 'PATCH' => $this->update()
        };
    }

    public function store()
    {
        return array_merge($this->commonRules(), [
            'plate_number' => ['required', 'string', 'size:8', 'regex:/^[A-Z0-9-]+$/', 'unique:vehicles,plate_number'],
            'spot_number' => ['required', 'integer', 'min:1', 'not_in:0', 'unique:vehicles,spot_number'],
            'model' => ['required'],
            'color' => ['required'],
            'entry_at' => ['required'],
            'exit_at' => ['nullable']
        ]);
    }

    public function update()
    {
        return array_merge($this->commonRules(), [
            'plate_number' => ['prohibited'],
            'spot_number' => ['prohibited'],
            'model' => ['nullable'],
            'color' => ['nullable'],
            'entry_at' => ['nullable'],
            'exit_at' => ['nullable']
        ]);
    }

    protected function commonRules(): array
    {
        return [
            'model' => ['string', 'min:1', 'max:50'],
            'color' => ['string', 'min:1', 'max:50'],
            'entry_at' => ['date_format:Y-m-d H:i:s'],
            'exit_at' => ['date_format:Y-m-d H:i:s', 'after:entry_at'],
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validatedData = parent::validated($key, $default);

        return array_map('trim', $validatedData);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorResponse(
            'Validation Error.',
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $validator->errors()
        ));
    }
}