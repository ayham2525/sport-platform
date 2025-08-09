@extends('layouts.app')
@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('attendance.create_attendance') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted"><i class="la la-home mr-1"></i> {{ __('attendance.dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.attendance.index') }}" class="text-muted"><i class="la la-calendar-check mr-1"></i> {{ __('attendance.title') }}</a>
    </li>
    <li class="breadcrumb-item"><span class="text-muted">{{ __('attendance.create_attendance') }}</span></li>
</ul>
@endsection

@section('content')
<div class="container">
     <div class="card-header">
                <h3 class="card-label">{{ __('attendance.create_attendance') }}</h3>
            </div>
   <form method="POST" action="{{ route('admin.attendance.store') }}">
    @csrf
    <div class="card card-custom gutter-b">
        <div class="card-body row">

            <div class="form-group col-md-6">
                <label><i class="la la-building mr-1"></i> {{ __('attendance.branch') }}</label>
                <select name="branch_id" id="branch_id" class="form-control select2 @error('branch_id') is-invalid @enderror">
                    <option value="">{{ __('attendance.select_branch') }}</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name_ar ?? $branch->name }}</option>
                    @endforeach
                </select>
                @error('branch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group col-md-6">
                <label><i class="la la-user mr-1"></i> {{ __('attendance.user') }}</label>
                <select name="user_id" id="user_id" class="form-control select2 @error('user_id') is-invalid @enderror">
                    <option value="">{{ __('attendance.select_user') }}</option>
                </select>
                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="form-group col-md-6">
                <label><i class="la la-clock mr-1"></i> {{ __('attendance.scanned_at') }}</label>
                <input type="datetime-local" name="scanned_at" class="form-control @error('scanned_at') is-invalid @enderror">
                @error('scanned_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="card-footer text-right">
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                <i class="la la-arrow-left"></i> {{ __('attendance.back') }}
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="la la-save"></i> {{ __('attendance.create') }}
            </button>
        </div>
    </div>
</form>

</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('#branch_id').on('change', function() {
            let branchId = $(this).val();
            if (!branchId) return;

            $.ajax({
                url: `/admin/get-users-by-branch/${branchId}`
                , method: 'GET'
                , success: function(users) {
                    let $userSelect = $('#user_id');
                    $userSelect.empty().append(`<option value="">@lang('attendance.select_user')</option>`);


                    users.forEach(user => {
                        $userSelect.append(
                            `<option value="${user.id}">${user.name} (${user.role})</option>`
                        );
                    });

                    $userSelect.trigger('change'); // Refresh select2
                }
                , error: function() {
                    alert('Failed to load users for selected branch.');
                }
            });
        });
    });

</script>

