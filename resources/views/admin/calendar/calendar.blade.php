@extends('layouts.app')

@section('page_title', 'Attendance Calendar')

@section('content')
<div class="container-fluid">

    <h4 class="mb-3">{{ __('calendar.programs') }}</h4>
    <div class="row">
        @forelse($programs as $program)
            <div class="col-md-4 col-sm-6 mb-3">
                <div
                    class="card h-100 shadow-sm"
                    style="cursor: pointer;"
                    data-bs-toggle="modal"
                    data-bs-target="#classesModal{{ $program->id }}">
                    <div class="card-body p-3">
                        <h6 class="card-title mb-2">{{ $program->name_en }}</h6>
                        <ul class="list-unstyled small mb-0">
                            <li><strong>{{ __('calendar.branch') }}:</strong> {{ $program->branch->name }}</li>
                            <li><strong>{{ __('calendar.academy') }}:</strong> {{ $program->academy->name_en }}</li>
                            <li><strong>{{ __('calendar.class_count') }}:</strong> {{ $program->class_count }}</li>
                            <li><strong>{{ __('calendar.price') }}:</strong> {{ $program->price }} {{ $program->currency }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Modal for Classes -->
            <div class="modal fade" id="classesModal{{ $program->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('calendar.classes') }} - {{ $program->name_en }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @php
                                $programClasses = $classes->where('program_id', $program->id);
                            @endphp
                            @if($programClasses->count())
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle mb-0">
                                        <thead class="table-light">
                                            <tr class="small text-center">
                                                <th>{{ __('calendar.day') }}</th>
                                                <th>{{ __('calendar.start_time') }}</th>
                                                <th>{{ __('calendar.end_time') }}</th>
                                                <th>{{ __('calendar.location') }}</th>
                                                <th>{{ __('calendar.coach') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($programClasses as $class)
                                                <tr class="small text-center">
                                                    <td>{{ $class->day }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }}</td>
                                                    <td>{{ $class->end_time ? \Carbon\Carbon::parse($class->end_time)->format('H:i') : '-' }}</td>
                                                    <td class="text-truncate" style="max-width: 120px;">{{ $class->location ?? '-' }}</td>
                                                    <td class="text-truncate" style="max-width: 120px;">{{ $class->coach_name ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">{{ __('calendar.no_classes') }}</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                {{ __('Close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>{{ __('calendar.no_programs') }}</p>
        @endforelse
    </div>

    <div class="row mb-4">
    <div class="col-md-2">
        <label>{{ __('columns.select_country') }}</label>
        <select id="country_id" class="form-control">
            <option value="">{{ __('columns.select_country') }}</option>
            @foreach($countries as $country)
                <option value="{{ $country->id }}">{{ $country->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label>{{ __('columns.select_state') }}</label>
        <select id="state_id" class="form-control">
            <option value="">{{ __('columns.select_state') }}</option>
        </select>
    </div>
    <div class="col-md-2">
        <label>{{ __('columns.select_city') }}</label>
        <select id="city_id" class="form-control">
            <option value="">{{ __('columns.select_city') }}</option>
        </select>
    </div>
    <div class="col-md-2">
        <label>{{ __('columns.select_system') }}</label>
        <select id="system_id" class="form-control">
            <option value="">{{ __('columns.select_system') }}</option>
            @foreach($systems as $system)
                <option value="{{ $system->id }}">{{ $system->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label>{{ __('columns.select_branch') }}</label>
        <select id="branch_id" class="form-control">
            <option value="">{{ __('columns.select_branch') }}</option>
        </select>
    </div>
</div>


    <div class="mt-4">
        <button id="add-event-button" class="btn btn-danger mb-3">
            + {{ __('calendar.add_event') }}
        </button>
        <button id="copy-week-button" class="btn btn-primary mb-3">
            {{ __('calendar.copy_week') }}
        </button>
        <button id="copy-month-button" class="btn btn-warning mb-3">
            {{ __('calendar.copy_month') }}
        </button>
    </div>
    <div class="mt-4">

    <button id="save-all-button" class="btn btn-success mb-3">
    {{ __('calendar.save_all_changes') }}
</button>
    </div>
    <div id="calendar"></div>

    <!-- Context Menu -->
    <div id="context-menu">
        <ul>
                <li id="duplicate-event">{{ __('calendar.duplicate_event') }}</li>
                <li id="copy-event">{{ __('calendar.copy_event') }}</li>
                <li id="update-event">{{ __('calendar.update') }}</li>
                <li id="delete-event">{{ __('calendar.delete') }}</li>
        </ul>
    </div>
</div>




@endsection

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.8/index.global.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4@5/bootstrap-4.min.css" rel="stylesheet">

<style>
    #calendar {
        max-width: 100%;
        margin: auto;
        background: #fff;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 8px;
    }

    #context-menu {
        position: absolute;
        display: none;
        background: #fff;
        border: 1px solid #ccc;
        z-index: 9999;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);
    }

    #context-menu ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    #context-menu li {
        padding: 8px 12px;
        cursor: pointer;
    }

    #context-menu li:hover {
        background: #f0f0f0;
    }
</style>

<!-- FullCalendar & SweetAlert Scripts -->
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

@push('scripts')
<script>
$(document).ready(function() {
    // Cascading selects
    $('#country_id').change(function() {
        let id = $(this).val();
        $('#state_id').html('<option>Loading...</option>');
        $('#city_id').html('<option>{{ __("columns.select_city") }}</option>');
        $.get(`/admin/get-states-by-country?country_id=${id}`, function(data) {
            let options = '<option value="">{{ __("columns.select_state") }}</option>';
            data.forEach(item => {
                options += `<option value="${item.id}">${item.name}</option>`;
            });
            $('#state_id').html(options);
        });
    });
    $('#state_id').change(function() {
        let id = $(this).val();
        $('#city_id').html('<option>Loading...</option>');
        $.get(`/admin/get-cities-by-state?state_id=${id}`, function(data) {
            let options = '<option value="">{{ __("columns.select_city") }}</option>';
            data.forEach(item => {
                options += `<option value="${item.id}">${item.name}</option>`;
            });
            $('#city_id').html(options);
        });
    });
    $('#system_id').change(function() {
        let systemId = $(this).val();
        $('#branch_id').html('<option>Loading...</option>');
        $.get(`/admin/get-branches-by-system/${systemId}`, function(branches) {
            let options = '<option value="">{{ __("columns.select_branch") }}</option>';
            branches.forEach(branch => {
                options += `<option value="${branch.id}">${branch.name}</option>`;
            });
            $('#branch_id').html(options);
        });
    });
    $('#branch_id').change(function() {
        let branchId = $(this).val();
        if(branchId){
            window.location = `?branch_id=${branchId}`;
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const contextMenu = document.getElementById('context-menu');
    const copyWeekButton = document.getElementById('copy-week-button');
    const copyMonthButton = document.getElementById('copy-month-button');
    const addEventButton = document.getElementById('add-event-button');
    const saveAllButton = document.getElementById('save-all-button');
    let copiedEvent = null;
    let currentEvent = null;

    // Arrays to track unsaved changes
    let pendingNewEvents = [];
    let pendingUpdatedEvents = [];

    const classEvents = @json($events);

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        editable: true,
        selectable: true,
        events: classEvents,
        dateClick(info) {
            if (copiedEvent) {
                const newEvent = calendar.addEvent({
                    title: copiedEvent.title,
                    start: info.dateStr,
                    color: copiedEvent.backgroundColor
                });
                pendingNewEvents.push(newEvent);
                Swal.fire('@lang('calendar.success')', '@lang('calendar.event_pasted') ' + info.dateStr, 'success');
                copiedEvent = null;
            }
        },
        eventDidMount(info) {
            info.el.addEventListener('contextmenu', function(ev) {
                ev.preventDefault();
                currentEvent = info.event;
                contextMenu.style.top = ev.pageY + 'px';
                contextMenu.style.left = ev.pageX + 'px';
                contextMenu.style.display = 'block';
            });
        },
        eventDrop(info) {
            if (!pendingUpdatedEvents.includes(info.event)) {
                pendingUpdatedEvents.push(info.event);
            }
            Swal.fire('@lang('calendar.changed')', '@lang('calendar.event_moved_pending')', 'info');
        },
        eventResize(info) {
            if (!pendingUpdatedEvents.includes(info.event)) {
                pendingUpdatedEvents.push(info.event);
            }
            Swal.fire('@lang('calendar.changed')', '@lang('calendar.event_resized_pending')', 'info');
        },
        weekends: true,
        nowIndicator: true
    });
    calendar.render();

    // Hide context menu
    document.addEventListener('click', () => {
        contextMenu.style.display = 'none';
    });

    // Duplicate Event
    document.getElementById('duplicate-event').addEventListener('click', function() {
        if (currentEvent) {
            const newStart = new Date(currentEvent.start);
            newStart.setDate(newStart.getDate() + 7);
            let newEnd = null;
            if (currentEvent.end) {
                newEnd = new Date(currentEvent.end);
                newEnd.setDate(newEnd.getDate() + 7);
            }
            const newEvent = calendar.addEvent({
                title: currentEvent.title,
                start: newStart.toISOString(),
                end: newEnd ? newEnd.toISOString() : undefined,
                color: currentEvent.backgroundColor
            });
            pendingNewEvents.push(newEvent);
            Swal.fire('@lang('calendar.success')', '@lang('calendar.event_duplicated_week')', 'success');
        }
        contextMenu.style.display = 'none';
    });

    // Copy Event
    document.getElementById('copy-event').addEventListener('click', function() {
        if (currentEvent) {
            copiedEvent = {
                title: currentEvent.title,
                backgroundColor: currentEvent.backgroundColor
            };
            Swal.fire('@lang('calendar.copied')', '@lang('calendar.click_to_paste')', 'info');
        }
        contextMenu.style.display = 'none';
    });

    // Delete Event (immediately)
    document.getElementById('delete-event').addEventListener('click', function() {
        if (currentEvent) {
            Swal.fire({
                title: '@lang('calendar.delete')',
                text: '@lang('calendar.confirm_delete')',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '@lang('calendar.ok')',
                cancelButtonText: '@lang('calendar.cancel')'
            }).then(result => {
                if (result.isConfirmed) {
                    axios.post('{{ route('admin.calendar.deleteEvent') }}', { id: currentEvent.id })
                        .then(() => {
                            currentEvent.remove();
                            Swal.fire('@lang('calendar.success')', '@lang('calendar.event_deleted')', 'success');
                        }).catch(() => {
                            Swal.fire('@lang('calendar.error')', '@lang('calendar.event_delete_failed')', 'error');
                        });
                }
            });
        }
        contextMenu.style.display = 'none';
    });

    // Add Event
    // Add Event
addEventButton.addEventListener('click', function() {
    Swal.fire({
        title: '@lang('calendar.add_new_event')',
        width: '600px',
        html: `
            <input id="swal-title" class="swal2-input" placeholder="@lang('calendar.title_placeholder')">
            <div style="margin:5px 0;">
                <select id="swal-program" class="swal2-select" style="width:100%;">
                    <option value="">@lang('calendar.select_program')</option>
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->name_en }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin:5px 0;">
                <select id="swal-coach" class="swal2-select" style="width:100%;">
                    <option value="">@lang('calendar.select_coach')</option>
                    @foreach($coaches as $coach)
                        <option value="{{ $coach->id }}">{{ $coach->name }}</option>
                    @endforeach
                </select>
            </div>
            <input id="swal-date" type="date" class="swal2-input">
            <input id="swal-start-time" type="time" class="swal2-input">
            <input id="swal-end-time" type="time" class="swal2-input">
            <label style="display:block;margin-top:5px;">@lang('calendar.color')</label>
            <input id="swal-color" type="color" class="swal2-input" value="#007bff" style="width:100%;">
        `,
        showCancelButton: true,
        confirmButtonText: '@lang('calendar.ok')',
        cancelButtonText: '@lang('calendar.cancel')',
        preConfirm: () => {
            const title = document.getElementById('swal-title').value;
            const programId = document.getElementById('swal-program').value;
            const coachId = document.getElementById('swal-coach').value;
            const date = document.getElementById('swal-date').value;
            const startTime = document.getElementById('swal-start-time').value;
            const endTime = document.getElementById('swal-end-time').value;
            const color = document.getElementById('swal-color').value;

            if (!title || !date || !startTime || !programId) {
                Swal.showValidationMessage('@lang('calendar.validation_required')');
                return false;
            }

            return { title, programId, coachId, date, startTime, endTime, color };
        }
    }).then(result => {
        if (result.isConfirmed) {
            const { title, programId, coachId, date, startTime, endTime, color } = result.value;
            const newEvent = calendar.addEvent({
                title: title,
                start: `${date}T${startTime}`,
                end: endTime ? `${date}T${endTime}` : undefined,
                color: color,
                extendedProps: {
                    program_id: programId,
                    coach_id: coachId
                }
            });
            pendingNewEvents.push(newEvent);
            Swal.fire('@lang('calendar.success')', '@lang('calendar.event_added_pending')', 'success');
        }
    });
});

    // Copy Week
    copyWeekButton.addEventListener('click', function() {
        const view = calendar.view;
        const startDate = new Date(view.currentStart);
        const endDate = new Date(view.currentEnd);
        const eventsInWeek = calendar.getEvents().filter(event => event.start >= startDate && event.start < endDate);
        if (eventsInWeek.length === 0) {
            Swal.fire('@lang('calendar.no_events')', '@lang('calendar.no_events_week')', 'warning');
            return;
        }
        eventsInWeek.forEach(event => {
            const newStart = new Date(event.start);
            newStart.setDate(newStart.getDate() + 7);
            let newEnd = null;
            if (event.end) {
                newEnd = new Date(event.end);
                newEnd.setDate(newEnd.getDate() + 7);
            }
            const newEvent = calendar.addEvent({
                title: event.title,
                start: newStart.toISOString(),
                end: newEnd ? newEnd.toISOString() : undefined,
                color: event.backgroundColor
            });
            pendingNewEvents.push(newEvent);
        });
        Swal.fire('@lang('calendar.success')', eventsInWeek.length + ' @lang('calendar.events_copied_week')', 'success');
    });

    // Copy Month
    copyMonthButton.addEventListener('click', function() {
        const events = calendar.getEvents();
        if (events.length === 0) {
            Swal.fire('@lang('calendar.no_events')', '@lang('calendar.no_events_month')', 'warning');
            return;
        }
        let copiedCount = 0;
        events.forEach(event => {
            const start = new Date(event.start);
            const nextMonth = new Date(start);
            nextMonth.setMonth(start.getMonth() + 1);
            const newStart = new Date(nextMonth);
            let newEnd = null;
            if (event.end) {
                const durationMs = event.end - event.start;
                newEnd = new Date(newStart.getTime() + durationMs);
            }
            const newEvent = calendar.addEvent({
                title: event.title,
                start: newStart.toISOString(),
                end: newEnd ? newEnd.toISOString() : undefined,
                color: event.backgroundColor
            });
            pendingNewEvents.push(newEvent);
            copiedCount++;
        });
        Swal.fire('@lang('calendar.success')', copiedCount + ' @lang('calendar.events_copied_month')', 'success');
    });

    // Save All Changes
    saveAllButton.addEventListener('click', function() {
    if (pendingNewEvents.length === 0 && pendingUpdatedEvents.length === 0) {
        Swal.fire('@lang('calendar.no_changes')', '@lang('calendar.no_changes_to_save')', 'info');
        return;
    }
    Swal.fire({
        title: '@lang('calendar.confirm_save')',
        text: '@lang('calendar.confirm_save_text')',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '@lang('calendar.ok')',
        cancelButtonText: '@lang('calendar.cancel')'
    }).then(result => {
        if (result.isConfirmed) {
            let saves = [];

            // Save new events
            pendingNewEvents.forEach(ev => {
                const start = ev.start ? formatLocalDateTime(ev.start) : null;
                const end = ev.end ? formatLocalDateTime(ev.end) : null;

                // Retrieve any extra props you stored (program_id, coach_id)
                const programId = ev.extendedProps.program_id || null;
                const coachId = ev.extendedProps.coach_id || null;

                saves.push(
                    axios.post('{{ route('admin.calendar.storeEvent') }}', {
                        title: ev.title,
                        start: start,
                        end: end,
                        color: ev.backgroundColor,
                        program_id: programId,
                        coach_id: coachId
                    })
                );
            });

            // Save updated events
            pendingUpdatedEvents.forEach(ev => {
                saves.push(
                    axios.post('{{ route('admin.calendar.updateEvent') }}', {
                        id: ev.id,
                        start: ev.start ? ev.start.toISOString() : null,
                        end: ev.end ? ev.end.toISOString() : null
                    })
                );
            });

            // Wait for all saves
            Promise.all(saves).then(() => {
                pendingNewEvents = [];
                pendingUpdatedEvents = [];
                Swal.fire('@lang('calendar.success')', '@lang('calendar.all_changes_saved')', 'success');
            }).catch(() => {
                Swal.fire('@lang('calendar.error')', '@lang('calendar.some_changes_failed')', 'error');
            });
        }
    });
});

});
</script>
@endpush





