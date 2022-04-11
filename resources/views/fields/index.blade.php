@extends('layouts.template')

@section('title', __('Fields'))

@section('css')
    <style>
    </style>
@endsection

@section('buttons')
    <div>
        <a class="btn btn-primary"
           href="{{ route('fields.create') }}">
            <i class="fa-solid fa-plus mr-2"></i>
            <span class="hidden md:inline">New Field</span>
        </a>
    </div>
@endsection

@section('content')
    <div>

        <div id="app">
            <field-list field-list-api="{{ route('api.fields.index') }}"
                        field-delete-api="{{ route('api.fields.destroy', ['field' => 0]) }}"
                        field-edit-page-url="{{ route('fields.edit', ['field' => 0]) }}"/>
        </div>

    </div>
@endsection

@section('js')
    <script>
    </script>
@endsection
