<?php

namespace Database\Factories;

use App\Models\BabyName;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BabyNameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BabyName::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName(),
            'description' => $this->faker->paragraph(4),
            'meaning' => $this->faker->paragraph(2),
            'gender_id' => $this->faker->boolean,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];
    }
}
