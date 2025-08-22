@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.programs') }}</h5>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="la la-home mr-1"></i> {{ __('titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">
                <i class="la la-book-open mr-1"></i> {{ __('titles.programs') }}
            </span>
        </li>
    </ul>
@endsection


@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ __('titles.programs') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('titles.programs_management') }}</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                @if (PermissionHelper::hasPermission('create', App\Models\Program::MODEL_NAME))
                    <a href="{{ route('admin.programs.create') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus"></i> {{ __('titles.new_record') }}
                    </a>
                @endif
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Search --}}
                <form method="GET" action="{{ route('admin.programs.index') }}" class="mb-5">
                    <div class="row">
                        <div class="col-md-4 col-12 mb-3">
                            <input type="text" name="search" class="form-control"
                                   placeholder="{{ __('columns.program_name') }}" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i> {{ __('actions.search') }}
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Programs Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('columns.program_name') }}</th>
                                <th>{{ __('columns.academy') }}</th>
                                <th>{{ __('columns.price') }}</th>
                                <th>{{ __('columns.currency') }}</th>
                                <th>{{ __('columns.active') }}</th>
                                <th>{{ __('columns.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($programs as $index => $program)

                                <tr>
                                    <td>{{ $index + $programs->firstItem() }}</td>
                                    <td>{{ $program->name_en }}</td>
                                    <td>{{ $program->academy->name_en ?? '-' }}</td>
                                    <td>{{ number_format($program->price, 2) }}</td>
                                    <td>{{ $program->currency }}</td>
                                    <td>
                                        @if ($program->is_active)
                                            <span class="label label-inline label-success">{{ __('labels.active') }}</span>
                                        @else
                                            <span class="label label-inline label-danger">{{ __('labels.inactive') }}</span>
                                        @endif
                                    </td>
                                    <td nowrap>
                                    @if (PermissionHelper::hasPermission('view', App\Models\Program::MODEL_NAME))
                                        <a href="{{ route('admin.programs.show', $program->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.view') }}">
                                            <i class="la la-eye"></i>
                                        </a>
                                    @endif
                                     @if (PermissionHelper::hasPermission('update', App\Models\Program::MODEL_NAME))
                                        <a href="{{ route('admin.programs.edit', $program->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.edit') }}">
                                            <i class="la la-edit"></i>
                                        </a>
                                    @endif
                                     @if (PermissionHelper::hasPermission('delete', App\Models\Program::MODEL_NAME))
                                        <form action="{{ route('admin.programs.destroy', $program->id) }}" method="POST" class="d-inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('actions.delete') }}">
                                                <i class="la la-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                        <a href="{{ route('admin.classes.create', $program->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.add_class') }}">
                                            <i class="la la-plus-circle"></i>
                                        </a>
                                       <a href="{{ route('admin.programs.players', $program->id) }}"
                                        class="btn btn-sm btn-clean btn-icon"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="{{ __('titles.view_players') }}">
                                            <i class="la la-users"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $programs->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
     $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');
            Swal.fire({
                title: '{{ __('messages.confirm_delete_title') }}',
                text: '{{ __('messages.confirm_delete_text') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('messages.yes_delete') }}',
                cancelButtonText: '{{ __('messages.cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

