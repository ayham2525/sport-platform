<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class CurrencyController extends Controller
{
    public function index()
    {
        if (!PermissionHelper::hasPermission('view', Currency::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $currencies = Currency::orderBy('code')->get();
        return view('admin.currencies.index', compact('currencies'));
    }

    public function create()
    {
         if (!PermissionHelper::hasPermission('create', Currency::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        return view('admin.currencies.create');
    }

    public function store(Request $request)
    {
         if (!PermissionHelper::hasPermission('create', Currency::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'code'   => 'required|string|size:3|unique:currencies,code',
            'name'   => 'required|string|max:100',
            'symbol' => 'nullable|string|max:10',
            'active' => 'boolean',
        ]);

        Currency::create($request->only(['code', 'name', 'symbol', 'active']));

        return redirect()->route('admin.currencies.index')->with('success', __('Currency created successfully.'));
    }

    public function edit(Currency $currency)
    {
         if (!PermissionHelper::hasPermission('update', Currency::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        return view('admin.currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
         if (!PermissionHelper::hasPermission('update', Currency::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'code'   => 'required|string|size:3|unique:currencies,code,' . $currency->id,
            'name'   => 'required|string|max:100',
            'symbol' => 'nullable|string|max:10',
            'active' => 'boolean',
        ]);

        $currency->update($request->only(['code', 'name', 'symbol', 'active']));

        return redirect()->route('admin.currencies.index')->with('success', __('Currency updated successfully.'));
    }

    public function destroy(Currency $currency)
    {
         if (!PermissionHelper::hasPermission('delete', Currency::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $currency->delete();

        return redirect()->route('admin.currencies.index')->with('success', __('Currency deleted successfully.'));
    }
}
