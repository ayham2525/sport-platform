<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Item;
use App\Models\Role;
use App\Models\User;
use App\Models\State;
use App\Models\Branch;
use App\Models\Player;
use App\Models\Academy;
use App\Models\Program;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function getStatesByCountry(Request $request)
    {
        $countryId = $request->country_id;
        $states = State::where('country_id', $countryId)->where('is_active', 1)->get();

        return response()->json($states);
    }

    public function getCitiesByState(Request $request)
    {
        $stateId = $request->state_id;
        $cities = City::where('state_id', $stateId)->where('is_active', 1)->get();

        return response()->json($cities);
    }

    public function getBranchesBySystem($system_id)
    {
        $branches = Branch::where('system_id', $system_id)->get(['id', 'name']);
        return response()->json($branches);
    }

    public function getAcademiesByBranch($branch_id)
    {
        $academies = Academy::where('branch_id', $branch_id)->get(['id', 'name_en']);
        return response()->json($academies);
    }

    public function getRolesBySystem($system_id)
    {
        $roles = Role::where('system_id', $system_id)
            ->select('id', 'name', 'slug')
            ->get();

        return response()->json($roles);
    }

    public function getPlayersBySystem(Request $request, $system_id)
    {
        $players = User::where('system_id', $system_id)
            ->where('role', 'player')
            ->select('id', 'name')
            ->get();

        return response()->json($players);
    }

    public function getByAcademy($academyId)
    {
        $programs = Program::where('academy_id', $academyId)->get(['id', 'name_en', 'price', 'currency']);
        return response()->json($programs);
    }



    public function convertCurrency(Request $request)
    {
        $baseCurrency = strtoupper($request->input('base_currency'));
        $targetCurrency = strtoupper($request->input('target_currency'));
        $amount = floatval($request->input('amount'));

        if (!$baseCurrency || !$targetCurrency || $amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data.'
            ], 422);
        }

        // Look for the exchange rate
        $rate = ExchangeRate::where('base_currency', $baseCurrency)
            ->where('target_currency', $targetCurrency)
            ->orderByDesc('fetched_at')
            ->first();

        if (!$rate) {
            return response()->json([
                'success' => false,
                'message' => 'Exchange rate not found.'
            ], 404);
        }

        $convertedAmount = $amount * $rate->rate;

        return response()->json([
            'success' => true,
            'base_currency' => $baseCurrency,
            'target_currency' => $targetCurrency,
            'rate' => $rate->rate,
            'original_amount' => $amount,
            'converted_amount' => round($convertedAmount, 4)
        ]);
    }



    public function getPlayersByBranch($branch_id)
    {
        return Player::with('user:id,name')->where('branch_id', $branch_id)->get();
    }

    public function getItemsBySystem($system_id)
    {
        $column = app()->getLocale() === 'ar' ? 'name_ar' : 'name_en';
        return Item::where('system_id', $system_id)
            ->select('id', "$column as name" , 'price', 'currency')
            ->get();
    }
}
