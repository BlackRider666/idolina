<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'role'          =>  'required|in:startup,investor',
            'name'          =>  'required|string|max:255',
            'country_id'    =>  'required|int|exists:countries,id',
            'city_id'       =>  'required|int|exists:cities,id',
            'direction'     =>  'nullable|sometimes',
            'site'          =>  'required|string|max:255',
            'file'          =>  'nullable|sometimes|file',
            'desc'          =>  'required|string',
            'email'         =>  'required|string|email|max:255|unique:users',
            'password'      =>  'required|string|max:255|confirmed',
            'phone'         =>  'required|string|max:255',
        ];
    }
}
