@php use App\Helpers\PermissionHelper; @endphp
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('columns.name') }}</th>
                <th>{{ __('columns.email') }}</th>
                <th>{{ __('columns.role') }}</th>
                <th>{{ __('columns.language') }}</th>
                <th>{{ __('columns.system') }}</th>
                <th>{{ __('columns.created_at') }}</th>
                <th>{{ __('columns.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
            <tr>
                <td>{{ $index + $users->firstItem() }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->language }}</td>
                <td>{{ optional($user->system)->name ?? '-' }}</td>
                <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    @if (PermissionHelper::hasPermission('update', 'User'))

                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-clean btn-icon">
                        <i class="la la-edit"></i>
                    </a>
                    @endif
                    @if (PermissionHelper::hasPermission('delete', 'User'))

                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline-block delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-clean btn-icon delete-button" title="{{ __('actions.delete') }}">
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
<div class="mt-4">
    {{ $users->withQueryString()->links('pagination::bootstrap-4') }}
</div>

