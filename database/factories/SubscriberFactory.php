<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\Subscriber;
use Database\Seeders\FieldSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscriber>
 */
class SubscriberFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->freeEmail(),
            'state' => $this->faker->randomElement(Subscriber::STATES),
        ];
    }

    public function withFields(): static
    {
        Field::factory()->count(FieldSeeder::FieldSeedCount)->create();

        return $this->state(fn(array $attributes) => []);
    }

    public function configure(): self
    {
        return $this->afterCreating(
            function (Subscriber $subscriber) {
                $this->seedRandomFieldsForSubscriber($subscriber);
            }
        );
    }

    private function seedRandomFieldsForSubscriber(Subscriber $subscriber): void
    {
        for ($fieldCount = 1; $fieldCount < random_int(1, FieldSeeder::FieldSeedCount); $fieldCount++) {
            $subscriber->fields()->attach($fieldCount, ['value' => $this->faker->sentence(3)]);
        }
    }
}
