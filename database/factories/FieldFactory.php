<?php

namespace Database\Factories;

use App\Models\Field;
use Database\Seeders\FieldSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends Factory<Field>
 */
class FieldFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->sentence(3),
            'type' => $this->faker->randomElement(Field::TYPES),
            'required' => $this->faker->boolean(),
        ];
    }

    public function createWithValues(): Collection
    {
        return Field::factory()->count(FieldSeeder::FieldSeedCount)->create()->map(
            function (Field $field) {
                $field->value = $field->generateFakeValue($this->faker);

                return $field;
            }
        );
    }
}
