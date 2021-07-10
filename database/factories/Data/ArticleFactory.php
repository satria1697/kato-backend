<?php

namespace Database\Factories\Data;

use App\Models\Data\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'title' => $this->faker->text(15),
            'title_id' => $this->faker->text(15),
            'text' => $this->faker->text(150),
            'text_id' => $this->faker->text(150),
            'show' => 1,
            'image' => null,
            'brief' => $this->faker->text(20),
            'brief_id' => $this->faker->text(20),
            'slug' => $this->faker->slug()
        ];
    }
}
