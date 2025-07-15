<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePuntoGOBRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Autoriza si el usuario tiene el permiso 'manage-all' o 'manage-punto-gobs'
        return $this->user()->can('manage-all') || $this->user()->can('manage-punto-gobs');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:punto_gobs,name',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:255|unique:punto_gobs,email',
            'is_active' => 'boolean',
            'institution_id' => 'required|exists:institutions,id', 
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Ya existe un Punto GOB con este nombre.',
            'name.required' => 'El nombre del Punto GOB es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está en uso por otro Punto GOB.',
            'institution_id.required' => 'La institución es obligatoria para el Punto GOB.',
            'institution_id.exists' => 'La institución seleccionada no existe.',
        ];
    }
}