<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ArraySheet implements FromArray, WithTitle, ShouldAutoSize
{
    protected array $rows;
    protected string $title;

    public function __construct(array $rows, string $title)
    {
        $this->rows  = $rows;
        $this->title = mb_substr($title, 0, 31); // Excel sheet title limit
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function title(): string
    {
        return $this->title;
    }
}
