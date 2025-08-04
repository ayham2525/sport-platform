<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
        h2 { margin-bottom: 20px; }
        h4 { margin-top: 30px; margin-bottom: 10px; }
        p { margin: 3px 0; }
    </style>
</head>
<body>
    <h2>Payment Invoice #{{ $payment->id }}</h2>

    <p><strong>Date:</strong> {{ $payment->payment_date }}</p>
    <p><strong>Player:</strong> {{ $payment->player->user->name ?? '-' }}</p>
    <p><strong>Program:</strong> {{ $payment->program->name_en ?? '-' }}</p>
    <p><strong>Payment Method:</strong> {{ $payment->paymentMethod->name ?? '-' }}</p>
    <p><strong>Currency:</strong> {{ $payment->currency }}</p>
    <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
    <p><strong>Note:</strong> {{ $payment->note }}</p>

    @if ($payment->classes->count())
        <h4>Classes</h4>
        <table>
            <thead>
                <tr>
                    <th>Academy</th>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Location</th>
                    <th>Coach</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payment->classes as $class)
                    <tr>
                        <td>{{ $class->academy->name_en ?? '-' }}</td>
                        <td>{{ $class->day }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($class->end_time)->format('H:i') }}
                        </td>
                        <td>{{ $class->location ?? '-' }}</td>
                        <td>{{ $class->coach_name ?? '-' }}</td>
                        <td>{{ $class->pivot->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if ($items)
        <h4>Items</h4>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $itemsMap[$item['item_id']] ?? 'Item #' . $item['item_id'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h4>Totals</h4>
    <table>
        <tbody>
            <tr>
                <th>Base Price</th>
                <td>{{ number_format($payment->base_price, 2) }}</td>
            </tr>
            <tr>
                <th>VAT %</th>
                <td>{{ number_format($payment->vat_percent, 2) }}</td>
            </tr>
            <tr>
                <th>Total Price</th>
                <td>{{ number_format($payment->total_price, 2) }}</td>
            </tr>
            <tr>
                <th>Paid Amount</th>
                <td>{{ number_format($payment->paid_amount, 2) }}</td>
            </tr>
            <tr>
                <th>Remaining Amount</th>
                <td>{{ number_format($payment->remaining_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
