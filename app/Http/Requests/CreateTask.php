<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTask extends FormRequest
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
            'task_name' => 'required|max:191',
            'due_date' => 'nullable|date',
            'priority' =>  'min:0|numeric|between:0,3',
        ];
    }

    public function attributes()
    {
        return [
            'task_name' => 'タスク名',
            'due_date' => '期限日',
            'priority' => '優先度',
        ];
    }

    public function messages()
    {
        return [
            // キーでルールを指定（'項目.ルール'）
            'due_date.after_or_equal' => ':attribute には今日以降の日付を入力してください。',
        ];
    }
}
