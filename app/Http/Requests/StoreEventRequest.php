<?php

namespace App\Http\Requests;

use App\Rules\BookedEventTimeRule;
use App\Rules\EndEventTimeRule;
use App\Rules\EventTimeRule;
use App\Rules\StartEventTimeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventRequest extends FormRequest
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
            'description' => 'required',
            'starttime' => ['required', 'integer'],
            'endtime' => ['required', 'integer', 'gt:starttime', new EventTimeRule($this->starttime, $this->boardroom_id)],
            'boardroom_id' => ['required', Rule::exists('boardrooms', 'id')],
            'user_id' => ['required', Rule::exists('users', 'id')]
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'endtime.gt' => 'End time cannot be more than start time.',
        ];
    }
}
