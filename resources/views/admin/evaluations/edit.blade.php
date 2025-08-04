@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.edit_evaluation') }}</h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('titles.dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.evaluations.index') }}" class="text-muted">{{ __('titles.evaluations') }}</a></li>
        <li class="breadcrumb-item"><span class="text-muted">{{ __('titles.edit_evaluation') }}</span></li>
    </ul>
@endsection

@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">{{ __('titles.edit_evaluation') }}</h3>
        </div>

        <form action="{{ route('admin.evaluations.update', $evaluation->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label>{{ __('columns.system') }}</label>
                    <select name="system_id" class="form-control" required>
                        @foreach ($systems as $system)
                            <option value="{{ $system->id }}" {{ old('system_id', $evaluation->system_id) == $system->id ? 'selected' : '' }}>
                                {{ $system->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ __('columns.title') }}</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $evaluation->title) }}" required>
                </div>

                <div class="form-group">
                    <label>{{ __('columns.description') }}</label>
                    <textarea name="description" class="form-control">{{ old('description', $evaluation->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label>{{ __('columns.type') }}</label>
                    <select name="type" class="form-control" id="type-select" required>
                        <option value="general" {{ old('type', $evaluation->type) == 'general' ? 'selected' : '' }}>General</option>
                        <option value="internal" {{ old('type', $evaluation->type) == 'internal' ? 'selected' : '' }}>Internal</option>
                        <option value="student" {{ old('type', $evaluation->type) == 'student' ? 'selected' : '' }}>Student</option>
                    </select>
                </div>

                <div class="form-group date-range">
                    <label>{{ __('columns.start_date') }}</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $evaluation->start_date?->format('Y-m-d')) }}">
                </div>

                <div class="form-group date-range">
                    <label>{{ __('columns.end_date') }}</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $evaluation->end_date?->format('Y-m-d')) }}">
                </div>

                <div class="form-group">
                    <label>{{ __('columns.active') }}</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ old('is_active', $evaluation->is_active) == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                        <option value="0" {{ old('is_active', $evaluation->is_active) == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                    </select>
                </div>
            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success me-2">{{ __('actions.save') }}</button>
                <a href="{{ route('admin.evaluations.index') }}" class="btn btn-secondary">{{ __('actions.cancel') }}</a>
            </div>
        </form>
    </div>

   {{-- Evaluation Criteria --}}
 <div class="card card-custom mt-5">
    <div class="card-header">
        <h4 class="card-title">{{ __('titles.criteria_title') }}</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered mb-0" id="criteria-table">
                <thead class="thead-light">
                    <tr>
                        <th>{{ __('columns.label') }}</th>
                        <th>{{ __('columns.input_type') }}</th>
                        <th>{{ __('columns.weight') }}</th>
                        <th>{{ __('columns.order') }}</th>
                        <th>{{ __('columns.required') }}</th>
                        <th style="width: 150px;">{{ __('columns.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="criteria-body">
                    @foreach ($evaluation->criteria as $criterion)
                        <tr id="criterion-{{ $criterion->id }}">
                            <td>
<input type="text" name="label" value="{{ $criterion->label }}" class="form-control form-control-sm w-100 label-input">
                            </td>
                            <td>
                                <select name="input_type" class="form-control form-control-sm input_type">
                                    <option value="rating" {{ $criterion->input_type == 'rating' ? 'selected' : '' }}>{{ __('inputs.rating') }}</option>
                                    <option value="text" {{ $criterion->input_type == 'text' ? 'selected' : '' }}>{{ __('inputs.text') }}</option>
                                    <option value="yesno" {{ $criterion->input_type == 'yesno' ? 'selected' : '' }}>{{ __('inputs.yesno') }}</option>
                                </select>
                            </td>
                            <td><input type="number" name="weight" value="{{ $criterion->weight }}" class="form-control form-control-sm weight"></td>
                            <td><input type="number" name="order" value="{{ $criterion->order }}" class="form-control form-control-sm order"></td>
                            <td>
                                <select name="required" class="form-control form-control-sm required">
                                    <option value="1" {{ $criterion->required ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                    <option value="0" {{ !$criterion->required ? 'selected' : '' }}>{{ __('No') }}</option>
                                </select>
                            </td>
                            <td class="d-flex gap-1">
                                <button class="btn btn-sm btn-success save-btn" data-id="{{ $criterion->id }}">{{ __('actions.save') }}</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $criterion->id }}">{{ __('actions.delete') }}</button>
                            </td>
                        </tr>
                    @endforeach

                    {{-- New input row --}}
                    <tr>
                        <form id="add-criterion-form">
                            @csrf
                            <input type="hidden" name="evaluation_id" value="{{ $evaluation->id }}">
                            <td><input type="text" name="label" class="form-control form-control-sm" required></td>
                            <td>
                                <select name="input_type" class="form-control form-control-sm" required>
                                    <option value="rating">{{ __('inputs.rating') }}</option>
                                    <option value="text">{{ __('inputs.text') }}</option>
                                    <option value="yesno">{{ __('inputs.yesno') }}</option>
                                </select>
                            </td>
                            <td><input type="number" name="weight" value="1" class="form-control form-control-sm"></td>
                            <td><input type="number" name="order" value="0" class="form-control form-control-sm"></td>
                            <td>
                                <select name="required" class="form-control form-control-sm">
                                    <option value="1">{{ __('Yes') }}</option>
                                    <option value="0">{{ __('No') }}</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-primary">{{ __('actions.add') }}</button>
                            </td>
                        </form>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Add new criterion
    $('#add-criterion-form').on('submit', function (e) {
        e.preventDefault();
        $.post("{{ route('admin.criteria.store') }}", $(this).serialize())
            .done(function () {
                location.reload();
            })
            .fail(function (xhr) {
                Swal.fire('Error', xhr.responseJSON.message || 'Failed to add criterion', 'error');
            });
    });

    // Update criterion
    $('.save-btn').on('click', function () {
       
        let id = $(this).data('id');
        let row = $('#criterion-' + id);


 
        $.post(`/admin/criteria/${id}/update`, {
            _token: '{{ csrf_token() }}',
            label: row.find('.label-input').val(),
            input_type: row.find('.input_type').val(),
            weight: row.find('.weight').val(),
            order: row.find('.order').val(),
            required: row.find('.required').val(),
        })
        .done(function () {
            Swal.fire('Success', 'Criterion updated', 'success');
        })
        .fail(function (xhr) {
    console.error('AJAX Error:', xhr); // Full raw response

    let message = 'Update failed';

    // Try to parse JSON safely
    if (xhr.responseJSON && xhr.responseJSON.message) {
        message = xhr.responseJSON.message;
    } else if (xhr.responseText) {
        // Fallback for HTML errors
        message = xhr.responseText;
    }

    Swal.fire({
        title: 'Error',
        html: `<pre style="text-align:left;">${message}</pre>`,
        icon: 'error',
        width: 600,
        customClass: {
            popup: 'text-left'
        }
    });
});
    });

    // Delete criterion
    $('.delete-btn').on('click', function () {
        let id = $(this).data('id');

        Swal.fire({
            title: '{{ __("Are you sure?") }}',
            text: "{{ __('This will delete the criterion permanently.') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{ __("Yes, delete it!") }}',
            cancelButtonText: '{{ __("Cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`/admin/criteria/${id}/delete`, {
                    _token: '{{ csrf_token() }}'
                })
                .done(function () {
                    $('#criterion-' + id).remove();
                    Swal.fire('{{ __("Deleted!") }}', '{{ __("The criterion has been deleted.") }}', 'success');
                })
                .fail(function () {
                    Swal.fire('Error', 'Failed to delete', 'error');
                });
            }
        });
    });
});
</script>
@endsection
