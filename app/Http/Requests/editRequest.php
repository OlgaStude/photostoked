<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class editRequest extends FormRequest
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
            'email' => 'email| unique:users,email,' . Auth::user()->id,
            'birthdate' => 'before:-14 years',
            'nikname' => 'max:60 | unique:users,nikname,' . Auth::user()->id,
            'pfp' => 'mimes:jpeg,png,jpg',
            'add_info' => 'max:255',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'E-mail был введён некорректно',
            'email.unique' => 'Этот E-mail уже занят',
            'nikname.unique' => 'Этот ник уже занят',
            'nikname.max' => 'Имя слишком длинное',
            'nikname.max' => 'Лимит символов привышен',
            'pfp.mimes' => 'Загружать можно только в форматах jpeg, png, jpg',
            'birthdate.before' => 'Вы должны быть старше 14 лет',
        ];
    }
}
