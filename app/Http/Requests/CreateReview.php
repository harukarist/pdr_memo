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
            'review_text' => 'max:1000',
            'good_text' => 'max:1000',
            'problem_text' => 'max:1000',
            'try_text' => 'max:1000',
            'actual_time' => 'required|min:0|numeric|digits_between:1, 3',
            'category_id' =>  'required|min:1|numeric',
            'flow_level' =>  'required|min:1|numeric|between:1,5',
            'started_date' =>  'required|date',
            'started_time' =>  'required|date_format:H:i',
        ];
    }

        
    public function messages()
    {
        return [
            'started_time.date_format' => ':attribute には時刻を指定してください。（例）09:00', //日本語メッセージ
        ];
    }
}
