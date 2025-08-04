@extends('layouts.app')

@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}" class="text-muted">
                <i class="fas fa-home mr-1"></i> {{ __('exchange_rate.titles.dashboard') }}
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="text-muted">{{ __('exchange_rate.titles.list') }}</span>
        </li>
    </ul>
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">{{ __('exchange_rate.titles.list') }}</h3>
            <a href="{{ route('admin.exchange-rates.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-1"></i> {{ __('exchange_rate.actions.create') }}
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('exchange_rate.fields.base_currency') }}</th>
                            <th>{{ __('exchange_rate.fields.target_currency') }}</th>
                            <th>{{ __('exchange_rate.fields.rate') }}</th>
                            <th>{{ __('exchange_rate.fields.fetched_at') }}</th>
                            <th>{{ __('exchange_rate.actions.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rates as $index => $rate)
                            <tr>
                                <td>{{ $index + $rates->firstItem() }}</td>
                                <td>{{ $rate->base_currency }}</td>
                                <td>{{ $rate->target_currency }}</td>
                                <td>{{ $rate->rate }}</td>
                                <td>{{ $rate->fetched_at }}</td>
                                <td>
                                    <a href="{{ route('admin.exchange-rates.edit', $rate->id) }}" class="btn btn-sm btn-clean" title="{{ __('exchange_rate.actions.edit') }}">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <form class="d-none" action="{{ route('admin.exchange-rates.destroy', $rate->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-clean delete-button" title="{{ __('exchange_rate.actions.delete') }}">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ __('exchange_rate.messages.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $rates->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
