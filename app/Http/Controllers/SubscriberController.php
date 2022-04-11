<?php

namespace App\Http\Controllers;

use App\Http\Transformers\FieldTransformer;
use App\Http\Transformers\SubscriberTransformer;
use App\Models\Field;
use App\Models\Subscriber;
use Illuminate\Contracts\View\View;

class SubscriberController extends Controller
{
    public function index(): View
    {
        return view('subscribers.index');
    }

    public function create(): View
    {
        return $this->createEditView();
    }

    public function edit(Subscriber $subscriber): View
    {
        return $this->createEditView($subscriber);
    }

    private function createEditView(?Subscriber $subscriber = null): View
    {
        $transformedSubscriber = $subscriber ? SubscriberTransformer::fromModel($subscriber) : null;
        $customFields = Field::all()->map(fn(Field $field) => FieldTransformer::fromModel($field));

        return view(
            'subscribers.create_edit',
            [
                'subscriber' => $transformedSubscriber,
                'customFields' => $customFields,
            ]
        );
    }
}
