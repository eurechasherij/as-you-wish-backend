<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Message;
use App\Models\User;
use App\Enums\MessageStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $sender = User::first();

        return [
            'event_id' => 1,
            'content' => $this->faker->sentence(),
            'status' => $this->faker->randomElement([MessageStatusEnum::PENDING, MessageStatusEnum::VISIBLE]),
            'displayed' => $this->faker->boolean,
            'sender_id' => $sender->id,
            'sender_type' => $sender->getMorphClass(),
        ];
    }
}
