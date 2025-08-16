
<style>
/* Titles */
.sb-title{ font-weight:600; color:#6c757d; margin-bottom:.5rem; }

/* Chip container */
.sb-chips{ display:flex; flex-wrap:wrap; gap:.5rem; }

/* Base chip */
.chip{
  display:inline-flex; align-items:center; gap:.4rem;
  padding:.35rem .6rem; border-radius:999px;
  font-size:.875rem; line-height:1; user-select:none; white-space:nowrap;
  transition:transform .15s ease, filter .15s ease;
}
.chip:hover{ transform:translateY(-1px); filter:brightness(.95); }

/* Chip parts */
.chip__icon{ font-size:1rem; line-height:1; }
.chip__count{ font-weight:700; padding:.1rem .45rem; border-radius:999px; background:rgba(255,255,255,.65); }

/* Palettes */
.chip--neutral{ background:#e9ecef; color:#495057; }  /* grey */
.chip--purple { background:#6f42c1; color:#fff; }
.chip--amber  { background:#ffc107; color:#212529; }
.chip--blue   { background:#3b82f6; color:#fff; }
.chip--green  { background:#28a745; color:#fff; }
.chip--red    { background:#dc3545; color:#fff; }
.chip--teal   { background:#20c997; color:#fff; }
.chip--indigo { background:#6610f2; color:#fff; }
.chip--pink   { background:#e83e8c; color:#fff; }
.chip--gray   { background:#adb5bd; color:#212529; }

.chip--off{ opacity:.65; }
.chip--on { opacity:1; }

/* Table: keep one line & enable horizontal scroll */



    .table-nowrap td,
    .table-nowrap th {
        white-space: nowrap !important;
        vertical-align: middle !important;
    }
    .table-nowrap td {

        overflow: hidden !important;
        text-overflow: ellipsis !important;
        max-width: 800px !important; /* adjust as needed */
         font-size: 12px !important;
    }

</style>


@if ($uniforms->count())
    @php
        $cards = [
            ['label' => __('uniform_reports.totals.amount_total'),   'val' => $totals->amount_sum ?? 0, 'icon'=>'la la-coins','color'=>'dark'],
            ['label' => __('uniform_reports.totals.quantity_total'), 'val' => $totals->qty_sum ?? 0,    'icon'=>'la la-boxes','color'=>'primary'],
            ['label' => __('uniform_reports.totals.rows_total'),     'val' => $totals->rows_count ?? 0, 'icon'=>'la la-list','color'=>'info'],
        ];

        // Icons & colors by status key
        $mainIcons = [
            'requested'=>'la-question-circle','approved'=>'la-check-circle','ordered'=>'la-shopping-cart',
            'delivered'=>'la-truck','rejected'=>'la-times-circle','cancelled'=>'la-ban','returned'=>'la-undo','none'=>'la-ellipsis-h'
        ];
        $mainColors = [
            'requested'=>'chip--blue','approved'=>'chip--green','ordered'=>'chip--indigo','delivered'=>'chip--teal',
            'rejected'=>'chip--red','cancelled'=>'chip--gray','returned'=>'chip--pink','none'=>'chip--neutral'
        ];

        $branchIcons = [
            'pending'=>'la-hourglass-half','processing'=>'la-cogs','completed'=>'la-check',
            'cancelled'=>'la-ban','delivered'=>'la-truck','received'=>'la-inbox','none'=>'la-ellipsis-h'
        ];
        $branchColors = [
            'pending'=>'chip--amber','processing'=>'chip--indigo','completed'=>'chip--green',
            'cancelled'=>'chip--gray','delivered'=>'chip--teal','received'=>'chip--purple','none'=>'chip--neutral'
        ];

        $officeIcons = [
            'none'=>'la-ellipsis-h','pending'=>'la-hourglass-start','processing'=>'la-cog',
            'completed'=>'la-check-circle','cancelled'=>'la-ban','delivered'=>'la-truck','received'=>'la-archive'
        ];
        $officeColors = [
            'none'=>'chip--neutral','pending'=>'chip--amber','processing'=>'chip--indigo',
            'completed'=>'chip--green','cancelled'=>'chip--gray','delivered'=>'chip--teal','received'=>'chip--purple'
        ];
    @endphp

    {{-- Totals --}}
    <div class="row mb-4">
        @foreach ($cards as $c)
            <div class="col-lg-4 col-xl-4 mb-4">
                <div class="card card-custom bg-{{ $c['color'] }} text-white">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <i class="{{ $c['icon'] }} la-2x text-white"></i>
                        <div class="text-right">
                            <div class="font-weight-bold">{{ $c['label'] }}</div>
                            <div class="h3 mb-0">{{ is_numeric($c['val']) ? number_format($c['val'], 2) : $c['val'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Status chips --}}
    <div class="status-breakdowns card card-custom mb-4">
        <div class="card-body">
            <div class="row">
                {{-- Main --}}
                <div class="col-md-4 mb-3">
                    <div class="sb-title">{{ __('uniform_reports.status_breakdown') }}</div>
                    <div class="sb-chips">
                        @foreach (\App\Models\UniformRequest::STATUS_OPTIONS as $k => $v)
                            @php $c = $mainStatusCounts[$k] ?? 0; @endphp
                            <span class="chip {{ $mainColors[$k] ?? 'chip--neutral' }} {{ $c ? 'chip--on' : 'chip--off' }}">
                                <i class="la {{ $mainIcons[$k] ?? 'la-tag' }} chip__icon"></i>
                                {{ __('uniform_requests.statuses.'.$k) }}
                                <span class="chip__count">{{ $c }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Branch --}}
                <div class="col-md-4 mb-3">
                    <div class="sb-title">{{ __('uniform_reports.branch_status_breakdown') }}</div>
                    <div class="sb-chips">
                        @foreach (\App\Models\UniformRequest::BRANCH_STATUS_OPTIONS as $k => $v)
                            @php $c = $branchStatusCounts[$k] ?? 0; @endphp
                            <span class="chip {{ $branchColors[$k] ?? 'chip--purple' }} {{ $c ? 'chip--on' : 'chip--off' }}">
                                <i class="la {{ $branchIcons[$k] ?? 'la-tag' }} chip__icon"></i>
                                {{ __('uniform_requests.branch_statuses.'.$k) }}
                                <span class="chip__count">{{ $c }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Office --}}
                <div class="col-md-4 mb-3">
                    <div class="sb-title">{{ __('uniform_reports.office_status_breakdown') }}</div>
                    <div class="sb-chips">
                        @foreach (\App\Models\UniformRequest::OFFICE_STATUS_OPTIONS as $k => $v)
                            @php $c = $officeStatusCounts[$k] ?? 0; @endphp
                            <span class="chip {{ $officeColors[$k] ?? 'chip--amber' }} {{ $c ? 'chip--on' : 'chip--off' }}">
                                <i class="la {{ $officeIcons[$k] ?? 'la-tag' }} chip__icon"></i>
                                {{ __('uniform_requests.office_statuses.'.$k) }}
                                <span class="chip__count">{{ $c }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Table: single-line cells + horizontal scroll --}}
    <div class="table-responsive table-scroll">
        <table class="table table-bordered table-hover table-sm report-table table-nowrap">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th><i class="la la-calendar mr-1"></i>{{ __('uniform_reports.table.request_date') }}</th>
                    <th><i class="la la-flag mr-1"></i>{{ __('uniform_reports.table.status') }}</th>
                    <th><i class="la la-sitemap mr-1"></i>{{ __('uniform_reports.table.branch_status') }}</th>
                    <th><i class="la la-building mr-1"></i>{{ __('uniform_reports.table.office_status') }}</th>
                    <th><i class="la la-user mr-1"></i>{{ __('uniform_reports.table.player') }}</th>
                    <th><i class="la la-tshirt mr-1"></i>{{ __('uniform_reports.table.item') }}</th>
                    <th><i class="la la-map-marker mr-1"></i>{{ __('uniform_reports.table.branch') }}</th>
                    <th><i class="la la-ruler mr-1"></i>{{ __('uniform_reports.table.size') }}</th>
                    <th><i class="la la-palette mr-1"></i>{{ __('uniform_reports.table.color') }}</th>
                    <th class="text-right"><i class="la la-sort-numeric-up mr-1"></i>{{ __('uniform_reports.table.quantity') }}</th>
                    <th class="text-right"><i class="la la-coins mr-1"></i>{{ __('uniform_reports.table.amount') }}</th>
                    <th><i class="la la-money-bill mr-1"></i>{{ __('uniform_reports.table.currency') }}</th>
                    <th><i class="la la-credit-card mr-1"></i>{{ __('uniform_reports.table.payment_method') }}</th>
                    <th><i class="la la-edit text-muted mr-1"></i> {{ __('uniform_requests.actions.edit') }} / <i class="la la-trash text-muted mr-1"></i> {{ __('uniform_requests.actions.delete') }}</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($uniforms as $i => $u)
                    @php
                        $msIcon = $mainIcons[$u->status] ?? 'la-tag';
                        $bsIcon = $branchIcons[$u->branch_status] ?? 'la-tag';
                        $osIcon = $officeIcons[$u->office_status] ?? 'la-tag';
                    @endphp
                    <tr>
                        <td>{{ $uniforms->firstItem() + $i }}</td>
                        <td>{{ optional($u->request_date)->format('Y-m-d') }}</td>
                        <td><i class="la {{ $msIcon }} text-muted mr-1"></i>{{ __('uniform_requests.statuses.'.$u->status) }}</td>
                        <td><i class="la {{ $bsIcon }} text-muted mr-1"></i>{{ __('uniform_requests.branch_statuses.'.$u->branch_status) }}</td>
                        <td><i class="la {{ $osIcon }} text-muted mr-1"></i>{{ __('uniform_requests.office_statuses.'.$u->office_status) }}</td>
                        <td>{{ optional($u->player?->user)->name ?? '-' }}</td>
                        <td>{{ optional($u->item)->name_en ?? optional($u->item)->name ?? '-' }}</td>
                        <td>{{ optional($u->branch)->translated_name ?? optional($u->branch)->name ?? '-' }}</td>
                        <td>{{ $u->size ?? '-' }}</td>
                        <td>{{ $u->color ?? '-' }}</td>
                        <td class="text-right">{{ (int)$u->quantity }}</td>
                        <td class="text-right">{{ number_format($u->amount, 2) }}</td>
                        <td>{{ optional($u->currency)->code ?? '-' }}</td>
                        <td>{{ $u->payment_method ?? '-' }}</td>
                         <td>
                         <a href="{{ route('admin.uniform-requests.edit', $u->id) }}" class="btn btn-sm btn-clean btn-icon" title="{{ __('uniform_requests.actions.edit') }}">
                                    <i class="la la-edit"></i>
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
            {{ $uniforms->firstItem() }}–{{ $uniforms->lastItem() }}
            {{ __('reports.table.of') }} {{ $uniforms->total() }}
        </div>
        {{ $uniforms->onEachSide(1)->links('pagination::bootstrap-4') }}
    </div>
@else
    <p class="text-muted mb-0">{{ __('reports.table.no_results') }}</p>
@endif
