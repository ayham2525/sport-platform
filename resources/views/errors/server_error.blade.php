@extends('layouts.error')

@section('title', '500 - Internal Server Error')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold text-danger">500</h1>
        <p class="fs-3 text-muted">Oops! Something went wrong on the server.</p>
        <p class="lead">We’re working on it. Please try again later or contact support if the problem persists.</p>
    </div>
</div>
@endsection
