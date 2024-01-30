<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AccountFactory extends Factory
{
    /** @var string */
    protected $model = Account::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique->email,
            'name' => $this->faker->name,
            'developer_key' => Str::random(64),
        ];
    }
}
