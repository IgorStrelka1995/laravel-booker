<?php

namespace App\Rules;

use App\Models\Event;
use Illuminate\Contracts\Validation\Rule;

class EventTimeRule implements Rule
{
    /**
     * @var integer
     */
    private $starttime;

    /**
     * @var integer
     */
    private $boardroom;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($starttime, $boardroom)
    {
        $this->starttime = $starttime;
        $this->boardroom = $boardroom;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $starttime = $this->starttime;
        $endtime = $value;

        return !Event::query()->where(function (\Illuminate\Database\Eloquent\Builder $query) use ($starttime, $endtime) {
            $query
                ->orWhereBetween('starttime', [$starttime, $endtime])
                ->orWhereBetween('endtime', [$starttime, $endtime])
            ;
        })->where('boardroom_id', '=', $this->boardroom)->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Boardroom already booked at this time. Choose another boardroom or change meeting time.';
    }
}
