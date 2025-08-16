<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BranchSummaryWorkbook implements WithMultipleSheets
{
    protected array $branchSheet;
    protected ?array $academySheet;
    protected string $dateRangeLabel;

    public function __construct(array $branchSheet, ?array $academySheet, string $dateRangeLabel)
    {
        $this->branchSheet    = $branchSheet;
        $this->academySheet   = $academySheet;
        $this->dateRangeLabel = $dateRangeLabel;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new ArraySheet($this->branchSheet, 'Branches');

        if ($this->academySheet && count($this->academySheet) > 1) {
            $sheets[] = new ArraySheet($this->academySheet, 'Academies');
        }

        return $sheets;
    }
}
