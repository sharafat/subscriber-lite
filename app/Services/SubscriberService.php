<?php

namespace App\Services;

use App\Models\Field;
use App\Models\Subscriber;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SubscriberService
{
    public function list(): LengthAwarePaginator
    {
        return Subscriber::with('fields')->latest('id')->paginate()->withQueryString();
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
            $fieldsForAttaching->put(
                $field->id,
                [
                    'value' => $fieldsFromRequestParams->filter(
                        fn($fieldParam) => $fieldParam['title'] === $field->title
                    )->first()['value']
                ]
            );
        }

        return $fieldsForAttaching;
    }
}
