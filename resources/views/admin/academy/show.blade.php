@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">Academy Details</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">Dashboard</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('academies.index') }}" class="text-muted">Academies</a>
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
                <h3 class="card-title">Academy: {{ $academy->name_en }}</h3>
            </div>

            <div class="card-body">
                @php
                    $displayClass = 'form-control form-control-solid bg-light';
                @endphp

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Academy Name (EN)</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $academy->name_en }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Academy Name (AR)</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $academy->name_ar }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Academy Name (UR)</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $academy->name_ur }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Branch</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $academy->branch->name ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Contact Email</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $academy->contact_email ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Phone</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $academy->phone ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Description (EN)</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $academy->description_en ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Description (AR)</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $academy->description_ar ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Description (UR)</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $academy->description_ur ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Status</label>
                    <div class="col-lg-6">
                        <span class="badge badge-{{ $academy->is_active ? 'success' : 'danger' }}">
                            {{ $academy->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Created At</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $academy->created_at->format('Y-m-d H:i') }}" readonly>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <a href="{{ route('academies.index') }}" class="btn btn-secondary">Back</a>
                <a href="{{ route('academies.edit', $academy->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection
