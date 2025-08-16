<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;

// (optional demo)
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * players:update-status
 * Active  = player HAS at least one payment with end_date >= today
 * Expired = player has NO payments with end_date >= today
 * Optional: --branch_id=123 to scope updates to one branch
 */
Artisan::command('players:update-status {--branch_id=}', function () {
    $branchId   = $this->option('branch_id');
    $extraWhere = $branchId ? ' AND p.branch_id = ?' : '';
    $bindings   = $branchId ? [(int) $branchId] : [];

    // 1) Mark EXPIRED
    $expired = DB::affectingStatement("
        UPDATE players p
        SET p.status = 'expired'
        WHERE 1=1 {$extraWhere}
          AND NOT EXISTS (
            SELECT 1
            FROM payments pay
            WHERE pay.player_id = p.id
              AND pay.end_date IS NOT NULL
              AND DATE(pay.end_date) >= CURDATE()
          )
    ", $bindings);

    // 2) Mark ACTIVE
    $active = DB::affectingStatement("
        UPDATE players p
        SET p.status = 'active'
        WHERE 1=1 {$extraWhere}
          AND EXISTS (
            SELECT 1
            FROM payments pay
            WHERE pay.player_id = p.id
              AND pay.end_date IS NOT NULL
              AND DATE(pay.end_date) >= CURDATE()
          )
    ", $bindings);

    // Show final totals (scoped if branch_id passed)
    $totals = DB::select(
        'SELECT status, COUNT(*) c FROM players ' . ($branchId ? 'WHERE branch_id = ? ' : '') . 'GROUP BY status',
        $bindings
    );

    $this->info("Affected → expired: {$expired}, active: {$active}");
    foreach ($totals as $t) {
        $this->line("Now → {$t->status}: {$t->c}");
    }
})->purpose('Update player statuses');

// Run it daily at 11:43 (server time)
Schedule::command('players:update-status')->dailyAt('11:51');
