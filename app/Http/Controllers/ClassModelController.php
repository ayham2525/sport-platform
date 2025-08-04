<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Program;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

class ClassModelController extends Controller
{
    public function create(Program $program)
    {
        if (!PermissionHelper::hasPermission('view', ClassModel::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $coaches = User::where('role', 'coach')
            ->where('system_id', $program->system_id)
            ->get();

        return view('admin.class.create', [
            'program' => $program,
            'coaches' => $coaches,
        ]);
    }


    public function store(Request $request, Program $program)
    {
        if (!PermissionHelper::hasPermission('create', ClassModel::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'day'         => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'nullable|date_format:H:i|after:start_time',
            'location'    => 'nullable|string|max:255',
            'coach_id'    => 'nullable|exists:users,id',
        ]);

        // لو اخترت coach_id نجيب اسمه
        $coachName = null;
        if ($request->coach_id) {
            $coach = \App\Models\User::find($request->coach_id);
            $coachName = $coach ? $coach->name : null;
        }

        ClassModel::create([
            'program_id'   => $program->id,
            'academy_id'   => $program->academy_id,
            'day'          => $request->day,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'location'     => $request->location,
            'coach_id'     => $request->coach_id,
            'coach_name'   => $coachName, // نخزن الاسم للنصوص
        ]);

        return redirect()
            ->route('admin.programs.show', $program->id)
            ->with('success', __('class.messages.added'));
    }

    public function edit($program, $class)
    {

        if (!PermissionHelper::hasPermission('update', ClassModel::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $program = Program::findOrFail($program);
        $classModel = ClassModel::findOrFail($class);
        $coaches = User::where('role', 'coach')->get();

        return view('admin.class.edit', [
            'program' => $program,
            'class' => $classModel,
            'coaches' => $coaches
        ]);
    }


    public function update(Request $request)
    {
        if (!PermissionHelper::hasPermission('update', ClassModel::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'nullable',
            'location' => 'nullable|string',
            'coach_id' => 'nullable|exists:users,id',
        ]);

        // Retrieve the class using the correct route parameter name ('class')
        $classModel = ClassModel::findOrFail($request->route('class'));

        // Update fields
        $classModel->update($request->only('day', 'start_time', 'end_time', 'location', 'coach_id'));

        return redirect()
            ->route('admin.programs.show', $classModel->program_id)
            ->with('success', __('Class updated successfully.'));
    }



    public function destroy(ClassModel $class)
    {
        if (!PermissionHelper::hasPermission('delete', ClassModel::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $class->delete();
        return back()->with('success', __('Class deleted successfully.'));
    }

    public function byProgram($programId)
    {
        $classes = ClassModel::where('program_id', $programId)
            ->get(['id', 'day', 'start_time', 'end_time', 'location', 'coach_name']);

        return response()->json(['classes' => $classes]);
    }
}
