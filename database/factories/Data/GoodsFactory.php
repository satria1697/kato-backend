<?php

namespace Database\Factories\Data;

use App\Models\Data\Goods;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoodsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Goods::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(40),
            'price' => $this->faker->numberBetween(1, 30),
            'stock' => $this->faker->numberBetween(0, 100),
            'image' => null,
            'category_id' => $this->faker->numberBetween(1,3),
            'brief' => $this->faker->text(20)
        ];
    }
}
