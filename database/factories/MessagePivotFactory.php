<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MessagePivot>
 */
class MessagePivotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sms_id' => rand(1, 10),
            'sender_id' => rand(1, 5),
            'receiver_id' => rand(6, 10)
        ];
    }
}
