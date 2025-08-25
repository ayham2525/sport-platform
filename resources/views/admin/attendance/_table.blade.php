<div class="attendance-table" data-current-page="{{ $records->currentPage() }}">
<div class="table-responsive">
<table class="table table-bordered table-hover table-nowrap">
    <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>{{ __('attendance.fields.user') }}</th>
            <th>{{ __('attendance.fields.role') }}</th>
            <th>{{ __('attendance.fields.player') }}</th>
            <th>{{ __('attendance.fields.branch') }}</th>
            <th>{{ __('attendance.fields.scanned_at') }}</th>
            <th>{{ __('player.fields.actions') }}</th>
        </tr>
    </thead>
    <tbody>
    @php
        $canUpdate = \App\Helpers\PermissionHelper::hasPermission('update', \App\Models\Attendance::MODEL_NAME);
        $canDelete = \App\Helpers\PermissionHelper::hasPermission('delete', \App\Models\Attendance::MODEL_NAME);
    @endphp

    @forelse($records as $idx => $a)
        <tr>
            <td>{{ ($records->firstItem() ?? 1) + $idx }}</td>
            <td>{{ optional($a->user)->name ?? '-' }}</td>
            <td>{{ optional($a->user)->role ?? '-' }}</td>
            <td>{{ optional(optional($a->player)->user)->name ?? '-' }}</td>
            <td>{{ optional($a->branch)->name ?? ($a->branch_id ?? '-') }}</td>
            <td>{{ \Carbon\Carbon::parse($a->scanned_at)->format('Y-m-d H:i:s') }}</td>
            <td>
                @if ($canUpdate)
                    <a href="{{ route('admin.attendance.edit', $a->id) }}"
                       class="btn btn-sm btn-clean text-primary"
                       title="{{ __('player.actions.edit') }}">
                        <i class="la la-edit"></i>
                    </a>
                @endif

                @if ($canDelete)
                    <button type="button"
                            class="btn btn-sm btn-clean text-danger ajax-delete"
                            data-url="{{ route('admin.attendance.destroy', $a->id) }}"
                            title="{{ __('player.actions.delete') }}">
                        <i class="la la-trash"></i>
                    </button>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-center text-muted">—</td></tr>
    @endforelse
    </tbody>
</table>
</div>

@if($records->hasPages())
<nav>
    <ul class="pagination">
        <li class="page-item {{ $records->onFirstPage() ? 'disabled' : '' }}">
            <a href="#" class="page-link ajax-page" data-page="{{ $records->currentPage() - 1 }}">&laquo;</a>
        </li>
        @foreach($records->getUrlRange(1, $records->lastPage()) as $p => $url)
            <li class="page-item {{ $p == $records->currentPage() ? 'active' : '' }}">
                <a href="#" class="page-link ajax-page" data-page="{{ $p }}">{{ $p }}</a>
            </li>
        @endforeach
        <li class="page-item {{ $records->currentPage() == $records->lastPage() ? 'disabled' : '' }}">
            <a href="#" class="page-link ajax-page" data-page="{{ $records->currentPage() + 1 }}">&raquo;</a>
        </li>
    </ul>
</nav>
@endif
</div>
