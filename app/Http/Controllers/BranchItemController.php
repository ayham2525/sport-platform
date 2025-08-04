<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Branch;
use App\Models\BranchItem;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;


class BranchItemController extends Controller
{
    public function index(Request $request, Branch $branch)
    {
        //dd(session()->all());
        if (!PermissionHelper::hasPermission('view',  BranchItem::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $items = $branch->items()
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name_en', 'like', "%{$request->search}%")
                          ->orWhere('name_ar', 'like', "%{$request->search}%");
                });
            })
            ->paginate(10);

        $availableItems = Item::where('system_id', $branch->system_id)
            ->whereNotIn('id', $branch->items->pluck('id'))
            ->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en', 'id');

        return view('admin.branch.items.index', compact('branch', 'items', 'availableItems'));
    }

    public function store(Request $request, Branch $branch)
    {
        if (!PermissionHelper::hasPermission('create',  BranchItem::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'min_value' => 'nullable|numeric|min:0',
            'max_value' => 'nullable|numeric|gte:min_value',
            'is_professional' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($branch->items()->where('item_id', $request->item_id)->exists()) {
            return back()->withErrors(['item_id' => __('This item is already added to the branch.')])->withInput();
        }

        $branch->items()->attach($request->item_id, [
            'min_value' => $request->min_value,
            'max_value' => $request->max_value,
            'is_professional' => $request->is_professional ? 1 : 0,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.branches.items', $branch)->with('success', __('branch.item_added_success'));
    }

    public function destroy(Branch $branch, Item $item)
    {
        if (!PermissionHelper::hasPermission('delete',  BranchItem::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $branch->items()->detach($item->id);

        return redirect()->route('admin.branches.items', $branch)->with('success', __('branch.item_deleted_success'));
    }

    public function update(Request $request, Branch $branch, Item $item)
    {
        if (!PermissionHelper::hasPermission('update',  BranchItem::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $validated = $request->validate([
            'min_value' => 'nullable|numeric|min:0',
            'max_value' => 'nullable|numeric|gte:min_value',
            'is_professional' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $branch->items()->updateExistingPivot($item->id, [
            'min_value' => $request->min_value,
            'max_value' => $request->max_value,
            'is_professional' => $request->is_professional ? 1 : 0,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.branches.items', $branch)->with('success', __('branch.item_updated_success'));
    }
}
