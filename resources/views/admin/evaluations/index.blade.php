@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.evaluations') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('titles.dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('titles.evaluations') }}</span>
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">{{ __('titles.evaluations') }}</h3>
                <a href="{{ route('admin.evaluations.create') }}" class="btn btn-primary">
                    <i class="la la-plus"></i> {{ __('actions.create') }}
                </a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('columns.title') }}</th>
                                <th>{{ __('columns.system') }}</th>
                                <th>{{ __('columns.type') }}</th>
                                <th>{{ __('columns.period') }}</th>
                                <th>{{ __('columns.active') }}</th>
                                <th>{{ __('columns.created_by') }}</th>
                                <th>{{ __('columns.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($evaluations as $index => $evaluation)
                            <tr>
                                <td>{{ $index + $evaluations->firstItem() }}</td>
                                <td>{{ $evaluation->title }}</td>
                                <td>{{ optional($evaluation->system)->name }}</td>
                                <td>{{ ucfirst($evaluation->type) }}</td>
                                <td>
                                    @if($evaluation->type === 'general' || !$evaluation->start_date || !$evaluation->end_date)
                                    —
                                    @else
                                    {{ \Carbon\Carbon::parse($evaluation->start_date)->format('Y-m-d') }}
                                    →
                                    {{ \Carbon\Carbon::parse($evaluation->end_date)->format('Y-m-d') }}
                                    @endif
                                </td>

                                <td>
                                    <span class="badge {{ $evaluation->is_active ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $evaluation->is_active ? __('Yes') : __('No') }}
                                    </span>
                                </td>
                                <td>{{ optional($evaluation->creator)->name }}</td>
                                <td>
                                    <a href="{{ route('admin.coach_evaluation.create', $evaluation->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('Evaluate Coaches') }}">
                                        <i class="la la-user-check"></i>
                                    </a>

                                    <a href="{{ route('admin.evaluations.edit', $evaluation->id) }}" class="btn btn-sm btn-clean btn-icon">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.evaluations.destroy', $evaluation->id) }}" method="POST" class="d-inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('actions.delete') }}">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $evaluations->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert delete confirmation --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: '{{ __("messages.confirm_delete_title") }}'
                , text: '{{ __("messages.confirm_delete_text") }}'
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#e3342f'
                , cancelButtonColor: '#6c757d'
                , confirmButtonText: '{{ __("messages.yes_delete") }}'
                , cancelButtonText: '{{ __("messages.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });
        });
    });

</script>
@endsection
