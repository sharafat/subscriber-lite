<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Transformers\FieldTransformer;
use App\Models\Field;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class FieldTest extends TestCase
{
    public function testFieldsAreListedCorrectly(): void
    {
        $fields = Field::factory()->count(2)->create();

        $response = $this->getJson(route('api.fields.index'));

        $response->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json->has('data', 2)
                    ->has('data.0', fn($json) => $this->assertJsonHasField($json, $fields->get(1)))
                    ->has('data.1', fn($json) => $this->assertJsonHasField($json, $fields->get(0)))
                    ->etc()
            );
    }

    public function testFieldCreatesCorrectly(): void
    {
        $field = Field::factory()->make();

        $response = $this->postJson(route('api.fields.store'), FieldTransformer::fromModel($field)->toArray());

        $response->assertCreated();
        $this->assertDatabaseHasRow($field);
    }

    public function testFieldCreationFailsIfRequiredAttributesAreMissing(): void
    {
        $response = $this->postJson(route('api.fields.store'));

        $response->assertUnprocessable()
            ->assertJson(
                fn(AssertableJson $json) => $json->has('errors', 2)
                    ->has('errors.title')
                    ->has('errors.type')
                    ->etc()
            );
    }

    public function testFieldShowsCorrectly(): void
    {
        $field = Field::factory()->create();

        $response = $this->getJson(route('api.fields.show', ['field' => $field]));

        $response->assertOk()
            ->assertJson(fn(AssertableJson $json) => $this->assertJsonHasField($json, $field));
    }

    public function testFieldUpdatesCorrectly(): void
    {
        $field = Field::factory()->create();
        $field->title = 'New Title';
        $field->type = Field::TYPE_BOOLEAN;

        $response = $this->putJson(
            route('api.fields.update', ['field' => $field]),
            FieldTransformer::fromModel($field)->toArray()
        );

        $response->assertOk()
            ->assertJson(fn(AssertableJson $json) => $this->assertJsonHasField($json, $field));
        $this->assertDatabaseHasRow($field);
    }

    public function testFieldDeletesCorrectly(): void
    {
        $field = Field::factory()->create();

        $response = $this->deleteJson(route('api.fields.destroy', ['field' => $field]));

        $response->assertNoContent();
        $this->assertSoftDeleted($field);
    }

    private function assertJsonHasField(AssertableJson $json, Field $field): void
    {
        $json->where('id', $field->id)
            ->where('title', $field->title)
            ->where('type', $field->type)
            ->etc();
    }

    private function assertDatabaseHasRow(Field $field): void
    {
        $this->assertDatabaseHas(
            'fields',
            [
                'title' => $field->title,
                'type' => $field->type,
            ]
        );
    }
}
