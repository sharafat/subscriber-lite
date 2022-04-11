<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\FieldTransformer;
use App\Http\Requests\SaveFieldRequest;
use App\Models\Field;
use App\Services\FieldService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;

class FieldApiController extends Controller
{
    private FieldService $fieldService;

    public function __construct(FieldService $fieldService)
    {
        $this->fieldService = $fieldService;
    }

    public function index(Request $request): JsonResponse
    {
        $fieldData = $this->fieldService->list($request->get('sort', ''));
        $fieldData->setCollection(
            $fieldData->getCollection()->map(fn(Field $field) => FieldTransformer::fromModel($field))
        );

        return response()->json($fieldData);
    }

    public function store(SaveFieldRequest $request): JsonResponse
    {
        $field = new Field($request->validated());
        $field = $this->fieldService->save($field);

        Log::info("Created field with id $field->id.");

        return response()->json(FieldTransformer::fromModel($field), 201);
    }

    public function show(Field $field): JsonResponse
    {
        return response()->json(FieldTransformer::fromModel($field));
    }

    public function update(SaveFieldRequest $request, Field $field): JsonResponse
    {
        $field->fill($request->validated());
        $field = $this->fieldService->save($field);

        Log::info("Updated field with id $field->id.");

        return response()->json(FieldTransformer::fromModel($field));
    }

    public function destroy(Field $field): JsonResponse
    {
        $field->delete();

        Log::info("Deleted field with id $field->id.");

        return response()->json(null, 204);
    }
}
