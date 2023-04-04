<?php

namespace App\Http\Requests\SchedulerEvent;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
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
            'doctor_id' => 'required|numeric',
            'cabinet_id' => 'required|numeric|min:0|max:24',
            'date_event' => 'required|date',
            'time_event_from' => 'required|date_format:H:i',
            'time_event_to' => 'required|after_or_equal:time_event_from|date_format:H:i',
            'status' => 'required|string',
            'comment' => 'string',
        ];
    }
}
