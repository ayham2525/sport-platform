@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('attendance.edit_attendance') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-home mr-1"></i> {{ __('attendance.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.attendance.index') }}" class="text-muted">
            <i class="la la-calendar-check mr-1"></i> {{ __('attendance.title') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('attendance.edit_attendance') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <form method="POST" action="{{ route('admin.attendance.update', $attendance->id) }}">
            @csrf
            @method('PUT')

            <div class="card card-custom gutter-b">
                <div class="card-body row">

                    <div class="form-group col-md-6">
                        <label><i class="la la-building mr-1"></i> {{ __('attendance.branch') }}</label>
                        <select name="branch_id" id="branch_id" class="form-control" disabled>
                            <option value="{{ $attendance->branch->id }}">{{ $attendance->branch->name_ar ?? $attendance->branch->name }}</option>
                        </select>
                        @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label><i class="la la-user mr-1"></i> {{ __('attendance.user') }}</label>
                        <select name="user_id" id="user_id" class="form-control" disabled>
                            <option value="{{ $attendance->user->id }}">{{ $attendance->user->name }}</option>
                        </select>
                        @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label><i class="la la-clock mr-1"></i> {{ __('attendance.scanned_at') }}</label>
                        <input type="datetime-local" name="scanned_at"
                            class="form-control @error('scanned_at') is-invalid @enderror"
                            value="{{ \Carbon\Carbon::parse($attendance->scanned_at)->format('Y-m-d\TH:i') }}">
                        @error('scanned_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                        <i class="la la-arrow-left"></i> {{ __('attendance.back') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="la la-save"></i> {{ __('attendance.update') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
