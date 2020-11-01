<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            "question" => $this->faker->sentence(),
            "option1" => $this->faker->sentence(),
            "option2" => $this->faker->sentence(),
            "option3" => 'this one is correct',
            "option4" => $this->faker->sentence(),
            "answer" => 'this one is correct',
            "quiz_id" => Quiz::all()->random()->id,
        ];
    }
}
