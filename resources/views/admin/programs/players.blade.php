@php use App\Helpers\PermissionHelper; @endphp
@extends('layouts.app')

@section('page_title')
<h5 class="text-dark font-weight-bold my-2 mr-5">

    {{ __('titles.players') }} - {{ $program->name_en  }}
</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="fas fa-home mr-1"></i> {{ __('titles.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.programs.index') }}" class="text-muted">
            <i class="fas fa-list mr-1"></i> {{ __('titles.programs') }}
        </a>
    </li>


    <li class="breadcrumb-item text-muted">
        <i class="fas fa-users mr-1"></i> {{ __('titles.players') }}
    </li>
</ul>
@endsection


@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-title">{{ __('titles.players') }}</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-nowrap ">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th><i class="la la-user mr-1"></i> {{ __('player.fields.name') }}</th>
                            <th><i class="la la-barcode mr-1"></i> {{ __('player.fields.code') }}</th>
                            <th><i class="la la-futbol mr-1"></i> {{ __('player.fields.sport') }}</th>
                            <th><i class="la la-code-branch mr-1"></i> {{ __('player.fields.branch') }}</th>
                            <th><i class="la la-university mr-1"></i> {{ __('player.fields.academy') }}</th>
                            <th><i class="la la-venus-mars mr-1"></i> {{ __('player.fields.gender') }}</th>
                             <th>{{ __('player.fields.actions') }}</th>
                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($players as $index => $player)
                        <tr>
                            <td>{{ $index + $players->firstItem() }}</td>
                            <td>{{ $player->user->name ?? '-' }}</td>
                            <td>{{ $player->player_code }}</td>
                            <td>{{ $player->sport->name_en ?? '-' }}</td>
                            <td>{{ $player->branch->name ?? '-' }}</td>
                            <td>{{ $player->academy->name_en ?? '-' }}</td>
                          <td>{{ $player->gender ? __('player.fields.'.$player->gender) : __('player.fields.unspecified') }}</td>
                            <td>
                                 @if (PermissionHelper::hasPermission('view', App\Models\Player::MODEL_NAME))

                    <a href="{{ route('admin.players.show', $player->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('player.actions.view') }}">
                        <i class="la la-eye"></i>
                    </a>
                    @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $players->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

