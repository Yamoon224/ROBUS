<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeveNewCoutrieMoney extends Seeder
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
                                'name' => 'Belgique  vers Mauritanie ',
                                'percent' => '0.015',
                                'percent_merchands' => '0',
                                'deleted_by' => null,
                                'created_by' => '1',
                                'percent_admin' => '0.01',
                                //
                                'percent_deposit' => '0.005',
                                'percent_with_drawal' => '0',
                                'status' => '1',

                                'distributer_money' => '1',
                                'is_mobile_money' => '1',
                                'deleted_at' => null,
                                'created_at' => '2023-09-12 14:38:06',
                                'updated_at' => now(),
                                'percent_under_distributer' => '0.03',
                                'percent_admin_under_distributer' => '0.01',
                                'percent_deposit_under_distributer' => '0.005',
                                'percent_with_drawal_under_distributer' => '0',
                                'percent_banque' => '0.05',
                                'is_web' => '0',
                                'percent_web' => null,
                                'percent_masters' => null,
                                'is_eastafrica' => '0',
                                'is_euro' => '1',
                        ],
                        // [
                        //         'name' => 'Belgique  vers  Gambie  ',
                        //         'percent' => '0.015',
                        //         'percent_merchands' => '0',
                        //         'deleted_by' => null,
                        //         'created_by' => '1',
                        //         'percent_admin' => '0.01',
                        //         //

                        //         'percent_deposit' => '0.005',
                        //         'percent_with_drawal' => '0',
                        //         'status' => '1',

                        //         'distributer_money' => '1',
                        //         'is_mobile_money' => '1',
                        //         'deleted_at' => null,
                        //         'created_at' => '2023-09-12 14:38:06',
                        //         'updated_at' => now(),
                        //         'percent_under_distributer' => '0.03',
                        //         'percent_admin_under_distributer' => '0.01',
                        //         'percent_deposit_under_distributer' => '0.005',
                        //         'percent_with_drawal_under_distributer' => '0',
                        //         'percent_banque' => '0.05',
                        //         'is_web' => '0',
                        //         'percent_web' => null,
                        //         'percent_masters' => null,
                        //         'is_eastafrica' => '0',
                        //         'is_euro' => '1',
                        // ],

                        // [
                        //         'name' => 'Belgique  vers Zambie ',
                        //         'percent' => '0.015',
                        //         'percent_merchands' => '0',
                        //         'deleted_by' => null,
                        //         'created_by' => '1',
                        //         'percent_admin' => '0.01',
                        //         //

                        //         'percent_deposit' => '0.005',
                        //         'percent_with_drawal' => '0',
                        //         'status' => '1',

                        //         'distributer_money' => '1',
                        //         'is_mobile_money' => '1',
                        //         'deleted_at' => null,
                        //         'created_at' => '2023-09-12 14:38:06',
                        //         'updated_at' => now(),
                        //         'percent_under_distributer' => '0.03',
                        //         'percent_admin_under_distributer' => '0.01',
                        //         'percent_deposit_under_distributer' => '0.005',
                        //         'percent_with_drawal_under_distributer' => '0',
                        //         'percent_banque' => '0.05',
                        //         'is_web' => '0',
                        //         'percent_web' => null,
                        //         'percent_masters' => null,
                        //         'is_eastafrica' => '0',
                        //         'is_euro' => '1',
                        // ],
                        // [
                        //         'name' => 'Belgique  vers Belgique ',
                        //         'percent' => '0.015',
                        //         'percent_merchands' => '0',
                        //         'deleted_by' => null,
                        //         'created_by' => '1',
                        //         'percent_admin' => '0.01',
                        //         //

                        //         'percent_deposit' => '0.005',
                        //         'percent_with_drawal' => '0',
                        //         'status' => '1',

                        //         'distributer_money' => '1',
                        //         'is_mobile_money' => '1',
                        //         'deleted_at' => null,
                        //         'created_at' => '2023-09-12 14:38:06',
                        //         'updated_at' => now(),
                        //         'percent_under_distributer' => '0.03',
                        //         'percent_admin_under_distributer' => '0.01',
                        //         'percent_deposit_under_distributer' => '0.005',
                        //         'percent_with_drawal_under_distributer' => '0',
                        //         'percent_banque' => '0.05',
                        //         'is_web' => '0',
                        //         'percent_web' => null,
                        //         'percent_masters' => null,
                        //         'is_eastafrica' => '0',
                        //         'is_euro' => '1',
                        // ],
                        [
                                'name' => 'Belgique  vers Algerie  ',
                                'percent' => '0.015',
                                'percent_merchands' => '0',
                                'deleted_by' => null,
                                'created_by' => '1',
                                'percent_admin' => '0.01',
                                //

                                'percent_deposit' => '0.005',
                                'percent_with_drawal' => '0',
                                'status' => '1',

                                'distributer_money' => '1',
                                'is_mobile_money' => '1',
                                'deleted_at' => null,
                                'created_at' => '2023-09-12 14:38:06',
                                'updated_at' => now(),
                                'percent_under_distributer' => '0.03',
                                'percent_admin_under_distributer' => '0.01',
                                'percent_deposit_under_distributer' => '0.005',
                                'percent_with_drawal_under_distributer' => '0',
                                'percent_banque' => '0.05',
                                'is_web' => '0',
                                'percent_web' => null,
                                'percent_masters' => null,
                                'is_eastafrica' => '0',
                                'is_euro' => '1',
                        ],
                        [
                                'name' => 'Belgique  vers Egypte  ',
                                'percent' => '0.015',
                                'percent_merchands' => '0',
                                'deleted_by' => null,
                                'created_by' => '1',
                                'percent_admin' => '0.01',
                                //

                                'percent_deposit' => '0.005',
                                'percent_with_drawal' => '0',
                                'status' => '1',

                                'distributer_money' => '1',
                                'is_mobile_money' => '1',
                                'deleted_at' => null,
                                'created_at' => '2023-09-12 14:38:06',
                                'updated_at' => now(),
                                'percent_under_distributer' => '0.03',
                                'percent_admin_under_distributer' => '0.01',
                                'percent_deposit_under_distributer' => '0.005',
                                'percent_with_drawal_under_distributer' => '0',
                                'percent_banque' => '0.05',
                                'is_web' => '0',
                                'percent_web' => null,
                                'percent_masters' => null,
                                'is_eastafrica' => '0',
                                'is_euro' => '1',
                        ],
                        [
                                'name' => 'Belgique  vers Burundi  ',
                                'percent' => '0.015',
                                'percent_merchands' => '0',
                                'deleted_by' => null,
                                'created_by' => '1',
                                'percent_admin' => '0.01',
                                //

                                'percent_deposit' => '0.005',
                                'percent_with_drawal' => '0',
                                'status' => '1',

                                'distributer_money' => '1',
                                'is_mobile_money' => '1',
                                'deleted_at' => null,
                                'created_at' => '2023-09-12 14:38:06',
                                'updated_at' => now(),
                                'percent_under_distributer' => '0.03',
                                'percent_admin_under_distributer' => '0.01',
                                'percent_deposit_under_distributer' => '0.005',
                                'percent_with_drawal_under_distributer' => '0',
                                'percent_banque' => '0.05',
                                'is_web' => '0',
                                'percent_web' => null,
                                'percent_masters' => null,
                                'is_eastafrica' => '0',
                                'is_euro' => '1',
                        ],
                        // [
                        //         'name' => 'Belgique  vers Angola  ',
                        //         'percent' => '0.015',
                        //         'percent_merchands' => '0',
                        //         'deleted_by' => null,
                        //         'created_by' => '1',
                        //         'percent_admin' => '0.01',
                        //         //

                        //         'percent_deposit' => '0.005',
                        //         'percent_with_drawal' => '0',
                        //         'status' => '1',

                        //         'distributer_money' => '1',
                        //         'is_mobile_money' => '1',
                        //         'deleted_at' => null,
                        //         'created_at' => '2023-09-12 14:38:06',
                        //         'updated_at' => now(),
                        //         'percent_under_distributer' => '0.03',
                        //         'percent_admin_under_distributer' => '0.01',
                        //         'percent_deposit_under_distributer' => '0.005',
                        //         'percent_with_drawal_under_distributer' => '0',
                        //         'percent_banque' => '0.05',
                        //         'is_web' => '0',
                        //         'percent_web' => null,
                        //         'percent_masters' => null,
                        //         'is_eastafrica' => '0',
                        //         'is_euro' => '1',
                        // ],

                        // [
                        //         'name' => 'Belgique  vers Turquie  ',
                        //         'percent' => '0.015',
                        //         'percent_merchands' => '0',
                        //         'deleted_by' => null,
                        //         'created_by' => '1',
                        //         'percent_admin' => '0.01',
                        //         //

                        //         'percent_deposit' => '0.005',
                        //         'percent_with_drawal' => '0',
                        //         'status' => '1',

                        //         'distributer_money' => '1',
                        //         'is_mobile_money' => '1',
                        //         'deleted_at' => null,
                        //         'created_at' => '2023-09-12 14:38:06',
                        //         'updated_at' => now(),
                        //         'percent_under_distributer' => '0.03',
                        //         'percent_admin_under_distributer' => '0.01',
                        //         'percent_deposit_under_distributer' => '0.005',
                        //         'percent_with_drawal_under_distributer' => '0',
                        //         'percent_banque' => '0.05',
                        //         'is_web' => '0',
                        //         'percent_web' => null,
                        //         'percent_masters' => null,
                        //         'is_eastafrica' => '0',
                        //         'is_euro' => '1',
                        // ],
                        // [
                        //         'name' => 'Belgique  vers Hollande(Pays bas)',
                        //         'percent' => '0.015',
                        //         'percent_merchands' => '0',
                        //         'deleted_by' => null,
                        //         'created_by' => '1',
                        //         'percent_admin' => '0.01',
                        //         //

                        //         'percent_deposit' => '0.005',
                        //         'percent_with_drawal' => '0',
                        //         'status' => '1',

                        //         'distributer_money' => '1',
                        //         'is_mobile_money' => '1',
                        //         'deleted_at' => null,
                        //         'created_at' => '2023-09-12 14:38:06',
                        //         'updated_at' => now(),
                        //         'percent_under_distributer' => '0.03',
                        //         'percent_admin_under_distributer' => '0.01',
                        //         'percent_deposit_under_distributer' => '0.005',
                        //         'percent_with_drawal_under_distributer' => '0',
                        //         'percent_banque' => '0.05',
                        //         'is_web' => '0',
                        //         'percent_web' => null,
                        //         'percent_masters' => null,
                        //         'is_eastafrica' => '0',
                        //         'is_euro' => '1',
                        // ],
                        // [
                        //         'name' => 'Belgique  vers Ethiopie',
                        //         'percent' => '0.015',
                        //         'percent_merchands' => '0',
                        //         'deleted_by' => null,
                        //         'created_by' => '1',
                        //         'percent_admin' => '0.01',
                        //         //

                        //         'percent_deposit' => '0.005',
                        //         'percent_with_drawal' => '0',
                        //         'status' => '1',

                        //         'distributer_money' => '1',
                        //         'is_mobile_money' => '1',
                        //         'deleted_at' => null,
                        //         'created_at' => '2023-09-12 14:38:06',
                        //         'updated_at' => now(),
                        //         'percent_under_distributer' => '0.03',
                        //         'percent_admin_under_distributer' => '0.01',
                        //         'percent_deposit_under_distributer' => '0.005',
                        //         'percent_with_drawal_under_distributer' => '0',
                        //         'percent_banque' => '0.05',
                        //         'is_web' => '0',
                        //         'percent_web' => null,
                        //         'percent_masters' => null,
                        //         'is_eastafrica' => '0',
                        //         'is_euro' => '1',
                        // ],
                        [
                                'name' => 'Belgique  vers Belgique',
                                'percent' => '0.015',
                                'percent_merchands' => '0',
                                'deleted_by' => null,
                                'created_by' => '1',
                                'percent_admin' => '0.01',
                                //

                                'percent_deposit' => '0.005',
                                'percent_with_drawal' => '0',
                                'status' => '1',

                                'distributer_money' => '1',
                                'is_mobile_money' => '1',
                                'deleted_at' => null,
                                'created_at' => '2023-09-12 14:38:06',
                                'updated_at' => now(),
                                'percent_under_distributer' => '0.03',
                                'percent_admin_under_distributer' => '0.01',
                                'percent_deposit_under_distributer' => '0.005',
                                'percent_with_drawal_under_distributer' => '0',
                                'percent_banque' => '0.05',
                                'is_web' => '0',
                                'percent_web' => null,
                                'percent_masters' => null,
                                'is_eastafrica' => '0',
                                'is_euro' => '1',
                        ],
                        // [
                        //         'name' => 'Belgique  vers Portugal',
                        //         'percent' => '0.015',
                        //         'percent_merchands' => '0',
                        //         'deleted_by' => null,
                        //         'created_by' => '1',
                        //         'percent_admin' => '0.01',
                        //         //

                        //         'percent_deposit' => '0.005',
                        //         'percent_with_drawal' => '0',
                        //         'status' => '1',

                        //         'distributer_money' => '1',
                        //         'is_mobile_money' => '1',
                        //         'deleted_at' => null,
                        //         'created_at' => '2023-09-12 14:38:06',
                        //         'updated_at' => now(),
                        //         'percent_under_distributer' => '0.03',
                        //         'percent_admin_under_distributer' => '0.01',
                        //         'percent_deposit_under_distributer' => '0.005',
                        //         'percent_with_drawal_under_distributer' => '0',
                        //         'percent_banque' => '0.05',
                        //         'is_web' => '0',
                        //         'percent_web' => null,
                        //         'percent_masters' => null,
                        //         'is_eastafrica' => '0',
                        //         'is_euro' => '1',
                        // ],
                        // Belgique 

                        // Belgique ia

                        // Ajoutez d'autres ensembles de données ici si nécessaire
                ];

                // Insérer les données en boucle pour dupliquer l'insertion
                // foreach ($data as $entry) {
                //     DB::table('level_countries')->insert($entry);
                // }
                DB::table('level_countries')->insert($data);
        }
}
