<?php
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

if (!function_exists('log_action')) {
    /**
     * Log a system action to the logs table.
     *
     * @param string $action         Action key (e.g. "created_program")
     * @param Model|null $target     The affected model (optional)
     * @param string|null $message   Optional readable message
     * @param array|null $payload    Optional before/after data
     * @param int|null $systemId     System ID (optional fallback)
     */
    function log_action(
        string $action,
        ?Model $target = null,
        ?string $message = null,
        ?array $payload = null,
        ?int $systemId = null
    ): void {
        $user = Auth::user();

        Log::create([
            'user_id'     => $user?->id,
            'system_id'   => $systemId ?? $user?->system_id,
            'action'      => $action,
            'target_type' => $target ? get_class($target) : null,
            'target_id'   => $target->id ?? null,
            'message'     => $message,
            'payload'     => $payload,
        ]);
    }
}
