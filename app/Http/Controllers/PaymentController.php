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
    if ($request->filled('player_id')) {
        $query->where('player_id', $request->player_id);
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
        'category'           => 'required|in:program,uniform,asset,camp,class',
        'program_id'         => 'nullable|exists:programs,id',
        'player_id'          => 'nullable|exists:players,id',
        'branch_id'          => 'nullable|integer',
        'academy_id'         => 'nullable|integer',
        'system_id'          => 'nullable|integer',
        'class_count'        => 'nullable|numeric|min:0',
        'total_price'        => 'required|numeric|min:0',
        'paid_amount'        => 'required|numeric|min:0',
        'base_price'         => 'required|numeric|min:0',
        'vat_percent'        => 'nullable|numeric|min:0',
        'is_vat_inclusive'   => 'required|boolean',
        'payment_method_id'  => 'required|exists:payment_methods,id',
        'currency'           => 'required|string|max:3',
        'classes'            => 'nullable|array',
        'classes.*'          => 'integer|exists:class_models,id',
        'payment_date'       => 'nullable|date',
        'start_date'         => 'nullable|date',
        'end_date'           => 'nullable|date|after_or_equal:start_date',
    ]);

    $baseCurrency = config('app.base_currency', 'AED');

    // Inputs (original currency)
    $originalCurrency = strtoupper($request->currency);
    $vatPercent       = (float) ($request->vat_percent ?? 0);
    $isVatInclusive   = (bool) $request->is_vat_inclusive;

    // Start with user-entered numbers
    $enteredBase  = (float) $request->base_price;
    $enteredTotal = (float) $request->total_price;
    $enteredPaid  = (float) $request->paid_amount;

    // Currency conversion to base currency
    $conversionRate = 1.0;
    if ($originalCurrency !== $baseCurrency) {
        $rate = ExchangeRate::where('base_currency', $originalCurrency)
            ->where('target_currency', $baseCurrency)
            ->orderByDesc('fetched_at')
            ->first();

        if (!$rate) {
            return back()->withErrors([
                'currency' => 'Exchange rate not found for ' . $originalCurrency . ' to ' . $baseCurrency
            ])->withInput();
        }

        $conversionRate = (float) $rate->rate;
    }

    $convertedBase  = $enteredBase  * $conversionRate;
    $convertedTotal = $enteredTotal * $conversionRate;
    $convertedPaid  = $enteredPaid  * $conversionRate;

    // --- VAT math in base currency ---
    if ($vatPercent < 0) { $vatPercent = 0; }

    if ($isVatInclusive) {
        // total includes VAT; derive vat and base from total
        $v = $vatPercent / 100.0;
        $vatAmount     = $v > 0 ? ($convertedTotal - ($convertedTotal / (1 + $v))) : 0.0;
        $convertedBase = $convertedTotal - $vatAmount;
    } else {
        // base excludes VAT; compute total
        $vatAmount     = $convertedBase * ($vatPercent / 100.0);
        $convertedTotal = $convertedBase + $vatAmount;
    }

    // Round to 2 decimals
    $convertedBase  = round($convertedBase, 2);
    $vatAmount      = round($vatAmount, 2);
    $convertedTotal = round($convertedTotal, 2);
    $convertedPaid  = round($convertedPaid, 2);

    $payment = new Payment();

    // Basic refs

    $payment->system_id  = $request->filled('system_id')  ? (int)$request->system_id  : null;
    $payment->branch_id  = $request->filled('branch_id')  ? (int)$request->branch_id  : null;
    $payment->academy_id = $request->filled('academy_id') ? (int)$request->academy_id : null;
    $payment->category   = $request->category;

    if (in_array($request->category, ['program', 'uniform', 'class'])) {
        $payment->player_id  = $request->player_id;
        $payment->program_id = $request->program_id;
        $payment->class_count = ($request->category === 'class' && is_array($request->classes))
            ? count($request->classes)
            : $request->class_count;
    }

    // Amounts (stored in base currency)
    $payment->base_price        = $convertedBase;
    $payment->vat_percent       = $vatPercent;
    $payment->vat_amount        = $vatAmount;
    $payment->total_price       = $convertedTotal;
    $payment->paid_amount       = $convertedPaid;
    $payment->remaining_amount  = $convertedTotal - $convertedPaid;

    // Currency meta
    $payment->currency           = $baseCurrency;       // stored currency
    $payment->original_currency  = $originalCurrency;   // user-selected
    $payment->exchange_rate_used = $conversionRate;

    // VAT flag
    $payment->is_vat_inclusive = $isVatInclusive;

    // Status & misc
    $payment->status            = $payment->remaining_amount == 0.0 ? 'paid' : ($payment->paid_amount > 0 ? 'partial' : 'pending');
    $payment->payment_method_id = $request->payment_method_id;
    $payment->note              = $request->note;

    // NEW: dates (use provided value, default payment_date to today if empty)
    $payment->payment_date = $request->filled('payment_date')
        ? $request->payment_date
        : now()->toDateString();
    $payment->start_date   = $request->filled('start_date') ? $request->start_date : null;
    $payment->end_date     = $request->filled('end_date')   ? $request->end_date   : null;

    $payment->save();

    // Sync classes
    if ($request->category === 'class' && is_array($request->classes)) {
        $payment->classes()->sync(
            collect($request->classes)->mapWithKeys(fn($classId) => [$classId => ['quantity' => 1]])->toArray()
        );
    }

    // Items (keep your conversion per item)
    if ($request->filled('items')) {
        $items = json_decode($request->items, true);
        if (is_array($items)) {
            foreach ($items as &$item) {
                $origPrice    = isset($item['price']) ? (float)$item['price'] : 0.0;
                $origCurrItem = isset($item['currency']) ? strtoupper($item['currency']) : $baseCurrency;

                $item['price']    = $origPrice;
                $item['currency'] = $origCurrItem;

                if ($origCurrItem !== $baseCurrency) {
                    $rate = ExchangeRate::where('base_currency', $origCurrItem)
                        ->where('target_currency', $baseCurrency)
                        ->orderByDesc('fetched_at')
                        ->first();

                    if ($rate) {
                        $item['converted_price']    = round($origPrice * (float)$rate->rate, 2);
                        $item['exchange_rate_used'] = (float)$rate->rate;
                    } else {
                        $item['converted_price']    = round($origPrice, 2);
                        $item['exchange_rate_used'] = 1;
                    }
                } else {
                    $item['converted_price']    = round($origPrice, 2);
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
        'category'          => 'required|in:program,uniform,asset,camp,class',
        'program_id'        => 'nullable|exists:programs,id',
        'player_id'         => 'nullable|exists:players,id',
        'branch_id'         => 'nullable|integer',
        'academy_id'        => 'nullable|integer',
        'system_id'         => 'nullable|integer',
        'class_count'       => 'nullable|numeric|min:0',
        'base_price'        => 'required|numeric|min:0',
        'total_price'       => 'required|numeric|min:0',
        'paid_amount'       => 'required|numeric|min:0',
        'vat_percent'       => 'nullable|numeric|min:0',
        'is_vat_inclusive'  => 'required|boolean',
        'payment_method_id' => 'required|exists:payment_methods,id',
        'currency'          => 'required|string|max:3',
        'classes'           => 'nullable|array',
        'classes.*'         => 'integer|exists:class_models,id',

        // NEW: dates
        'payment_date'      => 'nullable|date',
        'start_date'        => 'nullable|date',
        'end_date'          => 'nullable|date|after_or_equal:start_date',
    ]);

    $baseCurrency     = config('app.base_currency', 'AED');
    $originalCurrency = strtoupper($request->currency);
    $vatPercent       = (float) ($request->vat_percent ?? 0);
    $isVatInclusive   = (bool)  $request->is_vat_inclusive;

    // Incoming (user-entered, in original currency)
    $enteredBase  = (float) $request->base_price;
    $enteredTotal = (float) $request->total_price;
    $enteredPaid  = (float) $request->paid_amount;

    // Convert to base currency
    $conversionRate = 1.0;
    if ($originalCurrency !== $baseCurrency) {
        $rate = ExchangeRate::where('base_currency', $originalCurrency)
            ->where('target_currency', $baseCurrency)
            ->orderByDesc('fetched_at')
            ->first();

        if (!$rate) {
            return back()->withErrors([
                'currency' => 'Exchange rate not found for ' . $originalCurrency . ' to ' . $baseCurrency
            ])->withInput();
        }
        $conversionRate = (float) $rate->rate;
    }

    $convertedBase  = $enteredBase  * $conversionRate;
    $convertedTotal = $enteredTotal * $conversionRate;
    $convertedPaid  = $enteredPaid  * $conversionRate;

    // VAT normalization in base currency
    if ($vatPercent < 0) { $vatPercent = 0; }

    if ($isVatInclusive) {
        // total already includes VAT -> derive base & VAT from total
        $v         = $vatPercent / 100.0;
        $vatAmount = $v > 0 ? ($convertedTotal - ($convertedTotal / (1 + $v))) : 0.0;
        $convertedBase = $convertedTotal - $vatAmount;
    } else {
        // base excludes VAT -> derive VAT & total from base
        $vatAmount     = $convertedBase * ($vatPercent / 100.0);
        $convertedTotal = $convertedBase + $vatAmount;
    }

    // Round money
    $convertedBase  = round($convertedBase, 2);
    $vatAmount      = round($vatAmount, 2);
    $convertedTotal = round($convertedTotal, 2);
    $convertedPaid  = round($convertedPaid, 2);

    // Basic info
    $payment->system_id  = $request->filled('system_id')  ? (int) $request->system_id  : null;
    $payment->branch_id  = $request->filled('branch_id')  ? (int) $request->branch_id  : null;
    $payment->academy_id = $request->filled('academy_id') ? (int) $request->academy_id : null;
    $payment->category   = $request->category;

    if (in_array($request->category, ['program', 'uniform', 'class'])) {
        $payment->player_id   = $request->player_id;
        $payment->program_id  = $request->program_id;
        $payment->class_count = $request->category === 'class' && is_array($request->classes)
            ? count($request->classes)
            : $request->class_count;
    } else {
        $payment->player_id = null;
        $payment->program_id = null;
        $payment->class_count = null;
    }

    // Monetary fields (stored in base currency)
    $payment->base_price        = $convertedBase;
    $payment->vat_percent       = $vatPercent;
    $payment->vat_amount        = $vatAmount;
    $payment->total_price       = $convertedTotal;
    $payment->paid_amount       = $convertedPaid;
    $payment->remaining_amount  = $convertedTotal - $convertedPaid;

    // Currency meta
    $payment->currency           = $baseCurrency;        // storage currency
    $payment->original_currency  = $originalCurrency;    // user-entered currency
    $payment->exchange_rate_used = $conversionRate;

    // VAT flag
    $payment->is_vat_inclusive = $isVatInclusive;

    // Status & other fields
    $payment->status            = $payment->remaining_amount == 0.0
        ? 'paid'
        : ($payment->paid_amount > 0 ? 'partial' : 'pending');
    $payment->payment_method_id = $request->payment_method_id;
    $payment->note              = $request->note;

    // NEW: dates (use provided values; keep existing if omitted)
    $payment->payment_date = $request->filled('payment_date')
        ? $request->payment_date
        : ($payment->payment_date ?? now()->toDateString());
    $payment->start_date   = $request->filled('start_date') ? $request->start_date : $payment->start_date;
    $payment->end_date     = $request->filled('end_date')   ? $request->end_date   : $payment->end_date;

    $payment->save();

    // Sync classes
    if ($request->category === 'class' && is_array($request->classes)) {
        $payment->classes()->sync(
            collect($request->classes)->mapWithKeys(fn($id) => [$id => ['quantity' => 1]])->toArray()
        );
    } else {
        $payment->classes()->detach();
    }

    // Update items (preserve per-item original currency + converted price)
    if ($request->filled('items')) {
        $items = json_decode($request->items, true);
        if (is_array($items)) {
            foreach ($items as &$item) {
                $origPrice = isset($item['price']) ? (float) $item['price'] : 0.0;
                $origCurr  = isset($item['currency']) ? strtoupper($item['currency']) : $baseCurrency;

                $item['price']    = $origPrice;
                $item['currency'] = $origCurr;

                if ($origCurr !== $baseCurrency) {
                    $rate = ExchangeRate::where('base_currency', $origCurr)
                        ->where('target_currency', $baseCurrency)
                        ->orderByDesc('fetched_at')
                        ->first();

                    if ($rate) {
                        $item['converted_price']    = round($origPrice * (float)$rate->rate, 2);
                        $item['exchange_rate_used'] = (float)$rate->rate;
                    } else {
                        $item['converted_price']    = round($origPrice, 2);
                        $item['exchange_rate_used'] = 1;
                    }
                } else {
                    $item['converted_price']    = round($origPrice, 2);
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
        'classes.academy',
        'paymentMethod',
        'branch',
        'academy',
    ]);

    // ---- Normalize $items to [{ item_id:int|null, quantity:int, name:string|null }]
    $decoded = json_decode($payment->items ?? '[]', true);
    $decoded = is_array($decoded) ? $decoded : [];

    // Detect associative vs list without relying on PHP 8.1 array_is_list
    $isAssoc = static fn(array $a) => array_keys($a) !== range(0, count($a) - 1);

    // If it's an assoc like {"12":3,"19":1} convert to list
    if ($isAssoc($decoded)) {
        $decoded = collect($decoded)->map(function ($v, $k) {
            if (is_array($v)) {
                return [
                    'item_id'  => $v['item_id'] ?? $v['id'] ?? $v['product_id'] ?? null,
                    'quantity' => (int)($v['quantity'] ?? $v['qty'] ?? 1),
                    'name'     => $v['name'] ?? null,
                ];
            }
            // key is id, value is qty
            return [
                'item_id'  => is_numeric($k) ? (int)$k : null,
                'quantity' => (int)$v,
                'name'     => null,
            ];
        })->values()->all();
    }

    $items = collect($decoded)->map(function ($it) {
        $id   = $it['item_id'] ?? $it['id'] ?? $it['product_id'] ?? null;
        $qty  = (int)($it['quantity'] ?? $it['qty'] ?? 1);
        $name = $it['name'] ?? null;
        return ['item_id' => $id !== null ? (int)$id : null, 'quantity' => $qty, 'name' => $name];
    })->filter(fn ($it) => $it['item_id'] !== null || !empty($it['name']))->values();

    $itemIds  = $items->pluck('item_id')->filter()->unique()->values()->all();
    $itemsMap = empty($itemIds) ? [] : Item::whereIn('id', $itemIds)->pluck('name_en', 'id')->toArray();

    // ---- Logo as data URI for DomPDF

    $fileName = ((int)$payment->system_id === 2) ? 'logo-letter-1.jpeg' : '1.jpg';
    $logoPath = public_path('assets/media/logos/' . $fileName);
    $logoDataUri = null;
    if (is_file($logoPath)) {
        $mime = 'image/jpeg';
        $logoDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
    }

    $pdf = PDF::loadView('admin.payments.invoice', [
        'payment'     => $payment,
        'items'       => $items->all(),
        'itemsMap'    => $itemsMap,
        'logoDataUri' => $logoDataUri,
    ]);

    return $pdf->download('invoice_payment_' . $payment->id . '.pdf');
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
