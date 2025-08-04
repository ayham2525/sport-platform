@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="fas fa-home mr-1"></i> {{ __('item.titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ __('item.titles.items') }}</span>
        </li>
    </ul>
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-boxes mr-1"></i> {{ __('item.titles.items') }}
            </h3>
            <div class="card-toolbar">
                @if (PermissionHelper::hasPermission('create', App\Models\Item::MODEL_NAME))
                    <a href="{{ route('admin.items.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus mr-1"></i> {{ __('item.actions.add') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('item.fields.name_en') }}</th>
                            <th>{{ __('item.fields.name_ar') }}</th>
                            <th>{{ __('item.fields.system') }}</th>
                            <th>{{ __('item.fields.price') }}</th>
                            <th>{{ __('item.fields.currency') }}</th>
                            <th>{{ __('item.fields.active') }}</th>
                            <th>{{ __('item.fields.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $index => $item)
                            <tr>
                               <td>{{ $index + $items->firstItem() }}</td>

                                <td>{{ $item->name_en }}</td>
                                <td>{{ $item->name_ar }}</td>
                                <td>{{ $item->system->name ?? '-' }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->currency->code ?? '-' }}</td>
                                <td>
                                    @if ($item->active)
                                        <i class="fas fa-check"></i>
                                    @else
                                        <i class="fas fa-times"></i>
                                    @endif
                                </td>
                                <td nowrap>
                                    @if (PermissionHelper::hasPermission('update', App\Models\Item::MODEL_NAME))
                                    <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('item.actions.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    @if (PermissionHelper::hasPermission('delete', App\Models\Item::MODEL_NAME))
                                    <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="d-inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('item.actions.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                      @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">{{ __('item.messages.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $items->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
