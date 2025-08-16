@php use App\Helpers\PermissionHelper; @endphp
{{-- Totals --}}
<div class="row">
    @php
        $totalCards = [
            ['key' => 'base_total',      'val' => $totals->base_price_sum ?? 0,  'icon' => 'la la-layer-group',   'color' => 'primary'],
            ['key' => 'discount_total',  'val' => $totals->discount_sum ?? 0,    'icon' => 'la la-tag',           'color' => 'warning'],
            ['key' => 'vat_total',       'val' => $totals->vat_sum ?? 0,         'icon' => 'la la-percentage',    'color' => 'info'],
            ['key' => 'grand_total',     'val' => $totals->total_sum ?? 0,       'icon' => 'la la-calculator',    'color' => 'dark'],
            ['key' => 'paid_total',      'val' => $totals->paid_sum ?? 0,        'icon' => 'la la-check-circle',  'color' => 'success'],
            ['key' => 'remaining_total', 'val' => $totals->remaining_sum ?? 0,   'icon' => 'la la-clock',         'color' => 'danger'],
        ];
        $mapClass = ['pending'=>'badge badge-secondary','partial'=>'badge badge-warning','paid'=>'badge badge-success'];
    @endphp

    @foreach ($totalCards as $c)
        <div class="col-lg-4 col-xl-4 mb-4">
            <div class="card card-custom bg-{{ $c['color'] }} text-white">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <i class="{{ $c['icon'] }} la-2x text-white"></i>
                    <div class="text-right">
                        <div class="font-weight-bold">{{ __('reports.payments.totals.'.$c['key']) }}</div>
                        <div class="h3 mb-0">{{ number_format($c['val'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Status chips --}}
<div class="mb-4">
    <span class="mr-3">{{ __('reports.payments.status_breakdown') }}:</span>
    @foreach (['pending','partial','paid'] as $s)
        <span class="{{ $mapClass[$s] ?? 'badge badge-light' }} mr-2">
            {{ __('payment.status.'.$s) }}:
            <strong>{{ $statusCounts[$s] ?? 0 }}</strong>
        </span>
    @endforeach
</div>

{{-- Table --}}
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">
            <i class="la la-table mr-1"></i> {{ __('reports.payments.table.title') }}
        </h3>
    </div>
    <div class="card-body">
        @if ($payments->count())
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm report-table">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>{{ __('reports.payments.table.category') }}</th>
                            <th>{{ __('reports.payments.table.status') }}</th>
                            <th>{{ __('reports.payments.table.payment_date') }}</th>
                            <th>{{ __('reports.payments.table.player') }}</th>
                            <th>{{ __('reports.payments.table.program') }}</th>
                            <th>{{ __('reports.payments.table.branch') }}</th>
                            <th>{{ __('reports.payments.table.academy') }}</th>
                            <th>{{ __('reports.payments.table.method') }}</th>
                            <th class="text-right">{{ __('reports.payments.table.base') }}</th>
                            <th class="text-right">{{ __('reports.payments.table.discount') }}</th>
                            <th class="text-right">{{ __('reports.payments.table.vat') }}</th>
                            <th class="text-right">{{ __('reports.payments.table.total') }}</th>
                            <th class="text-right">{{ __('reports.payments.table.paid') }}</th>
                            <th class="text-right">{{ __('reports.payments.table.remaining') }}</th>
                            <th>{{ __('reports.payments.table.currency') }}</th>
                            <th>{{ __('reports.payments.table.reset_number') }}</th>
                              <th>{{ __('payment.fields.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $i => $p)
                            <tr>
                                <td>{{ $payments->firstItem() + $i }}</td>
                                <td>{{ __('payment.categories.' . $p->category) }}</td>
                                <td>
                                    <span class="{{ $mapClass[$p->status] ?? 'badge badge-light' }}">
                                        {{ __('payment.status.' . $p->status) }}
                                    </span>
                                </td>
                                <td>{{ optional($p->payment_date)->format('Y-m-d') }}</td>
                                <td>{{ optional(optional($p->player)->user)->name ?? '-' }}</td>
                                <td>{{ optional($p->program)->name ?? '-' }}</td>
                                <td>{{ optional($p->branch)->name ?? '-' }}</td>
                                <td>{{ optional($p->academy)->name ?? '-' }}</td>
                                <td>{{ optional($p->paymentMethod)->name ?? '-' }}</td>
                                <td class="text-right">{{ number_format($p->base_price, 2) }}</td>
                                <td class="text-right">{{ number_format($p->discount, 2) }}</td>
                                <td class="text-right">{{ number_format($p->vat_amount, 2) }}</td>
                                <td class="text-right font-weight-bold">{{ number_format($p->total_price, 2) }}</td>
                                <td class="text-right text-success">{{ number_format($p->paid_amount, 2) }}</td>
                                <td class="text-right text-danger">{{ number_format($p->remaining_amount, 2) }}</td>
                                <td>{{ $p->currency }}</td>
                                <td>{{ $p->reset_number ?? '-' }}</td>
                                <td>
                                     @if (PermissionHelper::hasPermission('update', App\Models\Player::MODEL_NAME))
                            <a href="{{ route('admin.payments.edit', $p->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('payment.actions.edit') }}">
                                <i class="la la-edit"></i>
                            </a>
                            @endif
                             <a href="{{ route('admin.payments.invoice', $p->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('payment.actions.invoice') }}">
                                <i class="la la-file-pdf"></i>
                            </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    {{ __('reports.table.showing') }}
                    {{ $payments->firstItem() }}–{{ $payments->lastItem() }}
                    {{ __('reports.table.of') }} {{ $payments->total() }}
                </div>
                {{ $payments->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        @else
            <p class="text-muted mb-0">{{ __('reports.table.no_results') }}</p>
        @endif
    </div>
</div>
