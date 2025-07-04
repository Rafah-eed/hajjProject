<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transport;
use App\Models\TransportSeat;

class TransportSeatSeeder extends Seeder
{
    public function run()
    {
        // Create transports
        $transports = [
            [
                'office_id' => 2,
                'company_name' => 'السراج للنقل الجوي  ',
                'description' => 'بانتظاركم معنا في رحلة ميحة و ممتعة إى بيت الله الحرام',
                'transport_type' => 'جوي',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'office_id' => 2,
                'company_name' => 'السراج للنل البري ',
                'description' => 'بانتظاركم معنا في رحلة ميحة و ممتعة إى بيت الله الحرام',
                'transport_type' => 'بري',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'office_id' => 1,
                'company_name' => 'السراج  ',
                'description' => 'بانتظاركم معنا في رحلة ميحة و ممتعة إى بيت الله الحرام',
                'transport_type' => 'جوي',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($transports as $transport) {
            $transportCreated = Transport::create($transport);
            
            // Create seats for this transport
            TransportSeat::create([
                'transport_id' => $transportCreated->id,
                'seat' => 'درجة أولى  ',
                'price' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            TransportSeat::create([
                'transport_id' => $transportCreated->id,
                'seat' => 'درجة اقتصادية  ',
                'price' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            TransportSeat::create([
                'transport_id' => $transportCreated->id,
                'seat' => 'درجة رجل الأعمال  ',
                'price' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}