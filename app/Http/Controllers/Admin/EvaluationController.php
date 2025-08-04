<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\System;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EvaluationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Evaluation::with(['system', 'creator']);

        switch ($user->role) {
            case 'system_admin':
                if ($user->system_id) {
                    $query->where('system_id', $user->system_id);
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            case 'full_admin':
                // no filtering
                break;

            default:
                $query->whereRaw('0 = 1');
                break;
        }

        $evaluations = $query->latest()->paginate(20);

        return view('admin.evaluations.index', compact('evaluations'));
    }


    public function create()
    {
        $user = auth()->user();

        switch ($user->role) {
            case 'full_admin':
                $systems = System::all();
                break;

            case 'system_admin':
                if ($user->system_id) {
                    $systems = System::where('id', $user->system_id)->get();
                } else {
                    $systems = collect();
                }
                break;

            default:
                $systems = collect();
                break;
        }

        return view('admin.evaluations.create', compact('systems'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'system_id'   => 'required|exists:systems,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'required|in:general,internal,student',
            'start_date'  => 'nullable|date_format:Y-m-d',
            'end_date'    => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'is_active'   => 'boolean',
        ]);

        $data['created_by'] = auth()->id();

        $evaluation = Evaluation::create($data);

        log_action('created_evaluation', $evaluation, 'Created evaluation: ' . $evaluation->title);

        return redirect()->route('admin.evaluations.index')->with('success', 'Evaluation created successfully.');
    }


    public function show(Evaluation $evaluation)
    {
        return view('admin.evaluations.show', compact('evaluation'));
    }

    public function edit(Evaluation $evaluation)
    {
        $user = auth()->user();

        switch ($user->role) {
            case 'full_admin':
                $systems = System::all();
                break;

            case 'system_admin':
                if ($user->system_id) {
                    $systems = System::where('id', $user->system_id)->get();
                } else {
                    $systems = collect();
                }
                break;

            default:
                $systems = collect();
                break;
        }

        return view('admin.evaluations.edit', compact('evaluation', 'systems'));
    }


    public function update(Request $request, Evaluation $evaluation)
    {
        $data = $request->validate([
            'system_id'   => 'required|exists:systems,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'required|in:general,internal,student',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'is_active'   => 'boolean',
        ]);

        $evaluation->update($data);

        log_action('updated_evaluation', $evaluation, 'Updated evaluation: ' . $evaluation->title);

        return redirect()->route('admin.evaluations.index')->with('success', 'Evaluation updated successfully.');
    }

    public function destroy(Evaluation $evaluation)
    {
        // Delete related criteria first
        $evaluation->criteria()->delete();

        // Then delete the evaluation itself
        $evaluation->delete();

        log_action('deleted_evaluation', $evaluation, 'Deleted evaluation: ' . $evaluation->title);

        return redirect()->route('admin.evaluations.index')->with('success', 'Evaluation deleted successfully.');
    }
}
