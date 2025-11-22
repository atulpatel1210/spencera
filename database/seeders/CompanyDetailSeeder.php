<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyDetail; 

class CompanyDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (CompanyDetail::count() === 0) {
            CompanyDetail::create([
                'name' => 'SPENCERA CERAMICA LLP',
                'address_line1' => 'SURVEY NO. 77/1 P.1, 77/1 P.3, 77/1 P.4',
                'address_line2' => 'SPENCERA CERAMICA LLP, MONDAL-SABALIA ROAD, OPP. ZARKO GRANITO PVT.LTD.', 
                'city' => 'MORBI',
                'state' => 'GUJARAT',
                'zip' => '363641',
                'contact_person' => 'JIGNESH BHAI',
                'phone' => '9988776655',
                'pan_number' => 'AACFSS0000Z',
                'gst_no' => '24AASCC5555C1Z7',
            ]);
        }
    }
}