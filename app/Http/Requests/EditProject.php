<?php

namespace App\Http\Requests;

use App\Project;
use Illuminate\Validation\Rule;

// CreateProjectを継承
class EditProject extends CreateProject
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
        $rule = parent::rules();
        return $rule;
    }
}
