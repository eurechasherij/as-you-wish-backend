<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $name = $this->faker->firstNameMale() . ' & ' . $this->faker->firstNameFemale();
        return [
            'event_name' => $name,
            'slug' => Str::slug($name),
            'code' => Str::uuid(),
            'description' => $this->faker->paragraph(),
            'event_date' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'created_by' => User::factory(),
        ];
    }
}
