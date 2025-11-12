<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SampleDesignExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['1', 'Small'],
            ['2', 'Medium'],
            ['3', 'Large'],
        ];
    }

    public function headings(): array
    {
        return ['party_id', 'name'];
    }
}