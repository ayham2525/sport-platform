<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>{{ __('attendance.user') }}</th>
            <th>{{ __('attendance.role') }}</th>
            <th>{{ __('attendance.branch') }}</th>
            <th>{{ __('attendance.scanned_at') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($attendances as $attendance)
        <tr>
            <td>{{ $attendance->id }}</td>
            <td>{{ $attendance->user->name ?? '-' }}</td>
            <td>{{ $attendance->user->role ?? '-' }}</td>
            <td>{{ $attendance->branch->name_ar ?? $attendance->branch->name ?? '-' }}</td>
            <td>{{ $attendance->scanned_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
