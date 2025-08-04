@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">Branch Details</h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.branches.index') }}" class="text-muted">Branches</a>
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
                <h3 class="card-title">Branch: {{ $branch->name }}</h3>
            </div>

            <div class="card-body">
                @php
                    $displayClass = 'form-control form-control-solid bg-light';
                @endphp

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Branch Name (EN)</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $branch->name }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Branch Name (AR)</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $branch->name_ar }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Branch Name (UR)</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $branch->name_ur }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">City</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $branch->city->name ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">State</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $branch->city->state->name ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Country</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $branch->city->state->country->name ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">System</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $branch->system->name ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Address</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $branch->address ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Phone</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $branch->phone ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Status</label>
                    <div class="col-lg-6">
                        <span class="badge badge-{{ $branch->is_active ? 'success' : 'danger' }}">
                            {{ $branch->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Created At</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $branch->created_at->format('Y-m-d H:i') }}" readonly>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary">Back</a>
                <a href="{{ route('admin.branches.edit', $branch->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection
