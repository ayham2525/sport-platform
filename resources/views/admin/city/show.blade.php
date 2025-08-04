@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">City Details</h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.cities.index') }}" class="text-muted">Cities</a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">Show</span>
        </li>
    </ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">City: {{ $city->name }}</h3>
            </div>

            <div class="card-body">
                @php
                    $displayClass = 'form-control form-control-solid bg-light';
                @endphp

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">City Name</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $city->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">State</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $city->state->name ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Country</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $city->country->name ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Status</label>
                    <div class="col-lg-6">
                        <span class="badge badge-{{ $city->is_active ? 'success' : 'danger' }}">
                            {{ $city->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Created At</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $city->created_at->format('Y-m-d H:i') }}" readonly>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">Back</a>
                <a href="{{ route('admin.cities.edit', $city->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection
