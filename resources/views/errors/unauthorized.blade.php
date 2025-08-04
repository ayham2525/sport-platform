@extends('layouts.error')

@section('title', '403 - Unauthorized Access')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold text-warning">403</h1>
        <p class="fs-3 text-muted">Access Denied</p>
        <p class="lead">You don't have permission to access this page or perform this action.</p>
        <a href="{{ url('/') }}" class="btn btn-outline-warning px-4 py-2">← Back to Homepage</a>
    </div>
</div>
@endsection
