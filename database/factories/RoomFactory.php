<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    /** @var string */
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'friendly_url' => $this->faker->url,
            'max_duration' => $this->faker->randomNumber(2),
        ];
    }
}
