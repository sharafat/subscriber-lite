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
    public bool $required;
    public ?string $value;

    public function __construct(string $title, string $type, bool $required, ?int $id = null, ?string $value = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->type = $type;
        $this->required = $required;
        $this->value = $value;
    }

    public static function fromModel(Field $field): self
    {
        return new self(
            $field->title,
            $field->type,
            $field->required,
            $field->id,
            $field->pivot?->value ?? ($field->value ?? null)
        );
    }
}
