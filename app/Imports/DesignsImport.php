<?php
namespace App\Imports;

use App\Models\Design;
use App\Models\Party;
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
        $existing = Design::select('party_id', 'name')->get()
            ->map(fn($item) => strtolower($item->party_id . '_' . $item->name))
            ->toArray();
        $seen = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because of heading row

            $partyId = trim($row['party_id'] ?? '');
            $designName = trim($row['name'] ?? '');

            // Basic validations
            if (!$partyId) {
                $this->customFailures[] = "Row {$rowNumber}: party_id is required.";
                continue;
            }

            if (!Party::find($partyId)) {
                $this->customFailures[] = "Row {$rowNumber}: Invalid party_id ({$partyId}).";
                continue;
            }

            if (!$designName) {
                $this->customFailures[] = "Row {$rowNumber}: name is required.";
                continue;
            }

            $uniqueKey = strtolower($partyId . '_' . $designName);

            // 3️⃣ Check duplicate (party_id + name)
            if (in_array($uniqueKey, $existing) || in_array($uniqueKey, $seen)) {
                $this->customFailures[] = "Row {$rowNumber}: Duplicate name \"{$designName}\" found for party_id {$partyId}.";
                continue;
            }

            // Store record
            Design::create([
                'party_id' => $partyId,
                'name' => $designName,
            ]);
            $this->successCount++;
            $seen[] = strtolower($designName);
        }
    }
}
