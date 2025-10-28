<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelBankEuropCountrie extends Seeder
{
     /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Préparer un tableau avec les données à insérer
        $data = [
            [
                'amount_begin' => '10000',
                'amount_end' => '50000',
                'amount' => 5840.0,
                'percent_bank' => 3840.0,
                'percent_admin' => 1250.0,
                'percent_under' => 500.0,
                'percent_companie' => 250.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],
            [
                'amount_begin' => '50001',
                'amount_end' => '100000',
                'amount' => 7640.0,
                'percent_bank' => 4140.0,
                'percent_admin' => 2450.0,
                'percent_under' => 750.0,
                'percent_companie' => 300.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],
               [
                'amount_begin' => '100001',
                'amount_end' => '175000',
                'amount' => 11630.0,
                'percent_bank' => 8130.0,
                'percent_admin' => 2400.0,
                'percent_under' => 780.0,
                'percent_companie' => 320.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],

               [
                'amount_begin' => '175001',
                'amount_end' => '230000',
                'amount' => 12960.0,
                'percent_bank' => 8460.0,
                'percent_admin' => 3300.0,
                'percent_under' => 850.0,
                'percent_companie' => 350.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],

               [
                'amount_begin' => '230001',
                'amount_end' => '300000',
                'amount' => 13380.0,
                'percent_bank' => 8880.0,
                'percent_admin' => 3230.0,
                'percent_under' => 900.0,
                'percent_companie' => 370.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],

               [
                'amount_begin' => '300001',
                'amount_end' => '345000',
                'amount' => 14130.0,
                'percent_bank' => 9130.0,
                'percent_admin' => 3480.0,
                'percent_under' => 1100.0,
                'percent_companie' => 420.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],

               [
                'amount_begin' => '345001',
                'amount_end' => '400000',
                'amount' => 14480.0,
                'percent_bank' => 9480.0,
                'percent_admin' => 3300.0,
                'percent_under' => 1250.0,
                'percent_companie' => 450.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],

               [
                'amount_begin' => '400001',
                'amount_end' => '445000',
                'amount' => 22240.0,
                'percent_bank' => 16240.0,
                'percent_admin' => 4020.0,
                'percent_under' => 1500.0,
                'percent_companie' => 480.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],

               [
                'amount_begin' => '445001',
                'amount_end' => '500000',
                'amount' => 23570.0,
                'percent_bank' => 16570.0,
                'percent_admin' => 4500.0,
                'percent_under' => 2000.0,
                'percent_companie' => 500.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],

            [
                'amount_begin' => '500001',
                'amount_end' => '550000',
                'amount' => 24870.0,
                'percent_bank' => 16870.0,
                'percent_admin' => 5250.0,
                'percent_under' => 2200.0,
                'percent_companie' => 550.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],


            [
                'amount_begin' => '550001',
                'amount_end' => '600000',
                'amount' => 26170.0,
                'percent_bank' => 17170.0,
                'percent_admin' => 6075.0,
                'percent_under' => 2350.0,
                'percent_companie' => 575.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],


            [
                'amount_begin' => '600001',
                'amount_end' => '650000',
                'amount' => 27470.0,
                'percent_bank' => 17470.0,
                'percent_admin' => 6975.0,
                'percent_under' => 2425.0,
                'percent_companie' => 600.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],

            [
                'amount_begin' => '650001',
                'amount_end' => '700000',
                'amount' => 28770.0,
                'percent_bank' => 17770.0,
                'percent_admin' => 7775.0,
                'percent_under' => 2590.0,
                'percent_companie' => 635.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],

            [
                'amount_begin' => '700001',
                'amount_end' => '750000',
                'amount' => 30000.0,
                'percent_bank' => 18070.0,
                'percent_admin' => 8650.0,
                'percent_under' => 2675.0,
                'percent_companie' => 675.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],

            [
                'amount_begin' => '750001',
                'amount_end' => '800000',
                'amount' => 31370.0,
                'percent_bank' => 18370.0,
                'percent_admin' => 9585.0,
                'percent_under' => 2725.0,
                'percent_companie' => 690.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],

            [
                'amount_begin' => '800001',
                'amount_end' => '850000',
                'amount' => 32670.0,
                'percent_bank' => 18670.0,
                'percent_admin' => 10490.0,
                'percent_under' => 2800.0,
                'percent_companie' => 710.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],

            [
                'amount_begin' => '850001',
                'amount_end' => '900000',
                'amount' => 33970.0,
                'percent_bank' => 18970.0,
                'percent_admin' => 11250.0,
                'percent_under' => 3000.0,
                'percent_companie' => 750.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],
             [
                'amount_begin' => '850001',
                'amount_end' => '900000',
                'amount' => 33970.0,
                'percent_bank' => 18970.0,
                'percent_admin' => 11250.0,
                'percent_under' => 3000.0,
                'percent_companie' => 750.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],
             [
                'amount_begin' => '900001',
                'amount_end' => '950000',
                'amount' => 35270.0,
                'percent_bank' => 19270.0,
                'percent_admin' => 12005.0,
                'percent_under' => 3200.0,
                'percent_companie' => 795.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],
             [
                'amount_begin' => '950001',
                'amount_end' => '1000000',
                'amount' => 36570.0,
                'percent_bank' => 19570.0,
                'percent_admin' => 11075.0,
                'percent_under' => 5100.0,
                'percent_companie' => 825.0,
                
                
                'bank_commission_id' => '2',
                'deleted_at' => null,
                'created_at' => '2025-09-12 14:38:06',
                'updated_at' => now(),
            ],
            // Ajoutez d'autres ensembles de données ici si nécessaire
        ];

        // Insérer les données en boucle pour dupliquer l'insertion
        foreach ($data as $entry) {
            DB::table('level_bank_authers')->insert($entry);
        }
        DB::table('level_bank_authers')->insert($data);
    }
}
