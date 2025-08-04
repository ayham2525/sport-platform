@extends('layouts.error')

@section('title', '404 - Page Not Found')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold text-warning">404</h1>
        <p class="fs-3 text-muted">Oops! Page Not Found</p>
        <p class="lead">The page you're looking for doesn't exist or has been moved.</p>
     </div>
</div>
@endsection
