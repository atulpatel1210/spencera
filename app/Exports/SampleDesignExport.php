<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SampleDesignExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['Small'],
            ['Medium'],
            ['Large'],
        ];
    }

    public function headings(): array
    {
        return ['name'];
    }
}