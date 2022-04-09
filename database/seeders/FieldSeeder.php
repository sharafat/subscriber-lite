<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    public const FieldSeedCount = 5;

    public function run(): void
    {
        Field::factory()
            ->count(self::FieldSeedCount)
            ->create();
    }
}
