@extends('layouts.app')

@section('page_title')
    <h5 class="text-dark font-weight-bold my-2 mr-5">{{ __('branch.players_in', ['branch' => $branch->name]) }}</h5>
@endsection

@section('breadcrumb')
<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
    <li class="breadcrumb-item">
        <a href="{{ route('admin.dashboard') }}" class="text-muted">
            <i class="la la-home mr-1"></i> {{ __('branch.dashboard') }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.branches.index') }}" class="text-muted">
            <i class="la la-building mr-1"></i> {{ __('branch.title') }}
        </a>
    </li>
    <li class="breadcrumb-item text-primary">
        <i class="la la-map-marker-alt mr-1"></i> {{ $branch->name }}
    </li>
</ul>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="card-label">{{ __('branch.players_in', ['branch' => $branch->name]) }}
                        <span class="d-block text-muted pt-2 font-size-sm">{{ __('branch.management') }}</span>
                    </h3>
                </div>
            </div>

            <div class="card-body">
                <div class="mb-4">
                    <input type="text" id="search-input" class="form-control" placeholder="{{ __('branch.search_placeholder') }}">
                </div>

                <div class="table-responsive">
                    <table class="table table-separate table-head-custom table-checkable" id="players-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('branch.name') }}</th>
                                <th>{{ __('branch.email') }}</th>
                                <th>{{ __('branch.birth_date') }}</th>
                                <th>{{ __('branch.sport') }}</th>
                                <th>{{ __('branch.created_at') }}</th>
                                <th>{{ __('branch.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="players-body">
                            {{-- Loaded via AJAX --}}
                        </tbody>
                    </table>
                </div>

                <div id="pagination" class="mt-4 text-center"></div>
            </div>
        </div>
    </div>
</div>
@endsection


<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input');
    const tbody = document.getElementById('players-body');
    const paginationContainer = document.getElementById('pagination');
    let timeout = null;

    function loadPlayers(query = '', page = 1) {
        fetch(`{{ route('admin.branches.players', $branch->id) }}?search=${encodeURIComponent(query)}&page=${page}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            tbody.innerHTML = '';

            if (!data.players.length) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center">{{ __('branch.no_players') }}</td></tr>`;
                paginationContainer.innerHTML = '';
                return;
            }

            data.players.forEach((player, index) => {
                tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${player.name ?? '-'}</td>
                        <td>${player.email ?? '-'}</td>
                        <td>${player.birth_date ?? '-'}</td>
                        <td>${player.sport ?? '-'}</td>
                        <td>${player.created_at ?? '-'}</td>
                        <td>
                            <a href="/admin/players/${player.id}" class="btn btn-sm btn-clean btn-icon" title="{{ __('branch.view') }}">
                                <i class="la la-eye"></i>
                            </a>
                        </td>
                    </tr>
                `;
            });

            renderPagination(data.pagination, query);
        })
        .catch(error => {
            console.error("Error loading players:", error);
        });
    }

    function renderPagination(pagination, query) {
        paginationContainer.innerHTML = '';
        for (let i = 1; i <= pagination.last_page; i++) {
            const btn = document.createElement('button');
            btn.className = `btn btn-sm mx-1 ${i === pagination.current_page ? 'btn-primary' : 'btn-light'}`;
            btn.textContent = i;
            btn.onclick = () => loadPlayers(query, i);
            paginationContainer.appendChild(btn);
        }
    }

    searchInput.addEventListener('keyup', function () {
        clearTimeout(timeout);
        const query = this.value;
        if (query.length >= 3 || query.length === 0) {
            timeout = setTimeout(() => {
                loadPlayers(query);
            }, 300);
        }
    });

    loadPlayers(); // Initial load
});
</script>


