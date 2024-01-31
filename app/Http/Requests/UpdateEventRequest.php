<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'description' => 'sometimes|required',
            'starttime' => 'sometimes|required|integer',
            'endtime' => 'sometimes|required|integer',
            'boardroom_id' => ['sometimes', 'required', Rule::exists('boardrooms', 'id')],
            'user_id' => ['sometimes', 'required', Rule::exists('users', 'id')]
        ];
    }
}
