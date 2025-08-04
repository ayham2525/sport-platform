<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\System;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;


class ItemController extends Controller
{
    public function index()
    {
        if (!PermissionHelper::hasPermission('view', Item::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $items = Item::with('system', 'currency')->latest()->paginate(10);

        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        if (!PermissionHelper::hasPermission('create', Item::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $systems = System::all();
        $currencies = Currency::all();
        return view('admin.items.create', compact('systems', 'currencies'));
    }

    public function store(Request $request)
    {
        if (!PermissionHelper::hasPermission('create', Item::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'system_id' => 'required|exists:systems,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'active' => 'nullable|boolean',
        ]);

        Item::create([
            'system_id' => $request->system_id,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'price' => $request->price,
            'currency_id' => $request->currency_id,
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('admin.items.index')->with('success', __('item.messages.created'));
    }

    public function edit(Item $item)
    {
        if (!PermissionHelper::hasPermission('update', Item::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $systems = System::all();
        $currencies = Currency::all();
        return view('admin.items.edit', compact('item', 'systems', 'currencies'));
    }

    public function show($id)
    {
        if (!PermissionHelper::hasPermission('view', Item::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $item = Item::with(['system', 'currency'])->findOrFail($id);
        return view('admin.items.show', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        if (!PermissionHelper::hasPermission('update', Item::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'system_id' => 'required|exists:systems,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'active' => 'nullable|boolean',
        ]);

        $item->update([
            'system_id' => $request->system_id,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'price' => $request->price,
            'currency_id' => $request->currency_id,
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('admin.items.index')->with('success', __('item.messages.updated'));
    }

    public function destroy(Item $item)
    {
        if (!PermissionHelper::hasPermission('delete', Item::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $item->delete();
        return redirect()->route('admin.items.index')->with('success', __('item.messages.deleted'));
    }
}
