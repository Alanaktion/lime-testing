<?php

namespace Database\Factories;

use App\Models\TestSuite;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestSuiteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TestSuite::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
    }

    /**
     * Indicate that the test suite should be archived.
     *
     * @return $this
     */
    public function archived()
    {
        return $this->state(function (array $attributes) {
            return [
                'deleted_at' => now(),
            ];
        });
    }
}
