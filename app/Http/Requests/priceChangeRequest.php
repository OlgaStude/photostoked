<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class priceChangeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'new_val' => 'required|numeric'
        ];
    }


    public function messages(): array
    {
        return [
            '*.required' => 'Поле не может быть пустым',
            '*.numeric' => 'Введите только числовое значение',
        ];
    }
}
