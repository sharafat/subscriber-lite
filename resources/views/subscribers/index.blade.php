@extends('layouts.template')

@section('title', __('Subscribers'))

@section('css')
    <style>
    </style>
@endsection

@section('content')
    <div>

        <div id="app">
            <subscriber-list subscriber-list-api="{{ route('api.subscribers.index') }}">
            </subscriber-list>
        </div>

    </div>
@endsection

@section('js')
    <script>

    </script>
@endsection
