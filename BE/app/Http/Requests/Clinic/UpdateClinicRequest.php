<?php

namespace App\Http\Requests\Clinic;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClinicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:128',
            'email' => 'required|unique:clinics,email,NULL,id,user_id,not_in:' .auth()->user()->id. '|string|max:128',
//            'web' => 'nullable|url|max:128',
            'phone' => 'nullable|string|max:128',
            'address' => 'nullable|string|max:128',
            'file' => 'image|mimes:jpeg,bmp,png|max:2048',
        ];
    }
}
