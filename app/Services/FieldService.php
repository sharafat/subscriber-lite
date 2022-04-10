<?php

namespace App\Services;

use App\Models\Field;
use Illuminate\Pagination\LengthAwarePaginator;

class FieldService
{
    public function list(): LengthAwarePaginator
    {
        return Field::latest('id')->paginate()->withQueryString();
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
