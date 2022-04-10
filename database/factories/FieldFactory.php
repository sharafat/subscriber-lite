<?php

namespace Database\Factories;

use App\Models\Field;
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
        ];
    }

    public function createWithValues(): Collection
    {
        return Field::factory()->count(5)->create()->map(
            function (Field $field) {
                $field->value = $this->faker->sentence();

                return $field;
            }
        );
    }
}
