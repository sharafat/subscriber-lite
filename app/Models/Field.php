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

    public const STRING_MAX_LENGTH = 250;

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
        return (string)match ($this->type) {
            self::TYPE_NUMBER => $faker->randomDigit(),
            self::TYPE_DATE => $faker->date(),
            self::TYPE_BOOLEAN => $this->required ? 1 : 0,
            default => $faker->sentence(3),
        };
    }

    /**
     * @return string|null error message, or null in case of no error
     */
    public function validate(mixed $value): ?string
    {
        if (!$this->required && !$value) {
            return null;
        }

        if ($this->required && !$value) {
            return __('Field is required.');
        }

        switch ($this->type) {
            case self::TYPE_DATE:
                if (!(bool)strtotime($value)) {
                    return __('Value must be a date.');
                }
                break;
            case self::TYPE_NUMBER:
                if (!is_numeric($value)) {
                    return __('Value must be a number.');
                }
                break;
            case self::TYPE_BOOLEAN:
                if (!in_array($value, [true, false, 0, 1], false)) {
                    return __('Value must be a boolean.');
                }
                break;
            case self::TYPE_STRING:
                if (strlen($value) > self::STRING_MAX_LENGTH) {
                    return __('Length cannot be greater than :maxlength.', ['maxlength' => self::STRING_MAX_LENGTH]);
                }
                break;
        }

        return null;
    }
}
