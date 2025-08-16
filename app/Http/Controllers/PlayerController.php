<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Sport;
use App\Models\Branch;
use App\Models\Player;
use App\Models\System;
use App\Models\Academy;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Currency;
use App\Models\ClassModel;
use App\Models\Nationality;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;
use App\Mail\NewPlayerCredentials;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;



class PlayerController extends Controller
{
    public function index(Request $request)
    {
        if (!PermissionHelper::hasPermission('view', Player::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $user = auth()->user();
        $query = Player::with('user', 'branch', 'academy', 'nationality', 'sport');
        $sports = Sport::all();



        // Restrict players based on role
        switch ($user->role) {
            case 'system_admin':
                if ($user->system_id) {
                    $branchIds = Branch::where('system_id', $user->system_id)->pluck('id');
                    $academyIds = Academy::whereIn('branch_id', $branchIds)->pluck('id')->toArray();
                    $query->whereIn('academy_id', $academyIds);
                } else {
                    $query->whereRaw('0 = 1');
                }
                break;

            case 'branch_admin':
                if ($user->branch_id) {
                    $academyIds = Academy::where('branch_id', $user->branch_id)->pluck('id')->toArray();
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
                // full_admin: no filtering
                break;
        }

        // Apply filters
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

        if ($request->filled('sport_id')) {
            $query->where('sport_id', $request->sport_id);
        }

        if ($request->filled('search')) {
            $query->where('player_code', 'like', '%' . $request->search . '%');
        }

        $players = $query->latest()->paginate(50);

        if ($request->ajax()) {
            return view('admin.player.partials.table', compact('players'))->render();
        }

        // Role-based dropdown filters
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

        return view('admin.player.index', compact('players', 'systems', 'branches', 'academies', 'sports'));
    }








    public function create()
    {
        if (!PermissionHelper::hasPermission('view', Player::MODEL_NAME)) {
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


        // 4. Other data
        $nationalities = Nationality::all();
        $sports = Sport::all();

        return view('admin.player.create', compact(
            'systems',
            'branches',
            'academies',
            'nationalities',
            'sports'
        ));
    }



    public function store(Request $request)
    {

        if (!PermissionHelper::hasPermission('create', Player::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:6|confirmed',
            'branch_id'         => 'required|exists:branches,id',
            'academy_id'        => 'required',
            'nationality_id'    => 'required|exists:nationalities,id',
            'sport_id'          => 'required|exists:sports,id',
            'birth_date'        => 'required|date',
            'gender'            => 'required|in:male,female',
            'player_code'       => 'required|unique:players,player_code',
            'previous_school'   => 'nullable|string|max:100',
            'previous_academy'  => 'nullable|string|max:100',
            'level'             => 'nullable|string|in:beginner,intermediate,advanced',
            'position'          => 'nullable|string|max:100',
            'shirt_size'        => 'nullable|string|max:50',
            'shorts_size'       => 'nullable|string|max:50',
            'shoe_size'         => 'nullable|string|max:50',
            'guardian_name'     => 'nullable|string|max:255',
            'guardian_phone'    => 'nullable|string|max:50',
            'medical_notes'     => 'nullable|string|max:1000',
            'remarks'           => 'nullable|string|max:1000',
        ]);

        // Normalize academy_id to int
        $rawAcademyId = $request->academy_id;
        if (is_array($rawAcademyId)) {
            $academyId = (int) $rawAcademyId[0];
        } elseif (is_string($rawAcademyId) && str_starts_with($rawAcademyId, '[')) {
            $academyId = (int) (json_decode($rawAcademyId, true)[0] ?? 0);
        } else {
            $academyId = (int) $rawAcademyId;
        }

        // 1. Create User
        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'player',
            'branch_id'  => (int) $request->branch_id,
            'academy_id' => $academyId,
            'system_id'  => (int) $request->system_id,
        ]);

        // 2. Create Player
        Player::create([
            'user_id'          => $user->id,
            'branch_id'        => (int) $request->branch_id,
            'academy_id'       => $academyId,
            'nationality_id'   => (int) $request->nationality_id,
            'birth_date'       => $request->birth_date,
            'gender'           => $request->gender,
            'player_code'      => $request->player_code,
            'sport_id'         => $request->sport_id,
            'previous_school'  => $request->previous_school,
            'previous_academy' => $request->previous_academy,
            'level'            => $request->level,
            'position'         => $request->position,
            'shirt_size'       => $request->shirt_size,
            'shorts_size'      => $request->shorts_size,
            'shoe_size'        => $request->shoe_size,
            'guardian_name'    => $request->guardian_name,
            'guardian_phone'   => $request->guardian_phone,
            'medical_notes'    => $request->medical_notes,
            'remarks'          => $request->remarks,
        ]);

        // 3. Send Mail
        Mail::to($user->email)->send(new NewPlayerCredentials(
            $user->name,
            $user->email,
            $request->password
        ));

        return redirect()
            ->route('admin.players.index')
            ->with('success', __('player.messages.player_created_successfully'));
    }




    // app/Http/Controllers/PlayerController.php

public function show(Player $player)
{
    if (!PermissionHelper::hasPermission('view', Player::MODEL_NAME)) {
        return PermissionHelper::denyAccessResponse();
    }

    $systemId = optional($player->branch)->system_id;

    $items = [];
    if ($systemId) {
        $items = Item::where('system_id', $systemId)
            ->pluck(app()->getLocale() === 'ar' ? 'name_ar' : 'name_en', 'id')
            ->toArray();
    }

    $currencies = Currency::all();

    $player->load([
        'user',
        'branch',
        'academy',
        'nationality',
        'sport',
        'payments.branch',
        'payments.paymentMethod',
        'uniformRequests.item',
        'uniformRequests.currency',
    ]);

    // If you prefer a sorted collection in the view:
    $uniformRequests = $player->uniformRequests()
        ->with(['item', 'currency'])
        ->orderByDesc('created_at')
        ->get();

    return view('admin.player.show', compact('player', 'items', 'currencies', 'uniformRequests'));
}






    public function edit(Player $player)
    {

         // dd($player);
        // Check update permission
        if (!PermissionHelper::hasPermission('update', Player::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $user = auth()->user();

        // 1. Systems – restrict based on role
        if (in_array($user->role, ['system_admin', 'branch_admin', 'academy_admin', 'coach', 'player'])) {
            $systems = System::where('id', $user->system_id)->get();
        } else {
            $systems = System::all();
        }

        // 2. Branches – restrict based on role
        $branchesQuery = Branch::query();
        if ($user->role === 'system_admin') {
            $branchesQuery->where('system_id', $user->system_id);
        } elseif (in_array($user->role, ['branch_admin', 'academy_admin', 'coach', 'player'])) {
            $branchesQuery->where('id', $user->branch_id);
        }
        $branches = $branchesQuery->get();

        // 3. Academies – restrict based on role
        $academiesQuery = Academy::query();
        if ($user->role === 'system_admin') {
            $academiesQuery->whereHas('branch', function ($q) use ($user) {
                $q->where('system_id', $user->system_id);
            });
        } elseif ($user->role === 'branch_admin') {
            $academiesQuery->where('branch_id', $user->branch_id);
        } elseif (in_array($user->role, ['academy_admin', 'coach', 'player'])) {
            $rawAcademyId = $user->academy_id;
            // Handle stringified JSON, arrays, or scalar IDs
            if (is_string($rawAcademyId) && str_starts_with($rawAcademyId, '[')) {
                $academyIds = json_decode($rawAcademyId, true) ?? [];
            } elseif (is_array($rawAcademyId)) {
                $academyIds = $rawAcademyId;
            } elseif (!is_null($rawAcademyId)) {
                $academyIds = [$rawAcademyId];
            } else {
                $academyIds = [];
            }
            // Ensure integer IDs
            $academyIds = array_filter(array_map('intval', $academyIds));
            $academiesQuery->whereIn('id', $academyIds);
        }
        $academies = $academiesQuery->get();

        // 4. Other data
        $nationalities = Nationality::all();
        $sports = Sport::all();

        // 5. Programs and Classes assigned to the player's academy
        $programs = Program::where('academy_id', $player->academy_id)->get();
        $classes  = ClassModel::whereIn('program_id', $programs->pluck('id'))->get();

        return view('admin.player.edit', compact(
            'player',
            'systems',
            'branches',
            'academies',
            'nationalities',
            'sports',
            'programs',
            'classes'
        ));
    }


    public function update(Request $request, Player $player)
    {

        if (!PermissionHelper::hasPermission('update', Player::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }

        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $player->user_id,
            'password'          => 'nullable|string|min:6|confirmed',
            'branch_id'         => 'required|exists:branches,id',
            'academy_id'        => 'required',
            'nationality_id'    => 'required|exists:nationalities,id',
            'sport_id'          => 'required|exists:sports,id',
            'birth_date'        => 'required|date',
            'gender'            => 'required|in:male,female',
            'player_code'       => 'required|unique:players,player_code,' . $player->id,
            'previous_school'   => 'nullable|string|max:100',
            'previous_academy'  => 'nullable|string|max:100',
            'level'             => 'nullable|string|in:beginner,intermediate,advanced',
            'position'          => 'nullable|string|max:100',
            'shirt_size'        => 'nullable|string|max:50',
            'shorts_size'       => 'nullable|string|max:50',
            'shoe_size'         => 'nullable|string|max:50',
            'guardian_name'     => 'nullable|string|max:255',
            'guardian_phone'    => 'nullable|string|max:50',
            'medical_notes'     => 'nullable|string|max:1000',
            'remarks'           => 'nullable|string|max:1000',
        ]);

        // Normalize academy_id
        $rawAcademyId = $request->academy_id;
        if (is_array($rawAcademyId)) {
            $academyId = (int) $rawAcademyId[0];
        } elseif (is_string($rawAcademyId) && str_starts_with($rawAcademyId, '[')) {
            $academyId = (int) (json_decode($rawAcademyId, true)[0] ?? 0);
        } else {
            $academyId = (int) $rawAcademyId;
        }

        // Update User
        $player->user->update([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => $request->filled('password') ? Hash::make($request->password) : $player->user->password,
            'branch_id'  => (int) $request->branch_id,
            'academy_id' => $academyId,
            'system_id'  => (int) $request->system_id,
        ]);

        // Update Player
        $player->update([
            'branch_id'        => (int) $request->branch_id,
            'academy_id'       => $academyId,
            'nationality_id'   => (int) $request->nationality_id,
            'birth_date'       => $request->birth_date,
            'gender'           => $request->gender,
            'player_code'      => $request->player_code,
            'sport_id'         => (int) $request->sport_id,
            'previous_school'  => $request->previous_school,
            'previous_academy' => $request->previous_academy,
            'level'            => $request->level,
            'position'         => $request->position,
            'shirt_size'       => $request->shirt_size,
            'shorts_size'      => $request->shorts_size,
            'shoe_size'        => $request->shoe_size,
            'guardian_name'    => $request->guardian_name,
            'guardian_phone'   => $request->guardian_phone,
            'medical_notes'    => $request->medical_notes,
            'remarks'          => $request->remarks,
        ]);

        return redirect()
            ->route('admin.players.index')
            ->with('success', __('player.messages.player_updated_successfully'));
    }


    public function destroy(Player $player)
    {
        if (!PermissionHelper::hasPermission('delete', Player::MODEL_NAME)) {
            return PermissionHelper::denyAccessResponse();
        }
        $player->delete();
        return redirect()->route('admin.players.index')->with('success', 'player.messages.player_deleted_successfully');
    }

    public function getAvailablePrograms($playerId)
    {
        $player = Player::with('branch', 'academy', 'programs.classes')->findOrFail($playerId);

        $programs = Program::where('system_id', $player->branch->system_id ?? null)
            ->where('branch_id', $player->branch_id)
            ->where('academy_id', $player->academy_id)
            ->get([
                'id',
                'name_en',
                'name_ar',
                'price',
                'currency',
                'vat',
                'is_offer_active',
                'offer_price'
            ]);

        // Get enrolled programs with class IDs
        $enrolled = $player->programs->map(function ($program) use ($player) {
            return [
                'id' => $program->id,
                'class_ids' => $program->classes()
                    ->whereHas('players', function ($q) use ($player) {
                        $q->where('player_id', $player->id);
                    })
                    ->pluck('id')
                    ->toArray(),
            ];
        });

        return response()->json([
            'programs' => $programs,
            'enrolled' => $enrolled
        ]);
    }



    public function assignProgram(Request $request, $playerId)
    {
        $messages = [
            'program_id.required'    => __('messages.select_program_required'),
            'program_id.exists'      => __('messages.program_not_found'),
            'class_ids.required'     => __('messages.select_class_required'),
            'class_ids.array'        => __('messages.invalid_class_format'),
            'class_ids.*.exists'     => __('messages.invalid_class_selected'),
        ];

        $request->validate([
            'program_id'   => 'required|exists:programs,id',
            'class_ids'    => 'required|array',
            'class_ids.*'  => 'exists:class_models,id',
        ], $messages);

        $player = Player::findOrFail($playerId);
        $programId = $request->input('program_id');
        $selectedClassIds = $request->input('class_ids');

        // Attach program if not already assigned
        if (!$player->programs()->where('program_id', $programId)->exists()) {
            $player->programs()->attach($programId);
        }

        // Detach existing classes for this program
        $programClassIds = ClassModel::where('program_id', $programId)->pluck('id')->toArray();
        $player->classes()->detach($programClassIds);

        // Attach new selected classes
        $player->classes()->attach($selectedClassIds);

        // ----------- Payment Calculation Logic -----------

        $program = Program::findOrFail($programId);
        $classCount = count($selectedClassIds);

        // Determine price per class (offer or regular)
        $pricePerClass = $program->price / $program->class_count;
        $offerPricePerClass = $program->is_offer_active && $program->offer_price
            ? $program->offer_price / $program->class_count
            : null;

        $basePrice = ($offerPricePerClass ?? $pricePerClass) * $classCount;

        $vatPercent = $program->vat;
        $vatAmount = $basePrice * ($vatPercent / 100);
        $totalPrice = $basePrice + $vatAmount;

        // Create payment
        Payment::create([
            'player_id'        => $player->id,
            'program_id'       => $program->id,
            'branch_id'        => $program->branch_id,
            'academy_id'       => $program->academy_id,
            'class_count'      => $classCount,
            'base_price'       => $basePrice,
            'vat_percent'      => $vatPercent,
            'vat_amount'       => $vatAmount,
            'total_price'      => $totalPrice,
            'remaining_amount' => $totalPrice,
            'currency'         => $program->currency,
            'status'           => 'pending',
            'note'             => 'Auto-generated upon program assignment',
            'items'            => json_encode([
                'class_ids' => $selectedClassIds,
            ]),
        ]);

        return redirect()->route('admin.players.index')
            ->with('success', __('messages.program_assigned_successfully'));
    }

    public function export()
    {
        return Excel::download(new \App\Exports\PlayersExport, 'players.xlsx');
    }
}
