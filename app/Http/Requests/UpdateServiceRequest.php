<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $serviceId = $this->route('service');

        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('services')->ignore($serviceId)
            ],
            'description' => 'nullable|string',
            'is_active' => 'sometimes|boolean'
        ];
    }
}