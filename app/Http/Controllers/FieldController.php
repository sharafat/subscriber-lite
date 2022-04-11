<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Contracts\View\View;

class FieldController extends Controller
{
    public function index(): View
    {
        return view('fields.index');
    }

    public function create(): View
    {
        return view('fields.create_edit', ['field' => null]);
    }

    public function edit(Field $field): View
    {
        return view('fields.create_edit', ['field' => $field]);
    }
}
