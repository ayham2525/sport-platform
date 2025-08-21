<?php

use App\Models\Item;
use App\Models\ClassModel;
use App\Models\ModelEntity;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\AcademyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BranchItemController;
use App\Http\Controllers\ClassModelController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ModelEntityController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\UniformRequestController;
use App\Http\Controllers\CoachEvaluationController;
use App\Http\Controllers\Admin\EvaluationController;
use App\Http\Controllers\Reports\PaymentReportController;
use App\Http\Controllers\Reports\UniformReportController;
use App\Http\Controllers\Admin\EvaluationCriteriaController;
use App\Http\Controllers\Reports\BranchPaymentsSummaryController;


Route::get('/calendar', function () {
    return view('calendar');
});


// 🟦 All web routes (guarantees session, auth, $errors in views, etc.)
Route::middleware(['web'])->group(function () {

    // Language switcher
  Route::get('lang/{locale}', function ($locale) {
    abort_unless(in_array($locale, ['en','ar']), 400);

    session(['locale' => $locale]);
    app()->setLocale($locale);

    if (Auth::check()) {
        Auth::user()->update(['language' => $locale]);
    }

    // always redirect back (or home if no referrer)
    return redirect()->back()->with('status', 'language-updated');
})->name('change.locale');


    // Auth routes (global, for all users)
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('logout', [LoginController::class, 'logout'])->name('logout'); // route('logout')
    Route::post('logout', [LoginController::class, 'logout'])->name('logout'); // route('logout')

    // Welcome page
    Route::get('/', function () {
        return view('welcome');
    });

    // 🟦 Admin panel
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['auth', 'not_player', 'set_locale'])
        ->group(function () {

        Route::prefix('calendar')->name('calendar.')->group(function () {
            Route::get('/', [CalendarController::class, 'index'])->name('index');
            Route::post('/store-event', [CalendarController::class, 'storeEvent'])->name('storeEvent');
            Route::post('/update-event', [CalendarController::class, 'updateEvent'])->name('updateEvent');
            Route::post('/delete-event', [CalendarController::class, 'deleteEvent'])->name('deleteEvent');
        });

            // 🔹 Dashboard
          //  Route::get('/', fn() => redirect()->route('admin.dashboard'))->name('home');
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // 🔹 Authentication
            Route::post('logout', [LoginController::class, 'logout'])->name('logout');

            // 🔹 Profile
            Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
            Route::post('/profile/update-account', [ProfileController::class, 'updateAccount'])->name('profile.update_account');
            Route::post('/profile/update-image', [ProfileController::class, 'updateImage'])->name('profile.update_image');

            // 🔹 Users
            Route::post('/users/ajax-search', [UserController::class, 'ajaxSearch'])->name('users.ajaxSearch');
            Route::resource('users', UserController::class);

            // 🔹 Players
            Route::get('admin/cards/scan/{player_id}', [CardController::class, 'scan'])->name('cards.scan');
            Route::post('admin/cards/store', [CardController::class, 'store'])->name('cards.store');

            Route::get('/players/export', [PlayerController::class, 'export'])->name('players.export');
            Route::get('programs/{program}/classes', [ClassModelController::class, 'byProgram']);
            Route::get('players/{player}/available-programs', [PlayerController::class, 'getAvailablePrograms'])->name('players.available_programs');
            Route::post('players/{player}/assign-program', [PlayerController::class, 'assignProgram'])->name('players.assignProgram');
            Route::resource('players', PlayerController::class);
            // 🔹 Role & Permission Management
            Route::resource('roles', RoleController::class);
            Route::resource('permissions', PermissionController::class);

            // 🔹 Sports Management
            Route::resource('sports', SportController::class);

            // 🔹 Attendance Management
            Route::get('attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');
            Route::resource('attendance', AttendanceController::class);



            // 🔹 Location Data
            Route::resource('countries', CountryController::class);
            Route::resource('states', StateController::class);
            Route::resource('cities', CityController::class);

            // 🔹 System & Entities
            Route::resource('systems', SystemController::class);
            Route::resource('models', ModelEntityController::class)->names('models');
            Route::resource('payment-methods', PaymentMethodController::class);


             Route::post('/player-payments/store', [PaymentController::class, 'storeFromPlayer'])->name('player_payments.store');
             Route::put('/player-payments/{payment}', [PaymentController::class, 'updateFromPlayer'])->name('player_payments.update');
             Route::get('player-payments/{payment}/edit', [PaymentController::class, 'editPlayerPayment'])->name('player_payments.edit');
             Route::get('branches/{branch}/items', [BranchItemController::class, 'index'])->name('branches.items');
             Route::post('branches/{branch}/items', [BranchItemController::class, 'store'])->name('branches.items.store');
             Route::put('branches/{branch}/items/{item}', [BranchItemController::class, 'update'])->name('branches.items.update');
             Route::delete('branches/{branch}/items/{item}', [BranchItemController::class, 'destroy'])->name('branches.items.destroy');


            Route::get('/branches/{branch}/players', [BranchController::class, 'players'])->name('branches.players');
            Route::resource('branches', BranchController::class);

            Route::get('/academies/{id}/players/export', [AcademyController::class, 'exportPlayers'])->name('academies.players.export');
            Route::get('academies/{academy}/players', [AcademyController::class, 'players'])->name('academies.players');
            Route::resource('academies', AcademyController::class);


            Route::get('programs/{program}/players', [ProgramController::class, 'players'])->name('programs.players');
            Route::get('programs/{program}/classes/create', [ClassModelController::class, 'create'])->name('classes.create');
            Route::get('programs/{program}/class/{class}/edit', [ClassModelController::class, 'edit'])->name('classes.edit');
            Route::put('programs/{program}/class/{class}', [ClassModelController::class, 'update'])->name('classes.update');

            Route::delete('classes/{class}', [ClassModelController::class, 'destroy'])->name('classes.destroy');
            Route::post('programs/{program}/classes', [ClassModelController::class, 'store'])->name('classes.store');

            Route::resource('programs', ProgramController::class);
            //Route::resource('classes', ClassModelController::class);

            // 🔹 Payments
            Route::get('payments/export', [PaymentController::class, 'export'])->name('payments.export');

            Route::get('payments/{payment}/invoice', [PaymentController::class, 'invoice'])->name('payments.invoice');

            Route::resource('payments', PaymentController::class);

            // 🔹 Currency
            Route::resource('currencies', CurrencyController::class);

              // 🔹 Items
            Route::get('/get-items-by-system/{system}', action: function ($systemId) {
                return Item::where('system_id', $systemId)
                    ->where('active', true)
                    ->with('currency:id,code')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name_en' => $item->name_en,
                            'price' => $item->price,
                            'currency' => $item->currency ? $item->currency->code : 'AED',
                        ];
                    });
            });

            Route::resource('uniform-requests', UniformRequestController::class);

           Route::get('/get-classes-by-program/{program}', function($programId) {
                $classes = ClassModel::where('program_id', $programId)->get(['id', 'day' , 'start_time' , 'end_time' , 'location' , 'coach_name']);
                return response()->json($classes);
            });
            Route::resource('items', ItemController::class);

            Route::resource( 'exchange-rates', ExchangeRateController::class);



            // Reports
             Route::get('/reports/payments/branch-summary', [BranchPaymentsSummaryController::class, 'index'])->name('reports.payments.branch_summary');
            Route::get('/reports/payments', [PaymentReportController::class, 'index'])->name('reports.payments.index');
            Route::get('/reports/uniforms', [UniformReportController::class, 'index'])->name('reports.uniforms.index');
            Route::post('/reports/uniforms/academies', [UniformReportController::class, 'academiesForBranch'])->name('reports.uniforms.academies');

            // 🔹 Evaluations & Criteria
            Route::resource('evaluations', EvaluationController::class);
            Route::post('/criteria', [EvaluationCriteriaController::class, 'store'])->name('criteria.store');
            Route::post('/criteria/{id}/update', [EvaluationCriteriaController::class, 'update'])->name('criteria.update');
            Route::post('/criteria/{id}/delete', [EvaluationCriteriaController::class, 'destroy'])->name('criteria.destroy');

            // 🔹 Coach Evaluations
            Route::get('/coach_evaluations', [CoachEvaluationController::class, 'index'])->name('coach_evaluation.index');
            Route::get('/evaluations/{evaluation}/assign', [CoachEvaluationController::class, 'create'])->name('coach_evaluation.create');
            Route::post('/evaluations/submit', [CoachEvaluationController::class, 'store'])->name('coach_evaluation.store');
            Route::get('/coach-evaluations/{coachEvaluation}/edit', [CoachEvaluationController::class, 'edit'])->name('coach_evaluation.edit');
            Route::post('/coach-evaluations/{coachEvaluation}', [CoachEvaluationController::class, 'update'])->name('coach_evaluation.update');
            Route::delete('/coach_evaluations/{id}', [CoachEvaluationController::class, 'destroy'])->name('coach_evaluation.destroy');

            // 🔹 AJAX Data Endpoints
            Route::get('/get-states-by-country', [AjaxController::class, 'getStatesByCountry'])->name('getStatesByCountry');
            Route::get('/get-cities-by-state', [AjaxController::class, 'getCitiesByState'])->name('getCitiesByState');
            Route::get('/get-roles-by-system/{system_id}', [AjaxController::class, 'getRolesBySystem'])->name('admin.getRolesBySystem');
            Route::get('/get-branches-by-system/{system_id}', [AjaxController::class, 'getBranchesBySystem'])->name('getBranchesBySystem');
            Route::get('/get-academies-by-branch/{branch_id}', [AjaxController::class, 'getAcademiesByBranch'])->name('getAcademiesByBranch');
            Route::get('/get-players-by-system/{system_id}', [AjaxController::class, 'getPlayersBySystem'])->name('getPlayersBySystem');
            Route::get('/get-programs-by-academy/{academy_id}', [AjaxController::class, 'getByAcademy']);
            Route::post('/convert-currency', [AjaxController::class, 'convertCurrency'])->name('ajax.convert-currency');
            Route::get('/get-players-by-branch/{branch_id}', [AjaxController::class, 'getPlayersByBranch']);
            Route::get('/get-items-by-system/{system_id}', [AjaxController::class, 'getItemsBySystem']);
            Route::get('/get-users-by-branch/{branch_id}', [AjaxController::class, 'getUsersByBranch'])->name('getUsersByBranch');


        });
});
