<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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
            'first_name' => 'required|string|max:128',
            'last_name' => 'required|string|max:128',
            'middle_name' => 'string|nullable',
            'email' => 'string',
            'gender' => 'string',
            'birthday' => 'date',
            'phone' => 'string',
            'phone_comment' => 'string',
            'card_number' => 'required|string',
            'address' => 'string',
            'add_phone' => 'string',
            'add_phone_comment' => 'string',
            'inn' => 'string',
            'passport' => 'string',
            'discount' => 'numeric',
            'doctor_id' => '',
            'patient_type' => 'string'
        ];
    }
}
