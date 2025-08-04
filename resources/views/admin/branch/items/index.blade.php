@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">
        {{ __('branch.view_items') }} - {{ $branch->name }}
    </h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-home"></i> {{ __('branch.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.branches.index') }}" class="text-muted">
            <i class="la la-building"></i> {{ __('branch.title') }}
        </a>
    </li>
    <li class="breadcrumb-item text-muted">
        {{ __('branch.view_items') }}
    </li>
</ul>
@endsection

@section('content')
<div class="container">
    <form method="GET" class="form-inline mb-4">
        <input type="text" name="search" class="form-control mr-2" value="{{ request('search') }}" placeholder="{{ __('branch.search_placeholder') }}">
        <button class="btn btn-primary mr-2" type="submit">
            <i class="la la-search"></i> {{ __('branch.search') }}
        </button>
        @if (PermissionHelper::hasPermission('create', App\Models\BranchItem::MODEL_NAME))
        <button class="btn btn-success" data-toggle="modal" data-target="#addItemModal" type="button">
            <i class="la la-plus-circle"></i> {{ __('branch.add_item') }}
        </button>
        @endif;
    </form>

    @if (session('success'))
        <div class="alert alert-success">
            <i class="la la-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="la la-exclamation-triangle"></i> {{ __('Whoops! Something went wrong.') }}
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-white text-gray-800">
            <strong>{{ __('branch.view_items') }}</strong>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __('branch.item') }}</th>
            <th>{{ __('branch.min_value') }}</th>
            <th>{{ __('branch.max_value') }}</th>
            <th>{{ __('branch.professional') }}</th>
            <th>{{ __('branch.notes') }}</th>
            <th>{{ __('branch.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $index => $item)
            <tr>
                <td>{{ $items->firstItem() + $index }}</td>
                <td>{{ app()->getLocale() === 'ar' ? $item->name_ar : $item->name_en }}</td>
                <td>{{ $item->pivot->min_value }}</td>
                <td>{{ $item->pivot->max_value }}</td>
                <td>
                    <span class="badge badge-{{ $item->pivot->is_professional ? 'info' : 'secondary' }}">
                        {{ $item->pivot->is_professional ? __('branch.yes') : __('branch.no') }}
                    </span>
                </td>
                <td>{{ $item->pivot->notes }}</td>
                <td>
                    {{-- Edit Button --}}
                    @if (PermissionHelper::hasPermission('update', App\Models\BranchItem::MODEL_NAME))
                    <button type="button" class="btn btn-sm btn-clean btn-icon edit-item"
                        data-toggle="tooltip" title="{{ __('branch.edit') }}"
                        data-item-id="{{ $item->id }}"
                        data-min="{{ $item->pivot->min_value }}"
                        data-max="{{ $item->pivot->max_value }}"
                        data-notes="{{ $item->pivot->notes }}"
                        data-professional="{{ $item->pivot->is_professional }}">
                        <i class="la la-edit"></i>
                    </button>
                    @endif



                    {{-- Delete Button --}}
                    @if (PermissionHelper::hasPermission('delete', App\Models\BranchItem::MODEL_NAME))
                    <form action="{{ route('admin.branches.items.destroy', [$branch->id, $item->id]) }}" method="POST" class="d-inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('branch.delete') }}">
                            <i class="la la-trash"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted">
                    {{ __('branch.no_items_found') }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Pagination with Bootstrap 5 --}}
<div class="mt-3">
    {{ $items->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>

        </div>
    </div>
</div>

{{-- Add Item Modal --}}
<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.branches.items.store', $branch->id) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">{{ __('branch.add_item') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('branch.cancel') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>{{ __('branch.select_item') }}</label>
                   <select name="item_id" class="form-control" required>
    <option value="">{{ __('branch.select') }}</option>
    @foreach ($availableItems as $id => $name)
        <option value="{{ $id }}">{{ $name }}</option>
    @endforeach
</select>
                </div>
                <div class="form-group">
                    <label>{{ __('branch.min_value') }}</label>
                    <input type="number" name="min_value" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>{{ __('branch.max_value') }}</label>
                    <input type="number" name="max_value" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>{{ __('branch.professional') }}</label>
                    <select name="is_professional" class="form-control" required>
                        <option value="0">{{ __('branch.no') }}</option>
                        <option value="1">{{ __('branch.yes') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ __('branch.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="la la-save"></i> {{ __('branch.save') }}
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('branch.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>
{{-- Edit Item Modal --}}
<div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editItemForm" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">{{ __('branch.edit_item') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('branch.cancel') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                {{-- Hidden item ID --}}
                <input type="hidden" name="item_id" id="edit-item-id">

                <div class="form-group">
                    <label>{{ __('branch.min_value') }}</label>
                    <input type="number" name="min_value" id="edit-min-value" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>{{ __('branch.max_value') }}</label>
                    <input type="number" name="max_value" id="edit-max-value" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>{{ __('branch.professional') }}</label>
                    <select name="is_professional" id="edit-professional" class="form-control" required>
                        <option value="0">{{ __('branch.no') }}</option>
                        <option value="1">{{ __('branch.yes') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ __('branch.notes') }}</label>
                    <textarea name="notes" id="edit-notes" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="la la-save"></i> {{ __('branch.update') }}
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('branch.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>


     $(document).on('click', '.edit-item', function () {
        const itemId = $(this).data('item-id');
        const min = $(this).data('min');
        const max = $(this).data('max');
        const notes = $(this).data('notes');
        const isProfessional = $(this).data('professional');

        $('#edit-item-id').val(itemId);
        $('#edit-min-value').val(min);
        $('#edit-max-value').val(max);
        $('#edit-notes').val(notes);
        $('#edit-professional').val(isProfessional);

        // Set form action
        const action = `{{ route('admin.branches.items.update', ['branch' => $branch->id, 'item' => ':itemId']) }}`.replace(':itemId', itemId);
        $('#editItemForm').attr('action', action);

        $('#editItemModal').modal('show');
    });

      $(document).on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"]', function () {
        $(this).closest('.modal').modal('hide');
    });
    $(document).on('click', '.delete-button', function (e) {
        e.preventDefault();
        const form = $(this).closest('form');

        Swal.fire({
            title: '{{ __("branch.delete_confirm_title") }}',
            text: '{{ __("branch.delete_confirm_text") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f64e60',
            cancelButtonColor: '#c4c4c4',
            confirmButtonText: '{{ __("branch.delete_confirm_yes") }}',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
@endpush

