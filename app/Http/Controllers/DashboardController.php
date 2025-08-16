<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Player;
use App\Models\Academy;
use App\Models\Payment;
use App\Models\Program;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Models\UniformRequest;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // ---------- FULL ADMIN (no filters): include charts ----------
        if ($user->role === 'full_admin') {
            $paymentsDateCol = Schema::hasColumn((new Payment)->getTable(), 'payment_date') ? 'payment_date' : 'created_at';
            $uniformsDateCol = Schema::hasColumn((new UniformRequest)->getTable(), 'request_date') ? 'request_date' : 'created_at';
            $year   = now()->year;
            $months = range(1, 12);

            // Monthly
            $pAgg = Payment::selectRaw("MONTH($paymentsDateCol) m, COALESCE(SUM(paid_amount),0) total")
                ->whereYear($paymentsDateCol, $year)->groupBy('m')->pluck('total','m')->all();

            $uAggAmt = UniformRequest::selectRaw("MONTH($uniformsDateCol) m, COALESCE(SUM(amount),0) total")
                ->whereYear($uniformsDateCol, $year)->groupBy('m')->pluck('total','m')->all();

            $uAggCnt = UniformRequest::selectRaw("MONTH($uniformsDateCol) m, COUNT(*) cnt")
                ->whereYear($uniformsDateCol, $year)->groupBy('m')->pluck('cnt','m')->all();

            $paymentsMonthlyPaid   = array_map(fn($m)=>(float)($pAgg[$m]??0), $months);
            $uniformsMonthlyAmount = array_map(fn($m)=>(float)($uAggAmt[$m]??0), $months);
            $uniformsMonthlyCount  = array_map(fn($m)=>(int)($uAggCnt[$m]??0), $months);

            // ===== YEARLY (last 5 years including current) =====
            $yearStart = $year - 4;
            $yearEnd   = $year;
            $years     = range($yearStart, $yearEnd);

            $pAggY = Payment::selectRaw("YEAR($paymentsDateCol) y, COALESCE(SUM(paid_amount),0) total")
                ->whereYear($paymentsDateCol, '>=', $yearStart)
                ->whereYear($paymentsDateCol, '<=', $yearEnd)
                ->groupBy('y')->pluck('total','y')->all();

            $uAggAmtY = UniformRequest::selectRaw("YEAR($uniformsDateCol) y, COALESCE(SUM(amount),0) total")
                ->whereYear($uniformsDateCol, '>=', $yearStart)
                ->whereYear($uniformsDateCol, '<=', $yearEnd)
                ->groupBy('y')->pluck('total','y')->all();

            $uAggCntY = UniformRequest::selectRaw("YEAR($uniformsDateCol) y, COUNT(*) cnt")
                ->whereYear($uniformsDateCol, '>=', $yearStart)
                ->whereYear($uniformsDateCol, '<=', $yearEnd)
                ->groupBy('y')->pluck('cnt','y')->all();

            $paymentsYearlyPaid    = array_map(fn($y)=>(float)($pAggY[$y]??0), $years);
            $uniformsYearlyAmount  = array_map(fn($y)=>(float)($uAggAmtY[$y]??0), $years);
            $uniformsYearlyCount   = array_map(fn($y)=>(int)($uAggCntY[$y]??0), $years);

            return view('ss', [
                'programsCount'          => Program::count(),
                'classesCount'           => ClassModel::count(),
                'playersCount'           => Player::count(),
                'coachesCount'           => User::where('role', 'coach')->count(),
                'academiesCount'         => Academy::count(),
                'branchesCount'          => Branch::count(),
                'paymentsCount'          => Payment::count(),
                'paymentsTotalPaid'      => Payment::sum('paid_amount'),
                'paymentsTotalRemaining' => Payment::sum('remaining_amount'),

                // Monthly charts
                'chartMonths'            => $months,
                'paymentsMonthlyPaid'    => $paymentsMonthlyPaid,
                'uniformsMonthlyAmount'  => $uniformsMonthlyAmount,
                'uniformsMonthlyCount'   => $uniformsMonthlyCount,

                // Yearly charts
                'chartYears'             => $years,
                'paymentsYearlyPaid'     => $paymentsYearlyPaid,
                'uniformsYearlyAmount'   => $uniformsYearlyAmount,
                'uniformsYearlyCount'    => $uniformsYearlyCount,
            ]);
        }

        // ---------- OTHER ROLES ----------
        // (… your existing scoping logic unchanged …)

        $programs  = Program::query();
        $classes   = ClassModel::query();
        $players   = Player::query();
        $coaches   = User::where('role', 'coach');
        $academies = Academy::query();
        $branches  = Branch::query();
        $payments  = Payment::query();
        $uniforms  = UniformRequest::query();

        // (… your existing helpers & switch(role) scoping …)

        // ---------- CHARTS for system_admin only ----------
        $chartMonths = range(1,12);
        $paymentsMonthlyPaid = $uniformsMonthlyAmount = $uniformsMonthlyCount = [];

        // Yearly placeholders
        $chartYears = [];
        $paymentsYearlyPaid = $uniformsYearlyAmount = $uniformsYearlyCount = [];

        if ($user->role === 'system_admin') {
            $paymentsDateCol = Schema::hasColumn((new Payment)->getTable(), 'payment_date') ? 'payment_date' : 'created_at';
            $uniformsDateCol = Schema::hasColumn((new UniformRequest)->getTable(), 'request_date') ? 'request_date' : 'created_at';
            $year = now()->year;

            // Monthly (scoped)
            $pAgg = (clone $payments)->selectRaw("MONTH($paymentsDateCol) m, COALESCE(SUM(paid_amount),0) total")
                ->whereYear($paymentsDateCol, $year)->groupBy('m')->pluck('total','m')->all();

            $uAggAmt = (clone $uniforms)->selectRaw("MONTH($uniformsDateCol) m, COALESCE(SUM(amount),0) total")
                ->whereYear($uniformsDateCol, $year)->groupBy('m')->pluck('total','m')->all();

            $uAggCnt = (clone $uniforms)->selectRaw("MONTH($uniformsDateCol) m, COUNT(*) cnt")
                ->whereYear($uniformsDateCol, $year)->groupBy('m')->pluck('cnt','m')->all();

            $paymentsMonthlyPaid   = array_map(fn($m)=>(float)($pAgg[$m]??0), $chartMonths);
            $uniformsMonthlyAmount = array_map(fn($m)=>(float)($uAggAmt[$m]??0), $chartMonths);
            $uniformsMonthlyCount  = array_map(fn($m)=>(int)($uAggCnt[$m]??0), $chartMonths);

            // ===== YEARLY (last 5 years, scoped) =====
            $yearStart = $year - 4;
            $yearEnd   = $year;
            $chartYears = range($yearStart, $yearEnd);

            $pAggY = (clone $payments)->selectRaw("YEAR($paymentsDateCol) y, COALESCE(SUM(paid_amount),0) total")
                ->whereYear($paymentsDateCol, '>=', $yearStart)
                ->whereYear($paymentsDateCol, '<=', $yearEnd)
                ->groupBy('y')->pluck('total','y')->all();

            $uAggAmtY = (clone $uniforms)->selectRaw("YEAR($uniformsDateCol) y, COALESCE(SUM(amount),0) total")
                ->whereYear($uniformsDateCol, '>=', $yearStart)
                ->whereYear($uniformsDateCol, '<=', $yearEnd)
                ->groupBy('y')->pluck('total','y')->all();

            $uAggCntY = (clone $uniforms)->selectRaw("YEAR($uniformsDateCol) y, COUNT(*) cnt")
                ->whereYear($uniformsDateCol, '>=', $yearStart)
                ->whereYear($uniformsDateCol, '<=', $yearEnd)
                ->groupBy('y')->pluck('cnt','y')->all();

            $paymentsYearlyPaid    = array_map(fn($y)=>(float)($pAggY[$y]??0), $chartYears);
            $uniformsYearlyAmount  = array_map(fn($y)=>(float)($uAggAmtY[$y]??0), $chartYears);
            $uniformsYearlyCount   = array_map(fn($y)=>(int)($uAggCntY[$y]??0), $chartYears);
        }

        return view('ss', [
            'programsCount'          => (clone $programs)->count(),
            'classesCount'           => (clone $classes)->count(),
            'playersCount'           => (clone $players)->count(),
            'coachesCount'           => (clone $coaches)->count(),
            'academiesCount'         => (clone $academies)->count(),
            'branchesCount'          => (clone $branches)->count(),
            'paymentsCount'          => (clone $payments)->count(),
            'paymentsTotalPaid'      => (clone $payments)->sum('paid_amount'),
            'paymentsTotalRemaining' => (clone $payments)->sum('remaining_amount'),

            // Monthly (for system_admin)
            'chartMonths'            => $chartMonths,
            'paymentsMonthlyPaid'    => $paymentsMonthlyPaid,
            'uniformsMonthlyAmount'  => $uniformsMonthlyAmount,
            'uniformsMonthlyCount'   => $uniformsMonthlyCount,

            // Yearly (for system_admin)
            'chartYears'             => $chartYears,
            'paymentsYearlyPaid'     => $paymentsYearlyPaid,
            'uniformsYearlyAmount'   => $uniformsYearlyAmount,
            'uniformsYearlyCount'    => $uniformsYearlyCount,
        ]);
    }
}
