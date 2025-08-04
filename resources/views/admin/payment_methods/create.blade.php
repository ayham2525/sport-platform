@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">Create Payment Method</h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.payment-methods.index') }}" class="text-muted">Payment Methods</a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">Create</span>
        </li>
    </ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">Add New Payment Method</h3>
            </div>

            <!--begin::Form-->
            <form action="{{ route('admin.payment-methods.store') }}" method="POST" class="form">
                @csrf
                <div class="card-body">
                    @php $inputClass = 'form-control form-control-solid'; @endphp

                    {{-- English Name --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Name (EN)</label>
                        <div class="col-lg-6">
                            <input type="text" name="name" class="{{ $inputClass }} @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="Enter English name">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Arabic Name --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Name (AR)</label>
                        <div class="col-lg-6">
                            <input type="text" name="name_ar" class="{{ $inputClass }} @error('name_ar') is-invalid @enderror"
                                value="{{ old('name_ar') }}" placeholder="أدخل الاسم بالعربية">
                            @error('name_ar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Urdu Name --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Name (UR)</label>
                        <div class="col-lg-6">
                            <input type="text" name="name_ur" class="{{ $inputClass }} @error('name_ur') is-invalid @enderror"
                                value="{{ old('name_ur') }}" placeholder="اردو نام درج کریں">
                            @error('name_ur')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Description</label>
                        <div class="col-lg-6">
                            <textarea name="description" rows="3" class="{{ $inputClass }} @error('description') is-invalid @enderror"
                                placeholder="Write optional description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-group row align-items-center">
                        <label class="col-lg-3 col-form-label">Status</label>
                        <div class="col-lg-6">
                            <span class="switch switch-outline switch-icon switch-success">
                                <label>
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} />
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success mr-2">Save</button>
                    <a href="{{ route('admin.payment-methods.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>
@endsection
