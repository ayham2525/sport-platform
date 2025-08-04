<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\EvaluationCriteria;
use App\Http\Controllers\Controller;

class EvaluationCriteriaController extends Controller
{
    public function store(Request $request)
    {
        $criterion = EvaluationCriteria::create([
            'evaluation_id' => $request->evaluation_id,
            'label' => $request->label,
            'input_type' => $request->input_type,
            'weight' => $request->weight ?? 1,
            'order' => $request->order ?? 0,
            'required' => $request->required ? true : false,
        ]);

        log_action(
            'created_criterion',
            $criterion,
            'Created new evaluation criterion: ' . $criterion->label,
            $criterion->toArray()
        );

        return back()->with('success', 'Criterion added successfully.');
    }

    public function update(Request $request, $id)
    {
        $criterion = EvaluationCriteria::findOrFail($id);
        $before = $criterion->toArray();

        $criterion->update([
            'label' => $request->label,
            'input_type' => $request->input_type,
            'weight' => $request->weight,
            'order' => $request->order,
            'required' => $request->required ? true : false,
        ]);

        $after = $criterion->fresh()->toArray();

        log_action(
            'updated_criterion',
            $criterion,
            'Updated evaluation criterion: ' . $criterion->label,
            ['before' => $before, 'after' => $after]
        );

        return response()->json(['message' => 'Criterion updated successfully.']);
    }

    public function destroy($id)
    {
        $criterion = EvaluationCriteria::findOrFail($id);
        $data = $criterion->toArray();

        $criterion->delete();

        log_action(
            'deleted_criterion',
            $criterion,
            'Deleted evaluation criterion: ' . ($data['label'] ?? 'unknown'),
            $data
        );

        return back()->with('success', 'Criterion deleted successfully.');
    }
}
    