<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'city_dictionary_id' => 1,
            'title' => 'Island',
            'gold' => 100,
            'population' => 50
        ];
    }
}
