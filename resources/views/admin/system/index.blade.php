@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('systems.title') }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-home"></i> {{ __('systems.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        <i class="la la-cogs"></i> {{ __('systems.title') }}
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{ __('systems.title') }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('systems.subtitle') }}</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    @if (PermissionHelper::hasPermission('create', App\Models\System::MODEL_NAME))
                    <a href="{{ route('admin.systems.create') }}" class="btn btn-primary font-weight-bolder">
                        <i class="la la-plus-circle"></i> {{ __('systems.new') }}
                    </a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="{{ __('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

              <div class="table-responsive">
    <table class="table table-bordered table-hover table-checkable" id="systems-table">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>{{ __('systems.name') }}</th>
                <th>{{ __('systems.description') }}</th>
                <th>{{ __('systems.created_at') }}</th>
                <th>{{ __('systems.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($systems as $index => $system)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $system->name }}</td>
                    <td>{{ Str::limit($system->description, 50) }}</td>
                    <td>{{ $system->created_at->format('Y-m-d') }}</td>
                    <td>
                        @if (PermissionHelper::hasPermission('update', App\Models\System::MODEL_NAME))
                            <a href="{{ route('admin.systems.edit', $system->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('Edit') }}">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                        @if (PermissionHelper::hasPermission('create', App\Models\System::MODEL_NAME))
                            <form action="{{ route('admin.systems.destroy', $system->id) }}" method="POST" class="delete-form d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('Delete') }}">
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


            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        $('#systems-table').DataTable({
            paging: true
            , searching: true
            , responsive: true
            , autoWidth: false
            , language: {
                searchPlaceholder: "{{ __('systems.search') }}"
                , paginate: {
                    previous: "<i class='la la-angle-left'></i>"
                    , next: "<i class='la la-angle-right'></i>"
                }
            }
        });

        $('.delete-button').click(function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: '{{ __("Are you sure?") }}'
                , text: '{{ __("This will delete the system permanently!") }}'
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#f64e60'
                , cancelButtonColor: '#c4c4c4'
                , confirmButtonText: '{{ __("Yes, delete it!") }}'
                , customClass: {
                    confirmButton: 'btn btn-danger'
                    , cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

</script>
@endpush

