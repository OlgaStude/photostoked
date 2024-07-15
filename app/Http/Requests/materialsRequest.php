<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class materialsRequest extends FormRequest
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
            'material' => 'required| mimes:jpg,jpeg,png,svg,mp4,avi,mkv',
            'tags' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'tags.required' => 'введите хотябы одну метку',
            'material.required' => 'вы забыли добавить материал',
            'material.mimes' => 'Можно отправлять только в фотматах: jpg, jpeg, png, svg, mp4, avi, mkv'
        ];
    }
}
