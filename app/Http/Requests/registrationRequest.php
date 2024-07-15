<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class registrationRequest extends FormRequest
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
            'email' => 'required| email| unique:users',
            'nikname' => 'required| unique:users',
            'birthdate' => 'required| before:-14 years',
            'password' => 'required| min: 8| confirmed',
            'pfp' => 'required| mimes:jpeg,png,jpg',
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'E-mail был введён некорректно',
            'birthdate.before' => 'Вы должны быть старше 14 лет',
            'email.unique' => 'Этот E-mail уже занят',
            'nikname.unique' => 'Этот ник уже занят',
            'password.min' => 'Пароль должен быть не менее 8 символов',
            'pfp.mimes' => 'Загружать можно только в форматах jpeg, png, jpg',
            'password.confirmed' => 'Пароли не совпадают',
            '*.required' => 'Пожалуйста, заполните это поле'
        ];
    }
}
