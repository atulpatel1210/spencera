<?php
namespace App\Imports;

use App\Models\Size;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class SizesImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    public $successCount = 0;
    public $customFailures = [];

    public function collection(Collection $rows)
    {
        $existing = Size::pluck('size_name')->map(fn($v) => strtolower($v))->toArray();
        $seen = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because of heading row

            $sizeName = trim($row['size_name']);

            if (!$sizeName) {
                $this->customFailures[] = "Row {$rowNumber}: size_name is required.";
                continue;
            }

            if (in_array(strtolower($sizeName), $existing) || in_array(strtolower($sizeName), $seen)) {
                $this->customFailures[] = "Row {$rowNumber}: Duplicate value \"{$sizeName}\" found.";
                continue;
            }

            Size::create(['size_name' => $sizeName]);
            $this->successCount++;
            $seen[] = strtolower($sizeName);
        }
    }
}
