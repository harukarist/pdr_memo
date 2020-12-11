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

    return $rule;
  }
}
