<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Later you can use PermissionHelper to check access
        return true;
    }

    public function rules(): array
    {
        return [
            'date_from'          => ['nullable', 'date'],
            'date_to'            => ['nullable', 'date', 'after_or_equal:date_from'],
            'status'             => ['nullable', 'in:pending,partial,paid'],
            'category'           => ['nullable', 'in:program,uniform,asset,camp'],
            'branch_id'          => ['nullable', 'integer', 'exists:branches,id'],
            'academy_id'         => ['nullable', 'integer', 'exists:academies,id'],
            'program_id'         => ['nullable', 'integer', 'exists:programs,id'],
            'player_id'          => ['nullable', 'integer', 'exists:players,id'],
            'payment_method_id'  => ['nullable', 'integer', 'exists:payment_methods,id'],
            'reset_search'       => ['nullable', 'string', 'max:191'],
            'per_page'           => ['nullable', 'integer', 'min:10', 'max:500'],
            'export'             => ['nullable', 'in:none,csv,pdf'],
        ];
    }

    public function perPage(): int
    {
        return (int) ($this->input('per_page', 50));
    }
}
