<?php

namespace App\Models;

use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Field extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPE_STRING = 'string';
    public const TYPE_NUMBER = 'number';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_DATE = 'date';
    public const TYPES = [
        self::TYPE_STRING,
        self::TYPE_NUMBER,
        self::TYPE_BOOLEAN,
        self::TYPE_DATE,
    ];

    protected $guarded = ['id'];

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(Subscriber::class, 'subscriber_fields')
            ->withPivot('value');
    }

    public function generateFakeValue(Generator $faker): string
    {
        return (string) match ($this->type) {
            self::TYPE_NUMBER => $faker->randomDigit(),
            self::TYPE_DATE => $faker->date(),
            self::TYPE_BOOLEAN => $this->required ? 1 : 0,
            default => $faker->sentence(3),
        };
    }
}
