<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\System;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use App\Models\CoachEvaluation;
use App\Models\EvaluationResponse;

class CoachEvaluationController extends Controller
{

   public function index(Request $request)
{
    $query = CoachEvaluation::with(['coach', 'evaluation', 'evaluation.system']);

    if ($request->filled('system_id')) {
        $query->whereHas('evaluation', function ($q) use ($request) {
            $q->where('system_id', $request->system_id);
        });
    }

    if ($request->filled('coach_name') && strlen($request->coach_name) >= 3) {
        $query->whereHas('coach', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->coach_name . '%');
        });
    }

    if ($request->filled('start_date')) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    $coachEvaluations = $query->latest()->paginate(10);

    $systems = System::all(); // to populate the dropdown
    return view('admin.coach_evaluations.index', compact('coachEvaluations', 'systems'));
}


    /**
     * Show the evaluation form for all coaches under the same system.
     */
    public function create(Evaluation $evaluation)
    {
         
        $coaches = User::where('role', 'coach')
            ->where('system_id', $evaluation->system_id)
            ->get();

        return view('admin.evaluations.assign', compact('evaluation', 'coaches'));
    }

 

    /**
     * Store coach evaluation responses.
     */
    public function store(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|exists:evaluations,id',
            'coach_id'      => 'required|exists:users,id',
            'responses'     => 'required|array',
        ]);

        $coachEvaluation = CoachEvaluation::create([
            'evaluation_id'   => $request->evaluation_id,
            'coach_id'        => $request->coach_id,
            'evaluator_type'  => 'admin',
            'evaluator_id'    => auth()->id(),
            'submitted_at'    => now(),
        ]);

        foreach ($request->responses as $criteriaId => $value) {
            EvaluationResponse::create([
                'coach_evaluation_id' => $coachEvaluation->id,
                'criteria_id'         => $criteriaId,
                'value'               => $value,
            ]);
        }

        return redirect()
            ->route('admin.evaluations.index')
            ->with('success', 'Evaluation submitted.');
    }

    public function edit(CoachEvaluation $coachEvaluation)
    {
        $evaluation = $coachEvaluation->evaluation;
        $coaches = User::where('role', 'coach')
            ->where('system_id', $evaluation->system_id)
            ->get();

        $responses = $coachEvaluation->responses->pluck('value', 'criteria_id');

        return view('admin.coach_evaluations.edit', compact('evaluation', 'coachEvaluation', 'coaches', 'responses'));
    }

   public function update(Request $request, CoachEvaluation $coachEvaluation)
{
    $request->validate([
        'coach_id'  => 'required|exists:users,id',
        'responses' => 'required|array',
    ]);

    // Update coach and timestamp
    $coachEvaluation->update([
        'coach_id'     => $request->coach_id,
        'submitted_at' => now(),
    ]);

    foreach ($request->responses as $criteriaId => $value) {
        $response = $coachEvaluation->responses()->where('criteria_id', $criteriaId)->first();

        if ($response) {
            // Update existing response
            $response->update(['value' => $value]);
        } else {
            // Create new response if missing
            EvaluationResponse::create([
                'coach_evaluation_id' => $coachEvaluation->id,
                'criteria_id'         => $criteriaId,
                'value'               => $value,
            ]);
        }
    }

    return redirect()->route('admin.coach_evaluations.index')->with('success', 'Evaluation updated successfully.');
}

 public function destroy($id)
    {
        $coachEvaluation = CoachEvaluation::findOrFail($id);
        $coachEvaluation->responses()->delete();
        $coachEvaluation->delete();

        return redirect()->route('admin.coach_evaluation.index')->with('success', __('Evaluation deleted.'));
    }


}
