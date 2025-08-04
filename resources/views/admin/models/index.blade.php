@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('content')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('titles.models') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">{{ __('titles.dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">{{ __('titles.models') }}</span>
    </li>
</ul>
@endsection

<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ __('titles.models') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('titles.models_management') }}</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    @if (PermissionHelper::hasPermission('create', App\Models\ModelEntity::MODEL_NAME))
                    <a href="{{ route('admin.models.create') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus"></i> {{ __('titles.new_record') }}
                    </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                {{-- Success message --}}
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Search --}}
                <form method="GET" action="{{ route('admin.models.index') }}" class="mb-5">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="{{ __('columns.name') }} / {{ __('columns.slug') }}" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> {{ __('actions.search') }}
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-separate table-head-custom table-checkable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('columns.name') }}</th>
                                <th>{{ __('columns.slug') }}</th>
                                <th>{{ __('columns.system') }}</th>
                                <th>{{ __('columns.description') }}</th>
                                <th>{{ __('columns.only_admin') }}</th>
                                <th>{{ __('columns.created_at') }}</th>
                                <th>{{ __('columns.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($models as $index => $model)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $model->name }}</td>
                                <td>{{ $model->slug }}</td>
                                <td>{{ $model->system->name ?? '-' }}</td>
                                <td>{{ Str::limit($model->description, 50) }}</td>
                                <td>
                                    @if ($model->only_admin)
                                    <span class="badge badge-danger">{{ __('columns.yes') }}</span>
                                    @else
                                    <span class="badge badge-success">{{ __('columns.no') }}</span>
                                    @endif
                                </td>
                                <td>{{ $model->created_at->format('Y-m-d') }}</td>
                                <td nowrap>
                                    <a href="{{ route('admin.models.show', $model->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.view') }}">
                                        <i class="la la-eye"></i>
                                    </a>
                                    @if (PermissionHelper::hasPermission('update', App\Models\ModelEntity::MODEL_NAME))
                                    <a href="{{ route('admin.models.edit', $model->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('actions.edit') }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                    @endif
                                    @if (PermissionHelper::hasPermission('delete', App\Models\ModelEntity::MODEL_NAME))
                                    <form action="{{ route('admin.models.destroy', $model->id) }}" method="POST" class="d-inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('admin.actions.delete') }}">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </form>
                                    @endif;
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ __('messages.no_data') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $models->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete confirmation --}}
<script>
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: '{{ __("messages.confirm_delete_title") }}'
                , text: '{{ __("messages.confirm_delete_text") }}'
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonText: '{{ __("messages.yes_delete") }}'
                , cancelButtonText: '{{ __("messages.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

</script>

@endsection

