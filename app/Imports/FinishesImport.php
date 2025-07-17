<?php
namespace App\Imports;

use App\Models\Finish;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class FinishesImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    public $successCount = 0;
    public $customFailures = [];

    public function collection(Collection $rows)
    {
        $existing = Finish::pluck('finish_name')->map(fn($v) => strtolower($v))->toArray();
        $seen = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because of heading row

            $FinishName = trim($row['finish_name']);

            if (!$FinishName) {
                $this->customFailures[] = "Row {$rowNumber}: finish_name is required.";
                continue;
            }

            if (in_array(strtolower($FinishName), $existing) || in_array(strtolower($FinishName), $seen)) {
                $this->customFailures[] = "Row {$rowNumber}: Duplicate value \"{$FinishName}\" found.";
                continue;
            }

            Finish::create(['finish_name' => $FinishName]);
            $this->successCount++;
            $seen[] = strtolower($FinishName);
        }
    }
}
