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

        $weddingWishes = [
            "Wishing you a lifetime of love and happiness! 💍❤️",
            "May your love grow stronger every day! 🥂",
            "Congratulations on your beautiful journey together! 🎉",
            "Wishing you endless joy and everlasting love! 💕",
            "May your hearts always beat as one! ❤️",
            "Selamat menempuh hidup baru, semoga bahagia selalu! 🎊",
            "Semoga cinta kalian selalu tumbuh dan abadi! 💖",
            "Selamat atas pernikahan kalian, semoga selalu harmonis! 💑",
            "Semoga pernikahan kalian penuh berkah dan kebahagiaan! 🕊️",
            "Selamat menikah, semoga selalu bersama dalam suka dan duka! 💞",
            "May your marriage be filled with laughter, joy, and endless love! 🌟",
            "Wishing you a journey filled with love and adventure! 🚀💑",
            "Cheers to a love that lasts a lifetime! 🥂💕",
            "May today be the beginning of a love that lasts forever! 💍",
            "Wishing you both a beautiful life full of love and happiness! 🌸",
            "Selamat berbahagia, semoga cinta kalian semakin kuat setiap hari! 💗",
            "Semoga rumah tangga kalian selalu dilimpahi kebahagiaan! 🏡💖",
            "Doa terbaik untuk pernikahan kalian, semoga selalu rukun dan damai! 🙏",
            "Semoga kalian selalu saling mencintai dan menghargai satu sama lain! 💞",
            "Selamat menempuh hidup baru, semoga penuh cinta dan berkah! 💑✨"
        ];


        return [
            'event_id' => 1,
            'content' => $this->faker->randomElement($weddingWishes),
            'status' => MessageStatusEnum::VISIBLE->value,
            'displayed' => $this->faker->boolean,
            'sender_id' => $sender->id,
            'sender_type' => $sender->getMorphClass(),
        ];
    }
}
