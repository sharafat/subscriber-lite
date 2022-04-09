<?php

namespace Database\Factories;

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
            'email' => $this->faker->unique()->email(),
            'state' => $this->faker->randomElement(Subscriber::STATES),
        ];
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
