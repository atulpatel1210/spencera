<?php
namespace App\Imports;

use App\Models\Design;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class DesignsImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    public $successCount = 0;
    public $customFailures = [];

    public function collection(Collection $rows)
    {
        $existing = Design::pluck('name')->map(fn($v) => strtolower($v))->toArray();
        $seen = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because of heading row

            $DesignName = trim($row['name']);

            if (!$DesignName) {
                $this->customFailures[] = "Row {$rowNumber}: name is required.";
                continue;
            }

            if (in_array(strtolower($DesignName), $existing) || in_array(strtolower($DesignName), $seen)) {
                $this->customFailures[] = "Row {$rowNumber}: Duplicate value \"{$DesignName}\" found.";
                continue;
            }

            Design::create(['name' => $DesignName]);
            $this->successCount++;
            $seen[] = strtolower($DesignName);
        }
    }
}
