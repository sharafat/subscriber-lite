<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\SubscriberTransformer;
use App\Http\Requests\SaveSubscriberRequest;
use App\Models\Subscriber;
use App\Services\SubscriberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;

class SubscriberApiController extends Controller
{
    private SubscriberService $subscriberService;

    public function __construct(SubscriberService $subscriberService)
    {
        $this->subscriberService = $subscriberService;
    }

    public function index(Request $request): JsonResponse
    {
        $subscriberData = $this->subscriberService->list($request->get('sort', ''));
        $subscriberData->setCollection(
            $subscriberData->getCollection()
                ->map(fn(Subscriber $subscriber) => SubscriberTransformer::fromModel($subscriber))
        );

        return response()->json($subscriberData);
    }

    public function store(SaveSubscriberRequest $request): JsonResponse
    {
        $requestParams = $request->validated();
        $subscriber = new Subscriber($requestParams);
        $subscriber = $this->subscriberService->save($subscriber, collect($requestParams['fields'] ?? []));

        Log::info("Created subscriber with id $subscriber->id.");

        return response()->json(SubscriberTransformer::fromModel($subscriber), 201);
    }

    public function show(Subscriber $subscriber): JsonResponse
    {
        return response()->json(SubscriberTransformer::fromModel($subscriber));
    }

    public function update(SaveSubscriberRequest $request, Subscriber $subscriber): JsonResponse
    {
        $requestParams = $request->validated();
        $subscriber->fill($requestParams);
        $subscriber = $this->subscriberService->save($subscriber, collect($requestParams['fields'] ?? []));

        Log::info("Updated subscriber with id $subscriber->id.");

        return response()->json(SubscriberTransformer::fromModel($subscriber));
    }

    public function destroy(Subscriber $subscriber): JsonResponse
    {
        $subscriber->delete();

        Log::info("Deleted subscriber with id $subscriber->id.");

        return response()->json(null, 204);
    }
}
