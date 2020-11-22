<?php

namespace App\Http\Requests;

use App\Task;
use Illuminate\Validation\Rule;

// CreateTaskを継承
class EditTask extends CreateTask
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
    // 親のルールを継承
    $rule = parent::rules();

    // array_keys()でステータスの配列を取得し、Ruleクラスのinメソッドでルールの文字列 in(1, 2, 3)を生成
    $status_rule = Rule::in(array_keys(Task::STATUS));

    return $rule + [
      'status' => 'required|' . $status_rule,
    ];
  }

  public function attributes()
  {
    $attributes = parent::attributes();

    return $attributes + [
      'status' => 'ステータス',
    ];
  }


  public function messages()
  {
    $messages = parent::messages();

    $status_names = array_map(function ($item) {
      return $item['status_name'];
    }, Task::STATUS);

    $status_names = implode('、', $status_names);

    return $messages + [
      'status.in' => ':attribute には ' . $status_names . ' のいずれかを指定してください。',
    ];
  }
}
