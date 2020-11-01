<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScoreFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Score::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "obtained_marks" => $this->faker->numberBetween(0,10),
            "total_marks" => 10,
            "category_id" => Category::all()->random()->id,
            "user_id" => 1,
        ];
    }
}
