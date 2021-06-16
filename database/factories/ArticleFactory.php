<?php

namespace Database\Factories;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(5),
            'slug' => $this->faker->slug(6),
            'content' => $this->faker->text(500),
            'is_public' => $this->faker->boolean,
            'user_id' => $this->faker->numberBetween(1, 20),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ];
    }
}
