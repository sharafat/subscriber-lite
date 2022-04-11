@extends('layouts.template')

@section('title', __('Subscribers'))

@section('css')
    <style>
    </style>
@endsection

@section('buttons')
    <div>
        <a class="btn btn-primary"
           href="{{ route('subscribers.create') }}">
            <i class="fa-solid fa-plus mr-2"></i>
            <span class="hidden md:inline">Add Subscriber</span>
        </a>
    </div>
@endsection

@section('content')
    <div>

        <div id="app">
            <subscriber-list subscriber-list-api="{{ route('api.subscribers.index') }}"
                             subscriber-delete-api="{{ route('api.subscribers.destroy', ['subscriber' => 0]) }}"
                             subscriber-edit-page-url="{{ route('subscribers.edit', ['subscriber' => 0]) }}"/>
        </div>

    </div>
@endsection

@section('js')
    <script>

    </script>
@endsection
