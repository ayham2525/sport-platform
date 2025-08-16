<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UniformReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // permission is checked in controller
    }

    public function rules(): array
    {
        return [
            'date_from'         => ['nullable','date'],
            'date_to'           => ['nullable','date','after_or_equal:date_from'],
            'status'            => ['nullable','in:requested,approved,ordered,delivered,rejected,cancelled,returned'],
            'branch_status'     => ['nullable','in:requested,approved,rejected,cancelled,non,received,ordered'],
            'office_status'     => ['nullable','in:pending,processing,completed,cancelled,delivered,received,non'],
            'branch_id'         => ['nullable','integer'],
            'academy_id'        => ['nullable','integer'],
            'player_id'         => ['nullable','integer'],
            'item_id'           => ['nullable','integer'],
            'payment_method'    => ['nullable','string','max:256'],
            'reset_search'      => ['nullable','string','max:191'],
            'per_page'          => ['nullable','integer','in:25,50,100,200'],
            'export'            => ['nullable','in:csv'],
        ];
    }

    public function perPage(): int
    {
        return (int)($this->input('per_page', 25));
    }
}
