<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\System;
use App\Models\Country;
use App\Models\Program;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use App\Http\Controllers\Controller;

class CalendarController extends Controller
{


    /**
     * Display the attendance calendar.
     */
        public function index(Request $request)
    {
        $user = auth()->user();

        $branchIdFilter = $request->branch_id;

        $programs = collect();
        $calendarEvents = collect();
        $coaches = collect();

        if ($branchIdFilter) {
            $programs = Program::with('classes')
                ->where('branch_id', $branchIdFilter)
                ->get();

            $calendarEvents = CalendarEvent::with(['program', 'class', 'coach', 'system', 'branch', 'academy'])
                ->where('branch_id', $branchIdFilter)
                ->get();

            $coaches = User::where('branch_id', $branchIdFilter)->where('role', 'coach')->get();

        } elseif ($user->role === 'full_admin') {
            $programs = Program::with('classes')->get();

            $calendarEvents = CalendarEvent::with(['program', 'class', 'coach', 'system', 'branch', 'academy'])->get();

            $coaches = User::where('role', 'coach')->get();

        } elseif ($user->role === 'system_admin') {
            $programs = Program::with('classes')
                ->where('system_id', $user->system_id)
                ->get();

            $calendarEvents = CalendarEvent::with(['program', 'class', 'coach', 'system', 'branch', 'academy'])
                ->where('system_id', $user->system_id)
                ->get();

            $coaches = User::where('system_id', $user->system_id)->where('role', 'coach')->get();

        } elseif ($user->role === 'branch_admin') {
            $programs = Program::with('classes')
                ->where('branch_id', $user->branch_id)
                ->get();

            $calendarEvents = CalendarEvent::with(['program', 'class', 'coach', 'system', 'branch', 'academy'])
                ->where('branch_id', $user->branch_id)
                ->get();

            $coaches = User::where('branch_id', $user->branch_id)->where('role', 'coach')->get();

        } elseif ($user->role === 'coach') {
            $calendarEvents = CalendarEvent::with(['program', 'class', 'coach', 'system', 'branch', 'academy'])
                ->where('coach_id', $user->id)
                ->get();

            $programIds = $calendarEvents->pluck('program_id')->unique();
            $programs = Program::whereIn('id', $programIds)->get();

            $coaches = collect([$user]); // Just the logged-in coach
        }

        // Build events array for FullCalendar
        $events = [];
        foreach ($calendarEvents as $event) {
            $events[] = [
                'id'    => $event->id,
                'title' => ($event->program->name_en ?? '') . ' - ' . ($event->coach->name ?? ''),
                'start' => $event->start_datetime,
                'end'   => $event->end_datetime,
                'color' => $event->color ?? '#007bff',
            ];
        }

        $classes = ClassModel::with(['coach', 'program'])->get();

        return view('admin.calendar.calendar', [
            'programs'  => $programs,
            'events'    => $events,
            'classes'   => $classes,
            'coaches'   => $coaches,
            'countries' => Country::all(),
            'systems'   => System::all(),
        ]);
    }


public function storeEvent(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:191',
        'start' => 'required|date',
        'end' => 'nullable|date|after:start',
        'program_id' => 'nullable|exists:programs,id',
        'coach_id' => 'nullable|exists:users,id',
        'system_id' => 'nullable|exists:systems,id',
        'branch_id' => 'nullable|exists:branches,id',
        'academy_id' => 'nullable|exists:academies,id',
        'color' => 'nullable|string|max:20',
    ]);

    // Get the program to extract related data
    $program = Program::findOrFail($request->program_id);

    // Determine day name from start date (e.g., 'Monday')
    $dayName = Carbon::parse($request->start)->format('l');

    // Create a new class entry (ClassModel)
    $class = ClassModel::create([
        'program_id' => $program->id,
        'academy_id' => $program->academy_id, // From program
        'day' => $dayName,
        'start_time' => Carbon::parse($request->start)->format('H:i:s'),
        'end_time' => $request->end ? Carbon::parse($request->end)->format('H:i:s') : null,
        'location' => optional($program->academy)->name_en,
        'coach_id' => $request->coach_id,
        'coach_name' => optional($request->coach_id ? User::find($request->coach_id) : null)->name,
    ]);

    // Create the calendar event
    $event = CalendarEvent::create([
        'title' => $request->title,
        'start_datetime' => Carbon::parse($request->start , 'Asia/Dubai'),
        'end_datetime' => $request->end ? Carbon::parse($request->end  , 'Asia/Dubai') : null,
        'color' => $request->color ?? '#007bff',
        'program_id' => $program->id,
        'class_id' => $class->id, // newly created class
        'coach_id' => $request->coach_id,
        'system_id' => $program->system_id,
        'branch_id' => $program->branch_id,
        'academy_id' => $program->academy_id,
    ]);

    return response()->json([
        'id' => $event->id,
        'status' => 'created',
    ]);
}


public function updateEvent(Request $request)
{
    $request->validate([
        'id' => 'required|exists:calendar_events,id',
        'start' => 'required|date',
        'end' => 'nullable|date|after:start',
        'title' => 'nullable|string|max:191',
        'color' => 'nullable|string|max:20',
    ]);

    $event = CalendarEvent::findOrFail($request->id);

    $event->update([
        'title' => $request->title ?? $event->title,
        'start_datetime' => Carbon::parse($request->start , 'Asia/Dubai'),
        'end_datetime' => $request->end ? Carbon::parse($request->end , 'Asia/Dubai') : null,
        'color' => $request->color ?? $event->color,
    ]);

    return response()->json(['status' => 'updated']);
}



public function deleteEvent(Request $request)
{
    $request->validate([
        'id' => 'required|exists:calendar_events,id',
    ]);

    $event = \App\Models\CalendarEvent::findOrFail($request->id);
    $event->delete();

    return response()->json(['status' => 'deleted']);
}

}
