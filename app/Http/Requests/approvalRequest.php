<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class approvalRequest extends FormRequest
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
            'material' => 'required| mimes:jpeg,png,jpg,svg,mp4,avi,mkv',
            'tags' => 'required',
            'users_id' => 'required',
            'format' => 'required',
            'type' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'material.required' => 'файл не выбран',
            'material.mimes' => 'некорректный формат файла. Разрешенны только: jpg, jpeg, png, svg, mp4, avi, mkv',
            'tags.required' => 'нужно указать хотя бы один тег',
        ];
    }
}
