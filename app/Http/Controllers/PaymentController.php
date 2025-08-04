<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Branch;
use App\Models\Player;
use App\Models\System;
use App\Models\Academy;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\PermissionHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;


class PaymentController extends Controller
{

    public function index(Request $request)
{
    if (!PermissionHelper::hasPermission('view', Payment::MODEL_NAME)) {
        return PermissionHelper::denyAccessResponse();
    }

    $user = auth()->user();
    $query = Payment::with(['player.user', 'program', 'branch', 'academy', 'paymentMethod']);

    // Role-based filtering
    switch ($user->role) {
        case 'system_admin':
            if ($user->system_id) {
                $branchIds = Branch::where('system_id', $user->system_id)->pluck('id');
                $academyIds = Academy::whereIn('branch_id', $branchIds)->pluck('id');
                $query->whereIn('academy_id', $academyIds);
            } else {
                $query->whereRaw('0 = 1');
            }
            break;

        case 'branch_admin':
            if ($user->branch_id) {
                $academyIds = Academy::where('branch_id', $user->branch_id)->pluck('id');
                $query->whereIn('academy_id', $academyIds);
            } else {
                $query->whereRaw('0 = 1');
            }
            break;

        case 'academy_admin':
        case 'coach':
        case 'player':
            $academyIds = is_array($user->academy_id)
                ? $user->academy_id
                : json_decode($user->academy_id, true) ?? [];

            if (!empty($academyIds)) {
                $query->whereIn('academy_id', $academyIds);
            } else {
                $query->whereRaw('0 = 1');
            }
            break;

        default:
            // full_admin: no restrictions
            break;
    }

    // Filtering
    if ($request->filled('system_id')) {
        $query->whereHas('branch.system', function ($q) use ($request) {
            $q->where('id', $request->system_id);
        });
    }

    if ($request->filled('branch_id')) {
        $query->where('branch_id', $request->branch_id);
    }

    if ($request->filled('academy_id')) {
        $query->where('academy_id', $request->academy_id);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('player.user', function ($q) use ($search) {
            $q->where('name', 'like', "%$search%");
        });
    }

    $payments = $query->latest()->paginate(20);

    // Dropdown options based on role
    switch ($user->role) {
        case 'system_admin':
            $systems = System::where('id', $user->system_id)->get();
            $branchIds = Branch::where('system_id', $user->system_id)->pluck('id');
            $branches = Branch::whereIn('id', $branchIds)->get();
            $academies = Academy::whereIn('branch_id', $branchIds)->get();
            break;

        case 'branch_admin':
            $branches = Branch::where('id', $user->branch_id)->get();
            $systems = System::whereHas('branches', function ($q) use ($user) {
                $q->where('id', $user->branch_id);
            })->get();
            $academies = Academy::where('branch_id', $user->branch_id)->get();
            break;

        case 'academy_admin':
        case 'coach':
        case 'player':
            $academyIds = is_array($user->academy_id)
                ? $user->academy_id
                : json_decode($user->academy_id, true) ?? [];

            $academies = Academy::whereIn('id', $academyIds)->get();
            $branchIds = $academies->pluck('branch_id')->unique();
            $branches = Branch::whereIn('id', $branchIds)->get();
            $systems = System::whereIn('id', Branch::whereIn('id', $branchIds)->pluck('system_id')->unique())->get();
            break;

        default:
            $systems = System::all();
            $branches = Branch::all();
            $academies = Academy::all();
            break;
    }

    return view('admin.payments.index', compact('payments', 'systems', 'branches', 'academies'));
}


public function create()
{
    if (!PermissionHelper::hasPermission('create', Payment::MODEL_NAME)) {
        return PermissionHelper::denyAccessResponse();
    }

    $user = auth()->user();

    // 1. Systems
    if (in_array($user->role, ['system_admin', 'branch_admin', 'academy_admin', 'coach', 'player'])) {
        $systems = System::where('id', $user->system_id)->get();
    } else {
        $systems = System::all();
    }

    // 2. Branches
    $branches = Branch::query();
    if ($user->role === 'system_admin') {
        $branches->where('system_id', $user->system_id);
    } elseif (in_array($user->role, ['branch_admin', 'academy_admin', 'coach', 'player'])) {
        $branches->where('id', $user->branch_id);
    }
    $branches = $branches->get();

    // 3. Academies
    $academies = Academy::query();
    if ($user->role === 'system_admin') {
        $academies->whereHas('branch', function ($q) use ($user) {
            $q->where('system_id', $user->system_id);
        });
    } elseif ($user->role === 'branch_admin') {
        $academies->where('branch_id', $user->branch_id);
    } elseif (in_array($user->role, ['academy_admin', 'coach', 'player'])) {
        $rawAcademyId = $user->academy_id;

        if (is_string($rawAcademyId) && str_starts_with($rawAcademyId, '[')) {
            $academyIds = json_decode($rawAcademyId, true) ?? [];
        } elseif (is_array($rawAcademyId)) {
            $academyIds = $rawAcademyId;
        } elseif (!is_null($rawAcademyId)) {
            $academyIds = [$rawAcademyId];
        } else {
            $academyIds = [];
        }

        $academyIds = array_filter(array_map('intval', $academyIds));
        $academies->whereIn('id', $academyIds);
    }
    $academies = $academies->get();

    // 4. Players
    $playerQuery = Player::with('user');
    if (isset($academyIds) && !empty($academyIds)) {
        $playerQuery->whereIn('academy_id', $academyIds);
    } elseif ($user->role === 'branch_admin') {
        $playerQuery->whereHas('academy', function ($q) use ($user) {
            $q->where('branch_id', $user->branch_id);
        });
    } elseif ($user->role === 'system_admin') {
        $playerQuery->whereHas('academy.branch', function ($q) use ($user) {
            $q->where('system_id', $user->system_id);
        });
    }
    $players = $playerQuery->get();

    return view('admin.payments.create', [
        'systems'        => $systems,
        'branches'       => $branches,
        'academies'      => $academies,
        'players'        => $players,
        'programs'       => Program::all(),
        'paymentMethods' => PaymentMethod::where('is_active', 1)->get(),
        'categories'     => Payment::CATEGORIES,
        'currencies'     => Currency::all(),
    ]);
}





    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|in:program,uniform,asset,camp,class',
            'program_id' => 'nullable|exists:programs,id',
            'player_id' => 'nullable|exists:users,id',
            'branch_id' => 'nullable|integer',
            'academy_id' => 'nullable|integer',
            'system_id' => 'nullable|integer',
            'class_count' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'base_price' => 'required|numeric|min:0',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'currency' => 'required|string|max:3',
            'classes' => 'nullable|array',
            'classes.*' => 'integer|exists:class_models,id',
        ]);

        $baseCurrency = config('app.base_currency', 'AED');

        // Convert base_price if currency differs
        $originalCurrency = strtoupper($request->currency);
        $conversionRate = 1;
        $convertedBasePrice = $request->base_price;
        $convertedTotalPrice = $request->total_price;
        $convertedPaidAmount = $request->paid_amount;

        if ($originalCurrency !== $baseCurrency) {
            $rate = ExchangeRate::where('base_currency', $originalCurrency)
                ->where('target_currency', $baseCurrency)
                ->orderByDesc('fetched_at')
                ->first();

            if (!$rate) {
                return redirect()->back()->withErrors(['currency' => 'Exchange rate not found for ' . $originalCurrency . ' to ' . $baseCurrency]);
            }

            $conversionRate = $rate->rate;
            $convertedBasePrice = $request->base_price * $conversionRate;
            $convertedTotalPrice = $request->total_price * $conversionRate;
            $convertedPaidAmount = $request->paid_amount * $conversionRate;
        }

        $payment = new Payment();

        $payment->system_id = $request->filled('system_id') ? (int)$request->system_id : null;
        $payment->branch_id = $request->filled('branch_id') ? (int)$request->branch_id : null;
        $payment->academy_id = $request->filled('academy_id') ? (int)$request->academy_id : null;
        $payment->category = $request->category;

        if (in_array($request->category, ['program', 'uniform', 'class'])) {
            $payment->player_id = $request->player_id;
            $payment->program_id = $request->program_id;
            if ($request->category === 'class' && is_array($request->classes)) {
                $payment->class_count = count($request->classes);
            } else {
                $payment->class_count = $request->class_count;
            }
        }

        // Store amounts in base currency
        $payment->base_price = $convertedBasePrice;
        $payment->total_price = $convertedTotalPrice;
        $payment->paid_amount = $convertedPaidAmount;
        $payment->remaining_amount = $convertedTotalPrice - $convertedPaidAmount;

        $payment->status = $payment->remaining_amount == 0 ? 'paid' : 'partial';
        $payment->payment_method_id = $request->payment_method_id;
        $payment->payment_date = now();
        $payment->note = $request->note;

        // Also record original currency and rate for audit
        $payment->original_currency = $originalCurrency;
        $payment->exchange_rate_used = $conversionRate;

        $payment->save();

        if ($request->category === 'class' && is_array($request->classes)) {
            $payment->classes()->sync(
                collect($request->classes)->mapWithKeys(function ($classId) {
                    return [$classId => ['quantity' => 1]];
                })->toArray()
            );
        }

        if ($request->filled('items')) {
            $items = json_decode($request->items, true);
            if (is_array($items)) {
                foreach ($items as &$item) {
                    // Ensure price and currency are present (default values if missing)
                    $originalPrice = isset($item['price']) ? floatval($item['price']) : 0;
                    $originalCurrency = isset($item['currency']) ? strtoupper($item['currency']) : $baseCurrency;

                    $item['price'] = $originalPrice;
                    $item['currency'] = $originalCurrency;

                    if ($originalCurrency !== $baseCurrency) {
                        $rate = ExchangeRate::where('base_currency', $originalCurrency)
                            ->where('target_currency', $baseCurrency)
                            ->orderByDesc('fetched_at')
                            ->first();

                        if ($rate) {
                            $item['converted_price'] = $originalPrice * $rate->rate;
                            $item['exchange_rate_used'] = $rate->rate;
                        } else {
                            $item['converted_price'] = $originalPrice;
                            $item['exchange_rate_used'] = 1;
                        }
                    } else {
                        $item['converted_price'] = $originalPrice;
                        $item['exchange_rate_used'] = 1;
                    }
                }

                $payment->items = json_encode($items);
                $payment->save();
            }
        }

        return redirect()->route('admin.payments.index')
            ->with('success', __('payment.messages.payment_created_successfully'));
    }




    public function show($id)
    {
        $payment = Payment::with(['player', 'program', 'system'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {

        $payment->load(['classes']);
        $selectedClasses = $payment->classes()->pluck('class_models.id')->toArray();
        $currencies = Currency::all();

        $systems = System::all();
        $paymentMethods = PaymentMethod::all();

        return view('admin.payments.edit', compact('payment', 'systems', 'paymentMethods', 'selectedClasses', 'currencies'));
    }


  public function update(Request $request, Payment $payment)
{


    $request->validate([
        'category' => 'required|in:program,uniform,asset,camp,class',
        'program_id' => 'nullable|exists:programs,id',
        'branch_id' => 'nullable|integer',
        'academy_id' => 'nullable|integer',
        'system_id' => 'nullable|integer',
        'class_count' => 'nullable|numeric|min:0',
        'total_price' => 'required|numeric|min:0',
        'paid_amount' => 'required|numeric|min:0',
        'base_price' => 'required|numeric|min:0',
        'payment_method_id' => 'required|exists:payment_methods,id',
        'currency' => 'required|string|max:3',
        'classes' => 'nullable|array',
        'classes.*' => 'integer|exists:class_models,id',
    ]);

    $baseCurrency = config('app.base_currency', 'AED');
    $originalCurrency = strtoupper($request->currency);

    $conversionRate = 1;
    $convertedBasePrice = $request->base_price;
    $convertedTotalPrice = $request->total_price;
    $convertedPaidAmount = $request->paid_amount;

    if ($originalCurrency !== $baseCurrency) {
        $rate = ExchangeRate::where('base_currency', $originalCurrency)
            ->where('target_currency', $baseCurrency)
            ->orderByDesc('fetched_at')
            ->first();

        if (!$rate) {
            return redirect()->back()->withErrors([
                'currency' => 'Exchange rate not found for ' . $originalCurrency . ' to ' . $baseCurrency
            ]);
        }

        $conversionRate = $rate->rate;
        $convertedBasePrice *= $conversionRate;
        $convertedTotalPrice *= $conversionRate;
        $convertedPaidAmount *= $conversionRate;
    }

    // Basic info
    $payment->system_id = $request->filled('system_id') ? (int)$request->system_id : null;
    $payment->branch_id = $request->filled('branch_id') ? (int)$request->branch_id : null;
    $payment->academy_id = $request->filled('academy_id') ? (int)$request->academy_id : null;
    $payment->category = $request->category;

    if (in_array($request->category, ['program', 'uniform', 'class'])) {
        $payment->player_id = $request->player_id;
        $payment->program_id = $request->program_id;

        $payment->class_count = $request->category === 'class' && is_array($request->classes)
            ? count($request->classes)
            : $request->class_count;
    } else {
        $payment->player_id = null;
        $payment->program_id = null;
        $payment->class_count = null;
    }

    $payment->base_price = $convertedBasePrice;
    $payment->total_price = $convertedTotalPrice;
    $payment->paid_amount = $convertedPaidAmount;
    $payment->remaining_amount = $convertedTotalPrice - $convertedPaidAmount;
    $payment->status = $payment->remaining_amount == 0 ? 'paid' : 'partial';
    $payment->payment_method_id = $request->payment_method_id;
    $payment->note = $request->note;
    $payment->original_currency = $originalCurrency;
    $payment->exchange_rate_used = $conversionRate;

    $payment->save();

    // Sync classes
    if ($request->category === 'class' && is_array($request->classes)) {
        $payment->classes()->sync(
            collect($request->classes)->mapWithKeys(fn($classId) => [$classId => ['quantity' => 1]])->toArray()
        );
    } else {
        $payment->classes()->detach();
    }

    // Update items
    if ($request->filled('items')) {
        $items = json_decode($request->items, true);

        if (is_array($items)) {
            foreach ($items as &$item) {
                // Ensure required keys exist

                $item['price'] = $item['price'] ?? 0;
                $item['currency'] = $item['currency'] ?? $originalCurrency;
                $item['item_id'] = $item['item_id'] ?? uniqid('item_');

                if ($item['currency'] !== $baseCurrency) {
                    $rate = ExchangeRate::where('base_currency', $item['currency'])
                        ->where('target_currency', $baseCurrency)
                        ->orderByDesc('fetched_at')
                        ->first();

                    $item['converted_price'] = $rate ? $item['price'] * $rate->rate : $item['price'];
                    $item['exchange_rate_used'] = $rate->rate ?? 1;
                } else {
                    $item['converted_price'] = $item['price'];
                    $item['exchange_rate_used'] = 1;
                }
            }

            $payment->items = json_encode($items);
        } else {
            $payment->items = null;
        }
    } else {
        $payment->items = null;
    }

    $payment->save();

    return redirect()->route('admin.payments.index')
        ->with('success', __('payment.messages.payment_updated_successfully'));
}



    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();
        return redirect()->route('admin.payments.index')->with('success', __('payment.messages.payment_deleted_successfully'));
    }

    public function invoice(Payment $payment)
    {
        $payment->load([
            'player.user',
            'program',
            'classes.academy', // Load academy relation for class
            'paymentMethod',
            'branch',
            'academy',
        ]);

        $items = [];
        if ($payment->items) {
            $items = json_decode($payment->items, true);
        }

        // Load item names
        $itemIds = collect($items)->pluck('item_id')->unique()->toArray();
        $itemsMap = Item::whereIn('id', $itemIds)->pluck('name_en', 'id')->toArray();

        $pdf = PDF::loadView('admin.payments.invoice', [
            'payment' => $payment,
            'items' => $items,
            'itemsMap' => $itemsMap,
        ]);

        $filename = 'invoice_payment_' . $payment->id . '.pdf';

        return $pdf->download($filename);
    }


    public function createFromPlayer(Request $request)
    {
        $player = Player::with(['branch'])->findOrFail($request->player_id);

        return view('admin.payments._form', compact('player')); // Optional: AJAX form partial
    }

    public function storeFromPlayer(Request $request)
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'branch_id' => 'required|exists:branches,id',
            'total_price' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:pending,partial,paid',
            'status_student' => 'nullable|string|in:new,renewal',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'discount' => 'nullable|numeric|min:0',
            'reset_number' => 'nullable|string|max:255',
            'class_time_from' => 'nullable|date_format:H:i',
            'class_time_to' => 'nullable|date_format:H:i',
            'note' => 'nullable|string|max:1000',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->only([
            'player_id',
            'branch_id',
            'total_price',
            'payment_date',
            'start_date',
            'end_date',
            'status',
            'status_student',
            'payment_method_id',
            'discount',
            'reset_number',
            'class_time_from',
            'class_time_to',
            'note',
        ]);

        $data['currency'] = 'AED';
        $data['remaining_amount'] = $data['total_price'] - ($data['discount'] ?? 0);
        $data['base_price'] = $data['total_price'];
        $data['vat_percent'] = 5.00;
        $data['vat_amount'] = round(($data['total_price'] * 5) / 100, 2);

        if ($request->hasFile('receipt_file')) {
            $data['receipt_path'] = $request->file('receipt_file')->store('receipts', 'public');
        }

        Payment::create($data);

        return redirect()->back()->with('success', __('player.messages.payment_created_successfully'));
    }


    public function editPlayerPayment(Payment $payment)
    {

        return response()->json($payment->load('branch', 'paymentMethod'));
    }

    public function updateFromPlayer(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'total_price' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:pending,partial,paid',
            'status_student' => 'nullable|string|in:new,renewal',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'discount' => 'nullable|numeric|min:0',
            'reset_number' => 'nullable|string|max:255',
            'class_time_from' => 'nullable|date_format:H:i',
            'class_time_to' => 'nullable|date_format:H:i',
            'note' => 'nullable|string|max:1000',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = $request->only([
            'total_price',
            'payment_date',
            'start_date',
            'end_date',
            'status',
            'status_student',
            'payment_method_id',
            'discount',
            'reset_number',
            'class_time_from',
            'class_time_to',
            'note',
        ]);

        $data['currency'] = 'AED';
        $data['remaining_amount'] = $data['total_price'] - ($data['discount'] ?? 0);
        $data['base_price'] = $data['total_price'];
        $data['vat_percent'] = 5.00;
        $data['vat_amount'] = round(($data['total_price'] * 5) / 100, 2);

        // Replace old file if new uploaded
        if ($request->hasFile('receipt_file')) {
            if ($payment->receipt_path && Storage::disk('public')->exists($payment->receipt_path)) {
                Storage::disk('public')->delete($payment->receipt_path);
            }
            $data['receipt_path'] = $request->file('receipt_file')->store('receipts', 'public');
        }

        $payment->update($data);

        return redirect()->back()->with('success', __('player.messages.payment_updated_successfully'));
    }

    public function export(Request $request)
    {
        if (!PermissionHelper::hasPermission('export', Payment::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        return Excel::download(new PaymentsExport($request), 'payments.xlsx');
    }
}
