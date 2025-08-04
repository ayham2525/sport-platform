@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('content')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ $program->name_en }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-home mr-1"></i> {{ __('program.titles.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.programs.index') }}" class="text-muted">
            <i class="la la-book-open mr-1"></i> {{ __('program.titles.programs') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <span class="text-muted">
            <i class="la la-eye mr-1"></i> {{ $program->name_en }}
        </span>
    </li>
</ul>
@endsection


<div class="d-flex flex-column-fluid">
    <div class="container">

        {{-- Program Details --}}
        <div class="card card-custom mb-5">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="la la-info-circle mr-1"></i> {{ __('program.titles.program_details') }}
                </h3>
            </div>
            <div class="card-body row">
                <div class="col-md-6">
                    <p><i class="la la-font mr-1 text-muted"></i> <strong>{{ __('program.fields.name') }}:</strong> {{ $program->name_en }}</p>
                    <p><i class="la la-university mr-1 text-muted"></i> <strong>{{ __('program.fields.academy') }}:</strong> {{ $program->academy->name_en ?? '-' }}</p>
                    <p><i class="la la-code-branch mr-1 text-muted"></i> <strong>{{ __('program.fields.branch') }}:</strong> {{ $program->branch->name ?? '-' }}</p>
                    <p><i class="la la-cogs mr-1 text-muted"></i> <strong>{{ __('program.fields.system') }}:</strong> {{ $program->system->name ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><i class="la la-dollar-sign mr-1 text-muted"></i> <strong>{{ __('program.fields.price') }}:</strong> {{ $program->price }} {{ $program->currency }}</p>
                    <p><i class="la la-percent mr-1 text-muted"></i> <strong>{{ __('program.fields.vat') }}:</strong> {{ $program->vat }}%</p>
                    <p><i class="la la-list-ol mr-1 text-muted"></i> <strong>{{ __('program.fields.class_count') }}:</strong> {{ $program->class_count }}</p>
                    <p><i class="la la-calendar mr-1 text-muted"></i> <strong>{{ __('program.fields.days') }}:</strong>
                        @foreach($program->days as $day)
                        <span class="badge badge-info">{{ __('days.' . strtolower($day->day)) }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
        @if (PermissionHelper::hasPermission('view', App\Models\ClassModel::MODEL_NAME))
        {{-- Classes List --}}
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="la la-chalkboard-teacher mr-1"></i> {{ __('program.titles.classes') }}
                </h3>
                <div class="card-toolbar">
                    @if (PermissionHelper::hasPermission('create', App\Models\ClassModel::MODEL_NAME))
                    <a href="{{ route('admin.classes.create', $program->id) }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus"></i> {{ __('program.actions.add_class') }}
                    </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @if ($program->classes->count() > 0)
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="la la-calendar-alt mr-1 text-muted"></i> {{ __('program.fields.day') }}</th>
                                <th><i class="la la-clock mr-1 text-muted"></i> {{ __('program.fields.start_time') }}</th>
                                <th><i class="la la-clock mr-1 text-muted"></i> {{ __('program.fields.end_time') }}</th>
                                <th><i class="la la-user mr-1 text-muted"></i> {{ __('program.fields.coach_name') }}</th>
                                <th><i class="la la-map-marker mr-1 text-muted"></i> {{ __('program.fields.location') }}</th>
                                <th><i class="la la-tools mr-1 text-muted"></i> {{ __('actions.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($program->classes as $index => $class)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ __('days.' . strtolower($class->day)) }}</td>
                                <td>{{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}</td>
                                <td>{{ $class->coach_name ?? '-' }}</td>
                                <td>{{ $class->location ?? '-' }}</td>
                                <td>
                                    @if (PermissionHelper::hasPermission('update', App\Models\ClassModel::MODEL_NAME))
                                    <a href="{{ route('admin.classes.edit', ['program' => $program->id, 'class' => $class->id]) }}" class="btn btn-sm">
                                        <i class="la la-edit"></i>
                                    </a>
                                    @endif

                                    @if (PermissionHelper::hasPermission('delete', App\Models\ClassModel::MODEL_NAME))
                                    <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted">
                    <i class="la la-info-circle mr-1"></i> {{ __('program.messages.no_classes_found') }}
                </p>
                @endif
            </div>
        </div>
        @endif


    </div>
</div>


@endsection
@push('scripts')
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ __("messages.confirm_delete") }}'
                , text: '{{ __("messages.delete_warning") }}'
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#d33'
                , cancelButtonColor: '#6c757d'
                , confirmButtonText: '{{ __("actions.confirm") }}'
                , cancelButtonText: '{{ __("actions.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

</script>
@endpush
 --}}
