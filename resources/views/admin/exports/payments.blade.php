<table>
    <thead>
        <tr>
            <th>{{ __('payment.fields.player') }}</th>
            <th>{{ __('payment.fields.program') }}</th>
            <th>{{ __('payment.fields.amount') }}</th>
            <th>{{ __('payment.fields.paid') }}</th>
            <th>{{ __('payment.fields.remaining') }}</th>
            <th>{{ __('payment.fields.payment_method') }}</th>
            <th>{{ __('payment.fields.status') }}</th>
            <th>{{ __('payment.fields.created_at') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->player->user->name ?? '' }}</td>
                <td>{{ $payment->program->name_en ?? '' }}</td>
                <td>{{ $payment->total_price }} {{ $payment->currency }}</td>
                <td>{{ $payment->paid_amount }}</td>
                <td>{{ $payment->remaining_amount }}</td>
                <td>{{ $payment->paymentMethod->name ?? '' }}</td>
                <td>{{ __('payment.status.' . $payment->status) }}</td>
                <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
