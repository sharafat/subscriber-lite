@extends('layouts.template')

@section('title', __($field ? 'Edit Field' : 'Create Field'))

@section('css')
    <style>
    </style>
@endsection

@section('content')
    <div>

        <div id="app">
            <field-add-edit field-list-page-url="{{ route('fields.index') }}"
                            field-create-api="{{ route('api.fields.store') }}"
                            field-update-api="{{ route('api.fields.update', ['field' => $field ?? 0]) }}"
                            @if ($field) :field="window.field" @endif
            />
        </div>

    </div>
@endsection

@section('js')
    <script>
        window.field = @json($field?->attributesToArray(), JSON_THROW_ON_ERROR)
    </script>
@endsection
