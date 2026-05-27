<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AnimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:2|max:100',
            'genre' => 'nullable',
            'description' => 'nullable|max:2000',
            'image' => 'nullable|image',
            'year' => 'nullable|integer|min:1950|max:2100',
            'format' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:50',
            'studio' => 'nullable|string|max:100',
            'voice_acting' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:ongoing,completed,announced'
        ];  
    }
}
