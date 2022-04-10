<?php

namespace App\Http\Transformers;

use App\Http\Traits\Arrayable;
use App\Models\Field;
use App\Models\Subscriber;

class SubscriberTransformer
{
    use Arrayable;

    public ?int $id;
    public string $name;
    public string $email;
    public ?string $state;
    /**
     * @var array<FieldTransformer> fields
     */
    public array $fields;

    public static function fromModel(Subscriber $subscriber): self
    {
        return new self(
            $subscriber->name,
            $subscriber->email,
            $subscriber->id,
            $subscriber->state,
            $subscriber->fields->map(fn(Field $field) => FieldTransformer::fromModel($field))->toArray(),
        );
    }

    /**
     * @param array<FieldTransformer> $fields
     */
    public function __construct(string $name, string $email, ?int $id = null, ?string $state = null, array $fields = [])
    {
        $this->name = $name;
        $this->email = $email;
        $this->id = $id;
        $this->state = $state;
        $this->fields = $fields;
    }
}
