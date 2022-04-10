<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Transformers\FieldTransformer;
use App\Http\Transformers\SubscriberTransformer;
use App\Models\Field;
use App\Models\Subscriber;
use Illuminate\Support\Collection;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    public function testSubscribersAreListedCorrectly(): void
    {
        $subscribers = Subscriber::factory()->count(2)->withFields()->create();

        $response = $this->getJson(route('api.subscribers.index'));

        $response->assertOk()
            ->assertJson(
                fn(AssertableJson $json) => $json->has('data', 2)
                    ->has('data.0', fn($json) => $this->assertJsonHasSubscriber($json, $subscribers->get(1)))
                    ->has('data.1', fn($json) => $this->assertJsonHasSubscriber($json, $subscribers->get(0)))
                    ->etc()
            );
    }

    public function testSubscriberCreatesCorrectly(): void
    {
        $subscriber = Subscriber::factory()->make();
        $fields = Field::factory()->createWithValues();

        $response = $this->postJson(
            route('api.subscribers.store'),
            $this->getSubscriberTransformer($subscriber, $fields)->toArray()
        );

        $response->assertCreated();
        $subscriber->id = $response->json('id');
        $subscriber->fields = $fields;
        $this->assertDatabaseHasRow($subscriber);
    }

    public function testSubscriberCreationFailsIfEmailDomainIsNotValid(): void
    {
        $subscriber = Subscriber::factory()->make(['email' => 'address@nonexistenthost.com']);
        $fields = Field::factory()->createWithValues();

        $response = $this->postJson(
            route('api.subscribers.store'),
            $this->getSubscriberTransformer($subscriber, $fields)->toArray()
        );

        $response->assertUnprocessable()
            ->assertJson(
                fn(AssertableJson $json) => $json->has('errors')
                    ->has('errors.email')
                    ->etc()
            );
    }

    public function testSubscriberCreationFailsIfFieldValueDoesNotConformToFieldType(): void
    {
        $subscriber = Subscriber::factory()->make();
        $field = Field::factory()->create(['title' => 'Title', 'type' => Field::TYPE_NUMBER]);
        $field->value = 'TEXT';

        $response = $this->postJson(
            route('api.subscribers.store'),
            $this->getSubscriberTransformer($subscriber, collect([$field]))->toArray()
        );

        $response->assertUnprocessable()
            ->assertJson(fn(AssertableJson $json) => $json->has('errors')->etc());
    }

    public function testSubscriberShowsCorrectly(): void
    {
        $subscriber = Subscriber::factory()->withFields()->create();

        $response = $this->getJson(route('api.subscribers.show', ['subscriber' => $subscriber]));

        $response->assertOk()
            ->assertJson(fn(AssertableJson $json) => $this->assertJsonHasSubscriber($json, $subscriber));
    }

    public function testSubscriberUpdatesCorrectly(): void
    {
        $subscriber = Subscriber::factory()->withFields()->create();
        $subscriber->name = 'New Name';

        $subscriberDto = SubscriberTransformer::fromModel($subscriber);

        $response = $this->putJson(
            route('api.subscribers.update', ['subscriber' => $subscriber]),
            $subscriberDto->toArray(),
        );

        $response->assertOk()
            ->assertJson(fn(AssertableJson $json) => $this->assertJsonHasSubscriber($json, $subscriber));
        $this->assertDatabaseHasRow($subscriber);
    }

    public function testSubscriberDeletesCorrectly(): void
    {
        $subscriber = Subscriber::factory()->withFields()->create();

        $response = $this->deleteJson(route('api.subscribers.destroy', ['subscriber' => $subscriber]));

        $response->assertNoContent();
        $this->assertSoftDeleted($subscriber);
    }

    private function assertJsonHasSubscriber(AssertableJson $json, Subscriber $subscriber): void
    {
        $json->where('id', $subscriber->id)
            ->where('name', $subscriber->name)
            ->where('email', $subscriber->email)
            ->where('state', $subscriber->state);

        if ($subscriber->fields->isNotEmpty()) {
            $json->has(
                'fields',
                $subscriber->fields()->count(),
                fn($json) => $this->assertJsonHasField($json, $subscriber->fields->first())
            );
        }

        $json->etc();
    }

    private function assertJsonHasField(AssertableJson $json, ?Field $field): void
    {
        if ($field === null) {
            return;
        }

        $json->where('id', $field->id)
            ->where('title', $field->title)
            ->where('type', $field->type)
            ->where('value', $field->pivot?->value ?? $field->value)
            ->etc();
    }

    private function assertDatabaseHasRow(Subscriber $subscriber): void
    {
        $this->assertDatabaseHas(
            'subscribers',
            [
                'name' => $subscriber->name,
                'email' => $subscriber->email,
                'state' => $subscriber->state,
            ]
        );

        foreach ($subscriber->fields as $field) {
            $this->assertDatabaseHas(
                'subscriber_fields',
                [
                    'subscriber_id' => $subscriber->id,
                    'field_id' => $field->id,
                    'value' => $field->pivot?->value ?? $field->value,
                ]
            );
        }
    }

    /**
     * @param Collection<Field> $fields
     */
    private function getSubscriberTransformer(Subscriber $subscriber, Collection $fields): SubscriberTransformer
    {
        $subscriberTransformer = SubscriberTransformer::fromModel($subscriber);
        $subscriberTransformer->fields = $fields->map(fn(Field $field) => FieldTransformer::fromModel($field))->toArray();

        return $subscriberTransformer;
    }
}
