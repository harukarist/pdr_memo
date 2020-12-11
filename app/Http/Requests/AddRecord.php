<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddRecord extends FormRequest
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
            'project_id' => 'required|numeric',
            'task_name' => 'required|max:191',
            'review_text' => 'max:1000',
            'good_text' => 'max:1000',
            'problem_text' => 'max:1000',
            'try_text' => 'max:1000',
            'prep_text' => 'max:1000',
            'unit_time' => 'required|min:0|numeric|digits_between:1, 3',
            'estimated_steps' => 'required|min:0|numeric|digits_between:1, 3',
            'actual_time' => 'required|min:0|numeric|digits_between:1, 3',
            'category_id' =>  'required|min:1|numeric',
            'flow_level' =>  'required|min:1|numeric|between:1,5',
            'started_date' =>  'required|date',
            'started_time' =>  'required|date_format:H:i',
        ];
    }
}
