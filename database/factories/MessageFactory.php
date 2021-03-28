<?php

namespace Database\Factories;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'firstName' => $this->faker->firstName(),
            'lastName' => $this->faker->monthName(),
            'email' => $this->faker->email,
            'message' => $this->faker->paragraph(4),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];
    }
}
