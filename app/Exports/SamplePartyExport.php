<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SamplePartyExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'Spencera Ceramics',     // party_name
                'Distributor',           // party_type
                'Rahul Mehta',           // contact_person
                'info@spencera.com',     // email
                '07940000000',           // contact_no
                '9876543210',            // mobile_no
                'Ahmedabad, Gujarat',    // address
                '24AABCU9603R1ZM'        // gst_no
            ],
            [
                'Vikram Tiles',
                'Retailer',
                'Vikram Patel',
                'vikram@example.com',
                '07941111111',
                '9090909090',
                'Rajkot, Gujarat',
                '24AACFV1234R1ZQ'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'party_name',
            'party_type',
            'contact_person',
            'email',
            'contact_no',
            'mobile_no',
            'address',
            'gst_no'
        ];
    }
}
