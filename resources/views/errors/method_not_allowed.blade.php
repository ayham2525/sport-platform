@extends('layouts.error')

@section('title', '405 - Method Not Allowed')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold text-danger">405</h1>
        <p class="fs-3 text-muted">Oops! Method Not Allowed</p>
        <p class="lead">The method used is not allowed for this route.</p>
    
    </div>
</div>
@endsection
