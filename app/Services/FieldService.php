<?php

namespace App\Services;

use App\Models\Field;
use Illuminate\Pagination\LengthAwarePaginator;

class FieldService
{
    public function list(int $perPage = 10, string $orderBy = 'id desc'): LengthAwarePaginator
    {
        return Field::orderByRaw(!empty($orderBy) ? $orderBy : 'id desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function save(Field $field): Field
    {
        $field->save();

        return $field;
    }

    public function delete(Field $field): void
    {
        $field->delete();
    }
}
