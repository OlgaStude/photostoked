<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
    public function rules()
    {
        return [
            'card' => 'required| min: 16',
            'month' => 'required| max: 31| numeric',
            'year' => 'required| max: 99| numeric',
            'cvc' => 'required| max: 999| numeric',
            'name' => 'required'
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'Пожалуйста заполните все поля',
            '*.max' => 'Значение некорректно',
            '*.numeric' => 'Значение некорректно 1',
        ];
    }
}
