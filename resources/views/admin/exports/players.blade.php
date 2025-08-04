<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Player Code</th>
            <th>Sport</th>
            <th>Branch</th>
            <th>Academy</th>
            <th>Nationality</th>
            <th>Gender</th>
            <th>Created At</th>
            <th>Payment Start Date</th>
            <th>Payment End Date</th>
            <th>Payment Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($players as $index => $player)
        @php
        $latestPayment = $player->payments->sortByDesc('end_date')->first();
        $now = \Carbon\Carbon::now();
        $status = 'No Payment';

        if ($latestPayment && $latestPayment->end_date) {
        $status = $now->lt($latestPayment->end_date) ? 'Active' : 'Expired';
        }
        @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $player->user->name ?? '-' }}</td>
            <td>{{ $player->player_code ?? '-' }}</td>
            <td>{{ $player->sport->name_en ?? '-' }}</td>
            <td>{{ $player->branch->name ?? '-' }}</td>
            <td>{{ $player->academy->name_en ?? '-' }}</td>
            <td>{{ $player->nationality->name_en ?? '-' }}</td>
            <td>{{ $player->gender }}</td>
            <td>{{ $player->user->created_at->format('d/m/Y') ?? '-' }}</td>
            <td>
                {{ $latestPayment && $latestPayment->start_date ? $latestPayment->start_date->format('d/m/Y') : '-' }}
            </td>
            <td>
                {{ $latestPayment && $latestPayment->end_date ? $latestPayment->end_date->format('d/m/Y') : '-' }}
            </td>
            <td>{{ $status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
