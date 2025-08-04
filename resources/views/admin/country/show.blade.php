@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">Country Details</h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.countries.index') }}" class="text-muted">Countries</a>
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
                <h3 class="card-title">Country: {{ $country->name }}</h3>
            </div>

            <!--begin::Body-->
            <div class="card-body">
                @php
                    $displayClass = 'form-control form-control-solid bg-light';
                @endphp

                @foreach([
                    'name' => 'Country Name (English)',
                    'name_native' => 'Native Name',
                    'iso2' => 'ISO2 Code',
                    'iso3' => 'ISO3 Code',
                    'phone_code' => 'Phone Code',
                    'currency' => 'Currency',
                    'currency_symbol' => 'Currency Symbol'
                ] as $field => $label)
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">{{ $label }}</label>
                        <div class="col-lg-6">
                            <input type="text" class="{{ $displayClass }}" value="{{ $country->$field }}" readonly>
                        </div>
                    </div>
                @endforeach

                {{-- Flag Display --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Flag</label>
                    <div class="col-lg-6">
                        @if ($country->flag)
                            <img src="{{ asset('images/admin/original/' . $country->flag) }}" width="50" alt="Flag">
                        @else
                            <span class="text-muted">No flag uploaded</span>
                        @endif
                    </div>
                </div>

                {{-- Status --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Status</label>
                    <div class="col-lg-6">
                        <span class="badge badge-{{ $country->is_active ? 'success' : 'danger' }}">
                            {{ $country->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                {{-- Created At --}}
                <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Created At</label>
                    <div class="col-lg-6">
                        <input type="text" class="{{ $displayClass }}" value="{{ $country->created_at->format('Y-m-d H:i') }}" readonly>
                    </div>
                </div>
            </div>
            <!--end::Body-->

            <div class="card-footer text-right">
                <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">Back</a>
                <a href="{{ route('admin.countries.edit', $country->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection
