@extends('layouts.app') {{-- or layouts.metronic or admin.master if you use a different one --}}

@section('page_title', __('messages.access_denied'))

@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center" style="height: 70vh;">
        <h1 class="text-danger display-4">{{ __('messages.access_denied') }}</h1>
        <p class="text-muted mb-4">{{ __('messages.no_permission') }}</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary">
            {{ __('messages.go_back') }}
        </a>
    </div>
@endsection
