<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Models\Currency;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $rates = ExchangeRate::orderByDesc('fetched_at')->paginate(15);
        return view('admin.exchange_rates.index', compact('rates'));
    }

    public function create()
    {
        $currencies = Currency::where('active', 1)->orderBy('code')->get();
        return view('admin.exchange_rates.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'base_currency' => 'required|string|size:3',
            'target_currency' => 'required|string|size:3',
            'rate' => 'required|numeric|min:0',
            'fetched_at' => 'required|date',
        ]);

        ExchangeRate::create($request->all());

        return redirect()->route('admin.exchange-rates.index')
            ->with('success', __('exchange_rate.messages.created'));
    }

    public function edit(ExchangeRate $exchangeRate)
    {
        $currencies = Currency::where('active', 1)->orderBy('code')->get();
        return view('admin.exchange_rates.edit', compact('exchangeRate', 'currencies'));
    }

    public function update(Request $request, ExchangeRate $exchangeRate)
    {
        $request->validate([
            'base_currency' => 'required|string|size:3',
            'target_currency' => 'required|string|size:3',
            'rate' => 'required|numeric|min:0',
            'fetched_at' => 'required|date',
        ]);

        $exchangeRate->update($request->all());

        return redirect()->route('admin.exchange-rates.index')
            ->with('success', __('exchange_rate.messages.updated'));
    }

    public function destroy(ExchangeRate $exchangeRate)
    {
        $exchangeRate->delete();

        return redirect()->route('admin.exchange-rates.index')
            ->with('success', __('exchange_rate.messages.deleted'));
    }
}
