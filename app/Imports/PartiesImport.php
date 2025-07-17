<?php

namespace App\Imports;

use App\Models\Party;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class PartiesImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    public $successCount = 0;
    public $customFailures = [];

    public function collection(Collection $rows)
    {
        $existing = Party::pluck('party_name')->map(fn($v) => strtolower($v))->toArray();
        $seen = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // Heading + 1-based index

            // Trim fields
            $partyName      = trim($row['party_name'] ?? '');
            $partyType      = trim($row['party_type'] ?? '');
            $contactPerson  = trim($row['contact_person'] ?? '');
            $email          = trim($row['email'] ?? '');
            $contactNo      = trim($row['contact_no'] ?? '');
            $mobileNo       = trim($row['mobile_no'] ?? '');
            $address        = trim($row['address'] ?? '');
            $gstNo          = trim($row['gst_no'] ?? '');

            // Required validation
            if (!$partyName) {
                $this->customFailures[] = "Row {$rowNumber}: party_name is required.";
                continue;
            }

            // Duplicate check
            if (in_array(strtolower($partyName), $existing) || in_array(strtolower($partyName), $seen)) {
                $this->customFailures[] = "Row {$rowNumber}: Duplicate value \"{$partyName}\" found.";
                continue;
            }

            // Email validation
            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->customFailures[] = "Row {$rowNumber}: Invalid email format.";
                continue;
            }

            // Contact number validation (optional but if exists, should be digits and 6–15 chars)
            if ($contactNo && (!preg_match('/^[0-9]{6,15}$/', $contactNo))) {
                $this->customFailures[] = "Row {$rowNumber}: Invalid contact number \"{$contactNo}\".";
                continue;
            }

            // Mobile number validation (typically 10 digits)
            if ($mobileNo && (!preg_match('/^[0-9]{10}$/', $mobileNo))) {
                $this->customFailures[] = "Row {$rowNumber}: Invalid mobile number \"{$mobileNo}\".";
                continue;
            }

            // GST number validation (15-char alphanumeric standard format)
            if ($gstNo && (!preg_match('/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}[Z]{1}[0-9A-Z]{1}$/', strtoupper($gstNo)))) {
                $this->customFailures[] = "Row {$rowNumber}: Invalid GST number \"{$gstNo}\".";
                continue;
            }

            // Save
            Party::create([
                'party_name'     => $partyName,
                'party_type'     => $partyType,
                'contact_person' => $contactPerson,
                'email'          => $email,
                'contact_no'     => $contactNo,
                'mobile_no'      => $mobileNo,
                'address'        => $address,
                'gst_no'         => strtoupper($gstNo),
            ]);

            $this->successCount++;
            $seen[] = strtolower($partyName);
        }
    }
}
