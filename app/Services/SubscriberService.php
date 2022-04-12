<?php

namespace App\Services;

use App\Models\Field;
use App\Models\Subscriber;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SubscriberService
{
    public function list(int $perPage = 10, string $orderBy = 'id desc'): LengthAwarePaginator
    {
        return Subscriber::with('fields')
            ->orderByRaw(!empty($orderBy) ? $orderBy : 'id desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function save(Subscriber $subscriber, Collection $fieldsFromRequestParams): Subscriber
    {
        if (!$subscriber->state) {
            $subscriber->state = Subscriber::STATE_UNCONFIRMED;
        }
        $fieldsForSyncing = $this->prepareFieldsForSyncing($fieldsFromRequestParams);

        DB::transaction(
            static function () use ($subscriber, $fieldsForSyncing) {
                $subscriber->save();
                $subscriber->fields()->sync($fieldsForSyncing);
            }
        );

        return $subscriber;
    }

    public function delete(Subscriber $subscriber): void
    {
        $subscriber->delete();
    }

    /**
     * Loads Field models from DB matching titles from request params.
     * Then returns fields.id paired with value from request params for all the loaded fields.
     */
    private function prepareFieldsForSyncing(Collection $fieldsFromRequestParams): Collection
    {
        $fieldsForAttaching = collect();

        $fields = Field::whereIn('title', $fieldsFromRequestParams->map(fn(array $field) => $field['title']))->get();
        foreach ($fields as $field) {
            $submittedFieldValue = $fieldsFromRequestParams->filter(
                fn($fieldParam) => $fieldParam['title'] === $field->title
            )->first()['value'];

            // For boolean fields, value will be null; therefore, set value to whether the field is required
            if ($field->type === Field::TYPE_BOOLEAN) {
                $submittedFieldValue = $field->required ? 1 : 0;
            }

            // For non-required fields, if value is null, replace it with empty string
            if (!$field->required && $submittedFieldValue === null) {
                $submittedFieldValue = '';
            }

            $fieldsForAttaching->put($field->id, ['value' => $submittedFieldValue]);
        }

        return $fieldsForAttaching;
    }
}
