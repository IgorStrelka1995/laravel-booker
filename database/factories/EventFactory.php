<?php

namespace Database\Factories;

use App\Models\Boardroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $starttime = $this->faker->unixTime();
        $endtime = $starttime + 1800; // 30 minutes

        return [
            'description' => $this->faker->text(),
            'starttime' => $starttime,
            'endtime' => $endtime,
            'boardroom_id' => Boardroom::all()->random()->id,
            'user_id' => User::all()->random()->id,
        ];
    }
}
