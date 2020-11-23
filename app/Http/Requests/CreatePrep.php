<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePrep extends FormRequest
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
            'prep_text' => 'required|max:255',
            'unit_time' => 'required',
            'estimated_steps' => 'required',
            'category_id' => 'min:1|numeric',
            'task_id' => 'required|min:1|numeric',
        ];
    }
}
