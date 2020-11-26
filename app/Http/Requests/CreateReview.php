<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReview extends FormRequest
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
            'review_text' => 'max:191',
            'good_text' => 'max:191',
            'problem_text' => 'max:191',
            'try_text' => 'max:191',
            'actual_time' => 'required|min:0|numeric|digits_between:1, 3',
            'step_counter' => 'required|min:1|numeric|digits_between:1, 3',
            'category_id' =>  'required|min:1|numeric',
        ];
    }
}
