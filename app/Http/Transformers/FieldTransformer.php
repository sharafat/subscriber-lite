<?php

namespace App\Http\Transformers;

use App\Http\Traits\Arrayable;
use App\Models\Field;

class FieldTransformer
{
    use Arrayable;

    public ?int $id;
    public string $title;
    public string $type;
    public ?string $value;

    public function __construct(string $title, string $type, ?int $id = null, ?string $value = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->type = $type;
        $this->value = $value;
    }

    public static function fromModel(Field $field): self
    {
        return new self($field->title, $field->type, $field->id, $field->pivot?->value ?? ($field->value ?? null));
    }
}
