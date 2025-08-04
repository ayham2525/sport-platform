<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ClassModel;
use App\Models\Player;
use App\Models\User;
use App\Models\Academy;
use App\Models\Branch;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
//dd(session('permissions'));
        return view('ss', [
            'programsCount' => Program::count(),
            'classesCount' => ClassModel::count(),
            'playersCount' => Player::count(),
            'coachesCount' => User::where('role', 'coach')->count(),
            'academiesCount' => Academy::count(),
            'branchesCount' => Branch::count(),
            'paymentsCount' => Payment::count(),
            'paymentsTotalPaid' => Payment::sum('paid_amount'),
            'paymentsTotalRemaining' => Payment::sum('remaining_amount'),
        ]);
    }
}
