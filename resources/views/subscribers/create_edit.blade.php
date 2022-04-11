@extends('layouts.template')

@section('title', __($subscriber ? 'Edit Field' : 'Create Field'))

@section('css')
    <style>
    </style>
@endsection

@section('content')
    <div>

        <div id="app">
            <subscriber-add-edit subscriber-list-page-url="{{ route('subscribers.index') }}"
                                 subscriber-create-api="{{ route('api.subscribers.store') }}"
                                 subscriber-update-api="{{ route('api.subscribers.update', ['subscriber' => $subscriber->id ?? 0]) }}"
                                 :custom-fields="window.customFields"
                                 @if ($subscriber) :subscriber="window.subscriber" @endif
            />
        </div>

    </div>
@endsection

@section('js')
    <script>
        window.subscriber = @json($subscriber, JSON_THROW_ON_ERROR);
        window.customFields = @json($customFields, JSON_THROW_ON_ERROR);
    </script>
@endsection
