<?php

namespace App\Http\Services;


use App\Models\Costs\BankCost;
use App\Models\Costs\DepositCost;
use App\Models\Costs\PaymentCost;
use App\Models\Costs\PhoneCreditCost;
use App\Models\Costs\ReloadCost;
use App\Models\Costs\TransactionCost;
use App\Models\LevelCountry;
use App\Models\Moneys\LevelMoney;
use App\Models\Onexbet;
use App\Models\Providers\Provider;
use App\Repositories\TransactionRepository;

class PercentRobusAuthService
{




    /** @var  TransactionRepository */
    public $transctionRepository;

    public function __construct(TransactionRepository $transactionReposi)
    {
        $this->transctionRepository = $transactionReposi;
    }


    public function getRobusPercentageSDistributers()
    {
        $countryNameGroup = robus()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(26)->first();
            case 'AFL':
                return TransactionCost::whereId(32)->first();
            case 'AFC':
                return TransactionCost::whereId(33)->first();
            case 'USA':
                return TransactionCost::whereId(34)->first();
            case 'URP':
                return TransactionCost::whereId(35)->first();
            case 'MAG':
                return TransactionCost::whereId(36)->first();
            case 'ASIE':
                return TransactionCost::whereId(44)->first();
            case 'AFE':
                return TransactionCost::whereId(45)->first();
            default:
                return null; // Handle the default case as needed
        }
    }

    public function getRobusPercentageWeb()
    {
        return TransactionCost::whereId(42)->first();
    }

    public function getRobusPercentageCustumers()
    {
        $countryNameGroup = robus()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(28)->first();
            case 'AFL':
                return TransactionCost::whereId(37)->first();
            case 'AFC':
                return TransactionCost::whereId(38)->first();
            case 'USA':
                return TransactionCost::whereId(39)->first();
            case 'URP':
                return TransactionCost::whereId(40)->first();
            case 'MAG':
                return TransactionCost::whereId(41)->first();
            case 'ASIE':
                return TransactionCost::whereId(46)->first();
            case 'AFE':
                return TransactionCost::whereId(48)->first();
            default:
                return null;
        }
    }

    public function getRobusPercentageCustumerDrawal()
    {
        $countryNameGroup = robus()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(26)->first();
            case 'AFL':
                return LevelMoney::whereId(6)->first();
            case 'AFC':
                return LevelMoney::whereId(21)->first();
            case 'USA':
                return LevelMoney::whereId(41)->first();
            case 'URP':
                return LevelMoney::whereId(51)->first();
            case 'MAG':
                return LevelMoney::whereId(73)->first();
            default:
                return null;
        }
    }


    public function getRobusPercentageMobile()
    {
        $countryNameGroup = robus()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(27)->first();
            default:
                return null;
        }
    }

    public function getRobusPercentageMobileMaroc()
    {
        $countryNameGroup = robus()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'MAG':
                return TransactionCost::whereId(44)->first();
            default:
                return null;
        }
    }


    public function getRobusPercentageMobileWave()
    {
        $countryNameGroup = robus()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return ReloadCost::whereId(1)->first();
            default:
                return null;
        }
    }



    public function getRobusPercentageOnexbet()
    {
        $countryNameGroup = robus()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return Onexbet::whereId(1)->first();
            default:
                return null;
        }
    }

    public function getRobusPercentageOnexbetWeb()
    {
        return Onexbet::whereId(3)->first();
    }

    public function getRobusPercentageVirement()
    {

        return  BankCost::when(robus()->is_under_distributer, function ($query) {
            return $query->whereId(2);
        }, function ($query) {
            return $query->whereId(3);
        })->first();
    }

    public function getRobusPercentagePhoneCredit()
    {

        return  PhoneCreditCost::when(robus()->is_under_distributer, function ($query) {
            return $query->whereId(1);
        }, function ($query) {
            return $query->whereId(1);
        })->first();
    }


    public function getRobusPercentagePhoneCreditWeb()
    {

        return  PhoneCreditCost::whereId(3)->first();
    }


    public function getRobusPercentageMobileTwo()
    {
        $countryNameGroup = robus()->country->name_groupe;

        switch ($countryNameGroup) {
            case 'CIV':
                return TransactionCost::whereId(29)->first();
            default:
                return null;
        }
    }


    public function getRobusPercentageMobileWeb()
    {
        return TransactionCost::whereId(43)->first();
    }

    public function getRobusPercentageCardOrder($id)
    {
        return Provider::where('provider_type_id',  5)
            ->whereId($id)
            ->first();
    }

    public function getRobusPercentageReloadOrder()
    {
        return DepositCost::whereId(2)->first();
    }

    public function getRobusPercentageReloadWeb()
    {
        return DepositCost::whereId(3)->first();
    }


    public function getRobusPercentagePhoneInter()
    {
        return PhoneCreditCost::whereId(2)->first();
    }
    public function getRobusPercentagePayment()
    {
        return PaymentCost::whereId(2)->first();
    }

    public function getRobusPercentagePaymentInter()
    {
        return PaymentCost::whereId(1)->first();
    }

    public function getRobusPercentageRelaodCard()
    {
        return BankCost::whereId(1)->first();
    }

    public function getRobusPercentageOrderCard()
    {
        return DepositCost::whereId(1)->first();
    }

    public function getRobusPercentageInter($countrie, $provider_valeur)
    {
        $levelValeur = 0;

        if (robus()->country->name_groupe == 'AFL') {

            $countriesLevels = [

                // Is Benin
                25 => [

                    1 => ($provider_valeur != 51) ? 4504 : 3977, // USA
                    1 => ($provider_valeur != 51) ? 6351 : 6352, // Algerie
                    8 => ($provider_valeur != 51) ?  4507 : 3979, // Angola
                    36 => ($provider_valeur != 51) ? 2 : 37, // BF
                    37 => ($provider_valeur != 51) ? 6336 : 6335, // Burundi
                    39 => ($provider_valeur != 51) ? 129 : 178, // Cameroun
                    42 => ($provider_valeur != 51) ? 135 : 184, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 134 : 183, // Tchad
                    45 => ($provider_valeur != 51) ? 2834 : 2879, // China
                    50 => ($provider_valeur != 51) ? 131 : 180, // COngo Braza
                    57 => ($provider_valeur != 51) ? 132 : 181, // RDC
                    64 => ($provider_valeur != 51) ? 6338 : 6337, // Egypte
                    66 => ($provider_valeur != 51) ? 4517 : 3990, //  Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 1394 : 1439, // Ethiopie
                    74 => ($provider_valeur != 51) ?  4519 : 3992, // France
                    79 => ($provider_valeur != 51) ? 130 : 179, // Gabon
                    79 => ($provider_valeur != 51) ? 6340 : 6339, // Gambie
                    82 => ($provider_valeur != 51) ?  4522 : 3995, // Allemagne
                    83 => ($provider_valeur != 51) ? 6 : 41, // Ghana
                    91 => ($provider_valeur != 51) ? 8 : 43, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 4525 : 3998, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 4526 : 3999, // Inde
                    106 => ($provider_valeur != 51) ? 515 : 403, // Italie
                    106 => ($provider_valeur != 51) ?  4527 : 4000, // Italie
                    107 => ($provider_valeur != 51) ? 1 : 44, // CIV
                    112 => ($provider_valeur != 51) ? 4529 : 4002, // Keneya
                    116 => ($provider_valeur != 51) ? 4530 : 4003, // Koweit
                    116 => ($provider_valeur != 51) ? 6341 : 6342, // Liban
                    122 => ($provider_valeur != 51) ? 133 : 182, // Liberia
                    133 => ($provider_valeur != 51) ? 3 : 38, // Mali
                    133 => ($provider_valeur != 51) ? 6343 : 6344, // Mauritanie
                    146 => ($provider_valeur != 51) ?  4535 : 4008, // Maroc
                    152 => ($provider_valeur != 51) ? 4536 : 4009, // Hollande
                    157 => ($provider_valeur != 51) ? 7 : 42, // Niger
                    158 => ($provider_valeur != 51) ? 4538 : 4011, // Nigeria
                    173 => ($provider_valeur != 51) ? 4539 : 4012, // Portugal
                    175 => ($provider_valeur != 51) ?  4540 : 4013, // Quatar
                    180 => ($provider_valeur != 51) ?  4541 : 4014, // RWanda
                    188 => ($provider_valeur != 51) ? 5 : 40, // Senegal
                    191 => ($provider_valeur != 51) ? 4543 : 4016, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 4544 : 4017, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 4545 : 4018, // Suisse
                    212 => ($provider_valeur != 51) ?  4546 : 4019, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4 : 39, // Togo
                    219 => ($provider_valeur != 51) ? 4548 : 4021, // Turquie
                    223 => ($provider_valeur != 51) ? 4549 : 4022, // Ouganda
                    225 => ($provider_valeur != 51) ? 4550 : 4023, // Dubai
                    226 => ($provider_valeur != 51) ?  4551 : 4024, // Royaume Unie
                    218 => ($provider_valeur != 51) ? 6346 : 6345, // Tunisia
                    179 => ($provider_valeur != 51) ? 6347 : 6348, // Ruissie
                    241 => ($provider_valeur != 51) ? 6349 : 6350, // Zambie
                    23 => ($provider_valeur != 51) ? 2204 : 2249, // Belgique

                ],
                // Is BF
                36 =>  [


                    1 => ($provider_valeur != 51) ? 4600 : 4073, // USA
                    1 => ($provider_valeur != 51) ? 6354 : 6353, // Algerie
                    8 => ($provider_valeur != 51) ? 4602 : 4075, // Angola
                    25 => ($provider_valeur != 51) ? 4508 : 3981, // Benin
                    37 => ($provider_valeur != 51) ? 6356 : 6355, // Burundi
                    39 => ($provider_valeur != 51) ? 4606 : 4078, // Cameroun
                    42 => ($provider_valeur != 51) ? 142 : 191, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 141 : 190, // Tchad
                    45 => ($provider_valeur != 51) ? 4609 : 4081, // China
                    50 => ($provider_valeur != 51) ? 138 : 187, // COngo Braza
                    57 => ($provider_valeur != 51) ? 139 : 188, // RDC
                    152 => ($provider_valeur != 51) ? 6359 : 6360, // Egypte
                    66 => ($provider_valeur != 51) ? 4085 : 4613, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 4614 : 4086, // Ethiopie
                    74 => ($provider_valeur != 51) ? 4615 : 4087, // France
                    79 => ($provider_valeur != 51) ? 137 : 186, // Gabon
                    79 => ($provider_valeur != 51) ? 6361 : 6362, // Gambie
                    82 => ($provider_valeur != 51) ? 484 : 372, // Allemagne
                    83 => ($provider_valeur != 51) ? 12 : 48, // Ghana
                    91 => ($provider_valeur != 51) ? 14 : 50, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 1755 : 1800, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 4622 : 4094, // Inde
                    106 => ($provider_valeur != 51) ? 4095 : 4623, // Italie
                    107 => ($provider_valeur != 51) ? 15 : 51, // CIV
                    112 => ($provider_valeur != 51) ? 4625 : 4097, // Keneya
                    116 => ($provider_valeur != 51) ? 4626 : 4098, // Koweit
                    116 => ($provider_valeur != 51) ? 6364 : 6363, // Liban
                    122 => ($provider_valeur != 51) ? 140 : 189, // Liberia
                    133 => ($provider_valeur != 51) ? 9 : 45, // Mali
                    133 => ($provider_valeur != 51) ? 6365 : 6366, // Mauritanie
                    146 => ($provider_valeur != 51) ? 4631 : 4103, // Maroc
                    152 => ($provider_valeur != 51) ? 4632 : 4104, // Hollande
                    157 => ($provider_valeur != 51) ? 13 : 49, // Niger
                    158 => ($provider_valeur != 51) ? 4634 : 4106, // Nigeria
                    173 => ($provider_valeur != 51) ? 4635 : 4107, // Portugal
                    175 => ($provider_valeur != 51) ? 4636 : 4108, // Quatar
                    180 => ($provider_valeur != 51) ? 4637 : 4109, // RWanda
                    188 => ($provider_valeur != 51) ? 11 : 47, // Senegal
                    191 => ($provider_valeur != 51) ? 4639 : 4111, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 4640 : 4112, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 4641 : 4113, // Suisse
                    212 => ($provider_valeur != 51) ? 4642 : 4114, // Tanzanie
                    214 => ($provider_valeur != 51) ? 10 : 46, // Togo
                    219 => ($provider_valeur != 51) ? 4644 : 4116, // Turquie
                    223 => ($provider_valeur != 51) ? 4645 : 4117, // Ouganda
                    225 => ($provider_valeur != 51) ? 4646 : 4118, // Dubai
                    226 => ($provider_valeur != 51) ? 4647 : 4119, // Royaume Unie
                    218 => ($provider_valeur != 51) ? 6368 : 6367, // Tunisia
                    179 => ($provider_valeur != 51) ? 6369 : 6370, // Ruissie
                    241 => ($provider_valeur != 51) ? 6372 : 6371, // Zambie
                    23 => ($provider_valeur != 51) ? 6373 : 6374, // Belgique



                ],
                // Is Mali
                133 =>  [

                    1 => ($provider_valeur != 51) ? 570 : 243, // USA
                    1 => ($provider_valeur != 51) ? 6390 : 6381, // Algerie
                    8 => ($provider_valeur != 51) ? 1687 : 1732, // Angola
                    25 => ($provider_valeur != 51) ? 4533 : 4006, // Benin
                    36 => ($provider_valeur != 51) ? 4629 : 4101, // BF
                    37 => ($provider_valeur != 51) ? 6391 : 6383, // Burundi
                    39 => ($provider_valeur != 51) ? 143 : 192, // Cameroun
                    42 => ($provider_valeur != 51) ? 149 : 198, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 148 : 197, // Thacd
                    45 => ($provider_valeur != 51) ? 2857 : 2902, // China
                    50 => ($provider_valeur != 51) ? 145 : 194, // COngo Braza
                    57 => ($provider_valeur != 51) ? 146 : 195, // RDC
                    152 => ($provider_valeur != 51) ? 6391 : 6382, // Egypte
                    66 => ($provider_valeur != 51) ? 2092 : 2047, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 1417 : 1462, // Ethiopie
                    74 => ($provider_valeur != 51) ? 4567 : 4040, // France
                    79 => ($provider_valeur != 51) ? 144 : 193, // Gabon
                    79 => ($provider_valeur != 51) ? 6386 : 6377, // Gambie
                    82 => ($provider_valeur != 51) ? 4570 : 4043, // Allemagne
                    83 => ($provider_valeur != 51) ? 18 : 54, // Ghana
                    91 => ($provider_valeur != 51) ? 20 : 56, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 4573 : 4046, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 4574 : 4047, // Inde
                    106 => ($provider_valeur != 51) ? 4575 : 4048, // Italie
                    107 => ($provider_valeur != 51) ? 21 : 57, // CIV
                    112 => ($provider_valeur != 51) ? 1507 : 1552, // Keneya
                    116 => ($provider_valeur != 51) ? 2632 : 2587, // Koweit
                    // New Countrie
                    116 => ($provider_valeur != 51) ? 2609 : 2564, // Liban
                    122 => ($provider_valeur != 51) ? 147 : 196, // Liberia
                    133 => ($provider_valeur != 51) ? 6384 : 6375, // Mauritanie
                    146 => ($provider_valeur != 51) ? 4583 : 4056, // Maroc
                    152 => ($provider_valeur != 51) ? 2497 : 2542, // Hollande
                    157 => ($provider_valeur != 51) ? 19 : 55, // Niger
                    158 => ($provider_valeur != 51) ? 4586 : 4059, // Nigeria
                    173 => ($provider_valeur != 51) ? 4587 : 4060, // Portugal
                    175 => ($provider_valeur != 51) ? 2677 : 2722, // Quatar
                    180 => ($provider_valeur != 51) ? 834 : 789, // RWanda
                    188 => ($provider_valeur != 51) ? 17 : 53, // Senegal
                    191 => ($provider_valeur != 51) ? 4590 : 4064, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 4592 : 4065, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 4593 : 4066, // Suisse
                    212 => ($provider_valeur != 51) ? 4594 : 4067, // Tanzanie
                    214 => ($provider_valeur != 51) ? 16 : 52, // Togo
                    219 => ($provider_valeur != 51) ? 4596 : 4069, // Turquie
                    223 => ($provider_valeur != 51) ? 4597 : 4070, // Ouganda
                    225 => ($provider_valeur != 51) ? 4598 : 4071, // Dubai
                    226 => ($provider_valeur != 51) ?  4599 : 4072, // Royaume Unie
                    218 => ($provider_valeur != 51) ? 6385 : 6376, // Tunisia
                    179 => ($provider_valeur != 51) ? 6387 : 6378, // Ruissie
                    241 => ($provider_valeur != 51) ? 6388 : 6379, // Zambie
                    23 => ($provider_valeur != 51) ? 6389 : 6380, // Belgique

                ],
                // Is Togo
                214 =>  [

                    1 => ($provider_valeur != 51) ? 4648 : 4120, // USA
                    1 => ($provider_valeur != 51) ? 6399 : 6408, // Algerie
                    // voir id
                    8 => ($provider_valeur != 51) ? 1679 : 1724, // Angola
                    25 => ($provider_valeur != 51) ? 4547 : 4020, // Benin
                    36 => ($provider_valeur != 51) ? 4643 : 4115, // BF
                    37 => ($provider_valeur != 51) ? 6410 : 6401, // Burundi
                    39 => ($provider_valeur != 51) ? 150 : 199, // Cameroun
                    42 => ($provider_valeur != 51) ? 156 : 205, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 155 : 204, // Thacd
                    45 => ($provider_valeur != 51) ? 4657 : 4129, // China
                    50 => ($provider_valeur != 51) ? 152 : 201, // COngo Braza
                    57 => ($provider_valeur != 51) ? 153 : 202, // RDC
                    152 => ($provider_valeur != 51) ? 6400 : 6409, // Egypte
                    66 => ($provider_valeur != 51) ? 4661 : 4133, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 4662 : 4134, // Ethiopie
                    74 => ($provider_valeur != 51) ? 4663 : 4135, // France
                    79 => ($provider_valeur != 51) ? 151 : 200, // Gabon
                    79 => ($provider_valeur != 51) ? 6395 : 6404, // Gambie
                    82 => ($provider_valeur != 51) ? 4666 : 4138, // Allemagne
                    83 => ($provider_valeur != 51) ? 23 : 59, // Ghana
                    91 => ($provider_valeur != 51) ? 25 : 61, // Guinne conakry
                    92 => ($provider_valeur != 51) ?  4669 : 4141, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 4670 : 4142, // Inde
                    106 => ($provider_valeur != 51) ? 4671 : 4143, // Italie
                    107 => ($provider_valeur != 51) ? 26 : 62, // CIV
                    112 => ($provider_valeur != 51) ? 4673 : 4145, // Keneya
                    116 => ($provider_valeur != 51) ? 4674 : 4146, // Koweit
                    // New countrie
                    116 => ($provider_valeur != 51) ? 2609 : 2564, // Liban
                    122 => ($provider_valeur != 51) ? 154 : 203, // Liberia
                    133 => ($provider_valeur != 51) ? 4595 : 4068, // Mali
                    133 => ($provider_valeur != 51) ? 6393 : 6402, // Mauritanie
                    146 => ($provider_valeur != 51) ? 4679 : 4151, // Maroc
                    152 => ($provider_valeur != 51) ? 4680 : 4152, // Hollande
                    157 => ($provider_valeur != 51) ? 24 : 60, // Niger
                    158 => ($provider_valeur != 51) ? 4682 : 4154, // Nigeria
                    173 => ($provider_valeur != 51) ? 4683 : 4155, // Portugal
                    175 => ($provider_valeur != 51) ? 4684 : 4156, // Quatar
                    180 => ($provider_valeur != 51) ? 4685 : 4157, // RWanda
                    188 => ($provider_valeur != 51) ? 22 : 58, // Senegal
                    191 => ($provider_valeur != 51) ? 4687 : 4159, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 4688 : 4160, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 4689 : 4161, // Suisse
                    212 => ($provider_valeur != 51) ? 4690 : 4162, // Tanzanie
                    219 => ($provider_valeur != 51) ? 4164 : 4163, // Turquie
                    223 => ($provider_valeur != 51) ? 4693 : 4165, // Ouganda
                    225 => ($provider_valeur != 51) ? 4694 : 4166, // Dubai
                    218 => ($provider_valeur != 51) ? 6394 : 6403, // Tunisia
                    179 => ($provider_valeur != 51) ? 6396 : 6405, // Ruissie
                    241 => ($provider_valeur != 51) ? 6406 : 6397, // Zambie
                    23 => ($provider_valeur != 51) ? 6398 : 6407, // Belgique
                    // New countrie
                    226 => ($provider_valeur != 51) ? 4695 : 4167, // Royaume Unie

                ],
                // Is Senegal
                188 =>  [

                    1 => ($provider_valeur != 51) ? 4696 : 4168, // USA
                    2 => ($provider_valeur != 51) ? 6489 : 6417, // Algerie
                    // Voir ID
                    8 => ($provider_valeur != 51) ? 1674 : 1719, // Angola
                    25 => ($provider_valeur != 51) ? 4542 : 4015, // Benin
                    36 => ($provider_valeur != 51) ? 4638 : 4110, // BF
                    37 => ($provider_valeur != 51) ? 6419 : 6491, // Burundi
                    39 => ($provider_valeur != 51) ? 157 : 206, // Cameroun
                    42 => ($provider_valeur != 51) ? 163 : 212, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 162 : 211, // Thacd
                    45 => ($provider_valeur != 51) ? 2866 : 2911, // China
                    50 => ($provider_valeur != 51) ? 159 : 208, // COngo Braza
                    57 => ($provider_valeur != 51) ? 160 : 209, // RDC
                    60 => ($provider_valeur != 51) ? 6490 : 6418, // Egypte
                    66 => ($provider_valeur != 51) ? 2101 : 2056, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 1426 : 1471, // Ethiopie
                    74 => ($provider_valeur != 51) ? 4711 : 4183, // France
                    79 => ($provider_valeur != 51) ? 158 : 207, // Gabon
                    79 => ($provider_valeur != 51) ? 130 : 179, // Gambie
                    82 => ($provider_valeur != 51) ? 4713 : 4186, // Allemagne
                    83 => ($provider_valeur != 51) ? 27 : 63, // Ghana
                    91 => ($provider_valeur != 51) ? 29 : 65, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 4717 : 4189, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 4718 : 4190, // Inde
                    106 => ($provider_valeur != 51) ? 4719 : 4191, // Italie
                    107 => ($provider_valeur != 51) ? 30 : 66, // CIV
                    112 => ($provider_valeur != 51) ? 4721 : 4192, // Keneya
                    116 => ($provider_valeur != 51) ? 4722 : 4193, // Koweit
                    // New countrie
                    116 => ($provider_valeur != 51) ? 4723 : 4194, // Liban
                    122 => ($provider_valeur != 51) ? 161 : 210, // Liberia
                    133 => ($provider_valeur != 51) ? 28 : 64, // Niger
                    158 => ($provider_valeur != 51) ? 4730 : 4202, // Nigeria
                    173 => ($provider_valeur != 51) ? 4731 : 4203, // Portugal
                    175 => ($provider_valeur != 51) ? 4732 : 4204, // Quatar
                    180 => ($provider_valeur != 51) ? 4733 : 4205, // RWanda
                    188 => ($provider_valeur != 51) ? 4725 : 4063, // Mali
                    191 => ($provider_valeur != 51) ? 4735 : 4207, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 4736 : 4208, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 4737 : 4209, // Suisse
                    212 => ($provider_valeur != 51) ? 4738 : 4210, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4686 : 4158, // Togo
                    219 => ($provider_valeur != 51) ? 4740 : 4212, // Turquie
                    223 => ($provider_valeur != 51) ? 4741 : 4213, // Ouganda
                    225 => ($provider_valeur != 51) ? 4742 : 4214, // Dubai
                    226 => ($provider_valeur != 51) ? 4743 : 4215, // Royaume Unie
                    218 => ($provider_valeur != 51) ? 6484 : 6412, // Tunisia
                    179 => ($provider_valeur != 51) ? 6486 : 6414, // Ruissie
                    241 => ($provider_valeur != 51) ? 6487 : 6415, // Zambie
                    23 => ($provider_valeur != 51) ? 6488 : 6416, // Belgique

                ],
                // IS Ghana
                83 =>  [

                    133 => ($provider_valeur != 51) ? 4571 : 4044, // Mali
                    1 => ($provider_valeur != 51) ? 4744 : 4216, // USA
                    2 => ($provider_valeur != 51) ? 6516 : 6426, // Algerie
                    8 => ($provider_valeur != 51) ? 4746 : 4218, // Angola
                    25 => ($provider_valeur != 51) ? 4524 : 3996, // Benin
                    36 => ($provider_valeur != 51) ? 4619 : 4091, // BF
                    37 => ($provider_valeur != 51) ? 6518 : 6428, // Burundi
                    39 => ($provider_valeur != 51) ? 164 : 213, // Cameroun
                    42 => ($provider_valeur != 51) ? 170 : 219, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 169 : 218, // Thacd
                    45 => ($provider_valeur != 51) ? 2848 : 2893, // China
                    50 => ($provider_valeur != 51) ? 166 : 215, // COngo Braza
                    57 => ($provider_valeur != 51) ? 167 : 216, // RDC
                    60 => ($provider_valeur != 51) ? 6517 : 6427, // Egypte
                    66 => ($provider_valeur != 51) ? 4757 : 4229, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 4758 : 4230, // Ethiopie
                    74 => ($provider_valeur != 51) ? 4759 : 4231, // France
                    79 => ($provider_valeur != 51) ? 165 : 214, // Gabon
                    79 => ($provider_valeur != 51) ? 6512 : 6422, // Gambie
                    82 => ($provider_valeur != 51) ? 4762 : 4234, // Allemagne
                    91 => ($provider_valeur != 51) ? 32 : 68, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 4765 : 4237, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 4766 : 4238, // Inde
                    106 => ($provider_valeur != 51) ? 4767 : 4239, // Italie
                    107 => ($provider_valeur != 51) ? 33 : 69, // CIV
                    112 => ($provider_valeur != 51) ? 4769 : 4241, // Keneya
                    116 => ($provider_valeur != 51) ? 4770 : 4242, // Koweit
                    // New countire
                    116 => ($provider_valeur != 51) ? 2609 : 2564, // Liban

                    122 => ($provider_valeur != 51) ? 168 : 217, // Liberia
                    133 => ($provider_valeur != 51) ? 31 : 67, // Niger
                    158 => ($provider_valeur != 51) ? 4778 : 4250, // Nigeria
                    173 => ($provider_valeur != 51) ? 4779 : 4251, // Portugal
                    175 => ($provider_valeur != 51) ? 4780 : 4252, // Quatar
                    180 => ($provider_valeur != 51) ? 825 : 780, // RWanda
                    188 => ($provider_valeur != 51) ? 4715 : 4187, // Senegal
                    191 => ($provider_valeur != 51) ? 4783 : 4255, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 4784 : 4256, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 4785 : 4257, // Suisse
                    212 => ($provider_valeur != 51) ? 4786 : 4258, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4667 : 4139, // Togo
                    219 => ($provider_valeur != 51) ? 4788 : 4260, // Turquie
                    223 => ($provider_valeur != 51) ? 4789 : 4261, // Ouganda
                    225 => ($provider_valeur != 51) ? 4790 : 4262, // Dubai
                    226 => ($provider_valeur != 51) ? 4791 : 4263, // Royaume Unie
                    218 => ($provider_valeur != 51) ? 6511 : 6421, // Tunisia
                    179 => ($provider_valeur != 51) ? 6513 : 6423, // Ruissie
                    241 => ($provider_valeur != 51) ? 6424 : 6514, // Zambie
                    23 => ($provider_valeur != 51) ? 6515 : 6425, // Belgique

                ],
                // Is Niger
                157 =>  [

                    // 4826 : 4298
                    1 => ($provider_valeur != 51) ? 4792 : 4264, // USA
                    2 => ($provider_valeur != 51) ? 6507 : 6435, // Algerie
                    // Review Id
                    8 => ($provider_valeur != 51) ? 1669 : 1714, // Angola
                    83 => ($provider_valeur != 51) ? 4777 : 4249, // Ghana
                    25 => ($provider_valeur != 51) ? 4537 : 4010, // Benin
                    36 => ($provider_valeur != 51) ? 4633 : 4105, // BF
                    37 => ($provider_valeur != 51) ? 6509 : 6437, // Burundi
                    39 => ($provider_valeur != 51) ? 576 : 220, // Cameroun
                    42 => ($provider_valeur != 51) ? 582 : 226, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 581 : 225, // Thacd
                    45 => ($provider_valeur != 51) ? 4801 : 4273, // China
                    50 => ($provider_valeur != 51) ? 578 : 222, // COngo Braza
                    57 => ($provider_valeur != 51) ? 579 : 223, // RDC
                    60 => ($provider_valeur != 51) ? 6508 : 6435, // Egypte
                    66 => ($provider_valeur != 51) ? 4805 : 4277, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 4806 : 4278, // Ethiopie
                    74 => ($provider_valeur != 51) ? 4807 : 4279, // France
                    79 => ($provider_valeur != 51) ? 577 : 221, // Gabon
                    79 => ($provider_valeur != 51) ? 6503 : 6431, // Gambie
                    82 => ($provider_valeur != 51) ? 4810 : 4282, // Allemagne
                    91 => ($provider_valeur != 51) ? 34 : 70, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 1781 : 1826, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 4814 : 4286, // Inde
                    106 => ($provider_valeur != 51) ? 4815 : 4287, // Italie
                    107 => ($provider_valeur != 51) ? 35 : 71, // CIV
                    112 => ($provider_valeur != 51) ? 4817 : 4289, // Keneya
                    116 => ($provider_valeur != 51) ?  4818 : 4290, // Koweit
                    // New Counties
                    116 => ($provider_valeur != 51) ? 4819 : 4291, // Liban
                    122 => ($provider_valeur != 51) ? 580 : 224, // Liberia
                    133 => ($provider_valeur != 51) ? 4585 : 4058, // Mali
                    158 => ($provider_valeur != 51) ? 4826 : 4298, // Nigeria
                    173 => ($provider_valeur != 51) ? 4827 : 4299, // Portugal
                    175 => ($provider_valeur != 51) ? 4828 : 4300, // Quatar
                    // Update Id
                    180 => ($provider_valeur != 51) ? 4829 : 4301, // RWanda
                    188 => ($provider_valeur != 51) ? 4729 : 4201, // Senegal
                    191 => ($provider_valeur != 51) ? 4831 : 4303, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 4832 : 4304, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 4833 : 4305, // Suisse
                    212 => ($provider_valeur != 51) ? 4834 : 4306, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4681 : 4153, // Togo
                    219 => ($provider_valeur != 51) ? 4836 : 4308, // Turquie
                    223 => ($provider_valeur != 51) ? 4837 : 4309, // Ouganda
                    225 => ($provider_valeur != 51) ? 4838 : 4310, // Dubai
                    226 => ($provider_valeur != 51) ? 4839 : 4311, // Royaume Unie
                    218 => ($provider_valeur != 51) ? 6502 : 6430, // Tunisia
                    179 => ($provider_valeur != 51) ? 6504 : 6432, // Ruissie
                    241 => ($provider_valeur != 51) ? 6505 : 6433, // Zambie
                    23 => ($provider_valeur != 51) ? 6506 : 6434, // Belgique


                ],
                // Is Guinnée Conakry
                91 =>  [

                    // Update Id
                    1 => ($provider_valeur != 51) ? 4840 : 4312, // USA
                    2 => ($provider_valeur != 51) ? 6498 : 6444, // Algerie
                    // New COuntrie
                    8 => ($provider_valeur != 51) ? 1679 : 1724, // Angola
                    25 => ($provider_valeur != 51) ? 4525 : 3997, // Benin
                    36 => ($provider_valeur != 51) ? 4620 : 4092, // BF
                    37 => ($provider_valeur != 51) ? 6500 : 6446, // Burundi
                    39 => ($provider_valeur != 51) ? 171 : 227, // Cameroun
                    42 => ($provider_valeur != 51) ? 177 : 233, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 176 : 232, // Thacd
                    45 => ($provider_valeur != 51) ? 4849 : 4321, // China
                    50 => ($provider_valeur != 51) ? 173 : 229, // COngo Braza
                    57 => ($provider_valeur != 51) ? 174 : 230, // RDC
                    60 => ($provider_valeur != 51) ? 6499 : 6445, // Egypte
                    66 => ($provider_valeur != 51) ? 4853 : 4325, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 4854 : 4326, // Ethiopie
                    74 => ($provider_valeur != 51) ? 4855 : 4327, // France
                    79 => ($provider_valeur != 51) ? 172 : 228, // Gabon
                    80 => ($provider_valeur != 51) ? 6494 : 6440, // Gambie
                    82 => ($provider_valeur != 51) ? 4858 : 4330, // Allemagne
                    83 => ($provider_valeur != 51) ? 4764 : 4236, // Ghana
                    92 => ($provider_valeur != 51) ? 4860 : 4333, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 4862 : 4334, // Inde
                    106 => ($provider_valeur != 51) ? 4863 : 4335, // Italie
                    107 => ($provider_valeur != 51) ? 36 : 72, // CIV
                    157 => ($provider_valeur != 51) ? 4812 : 4284, // Niger
                    112 => ($provider_valeur != 51) ? 4865 : 4337, // Keneya
                    116 => ($provider_valeur != 51) ? 4866 : 4338, // Koweit
                    // New COunr
                    116 => ($provider_valeur != 51) ? 4867 : 4339, // Liban
                    122 => ($provider_valeur != 51) ? 175 : 231, // Liberia
                    133 => ($provider_valeur != 51) ? 4572 : 4045, // Mali
                    158 => ($provider_valeur != 51) ? 4874 : 4346, // Nigeria
                    173 => ($provider_valeur != 51) ? 4875 : 4347, // Portugal
                    175 => ($provider_valeur != 51) ? 4876 : 4348, // Quatar
                    180 => ($provider_valeur != 51) ? 4877 : 4349, // RWanda
                    188 => ($provider_valeur != 51) ? 4716 : 4188, // Senegal
                    191 => ($provider_valeur != 51) ? 4879 : 4351, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 4880 : 4352, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 4881 : 4353, // Suisse
                    212 => ($provider_valeur != 51) ? 4882 : 4354, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4668 : 4140, // Togo
                    219 => ($provider_valeur != 51) ? 4884 : 4356, // Turquie
                    223 => ($provider_valeur != 51) ? 4885 : 4357, // Ouganda
                    225 => ($provider_valeur != 51) ? 4886 : 4358, // Dubai
                    226 => ($provider_valeur != 51) ? 4887 : 4359, // Royaume Unie
                    218 => ($provider_valeur != 51) ? 6493 : 6439, // Tunisia
                    179 => ($provider_valeur != 51) ? 6495 : 6441, // Ruissie
                    241 => ($provider_valeur != 51) ? 6496 : 6442, // Zambie
                    23 => ($provider_valeur != 51) ? 6497 : 6443, // Belgique

                ],
                // Is  Guinnée Buisso
                92 => [

                    //4928 : 4400
                    1 => ($provider_valeur != 51) ? 1750 : 1795, // USA
                    2 => ($provider_valeur != 51) ? 6480 : 6453, // Algerie
                    // New Update
                    8 => ($provider_valeur != 51) ? 4890 : 4362, // Angola
                    25 => ($provider_valeur != 51) ? 1754 : 1799, // Benin
                    36 => ($provider_valeur != 51) ? 4621 : 4093, // BF
                    37 => ($provider_valeur != 51) ? 4891 : 4363, // Burundi
                    39 => ($provider_valeur != 51) ? 1757 : 1802, // Cameroun
                    42 => ($provider_valeur != 51) ? 1758 : 1803, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 1759 : 1804, // Thacd
                    45 => ($provider_valeur != 51) ? 3149 : 3169, // China
                    50 => ($provider_valeur != 51) ? 1761 : 1806, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1762 : 1807, // RDC
                    60 => ($provider_valeur != 51) ? 6481 : 6454, // Egypte
                    66 => ($provider_valeur != 51) ? 3145 : 3165, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 3156 : 3176, // Ethiopie
                    74 => ($provider_valeur != 51) ? 1809 : null, // France
                    79 => ($provider_valeur != 51) ? 4904 : 4376, // Gabon
                    82 => ($provider_valeur != 51) ? 1812 : null, // Allemagne
                    83 => ($provider_valeur != 51) ? 1768 : 1813, // Ghana
                    91 => ($provider_valeur != 51) ? 1769 : 1814, // Guinne conakry
                    100 => ($provider_valeur != 51) ? 3155 : 3175, // Inde
                    106 => ($provider_valeur != 51) ? 1817 : null, // Italie
                    107 => ($provider_valeur != 51) ? 632 : 652, // CIV
                    112 => ($provider_valeur != 51) ? 3148 : 3168, // Keneya
                    116 => ($provider_valeur != 51) ? 3141 : 3161, // Koweit
                    // Update Id Two
                    122 => ($provider_valeur != 51) ? 1821 : 1866, // Liberia
                    133 => ($provider_valeur != 51) ? 1777 : 1822, // Mali
                    146 => ($provider_valeur == 51) ? 1824 : null, // Maroc
                    157 => ($provider_valeur != 51) ? 1781 : 1826, // Niger
                    158 => ($provider_valeur != 51) ? 3139 : 3159, // Nigeria
                    173 => ($provider_valeur != 51) ? 3152 : 3172, // Portugal
                    175 => ($provider_valeur != 51) ? 3143 : 3163, // Quatar
                    179 => ($provider_valeur == 51) ? 3030 : null, // Russie
                    180 => ($provider_valeur != 51) ? 3138 : 3158, // Rwanda
                    188 => ($provider_valeur != 51) ? 1786 : 1831, // Senegal
                    191 => ($provider_valeur != 51) ? 3140 : 3160, // Sierria Leone
                    199 => ($provider_valeur == 51) ? 1833 : null, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 3150 : 3170, // Suisse
                    212 => ($provider_valeur != 51) ? 3142 : 3162, // Tanzanie
                    214 => ($provider_valeur != 51) ? 1791 : 1836, // Togo
                    218 => ($provider_valeur == 51) ? 6448 : 6475, // Tunisia
                    219 => ($provider_valeur != 51) ? 3153 : 3173, // Turquie
                    223 => ($provider_valeur != 51) ? 3137 : 3157, // Ouganda
                    225 => ($provider_valeur != 51) ? 3147 : 3167, // Dubai
                    226 => ($provider_valeur == 51) ? 1839 : null, // Royaume Unie
                    241 => ($provider_valeur != 51) ? 3144 : 3164, // Zambie
                    23 => ($provider_valeur != 51) ? 3151 : 3171, // Belgique
                ],
                // Is Sierra Leone 
                191 => [

                    1 => ($provider_valeur != 51) ? 1840 : 1885, // USA
                    2 => ($provider_valeur != 51) ? 6534 : 6462, // Algerie
                    // Update Id
                    8 => ($provider_valeur != 51) ? 1679 : 1724, // Angola
                    25 => ($provider_valeur != 51) ? 1844 : 1889, // Benin
                    36 => ($provider_valeur != 51) ? 1845 : 1890, // BF
                    37 => ($provider_valeur != 51) ? 6536 : 6464, // Burundi
                    39 => ($provider_valeur != 51) ? 1847 : 1892, // Cameroun
                    42 => ($provider_valeur != 51) ? 1866 : 1977, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 1759 : 1804, // Thacd
                    45 => ($provider_valeur != 51) ? 3189 : 3208, // China
                    50 => ($provider_valeur != 51) ? 1851 : 1896, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1852 : 1897, // RDC
                    60 => ($provider_valeur != 51) ? 6535 : 6463, // Egypte
                    66 => ($provider_valeur != 51) ? 3185 : 3204, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 3196 : 3215, // Ethiopie
                    74 => ($provider_valeur == 51) ? 1899 : null, // France
                    79 => ($provider_valeur != 51) ?  4952 : 4424, // Gabon
                    82 => ($provider_valeur == 51) ? 1902 : null, // Allemagne
                    83 => ($provider_valeur != 51) ? 1858 : 1903, // Ghana
                    91 => ($provider_valeur != 51) ? 1859 : 1904, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 3177 : 3975, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 3195 : 3214, // Inde
                    106 => ($provider_valeur == 51) ? 1907 : null, // Italie
                    107 => ($provider_valeur != 51) ? 636 : 653, // CIV
                    112 => ($provider_valeur != 51) ? 3188 : 3207, // Keneya
                    116 => ($provider_valeur != 51) ? 3181 : 3200, // Koweit
                    122 => ($provider_valeur != 51) ? 1866 : 1911, // Liberia
                    133 => ($provider_valeur != 51) ? 1867 : 1912, // Mali
                    146 => ($provider_valeur == 51) ? 1914 : null, // Maroc
                    157 => ($provider_valeur != 51) ? 1871 : 1916, // Niger
                    158 => ($provider_valeur != 51) ? 3180 : 3199, // Nigeria
                    173 => ($provider_valeur != 51) ? 3192 : 3211, // Portugal
                    175 => ($provider_valeur != 51) ? 3183 : 3202, // Quatar
                    179 => ($provider_valeur == 51) ? 3034 : 6531, // Ruissie
                    180 => ($provider_valeur != 51) ? 3179 : 3198, // Rwanda
                    188 => ($provider_valeur != 51) ? 1876 : 1921, // Senegal
                    199 => ($provider_valeur == 51) ? 1923 : null, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 4977 : 4449, // Suisse
                    212 => ($provider_valeur != 51) ? 3182 : 3201, // Tanzanie
                    214 => ($provider_valeur != 51) ? 1881 : 1926, // Togo
                    218 => ($provider_valeur == 51) ? 6457 : 6529, // Tunisia
                    219 => ($provider_valeur != 51) ? 3193 : 3212, // Turquie
                    223 => ($provider_valeur != 51) ? 3178 : 3197, // Ouganda
                    225 => ($provider_valeur != 51) ? 3187 : 3206, // Dubai
                    226 => ($provider_valeur == 51) ? 1929 : null, // Royaume Unie
                    241 => ($provider_valeur != 51) ? 3184 : 3203, // Zambie
                    23 => ($provider_valeur != 51) ? 3191 : 3210, // Belgique
                ],
                // Is Nigeria
                158 => [


                    1 => ($provider_valeur != 51) ? 1930 : 1975, // USA
                    2 => ($provider_valeur != 51) ? 1931 : 1976, // Algerie
                    // New Update
                    8 => ($provider_valeur != 51) ? 4985 : 4457, // Angola
                    25 => ($provider_valeur != 51) ? 1934 : 1979, // Benin
                    36 => ($provider_valeur != 51) ? 1935 : 1980, // BF
                    37 => ($provider_valeur != 51) ? 1936 : 1981, // Burundi
                    39 => ($provider_valeur != 51) ? 1937 : 1982, // Cameroun
                    42 => ($provider_valeur != 51) ? 1938 : 1983, // Afrique centrale
                    //  UPdate Id Robus
                    43 => ($provider_valeur != 51) ? 1939 : 1849, // Thacd
                    45 => ($provider_valeur != 51) ? 3228 : 3247, // China
                    50 => ($provider_valeur != 51) ? 1941 : 1986, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1942 : 1987, // RDC
                    60 => ($provider_valeur != 51) ? 1943 : 1988, // Egypte
                    66 => ($provider_valeur != 51) ? 3224 : 3243, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 3235 : 3254, // Ethiopie
                    74 => ($provider_valeur == 51) ? 1989 : null, // France
                    79 => ($provider_valeur != 51) ? 5000 : 4472, // Gabon
                    82 => ($provider_valeur == 51) ? 1992 : null, // Allemagne
                    83 => ($provider_valeur != 51) ? 1948 : 1993, // Ghana
                    91 => ($provider_valeur != 51) ? 1949 : 1994, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 3216 : 3976, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 3234 : 3253, // Inde
                    106 => ($provider_valeur == 51) ? 1997 : null, // Italie
                    107 => ($provider_valeur != 51) ? 635 : 654, // CIV
                    112 => ($provider_valeur != 51) ? 3227 : 3246, // Keneya
                    116 => ($provider_valeur != 51) ? 3220 : 3239, // Koweit
                    122 => ($provider_valeur != 51) ? 1956 : 2001, // Liberia
                    133 => ($provider_valeur != 51) ? 1957 : 2002, // Mali
                    146 => ($provider_valeur == 51) ? 2004 : null, // Maroc
                    157 => ($provider_valeur != 51) ? 1961 : 2006, // Niger
                    173 => ($provider_valeur != 51) ? 3231 : 3250, // Portugal
                    175 => ($provider_valeur != 51) ? 3222 : 3241, // Quatar
                    179 => ($provider_valeur == 51) ? 6681 : null, // Ruissie
                    180 => ($provider_valeur != 51) ? 3218 : 3237, // Rwanda
                    188 => ($provider_valeur != 51) ? 1966 : 2011, // Senegal
                    191 => ($provider_valeur != 51) ? 3219 : 3238, // Sierria Leone
                    199 => ($provider_valeur == 51) ? 2013 : null, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 3229 : 3248, // Suisse
                    212 => ($provider_valeur != 51) ? 3221 : 3240, // Tanzanie
                    214 => ($provider_valeur != 51) ? 1971 : 2016, // Togo
                    218 => ($provider_valeur == 51) ? 6682 : null, // Tunisia
                    219 => ($provider_valeur != 51) ? 3232 : 3251, // Turquie
                    223 => ($provider_valeur != 51) ? 3217 : 3236, // Ouganda
                    225 => ($provider_valeur != 51) ? 3226 : 3245, // Dubai
                    226 => ($provider_valeur == 51) ? 2019 : null, // Royaume Unie
                    241 => ($provider_valeur != 51) ? 3223 : 3242, // Zambie
                    23 => ($provider_valeur != 51) ? 3230 : 3249, // Belgique
                ],

                // Update Countrie
                // Is Gambie
                80 =>  [

                    1 => ($provider_valeur != 51) ? 256 : 235, // USA
                    2 => ($provider_valeur != 51) ? 248 : 241, // Algerie
                    8 => ($provider_valeur != 51) ? 1679 : 1724, // Angola
                    25 => ($provider_valeur != 51) ? 4521 : 3994, // Benin
                    36 => ($provider_valeur != 51) ? 4617 : 4089, // BF
                    37 => ($provider_valeur != 51) ? 2474 : 2519, // Burundi
                    39 => ($provider_valeur != 51) ? 73 : 101, // Cameroun
                    42 => ($provider_valeur != 51) ? 84 : 112, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 83 : 111, // Thacd
                    45 => ($provider_valeur != 51) ? 3108 : 3129, // China
                    50 => ($provider_valeur != 51) ?  80 : 108, // COngo Braza
                    57 => ($provider_valeur != 51) ? 81 : 109, // RDC
                    60 => ($provider_valeur != 51) ? 152 : 201, // Egypte
                    66 => ($provider_valeur != 51) ? 3104 : 3125, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 3115 : 3136, // Ethiopie
                    74 => ($provider_valeur != 51) ? 477 : 365, // France
                    79 => ($provider_valeur != 51) ? 137 : 186, // Gabon
                    82 => ($provider_valeur != 51) ? 493 : 381, // Allemagne
                    83 => ($provider_valeur != 51) ? 165 : 214, // Ghana
                    91 => ($provider_valeur != 51) ? 4857 : 4329, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 3095 : 3116, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 3114 : 3135, // Inde
                    106 => ($provider_valeur != 51) ? 525 : 413, // Italie
                    107 => ($provider_valeur != 51) ? 85 : 113, // CIV
                    112 => ($provider_valeur != 51) ? 3107 : 3128, // Keneya
                    116 => ($provider_valeur != 51) ? 3100 : 3121, // Koweit
                    122 => ($provider_valeur != 51) ? 5190 : 5442, // Liberia
                    133 => ($provider_valeur != 51) ? 144 : 4042, // Mali
                    146 => ($provider_valeur != 51) ? 286 : 334, // Maroc
                    157 => ($provider_valeur != 51) ? 4809 : 4281, // Niger
                    158 => ($provider_valeur != 51) ? 3098 : 3119, // Nigeria
                    173 => ($provider_valeur != 51) ? 3111 : 3132, // Portugal
                    175 => ($provider_valeur != 51) ? 3102 : 3123, // Quatar
                    179 => ($provider_valeur != 51) ? 541 : 429, // Russie
                    180 => ($provider_valeur != 51) ? 3097 : 3118, // Rwanda
                    188 => ($provider_valeur != 51) ? 158 : 207, // Senegal
                    191 => ($provider_valeur != 51) ? 3099 : 3120, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 445 : 322, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 3109 : 3130, // Suisse
                    212 => ($provider_valeur != 51) ? 3101 : 3122, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4665 : 4137, // Togo
                    218 => ($provider_valeur != 51) ? 272 : 328, // Tunisia
                    219 => ($provider_valeur != 51) ? 3112 : 3133, // Turquie
                    223 => ($provider_valeur != 51) ? 3096 : 3117, // Ouganda
                    225 => ($provider_valeur != 51) ? 3106 : 3127, // Dubai
                    226 => ($provider_valeur != 51) ? 509 : 397, // Royaume Unie
                    241 => ($provider_valeur != 51) ? 3103 : 3124, // Zambie
                    23 => ($provider_valeur != 51) ? 3110 : 3131, // Belgique

                ],

                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif (robus()->country->name_groupe == 'AFC') {

            $countriesLevels = [
                // Is Cameroun
                39 => [

                    1 => ($provider_valeur != 51) ? 255 : 234, // USA
                    69 => ($provider_valeur != 51) ? 6543 : 6615, // Algerie
                    8 => ($provider_valeur != 51) ? 5034 : 5574, // Angola
                    25 => ($provider_valeur != 51) ? 4510 : 3983, // Benin
                    36 => ($provider_valeur != 51) ? 136 : 185, // BF (Burkina Faso)
                    123 => ($provider_valeur != 51) ? 6545 : 6617, // Burundi (New entry)
                    42 => ($provider_valeur != 51) ? 78 : 106, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    45 => ($provider_valeur != 51) ? 2837 : 2882, // China
                    50 => ($provider_valeur != 51) ? 74 : 102, // COngo Braza
                    57 => ($provider_valeur != 51) ? 75 : 103, // RDC
                    94 => ($provider_valeur != 51) ? 6544 : 6616, // Egypte 
                    66 => ($provider_valeur != 51) ? 2072 : 2027, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 5043 : 5583, // Ethiopie
                    74 => ($provider_valeur != 51) ? 5044 : 5584, // France
                    79 => ($provider_valeur != 51) ? 73 : 101, // Gabon
                    81 => ($provider_valeur != 51) ? 6539 : 6611, // Gambie (New entry)
                    82 => ($provider_valeur != 51) ? 524 : 6683, // Allemagne
                    83 => ($provider_valeur != 51) ? 4750 : 4222, // Ghana
                    91 => ($provider_valeur != 51) ? 4846 : 4318, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 4894 : 4366, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 5048 : 5588, // Inde
                    106 => ($provider_valeur != 51) ? 515 : 6684, // Italie
                    107 => ($provider_valeur != 51) ? 79 : 107, // CIV (Côte d'Ivoire)
                    112 => ($provider_valeur != 51) ? 5051 : 5591, // Keneya
                    116 => ($provider_valeur != 51) ? 5052 : 5592, // Koweit
                    // UPdate Coutrie
                    119 => ($provider_valeur != 51) ? 5053 : 5593, // Liban (New entry)
                    122 => ($provider_valeur != 51) ? 76 : 104, // Liberia
                    133 => ($provider_valeur != 51) ? 4558 : 4031, // Mali
                    138 => ($provider_valeur != 51) ? 6537 : 6609, // Mauritanie (New entry)
                    146 => ($provider_valeur != 51) ? 5056 : 5596, // Maroc
                    152 => ($provider_valeur != 51) ? 5057 : 5597, // Hollande
                    157 => ($provider_valeur != 51) ? 4798 : 4270, // Niger
                    158 => ($provider_valeur != 51) ? 4990 : 4462, // Nigeria
                    173 => ($provider_valeur != 51) ? 5058 : 5598, // Portugal
                    175 => ($provider_valeur != 51) ? 5059 : 5599, // Quatar
                    180 => ($provider_valeur != 51) ? 5060 : 5600, // RWanda
                    188 => ($provider_valeur != 51) ? 4702 : 4174, // Senegal
                    191 => ($provider_valeur != 51) ? 4942 : 4414, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 5061 : 5601, // ESPAGNE
                    // New COuntrie
                    208 => ($provider_valeur != 51) ? 5062 : 5602, // Suisse
                    212 => ($provider_valeur != 51) ? 5063 : 5603, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4691 : 4163, // Togo
                    219 => ($provider_valeur != 51) ? 5064 : 5604, // Turquie
                    223 => ($provider_valeur != 51) ? 5065 : 5605, // Ouganda
                    225 => ($provider_valeur != 51) ? 5066 : 5606, // Dubai
                    226 => ($provider_valeur != 51) ? 5067 : 5607, // Royaume Unie
                    218 => ($provider_valeur != 51) ? 6538 : 6610, // Tunisia
                    179 => ($provider_valeur != 51) ? 6540 : 6612, // Ruissie
                    241 => ($provider_valeur != 51) ? 6541 : 6613, // Zambie
                    23 => ($provider_valeur != 51) ? 6542 : 6614, // Belgique
                ],
                //Is Gabon
                79 =>  [

                    1 => ($provider_valeur != 51) ? 5068 : 5536, // USA
                    2 => ($provider_valeur != 51) ? 6552 : 6624, // Algerie
                    8 => ($provider_valeur != 51) ? 5070 : 5538, // Angola
                    25 => ($provider_valeur != 51) ? 4520 : 3993, // Benin
                    36 => ($provider_valeur != 51) ? 4616 : 4088, // BF
                    37 => ($provider_valeur != 51) ? 5071 : 5539, // Burundi
                    39 => ($provider_valeur != 51) ? 5045 : 5585, // Cameroun
                    42 => ($provider_valeur != 51) ? 84 : 112, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 83 : 111, // Thacd
                    45 => ($provider_valeur != 51) ? 3108 : 3129, // China
                    50 => ($provider_valeur != 51) ? 80 : 108, // COngo Braza
                    57 => ($provider_valeur != 51) ? 81 : 109, // RDC
                    60 => ($provider_valeur != 51) ? 5076 : 5545, // Egypte
                    66 => ($provider_valeur != 51) ? 3104 : 3125, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 3115 : 3136, // Ethiopie
                    74 => ($provider_valeur != 51) ? 5080 : 5548, // France
                    80 => ($provider_valeur != 51) ? 6548 : 6620, // Gambie
                    82 => ($provider_valeur != 51) ? 5083 : 5551, // Allemagne
                    83 => ($provider_valeur != 51) ? 4760 : 4232, // Ghana
                    91 => ($provider_valeur != 51) ? 4856 : 4328, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 3095 : 3116, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 3114 : 3135, // Inde
                    106 => ($provider_valeur != 51) ? 5085 : 5553, // Italie
                    107 => ($provider_valeur != 51) ? 85 : 113, // CIV
                    112 => ($provider_valeur != 51) ? 3107 : 3128, // Keneya
                    116 => ($provider_valeur != 51) ? 3100 : 3121, // Koweit
                    // New
                    122 => ($provider_valeur != 51) ? 5089 : 5557, // Liban
                    122 => ($provider_valeur != 51) ? 82 : 110, // Liberia
                    133 => ($provider_valeur != 51) ? 4568 : 4041, // Mali
                    146 => ($provider_valeur != 51) ? 6546 : 6618, // Mauritanie
                    146 => ($provider_valeur != 51) ? 5092 : 5560, // Maroc
                    152 => ($provider_valeur != 51) ? 3113 : 3134, // Hollande
                    157 => ($provider_valeur != 51) ? 4808 : 4280, // Niger
                    158 => ($provider_valeur != 51) ? 3098 : 3119, // Nigeria
                    173 => ($provider_valeur != 51) ? 3111 : 3132, // Portugal
                    175 => ($provider_valeur != 51) ? 3102 : 3123, // Quatar
                    180 => ($provider_valeur != 51) ? 3097 : 3118, // Rwanda
                    188 => ($provider_valeur != 51) ? 4712 : 4184, // Senegal
                    191 => ($provider_valeur != 51) ? 3099 : 3120, // Sierria Leone
                    199 => ($provider_valeur != 51) ?  5097 : 5565, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 3109 : 3130, // Suisse
                    212 => ($provider_valeur != 51) ? 3101 : 3122, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4664 : 4136, // Togo
                    219 => ($provider_valeur != 51) ? 3112 : 3133, // Turquie
                    223 => ($provider_valeur != 51) ? 3096 : 3117, // Ouganda
                    225 => ($provider_valeur != 51) ? 3106 : 3127, // Dubai
                    226 => ($provider_valeur != 51) ? 5103 : 5571, // Royaume Unie
                    218 => ($provider_valeur != 51) ? 6547 : 6619, // Tunisia
                    179 => ($provider_valeur != 51) ? 6549 : 6621, // Russie
                    241 => ($provider_valeur != 51) ? 6550 : 6622, // Zambie
                    23 => ($provider_valeur != 51) ? 3110 : 3131, // Belgique

                ],
                // Is Congo 
                50 =>  [

                    // Update ID 
                    1 => ($provider_valeur != 51) ? 570 : 243, // USA
                    2 => ($provider_valeur != 51) ? 6561 : 6633, // Algerie
                    8 => ($provider_valeur != 51) ? 5106 : 5502, // Angola
                    25 => ($provider_valeur != 51) ? 4514 : 3987, // Benin
                    36 => ($provider_valeur != 51) ? 4610 : 4082, // BF
                    37 => ($provider_valeur != 51) ? 5107 : 5503, // Burundi
                    39 => ($provider_valeur != 51) ? 5039 : 5579, // Cameroun
                    42 => ($provider_valeur != 51) ? 89 : 117, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 88 : 116, // Thacd
                    45 => ($provider_valeur != 51) ?  5110 : 5506, // China
                    57 => ($provider_valeur != 51) ? 86 : 114, // RDC
                    60 => ($provider_valeur != 51) ? 6561 : 6634, // Egypte
                    66 => ($provider_valeur != 51) ? 2076 : 2031, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 5115 : 5511, // Ethiopie
                    // Update ID
                    74 => ($provider_valeur != 51) ? 469 : 357, // France
                    79 => ($provider_valeur != 51) ? 5075 : 5543, // Gabon
                    80 => ($provider_valeur != 51) ? 6557 : 6629, // Gambie
                    // Update ID
                    82 => ($provider_valeur != 51) ? 485 : 373, // Allemagne
                    83 => ($provider_valeur != 51) ? 4754 : 4226, // Ghana
                    91 => ($provider_valeur != 51) ? 4850 : 4322, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 4898 : 4370, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 5120 : 5516, // Inde
                    106 => ($provider_valeur != 51) ? 5121 : 5517, // Italie
                    107 => ($provider_valeur != 51) ? 90 : 118, // CIV
                    112 => ($provider_valeur != 51) ? 5123 : 5519, // Keneya
                    116 => ($provider_valeur != 51) ? 5124 : 5520, // Koweit
                    // New
                    122 => ($provider_valeur != 51) ? 5125 : 5521, // Liban
                    122 => ($provider_valeur != 51) ? 87 : 115, // Liberia
                    133 => ($provider_valeur != 51) ? 4562 : 4035, // Mali
                    146 => ($provider_valeur != 51) ? 6555 : 6627, // Mauritanie
                    // Update Id
                    146 => ($provider_valeur != 51) ? 5128 : 5524, // Maroc
                    152 => ($provider_valeur != 51) ? 5129 : 5525, // Hollande
                    157 => ($provider_valeur != 51) ? 4802 : 4274, // Niger
                    158 => ($provider_valeur != 51) ? 4994 : 4466, // Nigeria
                    173 => ($provider_valeur != 51) ? 5130 : 5526, // Portugal
                    175 => ($provider_valeur != 51) ? 5131 : 5527, // Quatar
                    180 => ($provider_valeur != 51) ? 5132 : 5528, // RWanda
                    188 => ($provider_valeur != 51) ? 4706 : 4178, // Senegal
                    191 => ($provider_valeur != 51) ? 4946 : 4418, // Sierria Leone
                    // Update Id
                    199 => ($provider_valeur != 51) ? 437 : 314, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 5134 : 5530, // Suisse
                    212 => ($provider_valeur != 51) ? 5135 : 5531, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4658 : 4130, // Togo
                    219 => ($provider_valeur != 51) ? 5136 : 5532, // Turquie
                    223 => ($provider_valeur != 51) ? 5137 : 5533, // Ouganda
                    225 => ($provider_valeur != 51) ? 5138 : 5534, // Dubai
                    // Update Id
                    226 => ($provider_valeur != 51) ? 501 : 389, // Royaume Unie
                    218 => ($provider_valeur != 51) ? 6556 : 6628, // Tunisia
                    179 => ($provider_valeur != 51) ? 6558 : 6630, // Ruissie
                    241 => ($provider_valeur != 51) ? 6559 : 6631, // Zambie
                    23 => ($provider_valeur != 51) ? 6560 : 6632, // Belgique

                ],

                //Is RDC
                57 =>  [

                    1 => ($provider_valeur != 51) ? 5140 : 5464, // USA
                    // New Countrie
                    8 => ($provider_valeur != 51) ? 5141 : 5465, // Angola
                    2 => ($provider_valeur != 51) ? 6570 : 6642, // Algerie
                    23 => ($provider_valeur != 51) ? 6569 : 6641, // Belgique
                    25 => ($provider_valeur != 51) ? 4515 : 3988, // Benin
                    36 => ($provider_valeur != 51) ? 4611 : 4083, // BF
                    39 => ($provider_valeur != 51) ? 5040 : 5580, // Cameroun
                    42 => ($provider_valeur != 51) ? 93 : 121, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 92 : 120, // Thacd
                    45 => ($provider_valeur != 51) ? 5146 : 5470, // China
                    50 => ($provider_valeur != 51) ? 5112 : 5508, // COngo Braza
                    66 => ($provider_valeur != 51) ? 2077 : 2032, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 1402 : 1447, // Ethiopie
                    74 => ($provider_valeur != 51) ? 5152 : 5476, // France
                    79 => ($provider_valeur != 51) ? 5075 : 5544, // Gabon
                    82 => ($provider_valeur != 51) ? 5155 : 5479, // Allemagne
                    83 => ($provider_valeur != 51) ? 4755 : 4227, // Ghana
                    91 => ($provider_valeur != 51) ? 4851 : 4323, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 4899 : 4371, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 5156 : 5480, // Inde
                    106 => ($provider_valeur != 51) ? 5157 : 5481, // Italie
                    107 => ($provider_valeur != 51) ? 94 : 122, // CIV
                    112 => ($provider_valeur != 51) ? 5159 : 5483, // Keneya
                    116 => ($provider_valeur != 51) ? 5160 : 5484, // Koweit
                    122 => ($provider_valeur != 51) ? 91 : 119, // Liberia
                    133 => ($provider_valeur != 51) ? 4563 : 4036, // Mali
                    146 => ($provider_valeur != 51) ? 5164 : 5488, // Maroc
                    152 => ($provider_valeur != 51) ? 5165 : 5489, // Hollande
                    157 => ($provider_valeur != 51) ? 579 : 223, // Niger
                    158 => ($provider_valeur != 51) ? 4995 : 4467, // Nigeria
                    173 => ($provider_valeur != 51) ? 5166 : 5490, // Portugal
                    175 => ($provider_valeur != 51) ? 5167 : 5491, // Quatar
                    179 => ($provider_valeur != 51) ? 6567 : 6639, // Russie
                    180 => ($provider_valeur != 51) ? 5168 : 5492, // RWanda
                    188 => ($provider_valeur != 51) ? 4707 : 4179, // Senegal
                    191 => ($provider_valeur != 51) ? 4947 : 4419, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 5169 : 5493, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 5170 : 5494, // Suisse
                    212 => ($provider_valeur != 51) ? 5171 : 5495, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4659 : 4131, // Togo
                    218 => ($provider_valeur != 51) ? 6565 : 6637, // Tunisia
                    219 => ($provider_valeur != 51) ? 5172 : 5496, // Turquie
                    223 => ($provider_valeur != 51) ? 5173 : 5497, // Ouganda
                    225 => ($provider_valeur != 51) ? 5174 : 5498, // Dubai
                    226 => ($provider_valeur != 51) ? 5175 : 5499, // Royaume Unie
                    241 => ($provider_valeur != 51) ? 6568 : 6640, // Zambie
                    80 => ($provider_valeur != 51) ? 6566 : 6638, // Gambie
                    37 => ($provider_valeur != 51) ? 6572 : 6644, // Burundi
                    64 => ($provider_valeur != 51) ? 6580 : 6571, // Egypte

                ],
                // Is Liberia
                122 =>  [

                    1 => ($provider_valeur != 51) ? 5176 : 5428, // USA
                    2 => ($provider_valeur != 51) ? 6579 : 6651, // Algerie
                    // New Countrie
                    8 => ($provider_valeur != 51) ? 5177 : 5429, // Angola
                    23 => ($provider_valeur != 51) ? 6578 : 6650, // Belgique
                    25 => ($provider_valeur != 51) ? 4532 : 4005, // Benin
                    36 => ($provider_valeur != 51) ? 4628 : 4101, // BF
                    39 => ($provider_valeur != 51) ? 5054 : 5594, // Cameroun
                    42 => ($provider_valeur != 51) ? 96 : 124, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 95 : 123, // Thacd
                    45 => ($provider_valeur != 51) ? 5182 : 5434, // China
                    50 => ($provider_valeur != 51) ? 5126 : 5522, // COngo Braza
                    66 => ($provider_valeur != 51) ? 2091 : 2046, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 5187 : 5439, // Ethiopie
                    74 => ($provider_valeur != 51) ?  5188 : 5440, // France
                    79 => ($provider_valeur != 51) ? 5090 : 5558, // Gabon
                    80 => ($provider_valeur != 51) ? 6575 : 6647, // Gambie
                    82 => ($provider_valeur != 51) ? 5191 : 5443, // Allemagne
                    83 => ($provider_valeur != 51) ? 168 : 217, // Ghana
                    91 => ($provider_valeur != 51) ? 4868 : 4340, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 4916 : 4388, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 5192 : 5444, // Inde
                    106 => ($provider_valeur != 51) ? 5193 : 5445, // Italie
                    107 => ($provider_valeur != 51) ? 97 : 125, // CIV
                    112 => ($provider_valeur != 51) ? 5195 : 5447, // Keneya
                    116 => ($provider_valeur != 51) ? 5196 : 5448, // Koweit
                    57 => ($provider_valeur != 51) ? 5162 : 5486, // RDC
                    133 => ($provider_valeur != 51) ? 4820 : 4292, // Niger
                    146 => ($provider_valeur != 51) ? 5200 : 5452, // Maroc
                    152 => ($provider_valeur != 51) ? 5201 : 5453, // Hollande
                    158 => ($provider_valeur != 51) ? 5012 : 4484, // Nigeria
                    173 => ($provider_valeur != 51) ? 5202 : 5454, // Portugal
                    175 => ($provider_valeur != 51) ? 5203 : 5455, // Quatar
                    179 => ($provider_valeur != 51) ? 6576 : 6648, // Ruissie
                    180 => ($provider_valeur != 51) ? 5204 : 5456, // RWanda
                    188 => ($provider_valeur != 51) ? 4724 : 4194, // Senegal
                    191 => ($provider_valeur != 51) ? 4964 : 4436, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 5205 : 5457, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 5206 : 5458, // Suisse
                    212 => ($provider_valeur != 51) ? 5207 : 5459, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4676 : 4148, // TOgo
                    218 => ($provider_valeur != 51) ? 6574 : 6646, // Tunisia
                    219 => ($provider_valeur != 51) ? 5208 : 5460, // Turquie
                    223 => ($provider_valeur != 51) ? 5209 : 5461, // Ouganda
                    225 => ($provider_valeur != 51) ? 5210 : 5462, // Dubai
                    226 => ($provider_valeur != 51) ? 5211 : 5463, // Royaume Unie
                    241 => ($provider_valeur != 51) ? 1596 : 1641, // Zambie
                    37 => ($provider_valeur != 51) ? 6581 : 6653, // Burundi
                    64 => ($provider_valeur != 51) ? 6580 : 6652, // Egypte

                ],

                // Is Liban
                120 =>  [

                    1 => ($provider_valeur != 51) ? 238 : 259, // USA
                    8 => ($provider_valeur != 51) ? 1686 : 1731, // Angola
                    23 => ($provider_valeur != 51) ? 2226 : 2271, // Belgique
                    25 => ($provider_valeur != 51) ? 4531 : 4004, // Benin
                    36 => ($provider_valeur != 51) ? 4627 : 4099, // BF
                    39 => ($provider_valeur != 51) ? 157 : 206, // Cameroun
                    42 => ($provider_valeur != 51) ? 96 : 124, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 95 : 123, // Thacd
                    45 => ($provider_valeur != 51) ? 2856 : 2901, // China
                    50 => ($provider_valeur != 51) ? 159 : 208, // COngo Braza
                    57 => ($provider_valeur != 51) ? 160 : 209, // RDC
                    66 => ($provider_valeur != 51) ? 2091 : 2046, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 1416 : 1461, // Ethiopie
                    74 => ($provider_valeur != 51) ? 368 : 480, // France
                    79 => ($provider_valeur != 51) ? 158 : 207, // Gabon
                    82 => ($provider_valeur != 51) ? 496 : 384, // Allemagne
                    83 => ($provider_valeur != 51) ? 168 : 217, // Ghana
                    91 => ($provider_valeur != 51) ? 175 : 231, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 1821 : 1866, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 2946 : 2991, // Inde
                    106 => ($provider_valeur != 51) ? 416 : 528, // Italie
                    107 => ($provider_valeur != 51) ? 97 : 125, // CIV
                    112 => ($provider_valeur != 51) ? 1506 : 1551, // Keneya
                    116 => ($provider_valeur != 51) ? 2630 : 2586, // Koweit
                    122 => ($provider_valeur != 51) ? 91 : 119, // Liberia
                    133 => ($provider_valeur != 51) ? 580 : 224, // Niger
                    146 => ($provider_valeur != 51) ? 336 : 289, // Maroc
                    152 => ($provider_valeur != 51) ? 2496 : 2541, // Hollande
                    158 => ($provider_valeur != 51) ? 1956 : 2001, // Nigeria
                    173 => ($provider_valeur != 51) ? 2316 : 2361, // Portugal
                    175 => ($provider_valeur != 51) ? 2676 : 2721, // Quatar
                    179 => ($provider_valeur != 51) ? 544 : 432, // Russie
                    180 => ($provider_valeur != 51) ? 833 : 788, // RWanda
                    188 => ($provider_valeur != 51) ? 147 : 4052, // Mali
                    191 => ($provider_valeur != 51) ? 1866 : 1911, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 448 : 324, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 2136 : 2181, // Suisse
                    212 => ($provider_valeur != 51) ? 1326 : 1371, // Tanzanie
                    214 => ($provider_valeur != 51) ? 154 : 203, // TOgo
                    218 => ($provider_valeur != 51) ? 275 : 330, // Tunisia
                    219 => ($provider_valeur != 51) ? 2406 : 2451, // Turquie
                    223 => ($provider_valeur != 51) ? 699 : 727, // Ouganda
                    225 => ($provider_valeur != 51) ? 2766 : 2811, // Dubai
                    226 => ($provider_valeur != 51) ? 512 : 400, // Royaume Unie
                    241 => ($provider_valeur != 51) ? 1596 : 1641, // Zambie

                ],
                //Is  Tchad
                43 =>  [
                    1 => ($provider_valeur != 51) ? 5212 : 5392, // USA
                    2 => ($provider_valeur != 51) ? 6588 : 6691, // Algerie
                    // New Update
                    8 => ($provider_valeur != 51) ? 5213 : 5394, // Angola
                    23 => ($provider_valeur != 51) ? 6587 : 6690, // Belgique
                    25 => ($provider_valeur != 51) ? 4512 : 3985, // Benin
                    36 => ($provider_valeur != 51) ? 4608 : 4080, // BF
                    39 => ($provider_valeur != 51) ? 5037 : 5577, // Cameroun
                    42 => ($provider_valeur != 51) ? 98 : 126, // Afrique centrale
                    45 => ($provider_valeur != 51) ? 5218 : 5398, // China
                    50 => ($provider_valeur != 51) ? 5109 : 5505, // COngo Braza
                    57 => ($provider_valeur != 51) ? 5145 : 5469, // RDC
                    66 => ($provider_valeur != 51) ? 2074 : 2029, // Guinnée Equatoriale
                    69 => ($provider_valeur != 51) ? 5223 : 5403, // Ethiopie
                    74 => ($provider_valeur != 51) ? 5224 : 5404, // France
                    79 => ($provider_valeur != 51) ? 5073 : 5541, // Gabon
                    79 => ($provider_valeur != 51) ? 6584 : 6687, // Gambie
                    82 => ($provider_valeur != 51) ? 5227 : 5407, // Allemagne
                    91 => ($provider_valeur != 51) ? 4848 : 4320, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 4896 : 4368, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 5228 : 5408, // Inde
                    106 => ($provider_valeur != 51) ? 5229 : 5409, // Italie
                    107 => ($provider_valeur != 51) ? 99 : 127, // CIV
                    112 => ($provider_valeur != 51) ? 5231 : 5411, // Keneya
                    116 => ($provider_valeur != 51) ? 5232 : 5412, // Koweit
                    122 => ($provider_valeur != 51) ? 5181 : 5433, // Liberia
                    133 => ($provider_valeur != 51) ? 4560 : 4033, // Mali
                    // Update Id
                    133 => ($provider_valeur != 51) ? 5233 : 5413, // LIban
                    133 => ($provider_valeur != 51) ? 6582 : 6685, // Mauritanie
                    146 => ($provider_valeur != 51) ? 5236 : 5416, // Maroc
                    152 => ($provider_valeur != 51) ? 5237 : 5417, // Hollande
                    157 => ($provider_valeur != 51) ? 4800 : 4272, // Niger
                    158 => ($provider_valeur != 51) ? 4992 : 4464, // Nigeria
                    173 => ($provider_valeur != 51) ? 5238 : 5418, // Portugal
                    175 => ($provider_valeur != 51) ? 5239 : 5419, // Quatar
                    179 => ($provider_valeur != 51) ? 6585 : 6688, // Russie
                    180 => ($provider_valeur != 51) ? 5240 : 5420, // RWanda
                    188 => ($provider_valeur != 51) ? 4704 : 4176, // Senegal
                    191 => ($provider_valeur != 51) ? 1866 : 1977, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 5241 : 5421, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 5242 : 5422, // Suisse
                    212 => ($provider_valeur != 51) ? 5243 : 5423, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4752 : 4224, // Ghana
                    218 => ($provider_valeur != 51) ? 6583 : 6686, // Tunisia
                    219 => ($provider_valeur != 51) ? 5244 : 5424, // Turquie
                    223 => ($provider_valeur != 51) ? 5245 : 5425, // Ouganda
                    225 => ($provider_valeur != 51) ? 5246 : 5426, // Dubai
                    226 => ($provider_valeur != 51) ? 5247 : 5427, // Royaume Unie
                    241 => ($provider_valeur != 51) ? 6586 : 6689, // Zambie
                    37 => ($provider_valeur != 51) ? 6693 : 6590, // Burundi
                    64 => ($provider_valeur != 51) ? 6589 : 6692, // Egypte
                ],
                // Is Centre Afrique
                42 =>  [

                    1 => ($provider_valeur != 51) ?  5248 : 5356, // USA
                    2 => ($provider_valeur != 51) ? 6588 : 6606, // Algerie
                    // Update
                    8 => ($provider_valeur != 51) ? 5249 : 5357, // Angola
                    25 => ($provider_valeur != 51) ? 4511 : 3984, // Benin
                    36 => ($provider_valeur != 51) ? 4607 : 4079, // BF
                    39 => ($provider_valeur != 51) ? 5036 : 5576, // Cameroun
                    42 => ($provider_valeur != 51) ? 93 : 121, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 5216 : 5396, // Thacd
                    45 => ($provider_valeur != 51) ? 5254 : 5362, // China
                    50 => ($provider_valeur != 51) ? 5108 : 5504, // COngo Braza
                    57 => ($provider_valeur != 51) ? 5144 : 5468, // RDC
                    66 => ($provider_valeur != 51) ? 5258 : 5366, // Guinnée Equatoriale
                    // Update Id
                    66 => ($provider_valeur != 51) ? 6607 : 6679, // Egypte
                    69 => ($provider_valeur != 51) ? 5259 : 5367, // Ethiopie
                    74 => ($provider_valeur != 51) ? 5260 : 5368, // France
                    79 => ($provider_valeur != 51) ? 5072 : 5540, // Gabon
                    74 => ($provider_valeur != 51) ? 5261 : 5369, // Gambie
                    82 => ($provider_valeur != 51) ? 5263 : 5371, // Allemagne
                    83 => ($provider_valeur != 51) ? 4751 : 4223, // Ghana
                    91 => ($provider_valeur != 51) ? 4847 : 4319, // Guinnée Conakry
                    92 => ($provider_valeur != 51) ? 4895 : 4367, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 5264 : 5372, // Inde
                    106 => ($provider_valeur != 51) ? 5265 : 5373, // Italie
                    107 => ($provider_valeur != 51) ? 100 : 128, // CIV
                    112 => ($provider_valeur != 51) ? 5267 : 5375, // Keneya
                    116 => ($provider_valeur != 51) ? 5268 : 5376, // Koweit
                    122 => ($provider_valeur != 51) ? 5180 : 5432, // Liberia
                    // Update Id
                    122 => ($provider_valeur != 51) ?  5269 : 5377, // Liban
                    122 => ($provider_valeur != 51) ?  6600 : 6672, // Mauritanie
                    133 => ($provider_valeur != 51) ? 4559 : 4032, // Mali
                    146 => ($provider_valeur != 51) ? 5272 : 5380, // Maroc
                    // UPdate ID
                    152 => ($provider_valeur != 51) ? 5273 : 5381, // Hollande
                    157 => ($provider_valeur != 51) ? 4799 : 4271, // Niger
                    158 => ($provider_valeur != 51) ? 4991 : 4463, // Nigeria
                    173 => ($provider_valeur != 51) ? 5274 : 5382, // Portugal
                    175 => ($provider_valeur != 51) ? 5275 : 5383, // Quatar
                    180 => ($provider_valeur != 51) ? 5276 : 5384, // RWanda
                    188 => ($provider_valeur != 51) ? 4703 : 4175, // Senegal
                    191 => ($provider_valeur != 51) ? 1848 : 1893, // Sierria Leone
                    199 => ($provider_valeur != 51) ? 5277 : 5385, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 5278 : 5386, // Suisse
                    212 => ($provider_valeur != 51) ? 5279 : 5387, // Tanzanie
                    214 => ($provider_valeur != 51) ? 4655 : 4127, // Togo
                    219 => ($provider_valeur != 51) ? 5280 : 5388, // Turquie
                    223 => ($provider_valeur != 51) ? 5281 : 5389, // Ouganda
                    225 => ($provider_valeur != 51) ? 5282 : 5390, // Dubai
                    226 => ($provider_valeur != 51) ? 5283 : 5391, // Royaume Unie
                    218 => ($provider_valeur != 51) ? 6601 : 6673, // Tunisia
                    179 => ($provider_valeur != 51) ? 6603 : 6675, // Ruissie
                    241 => ($provider_valeur != 51) ? 1578 : 1623, // Zambie
                    23 => ($provider_valeur != 51) ? 2208 : 2253, // Belgique
                    37 => ($provider_valeur != 51) ? 6608 : 6680, // Burundi
                    64 => ($provider_valeur != 51) ? 6607 : 6679, // Egypte
                ],

                // IS Burundi
                37 =>  [

                    1 => ($provider_valeur != 51) ? 261 : 240, // USA
                    8 => ($provider_valeur != 51) ? 1668 : 1713, // Angola
                    25 => ($provider_valeur != 51) ? 4509 : 3984, // Benin
                    36 => ($provider_valeur != 51) ? 6358 : 6357, // BF
                    157 => ($provider_valeur != 51) ? 582 : 226, // Niger
                    133 => ($provider_valeur != 51) ? 4557 : 4030, // Mali
                    107 => ($provider_valeur != 51) ? 100 : 128, // CIV
                    214 => ($provider_valeur != 51) ? 156 : 205, // Togo
                    218 => ($provider_valeur != 51) ? 277 : 332, // Tunisia
                    146 => ($provider_valeur != 51) ? 291 : 338, // Maroc
                    199 => ($provider_valeur != 51) ? 450 : 326, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 482 : 370, // France
                    179 => ($provider_valeur != 51) ? 546 : 434, // Ruissie
                    226 => ($provider_valeur != 51) ? 514 : 402, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 530 : 418, // Italie
                    82 => ($provider_valeur != 51) ? 498 : 386, // Allemagne
                    39 => ($provider_valeur != 51) ? 78 : 106, // Cameroun
                    79 => ($provider_valeur != 51) ? 5071 : 5539, // Gabon
                    50 => ($provider_valeur != 51) ? 5107 : 5503, // COngo Braza
                    57 => ($provider_valeur != 51) ? 93 : 121, // RDC
                    122 => ($provider_valeur != 51) ? 96 : 124, // Liberia
                    43 => ($provider_valeur != 51) ? 98 : 126, // Thacd
                    45 => ($provider_valeur != 51) ? 2838 : 2883, // China
                    92 => ($provider_valeur != 51) ? 1758 : 1803, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 681 : 721, // Ouganda
                    158 => ($provider_valeur != 51) ? 1938 : 1983, // Nigeria
                    191 => ($provider_valeur != 51) ? 1848 : 1893, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2612 : 2568, // Koweit
                    212 => ($provider_valeur != 51) ? 1308 : 1353, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2658 : 2703, // Quatar
                    241 => ($provider_valeur != 51) ? 1578 : 1623, // Zambie
                    100 => ($provider_valeur != 51) ? 2928 : 2973, // Inde
                    69 => ($provider_valeur != 51) ? 1398 : 1443, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2073 : 2028, // Guinnée Equatoriale
                    225 => ($provider_valeur != 51) ? 2748 : 2793, // Dubai
                    112 => ($provider_valeur != 51) ? 1488 : 1533, // Keneya
                    208 => ($provider_valeur != 51) ? 2118 : 2163, // Suisse
                    23 => ($provider_valeur != 51) ? 2208 : 2253, // Belgique
                    173 => ($provider_valeur != 51) ? 2298 : 2343, // Portugal
                    219 => ($provider_valeur != 51) ? 2405 : 2450, // Turquie
                    152 => ($provider_valeur != 51) ? 2388 : 2433, // Hollande
                    180 => ($provider_valeur != 51) ? 815 : 770, // RWanda
                    188 => ($provider_valeur != 51) ? 163 : 212, // Senegal
                    83 => ($provider_valeur != 51) ? 4749 : 4221, // Ghana

                ],

                //Is Guinnée Equatoriale 
                66 =>  [

                    1 => ($provider_valeur == 51) ? 2020 : null, // USA
                    8 => ($provider_valeur != 51) ? 6597 : 6669, // Algerie
                    8 => ($provider_valeur != 51) ? 3544 : 3564, // Angola
                    25 => ($provider_valeur != 51) ? 2069 : 2024, // Benin
                    36 => ($provider_valeur != 51) ? 2070 : 2025, // BF
                    8 => ($provider_valeur != 51) ? 6599 : 6671, // Burundi
                    39 => ($provider_valeur != 51) ? 1847 : 1892, // Cameroun
                    42 => ($provider_valeur != 51) ? 2073 : 2028, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 2074 : 2029, // Thacd
                    45 => ($provider_valeur != 51) ? 3547 : 3567, // China
                    50 => ($provider_valeur != 51) ? 2076 : 2031, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2077 : 2032, // RDC
                    66 => ($provider_valeur != 51) ?  6598 : 6670, // Egypte
                    69 => ($provider_valeur != 51) ? 3554 : 3574, // Ethiopie
                    74 => ($provider_valeur == 51) ? 2034 : null, // France
                    79 => ($provider_valeur != 51) ? 5297 : 5333, // Gabon
                    79 => ($provider_valeur != 51) ?  6593 : 6665, // Gambie
                    82 => ($provider_valeur == 51) ? 2037 : null, // Allemagne
                    83 => ($provider_valeur != 51) ? 2083 : 2038, // Ghana
                    91 => ($provider_valeur != 51) ? 2084 : 2039, // Guinne conakry
                    92 => ($provider_valeur != 51) ? 3535 : 3555, // Guinnée Buisso
                    100 => ($provider_valeur != 51) ? 3553 : 3573, // Inde
                    106 => ($provider_valeur == 51) ? 2042 : null, // Italie
                    107 => ($provider_valeur != 51) ? 642 : 662, // CIV
                    112 => ($provider_valeur != 51) ? 3546 : 3566, // Keneya
                    116 => ($provider_valeur != 51) ? 3540 : 3560, // Koweit
                    // Liban (Missing in provided data)
                    122 => ($provider_valeur != 51) ? 5303 : 5339, // Laban
                    122 => ($provider_valeur != 51) ? 2091 : 2046, // Liberia
                    133 => ($provider_valeur != 51) ? 4565 : 4038, // Mali
                    146 => ($provider_valeur == 51) ? 6591 : 6663, // Mauritanie
                    146 => ($provider_valeur == 51) ? 2049 : null, // Maroc
                    152 => ($provider_valeur != 51) ? 3552 : 3572, // Hollande
                    157 => ($provider_valeur != 51) ? 2096 : 2051, // Niger
                    158 => ($provider_valeur != 51) ? 3538 : 3558, // Nigeria
                    173 => ($provider_valeur != 51) ? 3550 : 3570, // Portugal
                    175 => ($provider_valeur != 51) ? 3542 : 3562, // Quatar
                    180 => ($provider_valeur != 51) ? 3537 : 3557, // Rwanda 
                    188 => ($provider_valeur != 51) ? 4709 : 4181, // Senegal
                    191 => ($provider_valeur != 51) ? 3539 : 3559, // Sierria Leone
                    199 => ($provider_valeur == 51) ? 2058 : null, // ESPAGNE
                    208 => ($provider_valeur != 51) ? 3548 : 3568, // Suisse
                    212 => ($provider_valeur != 51) ? 3541 : 3561, // Tanzanie
                    214 => ($provider_valeur != 51) ? 2106 : 2061, // Togo
                    219 => ($provider_valeur != 51) ? 3551 : 3571, // Turquie
                    223 => ($provider_valeur != 51) ? 3536 : 3556, // Ouganda 
                    225 => ($provider_valeur != 51) ? 3545 : 3565, // Dubai
                    226 => ($provider_valeur == 51) ? 2064 : null, // Royaume Unie
                    218 => ($provider_valeur == 51) ? 6664 : 6592, // Tunisia
                    179 => ($provider_valeur == 51) ? 6594 : 6666, // Ruissie
                    241 => ($provider_valeur != 51) ? 3543 : 3563, // Zambie
                    23 => ($provider_valeur != 51) ?  3549 : 3569, // Belgique
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif (robus()->country->name_groupe == 'CIV') {


            $countriesLevels = [
                // Is CIV
                107 => [

                    36 => ($provider_valeur != 51) ? 4624 : 4096, // BF
                    25 => ($provider_valeur != 51) ? 4528 : 4001, // BENIN
                    133 => ($provider_valeur != 51) ? 4576 : 4049, // Mali
                    214 => ($provider_valeur != 51) ? 4672 : 4144, // Togo
                    188 => ($provider_valeur != 51) ? 4720 : 4192, // Senegal
                    83 => ($provider_valeur != 51) ? 4768 : 4240, // Ghana
                    157 => ($provider_valeur != 51) ? 4816 : 4288, // Niger
                    91 => ($provider_valeur != 51) ? 4864 : 4336, // Guinne conakry
                    1 => ($provider_valeur != 51) ? 6695 : 6694, // USA
                    218 => ($provider_valeur != 51) ? 6697 : 6696, // Tunisia
                    146 => ($provider_valeur != 51) ? 6698 : 6699, // Maroc
                    199 => ($provider_valeur != 51) ? 6700 : 6325, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 6701 : 6702, // France
                    179 => ($provider_valeur != 51) ? 6703 : 6704, // Ruissie
                    226 => ($provider_valeur != 51) ? 6705 : 6706, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 6707 : 6708, // Italie
                    82 => ($provider_valeur != 51) ? 6780 : 6709, // Allemagne
                    39 => ($provider_valeur != 51) ? 5050 : 5590, // Cameroun
                    79 => ($provider_valeur != 51) ? 5086 : 5554, // Gabon
                    50 => ($provider_valeur != 51) ?  5122 : 5518, // COngo Braza
                    57 => ($provider_valeur != 51) ? 5158 : 5482, // RDC
                    122 => ($provider_valeur != 51) ? 5194 : 5446, // Liberia
                    43 => ($provider_valeur != 51) ? 5230 : 5410, // Thacd
                    42 => ($provider_valeur != 51) ? 5266 : 5374, // Afrique centrale
                    92 => ($provider_valeur != 51) ? 4912 : 4384, // Guinnée Buisso voir
                    223 => ($provider_valeur != 51) ? 6712 : 6711, // Ouganda 
                    223 => ($provider_valeur != 51) ? 6714 : 6713, // Rwanda 
                    158 => ($provider_valeur != 51) ? 5008 : 4480, // Nigeria
                    191 => ($provider_valeur != 51) ? 4960 : 4432, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 638 : 668, // Koweit
                    212 => ($provider_valeur != 51) ? 639 : 657, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3010 : 669, // Quatar voirs
                    241 => ($provider_valeur != 51) ? 637 : 660, // Zambie
                    100 => ($provider_valeur != 51) ? 640 : 672, // Inde
                    69 => ($provider_valeur != 51) ? 641 : 658, // Ethiopie
                    66 => ($provider_valeur != 51) ? 5302 : 5338, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 643 : 661, // Angola
                    225 => ($provider_valeur != 51) ? 644 : 670, // Dubai
                    112 => ($provider_valeur != 51) ? 645 : 659, // Keneya
                    45 => ($provider_valeur != 51) ? 646 : 671, // China
                    208 => ($provider_valeur != 51) ? 647 : 683, // Suisse voir
                    23 => ($provider_valeur != 51) ? 664 : 648, // Belgique
                    173 => ($provider_valeur != 51) ? 665 : 649, // Portugal
                    219 => ($provider_valeur != 51) ? 666 : 650, // Turquie
                    152 => ($provider_valeur != 51) ? 667 : 651, // Hollande
                    1 => ($provider_valeur != 51) ? 6399 : 6408, // Algerie
                    133 => ($provider_valeur != 51) ? 6715 : 6716, // Mauritanie
                    37 => ($provider_valeur != 51) ? 6717 : 6718, // Burundi

                ]
            ];
        } elseif (robus()->country->name_groupe == 'URP') {

            $countriesLevels = [
                // Is Espagne
                199 => [

                    1 => ($provider_valeur == 51) ? 6065 : 5666, // USA
                    8 => ($provider_valeur != 51) ? 6731 : 6723, // Algerie
                    8 => ($provider_valeur != 51) ? 6734 : 6726, // Angola
                    25 => ($provider_valeur != 51) ? 435 : 312, // Bénin
                    36 => ($provider_valeur != 51) ? 436 : 313, // Burkina Faso (BF)
                    8 => ($provider_valeur != 51) ? 6733 : 6725, // Burundi
                    39 => ($provider_valeur != 51) ? 444 : 321, // Cameroun
                    42 => ($provider_valeur != 51) ? 450 : 326, // Afrique centrale
                    43 => ($provider_valeur != 51) ? 448 : 325, // Tchad
                    45 => ($provider_valeur != 51) ? 2858 : 2903, // Chine
                    50 => ($provider_valeur != 51) ? 5133 : 5529, // Congo Brazzaville
                    57 => ($provider_valeur != 51) ? 447 : 323, // RDC
                    8 => ($provider_valeur != 51) ? 6732 : 6724, // Égypte
                    66 => ($provider_valeur != 51) ? 5313 : 5349, // Guinée Équatoriale
                    69 => ($provider_valeur != 51) ? 5671 : 6070, // Éthiopie
                    79 => ($provider_valeur != 51) ? 445 : 322, // Gabon
                    8 => ($provider_valeur != 51) ? 6728 : 6720, // Gambie
                    83 => ($provider_valeur != 51) ? 440 : 317, // Ghana
                    91 => ($provider_valeur != 51) ? 442 : 319, // Guinée Conakry
                    92 => ($provider_valeur != 51) ? 4928 : 4400, // Guinée-Bissau
                    157 => ($provider_valeur != 51) ? 441 : 318, // Niger
                    158 => ($provider_valeur != 51) ? 5024 : 4496, // Nigeria
                    133 => ($provider_valeur != 51) ? 437 : 314, // Mali
                    214 => ($provider_valeur != 51) ? 438 : 315, // Togo
                    188 => ($provider_valeur != 51) ? 439 : 316, // Sénégal
                    191 => ($provider_valeur != 51) ? 4976 : 4448, // Sierra Leone
                    // (Ajoutez ici le code pour le Liban)
                    116 => ($provider_valeur != 51) ? 2642 : 2598, // Liban
                    122 => ($provider_valeur != 51) ? 448 : 324, // Liberia
                    74 => ($provider_valeur == 51) ? 584 : 6320, // France
                    82 => ($provider_valeur == 51) ? 586 : null, // Allemagne
                    100 => ($provider_valeur != 51) ? 2958 : 6323, // Inde
                    106 => ($provider_valeur == 51) ? 589 : null, // Italie
                    107 => ($provider_valeur != 51) ? 443 : 320, // Côte d'Ivoire (CIV)
                    116 => ($provider_valeur != 51) ? 5675 : 6074, // Koweït
                    112 => ($provider_valeur != 51) ? 5674 : 6073, // Kenya
                    116 => ($provider_valeur != 51) ? 6727 : 6719, // Mauritanie
                    146 => ($provider_valeur == 51) ? 5678 : 6077, // Maroc
                    152 => ($provider_valeur != 51) ? 6738 : 6737, // Hollande
                    173 => ($provider_valeur != 51) ? 2328 : 2373, // Portugal
                    175 => ($provider_valeur != 51) ? 5679 : 6078, // Qatar
                    223 => ($provider_valeur != 51) ? 5680 : 6079, // Rwanda
                    208 => ($provider_valeur != 51) ? 2148 : 2193, // Suisse
                    212 => ($provider_valeur != 51) ? 5681 : 6080, // Tanzanie
                    219 => ($provider_valeur != 51) ? 6736 : 6735, // Turquie
                    223 => ($provider_valeur != 51) ? 5682 : 6081, // Ouganda
                    225 => ($provider_valeur != 51) ? 5683 : 6082, // Dubaï
                    226 => ($provider_valeur == 51) ? 588 : null, // Royaume-Uni
                    218 => ($provider_valeur == 51) ? 6084 : null, // Tunisie
                    179 => ($provider_valeur == 51) ? 587 : null, // Ruissie
                    241 => ($provider_valeur != 51) ? 6729 : 6721, // Zambie
                    23 => ($provider_valeur != 51) ? 6730 :  6722, // Belgique


                ],
                // Is France
                74 =>  [

                    25 => ($provider_valeur != 51) ? 467 : 355, // Benin
                    36 => ($provider_valeur != 51) ? 468 : 356, // BF
                    133 => ($provider_valeur != 51) ? 469 : 357, // Mali
                    214 => ($provider_valeur != 51) ? 470 : 358, // Togo
                    188 => ($provider_valeur != 51) ? 471 : 359, // Senegal
                    83 => ($provider_valeur != 51) ? 472 : 360, // Ghana
                    157 => ($provider_valeur != 51) ? 473 : 361, // Niger
                    91 => ($provider_valeur != 51) ? 474 : 362, // Guinne conakry
                    199 => ($provider_valeur == 51) ? 6321 : null, // ESPAGNE
                    39 => ($provider_valeur != 51) ? 476 : 364, // Cameroun
                    79 => ($provider_valeur != 51) ? 477 : 365, // Gabon
                    50 => ($provider_valeur != 51) ? 5116 : 5512, // COngo Braza
                    57 => ($provider_valeur != 51) ? 479 : 367, // RDC
                    122 => ($provider_valeur != 51) ? 368 : 480, // Liberia
                    43 => ($provider_valeur != 51) ? 481 : 369, // Thacd
                    42 => ($provider_valeur != 51) ? 482 : 370, // Afrique centrale
                    158 => ($provider_valeur != 51) ? 4999 : 4471, // Nigeria
                    191 => ($provider_valeur != 51) ? 4951 : 4423, // Sierria Leone
                    92 => ($provider_valeur != 51) ? 4903 : 4375, // Guinnée Buisso 
                    107 => ($provider_valeur != 51) ? 5614 : 5643, // CIV
                    1 => ($provider_valeur == 51) ? 6044 : null, // USA
                    218 => ($provider_valeur == 51) ? 6063 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 6056 : null, // Maroc
                    179 => ($provider_valeur == 51) ? 6326 : null, // Russie
                    226 => ($provider_valeur == 51) ? 592 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 593 : null, // Italie
                    82 => ($provider_valeur == 51) ? 591 : null, // Allemagne
                    223 => ($provider_valeur != 51) ? 745 : 6328, // Ouganda 
                    223 => ($provider_valeur != 51) ? 634 : 6058, // Rwanda 
                    116 => ($provider_valeur != 51) ? 2618 : 2574, // Koweit
                    212 => ($provider_valeur != 51) ? 6059 : 5702, // Tanzanie
                    175 => ($provider_valeur != 51) ? 5700 : 6057, // Quatar 
                    241 => ($provider_valeur != 51) ? 1584 : 6769, // Zambie
                    100 => ($provider_valeur != 51) ? 5694 : 6051, // Inde
                    66 => ($provider_valeur != 51) ? 5296 : 5332, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 5689 : 6046, // Angola
                    225 => ($provider_valeur != 51) ? 5704 : 6061, // Dubai
                    112 => ($provider_valeur != 51) ? 5695 : 6052, // Keneya
                    45 => ($provider_valeur != 51) ? 5691 : 6048, // China
                    208 => ($provider_valeur != 51) ? 6764 : 6778, // Suisse
                    23 => ($provider_valeur != 51) ? 6756 : 6770, // Belgique
                    69 => ($provider_valeur != 51) ? 6763 : 6777, // Ethiopie
                    173 => ($provider_valeur != 51) ? 2304 : 2349, // Portugal
                    219 => ($provider_valeur != 51) ? 6761 : 6775, // Turquie
                    152 => ($provider_valeur != 51) ? 6762 : 6776, // Hollande
                    1 => ($provider_valeur != 51) ? 6757 : 6408, // Algerie
                    133 => ($provider_valeur != 51) ? 6753 : 6766, // Mauritanie
                    37 => ($provider_valeur != 51) ? 6759 : 6773, // Burundi
                    8 => ($provider_valeur != 51) ? 6732 : 6724, // Égypte

                ],
                // Is Allemagne
                82 =>  [

                    25 => ($provider_valeur != 51) ? 483 : 371, // Benin
                    36 => ($provider_valeur != 51) ? 4618 : 4090, // BF
                    133 => ($provider_valeur != 51) ? 4570 : 4043, // Mali
                    214 => ($provider_valeur != 51) ? 486 : 374, // Togo
                    188 => ($provider_valeur != 51) ? 487 : 375, // Senegal
                    83 => ($provider_valeur != 51) ? 488 : 376, // Ghana
                    157 => ($provider_valeur != 51) ? 489 : 377, // Niger
                    91 => ($provider_valeur != 51) ? 490 : 378, // Guinne conakry
                    // UPdate id send
                    107 => ($provider_valeur != 51) ? 491 : 379, // CIV

                    1 => ($provider_valeur != 51) ? 5708 : 6023, // USA
                    218 => ($provider_valeur != 51) ? 5727 : 6042, // Tunisia
                    146 => ($provider_valeur != 51) ? 5720 : 6035, // Maroc
                    199 => ($provider_valeur == 51) ? 6322 : null, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 477 : 6327, // France
                    179 => ($provider_valeur == 51) ? 601 : null, // Russie
                    226 => ($provider_valeur == 51) ? 599 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 600 : null, // Italie
                    39 => ($provider_valeur != 51) ? 5047 : 5587, // Cameroun
                    79 => ($provider_valeur != 51) ? 493 : 381, // Gabon
                    50 => ($provider_valeur != 51) ? 485 : 373, // COngo Braza
                    57 => ($provider_valeur != 51) ? 495 : 383, // RDC
                    122 => ($provider_valeur != 51) ? 496 : 384, // Liberia
                    43 => ($provider_valeur != 51) ? 497 : 385, // Thacd
                    42 => ($provider_valeur != 51) ? 498 : 386, // Afrique centrale
                    92 => ($provider_valeur != 51) ? 4906 : 4378, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 748 : 690, // Ouganda 
                    223 => ($provider_valeur != 51) ? 5722 : 6037, // Rwanda 
                    158 => ($provider_valeur != 51) ?  5002 : 4474, // Nigeria
                    191 => ($provider_valeur != 51) ? 4954 : 4426, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 5717 : 6032, // Koweit
                    212 => ($provider_valeur != 51) ? 5723 : 6038, // Tanzanie
                    175 => ($provider_valeur != 51) ? 5721 : 6036, // Quatar
                    100 => ($provider_valeur != 51) ? 5715 : 6030, // Inde
                    69 => ($provider_valeur != 51) ? 5713 : 6028, // Ethiopie
                    66 => ($provider_valeur != 51) ? 5299 : 5335, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 5710 : 6025, // Angola
                    225 => ($provider_valeur != 51) ? 5725 : 6040, // Dubai
                    112 => ($provider_valeur != 51) ? 5716 : 6031, // Keneya
                    45 => ($provider_valeur != 51) ? 5712 : 6027, // China

                    133 => ($provider_valeur != 51) ? 6817 : 6804, // Mauritanie
                    80 => ($provider_valeur != 51) ? 6818 : 6805, // Gambie
                    241 => ($provider_valeur != 51) ? 6819 : 6807, // Zambie
                    23 => ($provider_valeur != 51) ? 6820 : 6808, // Belgique
                    1 => ($provider_valeur != 51) ? 6821 : 6809, // Algerie
                    8 => ($provider_valeur != 51) ? 6822 : 6810, // Égypte
                    37 => ($provider_valeur != 51) ? 6823 : 6811, // Burundi
                    219 => ($provider_valeur != 51) ? 6824 : 6812, // Turquie
                    152 => ($provider_valeur != 51) ? 6825 : 6813, // Hollande
                    69 => ($provider_valeur != 51) ? 6826 : 6814, // Ethiopie
                    208 => ($provider_valeur != 51) ? 6827 : 6815, // Suisse
                    173 => ($provider_valeur != 51) ? 6828 : 6816, // Portugal

                ],

                // Is Italie
                106 =>  [

                    25 => ($provider_valeur != 51) ? 515 : 403, // Benin
                    36 => ($provider_valeur != 51) ? 516 : 404, // BF
                    133 => ($provider_valeur != 51) ? 4575 : 4048, // Mali
                    214 => ($provider_valeur != 51) ? 518 : 406, // Togo
                    188 => ($provider_valeur != 51) ? 519 : 407, // Senegal
                    83 => ($provider_valeur != 51) ? 520 : 408, // Ghana
                    157 => ($provider_valeur != 51) ? 521 : 409, // Niger
                    91 => ($provider_valeur != 51) ? 522 : 410, // Guinne conakry
                    39 => ($provider_valeur != 51) ? 515 : 403, // Cameroun
                    79 => ($provider_valeur != 51) ? 525 : 413, // Gabon
                    50 => ($provider_valeur != 51) ? 517 : 405, // COngo Braza
                    57 => ($provider_valeur != 51) ? 527 : 415, // RDC
                    122 => ($provider_valeur != 51) ? 416 : 528, // Liberia
                    43 => ($provider_valeur != 51) ? 529 : 417, // Thacd
                    42 => ($provider_valeur != 51) ? 530 : 418, // Afrique centrale
                    92 => ($provider_valeur != 51) ? 4911 : 4383, // Guinnée Buisso 
                    // Update Id Sned
                    107 => ($provider_valeur != 51) ? 523 : 411, // CIV

                    158 => ($provider_valeur != 51) ? 5007 : 4479, // Nigeria
                    191 => ($provider_valeur != 51) ? 4959 : 4431, // Sierria Leone
                    66 => ($provider_valeur != 51) ? 5301 : 5337, // Guinnée Equatoriale
                    199 => ($provider_valeur == 51) ? 589 : null, // ESPAGNE
                    82 => ($provider_valeur == 51) ? 600 : null, // Allemagne
                    1 => ($provider_valeur != 51) ? 5729 : 6002, // USA
                    218 => ($provider_valeur != 51) ? 5748 : 6021, // Tunisia
                    146 => ($provider_valeur != 51) ? 5741 : 6014, // Maroc
                    // UPdate Id
                    74 => ($provider_valeur != 51) ? 477 : 365, // France

                    179 => ($provider_valeur == 51) ? 604 : null, // Ruissie
                    226 => ($provider_valeur != 51) ? 5747 : 6020, // Royaume Unie
                    // UPdate Id send
                    223 => ($provider_valeur != 51) ? 5745 : 6018, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 5743 : 6016, // Rwanda 
                    116 => ($provider_valeur != 51) ? 5738 : 6011, // Koweit
                    212 => ($provider_valeur != 51) ? 5744 : 6017, // Tanzanie
                    175 => ($provider_valeur != 51) ? 5742 : 2717, // Quatar

                    100 => ($provider_valeur != 51) ? 5736 : 6009, // Inde
                    69 => ($provider_valeur != 51) ? 5734 : 6007, // Ethiopie
                    8 => ($provider_valeur != 51) ? 5731 : 6004, // Angola
                    225 => ($provider_valeur != 51) ? 5746 : 6019, // Dubai
                    112 => ($provider_valeur != 51) ? 5737 : 6010, // Keneya
                    45 => ($provider_valeur != 51) ? 5733 : 6006, // China
                    // Update Id send
                    // 208 => ($provider_valeur != 51) ? 2132 : 2177, // Suisse
                    // 23 => ($provider_valeur != 51) ? 2222 : 2267, // Belgique
                    // 173 => ($provider_valeur != 51) ? 2312 : 2357, // Portugal
                    // 219 => ($provider_valeur != 51) ? 2426 : 2471, // Turquie
                    // 152 => ($provider_valeur != 51) ? 2402 : 2447, // Hollande

                    133 => ($provider_valeur != 51) ? 6829 : 6839, // Mauritanie
                    80 => ($provider_valeur != 51) ? 6830 : 6840, // Gambie
                    241 => ($provider_valeur != 51) ? 6831 : 6842, // Zambie
                    23 => ($provider_valeur != 51) ? 6832 : 6843, // Belgique
                    1 => ($provider_valeur != 51) ? 6833 : 6844, // Algerie
                    8 => ($provider_valeur != 51) ? 6834 : 6845, // Égypte
                    37 => ($provider_valeur != 51) ? 6835 : 6846, // Burundi
                    219 => ($provider_valeur != 51) ? 6836 : 6847, // Turquie
                    152 => ($provider_valeur != 51) ? 6837 : 6848, // Hollande
                    208 => ($provider_valeur != 51) ? 6838 : 6849, // Suisse
                    173 => ($provider_valeur != 51) ? 6851 : 6850, // Portugal


                ],
                // Is Royaume Unie
                226 =>  [

                    25 => ($provider_valeur != 51) ? 499 : 387, // Benin
                    36 => ($provider_valeur != 51) ? 500 : 388, // BF
                    133 => ($provider_valeur != 51) ? 501 : 389, // Mali
                    214 => ($provider_valeur != 51) ? 502 : 390, // Togo
                    188 => ($provider_valeur != 51) ? 503 : 391, // Senegal
                    83 => ($provider_valeur != 51) ? 504 : 392, // Ghana
                    157 => ($provider_valeur != 51) ? 505 : 393, // Niger
                    91 => ($provider_valeur != 51) ? 506 : 394, // Guinne conakry
                    199 => ($provider_valeur == 51) ? 6083 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 6062 : null, // France
                    107 => ($provider_valeur != 51) ? 507 : 395, // CIV
                    1 => ($provider_valeur == 51) ? 5981 : 5750, // USA
                    218 => ($provider_valeur == 51) ? 6000 : 5769, // Tunisia
                    146 => ($provider_valeur == 51) ? 5993 : 5762, // Maroc
                    179 => ($provider_valeur == 51) ? 603 : null, // Ruissie
                    106 => ($provider_valeur == 51) ? 602 : null, // Italie
                    82 => ($provider_valeur != 51) ? 5726 : 6041, // Allemagne
                    39 => ($provider_valeur != 51) ? 508 : 396, // Cameroun
                    79 => ($provider_valeur != 51) ? 509 : 397, // Gabon
                    50 => ($provider_valeur != 51) ? 5139 : 5535, // COngo Braza
                    57 => ($provider_valeur != 51) ? 511 : 399, // RDC
                    122 => ($provider_valeur != 51) ? 512 : 400, // Liberia
                    43 => ($provider_valeur != 51) ? 513 : 401, // Thacd
                    42 => ($provider_valeur != 51) ? 514 : 402, // Afrique centrale
                    // New countries
                    92 => ($provider_valeur != 51) ? 4935 : 4407, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 5766 : 5997, // Ouganda 
                    180 => ($provider_valeur != 51) ? 5764 : 5995, // Rwanda 
                    158 => ($provider_valeur != 51) ? 4984 : 4503, // Nigeria
                    191 => ($provider_valeur != 51) ? 4983 : 4455, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 5759 : 5990, // Koweit
                    212 => ($provider_valeur != 51) ? 5765 : 5996, // Tanzanie
                    175 => ($provider_valeur != 51) ? 5763 : 5994, // Quatar
                    100 => ($provider_valeur != 51) ? 5757 : 5988, // Inde
                    69 => ($provider_valeur != 51) ? 5986 : 5755, // Ethiopie
                    66 => ($provider_valeur != 51) ? 5319 : 5355, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 5752 : 5983, // Angola
                    225 => ($provider_valeur != 51) ? 5767 : 5998, // Dubai
                    112 => ($provider_valeur != 51) ? 5758 : 5989, // Keneya
                    45 => ($provider_valeur != 51) ? 2874 : 2919, // China

                    // UPdate ID send
                    // 208 => ($provider_valeur != 51) ? 2154 : 2199, // Suisse
                    // 23 => ($provider_valeur != 51) ? 2244 : 2289, // Belgique
                    // 219 => ($provider_valeur != 51) ? 2448 : 2493, // Turquie
                    // 152 => ($provider_valeur != 51) ? 2424 : 2469, // Hollande
                    // 173 => ($provider_valeur != 51) ? 2334 : 2379, // Portugal

                    133 => ($provider_valeur != 51) ? 6852 : 6863, // Mauritanie
                    80 => ($provider_valeur != 51) ? 6853 : 6864, // Gambie
                    241 => ($provider_valeur != 51) ? 6854 : 6865, // Zambie
                    23 => ($provider_valeur != 51) ? 6855 : 6867, // Belgique
                    1 => ($provider_valeur != 51) ? 6856 : 6868, // Algerie
                    8 => ($provider_valeur != 51) ? 6857 : 6869, // Égypte
                    37 => ($provider_valeur != 51) ? 6858 : 6870, // Burundi
                    219 => ($provider_valeur != 51) ? 6859 : 6871, // Turquie
                    152 => ($provider_valeur != 51) ? 6860 : 6872, // Hollande
                    208 => ($provider_valeur != 51) ? 6861 : 6873, // Suisse
                    173 => ($provider_valeur != 51) ? 6862 : 6874, // Portugal

                ],
                // Is Ruissie
                179 =>  [

                    25 => ($provider_valeur != 51) ? 4545 : 419, // Benin
                    36 => ($provider_valeur != 51) ? 532 : 420, // BF
                    133 => ($provider_valeur != 51) ? 4570 : 421, // Mali
                    214 => ($provider_valeur != 51) ? 534 : 422, // Togo
                    188 => ($provider_valeur != 51) ? 535 : 423, // Senegal
                    83 => ($provider_valeur != 51) ? 536 : 424, // Ghana
                    157 => ($provider_valeur != 51) ? 537 : 425, // Niger
                    91 => ($provider_valeur != 51) ?  538 : 426, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 539 : 427, // CIV
                    199 => ($provider_valeur == 51) ? 587 : null, // ESPAGNE
                    39 => ($provider_valeur != 51) ? 540 : 428, // Cameroun
                    79 => ($provider_valeur != 51) ? 541 : 429, // Gabon
                    50 => ($provider_valeur != 51) ? 533 : 421, // COngo Braza
                    57 => ($provider_valeur != 51) ? 543 : 431, // RDC
                    122 => ($provider_valeur != 51) ? 544 : 432, // Liberia
                    43 => ($provider_valeur != 51) ?  545 : 433, // Thacd
                    42 => ($provider_valeur != 51) ? 546 : 434, // Afrique centrale
                    158 => ($provider_valeur != 51) ? 3014 : 3033, // Nigeria
                    66 => ($provider_valeur != 51) ?  3020 : 3039, // Guinnée Equatoriale
                    82 => ($provider_valeur == 51) ? 601 : null, // Allemagne
                    // Update Id RUissie
                    191 => ($provider_valeur != 51) ?  4967 : 4439, // Sierria Leone
                    74 => ($provider_valeur == 51) ? 594 : null, // France
                    226 => ($provider_valeur == 51) ? 6875 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 6876 : null, // Italie
                    92 => ($provider_valeur != 51) ? 4919 : 3030, // Guinnée Buisso 
                    173 => ($provider_valeur != 51) ? 3027 : 3046, // Portugal
                    1 => ($provider_valeur == 51) ? 6086 : 5771, // USA
                    218 => ($provider_valeur == 51) ? 6105 : 5790, // Tunisia
                    146 => ($provider_valeur == 51) ? 6098 : 5783, // Maroc
                    100 => ($provider_valeur != 51) ? 3092 : 3091, // Inde
                    69 => ($provider_valeur != 51) ? 3094 : 3093, // Ethiopie
                    223 => ($provider_valeur != 51) ? 3012 : 3031, // Ouganda 
                    180 => ($provider_valeur != 51) ? 3013 : 3032, // Rwanda 
                    116 => ($provider_valeur != 51) ? 3016 : 3035, // Koweit
                    212 => ($provider_valeur != 51) ? 3017 : 3036, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3018 : 3037, // Quatar
                    241 => ($provider_valeur != 51) ? 3019 : 3038, // Zambie
                    8 => ($provider_valeur != 51) ? 3021 : 3040, // Angola
                    225 => ($provider_valeur != 51) ? 3022 : 3041, // Dubai
                    112 => ($provider_valeur != 51) ? 3023 : 3042, // Keneya
                    45 => ($provider_valeur != 51) ? 3024 : 3043, // China
                    208 => ($provider_valeur != 51) ? 3025 : 3044, // Suisse
                    23 => ($provider_valeur != 51) ?  3026 : 3045, // Belgique
                    219 => ($provider_valeur != 51) ? 3028 : 3047, // Turquie
                    152 => ($provider_valeur != 51) ? 3029 : 3048, // Hollande
                    133 => ($provider_valeur != 51) ? 6878 : 6877, // Mauritanie
                    // 80 => ($provider_valeur != 51) ? 6853 : 6864, // Gambie
                    // 241 => ($provider_valeur != 51) ? 6854 : 6865, // Zambie
                    // 23 => ($provider_valeur != 51) ? 6855 : 6867, // Belgique
                    1 => ($provider_valeur != 51) ? 6879 : 6882, // Algerie
                    8 => ($provider_valeur != 51) ? 6880 : 6883, // Égypte
                    37 => ($provider_valeur != 51) ? 6881 : 6884, // Burundi
                    // 219 => ($provider_valeur != 51) ? 6859 : 6871, // Turquie
                    // 152 => ($provider_valeur != 51) ? 6860 : 6872, // Hollande
                    // 208 => ($provider_valeur != 51) ? 6861 : 6873, // Suisse
                    // 173 => ($provider_valeur != 51) ? 6862 : 6874, // Portugal
                ],

                // Is Suisse
                208 =>  [

                    25 => ($provider_valeur != 51) ? 2114 : 2159, // Benin
                    36 => ($provider_valeur != 51) ? 2115 : 2160, // BF
                    133 => ($provider_valeur != 51) ? 2137 : 2182, // Mali
                    214 => ($provider_valeur != 51) ? 2151 : 2196, // Togo
                    188 => ($provider_valeur != 51) ? 2146 : 2191, // Senegal
                    83 => ($provider_valeur != 51) ? 2128 : 2173, // Ghana
                    157 => ($provider_valeur != 51) ? 2141 : 2186, // Niger
                    91 => ($provider_valeur != 51) ?  2129 : 2174, // Guinne conakry
                    199 => ($provider_valeur == 51) ? 2148 : 2193, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 2124 : 2169, // France
                    1 => ($provider_valeur == 51) ? 2155 : null, // USA
                    218 => ($provider_valeur == 51) ? 3082 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2184 : null, // Maroc
                    107 => ($provider_valeur != 51) ? 647 : 683, // CIV
                    179 => ($provider_valeur == 51) ? 6885 : null, // Ruissie
                    226 => ($provider_valeur == 51) ? 2199 : 2154, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2177 : 2132, // Italie
                    82 => ($provider_valeur != 51) ? 2127 : 2172, // Allemagne
                    39 => ($provider_valeur != 51) ? 2117 : 2162, // Cameroun
                    79 => ($provider_valeur != 51) ? 5098 : 5566, // Gabon
                    50 => ($provider_valeur != 51) ? 2121 : 2166, // COngo Braza
                    57 => ($provider_valeur != 51) ?  2122 : 2167, // RDC
                    122 => ($provider_valeur != 51) ? 2136 : 2181, // Liberia
                    43 => ($provider_valeur != 51) ?  2119 : 2164, // Thacd
                    42 => ($provider_valeur != 51) ? 2118 : 2163, // Afrique centrale
                    92 => ($provider_valeur != 51) ? 3575 : 3595, // Guinnée Buisso 
                    191 => ($provider_valeur != 51) ? 3579 : 3599, // Sierria Leone
                    158 => ($provider_valeur != 51) ? 3578 : 3598, // Nigeria
                    223 => ($provider_valeur != 51) ? 3576 : 3596, // Ouganda 
                    180 => ($provider_valeur != 51) ? 3577 : 3597, // Rwanda 
                    116 => ($provider_valeur != 51) ? 3580 : 3600, // Koweit
                    212 => ($provider_valeur != 51) ? 3581 : 3601, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3582 : 3602, // Quatar
                    241 => ($provider_valeur != 51) ? 3583 : 3603, // Zambie
                    66 => ($provider_valeur != 51) ?  3584 : 3604, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?  3585 : 3605, // Angola
                    225 => ($provider_valeur != 51) ? 3586 : 3606, // Dubai
                    112 => ($provider_valeur != 51) ? 3587 : 3607, // Keneya
                    45 => ($provider_valeur != 51) ? 3588 : 3568, // China
                    23 => ($provider_valeur != 51) ?  3589 : 3609, // Belgique
                    173 => ($provider_valeur != 51) ?  3590 : 3610, // Portugal
                    219 => ($provider_valeur != 51) ? 3591 : 3611, // Turquie
                    152 => ($provider_valeur != 51) ? 3592 : 3612, // Hollande
                    100 => ($provider_valeur != 51) ? 3593 : 3613, // Inde
                    69 => ($provider_valeur != 51) ? 3594 : 3614, // Ethiopie
                    133 => ($provider_valeur != 51) ? 6890 : 6886, // Mauritanie
                    // 80 => ($provider_valeur != 51) ? 6853 : 6864, // Gambie
                    // 241 => ($provider_valeur != 51) ? 6854 : 6865, // Zambie
                    // 23 => ($provider_valeur != 51) ? 6855 : 6867, // Belgique
                    1 => ($provider_valeur != 51) ? 6891 : 6887, // Algerie
                    8 => ($provider_valeur != 51) ? 6892 : 6888, // Égypte
                    37 => ($provider_valeur != 51) ? 6893 : 6889, // Burundi
                    // 219 => ($provider_valeur != 51) ? 6859 : 6871, // Turquie
                    // 152 => ($provider_valeur != 51) ? 6860 : 6872, // Hollande
                    // 208 => ($provider_valeur != 51) ? 6861 : 6873, // Suisse
                    // 173 => ($provider_valeur != 51) ? 6862 : 6874, // Portugal
                ],

                // Is Belgique
                23 =>  [

                    25 => ($provider_valeur != 51) ? 2204 : 2249, // Benin
                    36 => ($provider_valeur != 51) ? 2205 : 2250, // BF
                    133 => ($provider_valeur != 51) ?  2227 : 2272, // Mali
                    214 => ($provider_valeur != 51) ? 2241 : 2286, // Togo
                    188 => ($provider_valeur != 51) ? 2236 : 2281, // Senegal
                    83 => ($provider_valeur != 51) ? 2218 : 2263, // Ghana
                    157 => ($provider_valeur != 51) ? 2231 : 2276, // Niger
                    91 => ($provider_valeur != 51) ? 2219 : 2264, // Guinne conakry
                    // UPdate Is send
                    107 => ($provider_valeur != 51) ? 664 : 648, // CIV
                    199 => ($provider_valeur == 51) ? 2283 : null, // ESPAGNE
                    74 => ($provider_valeur != 51) ?  2214 : 2259, // France
                    1 => ($provider_valeur == 51) ? 2245 : null, // USA
                    218 => ($provider_valeur == 51) ? 5958 : 5811, // Tunisia
                    146 => ($provider_valeur == 51) ? 2274 : null, // Maroc
                    226 => ($provider_valeur == 51) ? 2289 : 2244, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2267 : 2222, // Italie
                    82 => ($provider_valeur != 51) ? 2217 : 2262, // Allemagne
                    39 => ($provider_valeur != 51) ? 2207 : 2252, // Cameroun
                    79 => ($provider_valeur != 51) ? 6551 : 6623, // Gabon
                    50 => ($provider_valeur != 51) ? 2211 : 2256, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2212 : 2257, // RDC
                    122 => ($provider_valeur != 51) ? 2226 : 2271, // Liberia
                    43 => ($provider_valeur != 51) ? 2209 : 2254, // Thacd
                    42 => ($provider_valeur != 51) ? 2208 : 2253, // Afrique centrale
                    92 => ($provider_valeur != 51) ? 3615 : 3635, // Guinnée Buisso
                    158 => ($provider_valeur != 51) ? 3618 : 3638, // Nigeria
                    191 => ($provider_valeur != 51) ? 3619 : 3639, // Sierria Leone
                    66 => ($provider_valeur != 51) ?  3624 : 3644, // Guinnée Equatoriale
                    223 => ($provider_valeur != 51) ? 3616 : 3636, // Ouganda 
                    180 => ($provider_valeur != 51) ? 3617 : 3637, // Rwanda 
                    116 => ($provider_valeur != 51) ? 3620 : 3640, // Koweit
                    212 => ($provider_valeur != 51) ?  3621 : 3641, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3622 : 3642, // Quatar
                    241 => ($provider_valeur != 51) ? 3623 : 3643, // Zambie
                    8 => ($provider_valeur != 51) ?  3625 : 3645, // Angola
                    225 => ($provider_valeur != 51) ? 3626 : 3646, // Dubai
                    112 => ($provider_valeur != 51) ? 3627 : 3647, // Keneya
                    45 => ($provider_valeur != 51) ? 3628 : 3648, // China
                    173 => ($provider_valeur != 51) ?  3630 : 3650, // Portugal
                    219 => ($provider_valeur != 51) ? 3631 : 3651, // Turquie
                    152 => ($provider_valeur != 51) ? 3632 : 3652, // Hollande
                    100 => ($provider_valeur != 51) ? 3633 : 3653, // Inde
                    69 => ($provider_valeur != 51) ? 3634 : 3654, // Ethiopie

                    133 => ($provider_valeur != 51) ? 6894 : 6899, // Mauritanie
                    // 80 => ($provider_valeur != 51) ? 6853 : 6864, // Gambie
                    // 241 => ($provider_valeur != 51) ? 6854 : 6865, // Zambie
                    // 23 => ($provider_valeur != 51) ? 6855 : 6867, // Belgique
                    1 => ($provider_valeur != 51) ? 6895 : 6900, // Algerie
                    8 => ($provider_valeur != 51) ? 6896 : 6901, // Égypte
                    37 => ($provider_valeur != 51) ? 6897 : 6902, // Burundi
                    // 219 => ($provider_valeur != 51) ? 6859 : 6871, // Turquie
                    // 152 => ($provider_valeur != 51) ? 6860 : 6872, // Hollande
                    208 => ($provider_valeur != 51) ? 6898 : 6903, // Suisse
                    // 173 => ($provider_valeur != 51) ? 6862 : 6874, // Portugal
                ],
                //Is PORTUGAL
                173 =>  [

                    25 => ($provider_valeur != 51) ? 2294 : 2339, // Benin
                    36 => ($provider_valeur != 51) ? 2295 : 2340, // BF
                    133 => ($provider_valeur != 51) ? 2317 : 2362, // Mali
                    214 => ($provider_valeur != 51) ? 2331 : 2376, // Togo
                    188 => ($provider_valeur != 51) ? 2326 : 2371, // Senegal
                    83 => ($provider_valeur != 51) ? 2308 : 2353, // Ghana
                    157 => ($provider_valeur != 51) ? 2321 : 2366, // Niger
                    91 => ($provider_valeur != 51) ? 2309 : 2354, // Guinne conakry
                    179 => ($provider_valeur == 51) ? 3046 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2379 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2357 : null, // Italie
                    82 => ($provider_valeur != 51) ? 2307 : 2352, // Allemagne
                    39 => ($provider_valeur != 51) ? 2297 : 2342, // Cameroun
                    79 => ($provider_valeur != 51) ? 5094 : 5562, // Gabon
                    50 => ($provider_valeur != 51) ? 2301 : 2346, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2302 : 2347, // RDC
                    122 => ($provider_valeur != 51) ? 2316 : 2361, // Liberia
                    43 => ($provider_valeur != 51) ? 2299 : 2344, // Thacd
                    42 => ($provider_valeur != 51) ? 2298 : 2343, // Afrique centrale
                    199 => ($provider_valeur == 51) ? 2373 : null, // ESPAGNE
                    74 => ($provider_valeur   != 51) ? 2304 : 2349, // France
                    92 => ($provider_valeur != 51) ? 3655 : 3675, // Guinnée Buisso 
                    158 => ($provider_valeur != 51) ? 3658 : 3678, // Nigeria
                    191 => ($provider_valeur != 51) ? 3659 : 3679, // Sierria Leone
                    107 => ($provider_valeur != 51) ? 649 : 665, // CIV
                    1 => ($provider_valeur == 51) ? 2335 : null, // USA
                    218 => ($provider_valeur == 51) ? 5937 : 5832, // Tunisia
                    146 => ($provider_valeur == 51) ? 2364 : null, // Maroc
                    223 => ($provider_valeur != 51) ? 3656 : 3676, // Ouganda 
                    180 => ($provider_valeur != 51) ? 3657 : 3677, // Rwanda 
                    116 => ($provider_valeur != 51) ? 3660 : 3680, // Koweit
                    212 => ($provider_valeur != 51) ?  3661 : 3681, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3662 : 3682, // Quatar
                    241 => ($provider_valeur != 51) ? 3663 : 3683, // Zambie
                    66 => ($provider_valeur != 51) ?  3664 : 3684, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?  3665 : 3685, // Angola
                    225 => ($provider_valeur != 51) ? 3666 : 3686, // Dubai
                    112 => ($provider_valeur != 51) ? 3667 : 3687, // Keneya
                    45 => ($provider_valeur != 51) ? 3668 : 3688, // China
                    // UPdate Id send
                    208 => ($provider_valeur != 51) ? 3669 : 3609, // Suisse
                    23 => ($provider_valeur != 51) ?  3670 : 3609, // Belgique
                    219 => ($provider_valeur != 51) ? 3671 : 3691, // Turquie
                    152 => ($provider_valeur != 51) ? 3672 : 3692, // Hollande

                    100 => ($provider_valeur != 51) ? 3673 : 3693, // Inde
                    69 => ($provider_valeur != 51) ? 3674 : 3694, // Ethiopie

                    133 => ($provider_valeur != 51) ? 6894 : 6899, // Mauritanie
                    // 80 => ($provider_valeur != 51) ? 6853 : 6864, // Gambie
                    // 241 => ($provider_valeur != 51) ? 6854 : 6865, // Zambie
                    // 23 => ($provider_valeur != 51) ? 6855 : 6867, // Belgique
                    1 => ($provider_valeur != 51) ? 6895 : 6900, // Algerie
                    8 => ($provider_valeur != 51) ? 6896 : 6901, // Égypte
                    37 => ($provider_valeur != 51) ? 6897 : 6902, // Burundi
                    // 219 => ($provider_valeur != 51) ? 6859 : 6871, // Turquie
                    // 152 => ($provider_valeur != 51) ? 6860 : 6872, // Hollande
                    208 => ($provider_valeur != 51) ? 6898 : 6903, // Suisse
                    // 173 => ($provider_valeur != 51) ? 6862 : 6874, // Portugal
                ],
                //Is TURQUIE
                219 =>  [

                    25 => ($provider_valeur != 51) ? 2384 : 2429, // Benin
                    36 => ($provider_valeur != 51) ? 2385 : 2430, // BF
                    133 => ($provider_valeur != 51) ? 2407 : 2452, // Mali
                    214 => ($provider_valeur != 51) ? 2421 : 2466, // Togo
                    188 => ($provider_valeur != 51) ? 2416 : 2461, // Senegal
                    83 => ($provider_valeur != 51) ? 2398 : 2443, // Ghana
                    157 => ($provider_valeur != 51) ? 2411 : 2456, // Niger
                    91 => ($provider_valeur != 51) ? 2399 : 2444, // Guinne conakry
                    // Update id send
                    107 => ($provider_valeur != 51) ? 666 : 650, // CIV

                    1 => ($provider_valeur == 51) ? 2425 : null, // USA
                    218 => ($provider_valeur == 51) ? 6330 : 6329, // Tunisia
                    146 => ($provider_valeur == 51) ? 2454 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2418 : 2463, // ESPAGNE
                    74 => ($provider_valeur  != 51) ? 2418 : 2439, // France

                    // Update Id send
                    179 => ($provider_valeur == 51) ? 3047 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2469 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2447 : 2402, // Italie
                    82 => ($provider_valeur != 51) ? 2421 : 2466, // Allemagne
                    39 => ($provider_valeur != 51) ? 2387 : 2432, // Cameroun
                    79 => ($provider_valeur != 51) ?  5100 : 5568, // Gabon
                    50 => ($provider_valeur != 51) ? 2391 : 2436, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2392 : 2437, // RDC
                    122 => ($provider_valeur != 51) ? 2406 : 2451, // Liberia
                    43 => ($provider_valeur != 51) ? 2389 : 2434, // Thacd
                    42 => ($provider_valeur != 51) ? 2405 : 2450, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3695 : 3715, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3696 : 3716, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3697 : 3717, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3698 : 3718, // Nigeria
                    191 => ($provider_valeur != 51) ? 3699 : 3719, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3700 : 3720, // Koweit
                    212 => ($provider_valeur != 51) ?  3701 : 3721, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3702 : 3722, // Quatar
                    241 => ($provider_valeur != 51) ? 3703 : 3723, // Zambie
                    66 => ($provider_valeur != 51) ?  3704 : 3724, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?  3705 : 3725, // Angola
                    225 => ($provider_valeur != 51) ? 3706 : 3726, // Dubai
                    112 => ($provider_valeur != 51) ? 3707 : 3727, // Keneya
                    45 => ($provider_valeur != 51) ? 3708 : 3728, // China
                    208 => ($provider_valeur != 51) ? 3709 : 3729, // Suisse
                    23 => ($provider_valeur != 51) ?  3710 : 3730, // Belgique
                    173 => ($provider_valeur != 51) ?  3711 : 3731, // Portugal
                    152 => ($provider_valeur != 51) ? 3712 : 3732, // Hollande
                    100 => ($provider_valeur != 51) ? 3713 : 3733, // Inde
                    69 => ($provider_valeur != 51) ? 3714 : 3734, // Ethiopie
                ],

                // Is PAYS BAS(HOLLANDE)
                152 =>  [

                    25 => ($provider_valeur != 51) ? 2474 : 2519, // Benin
                    36 => ($provider_valeur != 51) ? 2475 : 2520, // BF
                    133 => ($provider_valeur != 51) ? 4584 : 4057, // Mali
                    214 => ($provider_valeur != 51) ? 2511 : 2556, // Togo
                    188 => ($provider_valeur != 51) ? 2506 : 2551, // Senegal
                    83 => ($provider_valeur != 51) ? 2488 : 2533, // Ghana
                    157 => ($provider_valeur != 51) ? 2501 : 2546, // Niger
                    91 => ($provider_valeur != 51) ? 2489 : 2534, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 667 : 651, // CIV
                    1 => ($provider_valeur == 51) ? 2515 : null, // USA
                    218 => ($provider_valeur == 51) ? 3086 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2544 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2508 : 2553, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 2439 : null, // France
                    39 => ($provider_valeur != 51) ? 2477 : 2522, // Cameroun
                    79 => ($provider_valeur != 51) ? 5093 : 5561, // Gabon
                    50 => ($provider_valeur != 51) ? 2481 : 2526, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2482 : 2527, // RDC
                    122 => ($provider_valeur != 51) ?  2496 : 2541, // Liberia
                    43 => ($provider_valeur != 51) ? 2479 : 2524, // Thacd
                    42 => ($provider_valeur != 51) ? 2388 : 2433, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3735 : 3755, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3736 : 3756, // Ouganda 
                    // Update Id send
                    179 => ($provider_valeur == 51) ? 3048 : null, // Ruissie
                    226 => ($provider_valeur == 51) ? 2469 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2447 : null, // Italie
                    82 => ($provider_valeur == 51) ? 2442 : null, // Allemagne
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3737 : 3757, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3738 : 3758, // Nigeria
                    191 => ($provider_valeur != 51) ? 3739 : 3759, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3740 : 3760, // Koweit
                    212 => ($provider_valeur != 51) ?  3741 : 3761, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3742 : 3762, // Quatar
                    241 => ($provider_valeur != 51) ? 3743 : 3763, // Zambie
                    66 => ($provider_valeur != 51) ?  3744 : 3764, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?  3745 : 3765, // Angola
                    225 => ($provider_valeur != 51) ? 3746 : 3766, // Dubai
                    112 => ($provider_valeur != 51) ? 3747 : 3767, // Keneya
                    45 => ($provider_valeur != 51) ? 3748 : 3768, // China
                    // Update info arretes 
                    208 => ($provider_valeur != 51) ? 2509 : 2554, // Suisse
                    23 => ($provider_valeur != 51) ?  3750 : 3770, // Belgique
                    173 => ($provider_valeur != 51) ?  3751 : 3771, // Portugal
                    219 => ($provider_valeur != 51) ? 3752 : 3772, // Turquie

                    100 => ($provider_valeur != 51) ? 3753 : 3773, // Inde
                    69 => ($provider_valeur != 51) ? 3754 : 3774, // Ethiopie
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif (robus()->country->name_groupe == 'USA') {

            $countriesLevels = [
                // Is USA
                1 => [

                    25 => ($provider_valeur != 51) ? 4505 : 3977, // Benin
                    36 => ($provider_valeur != 51) ? 249 : 242, // BF
                    133 => ($provider_valeur != 51) ? 4552 : 4025, // Mali
                    214 => ($provider_valeur != 51) ? 250 : 244, // Togo
                    188 => ($provider_valeur != 51) ? 251 : 245, // Senegal
                    83 => ($provider_valeur != 51) ? 252 : 571, // Ghana
                    157 => ($provider_valeur != 51) ? 253 : 246, // Niger
                    91 => ($provider_valeur != 51) ? 35 : 71, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 5608 : 5637, // CIV
                    218 => ($provider_valeur == 51) ? 554 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 555 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 547 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 548 : null, // France
                    179 => ($provider_valeur == 51) ? 553 : null, // Ruissie
                    226 => ($provider_valeur == 51) ? 551 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 552 : null, // Italie
                    82 => ($provider_valeur == 51) ? 550 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 5032 : 5572, // Cameroun
                    79 => ($provider_valeur != 51) ? 256 : 235, // Gabon
                    50 => ($provider_valeur != 51) ? 5104 : 5500, // COngo Braza
                    57 => ($provider_valeur != 51) ? 258 : 237, // RDC
                    122 => ($provider_valeur != 51) ? 238 : 259, // Liberia
                    43 => ($provider_valeur != 51) ? 260 : 239, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ? 261 : 240, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 4888 : 4360, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 731 : 673, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 837 : 792, // Rwanda 

                    158 => ($provider_valeur != 51) ? 4984 : 4456, // Nigeria
                    191 => ($provider_valeur != 51) ? 4936 : 4408, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2604 : 2560, // Koweit
                    212 => ($provider_valeur != 51) ? 1851 : 1345, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2650 : 2695, // Quatar
                    241 => ($provider_valeur != 51) ? 1570 : 1615, // Zambie
                    100 => ($provider_valeur != 51) ? 2920 : 2965, // Inde
                    69 => ($provider_valeur != 51) ? 1390 : 1435, // Ethiopie
                    66 => ($provider_valeur != 51) ? 5284 : 5320, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1660 : 1705, // Angola
                    225 => ($provider_valeur != 51) ? 2740 : 2785, // Dubai
                    112 => ($provider_valeur != 51) ? 1480 : 1525, // Keneya
                    45 => ($provider_valeur != 51) ? 2830 : 2875, // China
                    //uPDATE ID send
                    208 => ($provider_valeur != 51) ? 2110 : 2155, // Suisse
                    23 => ($provider_valeur != 51) ? 5792 : 5939, // Belgique

                    173 => ($provider_valeur != 51) ? 5918 : 5813, // Portugal
                    219 => ($provider_valeur != 51) ? 5897 : 5834, // Turquie
                    152 => ($provider_valeur != 51) ? 5855 : 5876, // Hollande
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif (robus()->country->name_groupe == 'MAG') {

            $countriesLevels = [
                // Is Tunisia
                218 => [

                    25 => ($provider_valeur != 51) ? 264 : 292, // Benin
                    36 => ($provider_valeur != 51) ? 265 : 293, // BF
                    // Ajouter Tunisie
                    133 => ($provider_valeur != 51) ? 574 : 294, // Mali
                    214 => ($provider_valeur != 51) ? 266 : 295, // Togo
                    188 => ($provider_valeur != 51) ? 267 : 296, // Senegal
                    83 => ($provider_valeur != 51) ? 268 : 297, // Ghana
                    157 => ($provider_valeur != 51) ? 269 : 298, // Niger
                    91 => ($provider_valeur != 51) ? 270 : 299, // Guinne conakry
                    // Update Id Send
                    107 => ($provider_valeur != 51) ? 310 : 300, // CIV
                    // UPdate Id send
                    1 => ($provider_valeur != 51) ? 6107 : 6124, // USA
                    146 => ($provider_valeur == 51) ? 583 : null, // Maroc

                    199 => ($provider_valeur == 51) ? 556 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 557 : null, // France
                    179 => ($provider_valeur == 51) ? 562 : null, // Ruissie
                    226 => ($provider_valeur == 51) ? 560 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 561 : null, // Italie
                    82 => ($provider_valeur == 51) ? 559 : null, // Allemagne
                    39 => ($provider_valeur != 51) ?  271 : 327, // Cameroun
                    79 => ($provider_valeur != 51) ? 272 : 328, // Gabon
                    50 => ($provider_valeur != 51) ? 574 : 294, // COngo Braza
                    57 => ($provider_valeur != 51) ? 374 : 329, // RDC
                    122 => ($provider_valeur != 51) ? 275 : 330, // Liberia
                    43 => ($provider_valeur != 51) ? 276 : 331, // Thad
                    42 => ($provider_valeur != 51) ? 277 : 332, // Afrique centrale
                    158 => ($provider_valeur != 51) ? 3052 : 3071, // Nigeria
                    191 => ($provider_valeur != 51) ? 3053 : 3072, // Sierria Leone
                    66 => ($provider_valeur != 51) ?  3058 : 3077, // Guinnée Equatoriale
                    // New countries
                    92 => ($provider_valeur != 51) ? 3049 : 3068, // Guinnée Buisso 

                    223 => ($provider_valeur != 51) ? 3050 : 3069, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3051 : 3070, // Rwanda 
                    // Upodate ID send
                    116 => ($provider_valeur != 51) ? 3054 : 3073, // Koweit
                    212 => ($provider_valeur != 51) ? 3055 : 3074, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3056 : 3075, // Quatar
                    241 => ($provider_valeur != 51) ? 3057 : 3076, // Zambie
                    8 => ($provider_valeur != 51) ? 3059 : 3078, // Angola
                    225 => ($provider_valeur != 51) ? 3060 : 3079, // Dubai
                    112 => ($provider_valeur != 51) ? 3061 : 3080, // Keneya
                    45 => ($provider_valeur != 51) ? 3062 : 3081, // China
                    208 => ($provider_valeur != 51) ? 3063 : 3082, // Suisse

                    23 => ($provider_valeur != 51) ?  3064 : 3083, // Belgique
                    173 => ($provider_valeur != 51) ? 3065 : 3084, // Portugal
                    219 => ($provider_valeur != 51) ? 5853 : 5916, // Turquie
                    // Update send Id
                    152 => ($provider_valeur != 51) ? 5874 : 3086, // Hollande
                    100 => ($provider_valeur != 51) ? 3087 : 3088, // Inde
                    69 => ($provider_valeur != 51) ? 3089 : 3090, // Ethiopie
                ],
                // Is Maroc
                146 => [

                    25 => ($provider_valeur != 51) ? 278 : 302, // Benin
                    36 => ($provider_valeur != 51) ? 279 : 302, // BF
                    133 => ($provider_valeur != 51) ? 575 : 303, // Mali
                    214 => ($provider_valeur != 51) ? 280 : 304, // Togo
                    188 => ($provider_valeur != 51) ? 281 : 305, // Senegal
                    83 => ($provider_valeur != 51) ? 282 : 306, // Ghana
                    157 => ($provider_valeur != 51) ? 283 : 307, // Niger
                    91 => ($provider_valeur != 51) ? 284 : 308, // Guinne conakry

                    // Update Send Id
                    107 => ($provider_valeur != 51) ? 311 : 309, // CIV
                    1 => ($provider_valeur == 51) ? 555 : null, // USA

                    218 => ($provider_valeur == 51) ? 6331 : 6332, // Tunisia
                    199 => ($provider_valeur == 51) ? 563 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 564 : null, // France
                    179 => ($provider_valeur == 51) ? 569 : null, // Russie
                    226 => ($provider_valeur == 51) ? 567 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 568 : null, // Italie
                    82 => ($provider_valeur == 51) ? 566 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 285 : 333, // Cameroun
                    79 => ($provider_valeur != 51) ? 286 : 334, // Gabon
                    50 => ($provider_valeur != 51) ? 5128 : 5524, // COngo Braza
                    57 => ($provider_valeur != 51) ? 288 : 335, // RDC
                    122 => ($provider_valeur != 51) ? 336 : 289, // Liberia
                    43 => ($provider_valeur != 51) ? 290 : 337, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  291 : 338, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 4919 : 4391, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 730 : 702, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 836 : 791, // Rwanda 

                    158 => ($provider_valeur != 51) ? 5015 : 4487, // Nigeria
                    191 => ($provider_valeur != 51) ? 4967 : 4439, // Sierria Leone

                    // UPdate Id send
                    116 => ($provider_valeur != 51) ? 2633 : 2589, // Koweit
                    212 => ($provider_valeur != 51) ? 1329 : 1374, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2679 : 2724, // Quatar
                    241 => ($provider_valeur != 51) ? 1599 : 1644, // Zambie
                    100 => ($provider_valeur != 51) ? 2949 : 2994, // Inde
                    69 => ($provider_valeur != 51) ? 1419 : 1464, // Ethiopie
                    66 => ($provider_valeur != 51) ? 5308 : 5339, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1689 : 1734, // Angola
                    225 => ($provider_valeur != 51) ? 2769 : 2814, // Dubai
                    112 => ($provider_valeur != 51) ? 1509 : 1554, // Keneya
                    45 => ($provider_valeur != 51) ? 2859 : 2904, // China
                    208 => ($provider_valeur != 51) ? 2139 : 2184, // Suisse
                    23 => ($provider_valeur != 51) ? 2229 : 2274, // Belgique
                    173 => ($provider_valeur != 51) ? 5825 : 5930, // Portugal
                    219 => ($provider_valeur != 51) ? 2409 : 2454, // Turquie
                    152 => ($provider_valeur != 51) ? 5867 : 5888, // Hollande
                ],

                // Is Algerie
                5 => [
                    25 => ($provider_valeur != 51) ?  4506 : 3978, // Benin
                    36 => ($provider_valeur != 51) ? 4601 : 4074, // BF
                    133 => ($provider_valeur != 51) ? 4553 : 4026, // Mali
                    214 => ($provider_valeur != 51) ? 4649 : 4121, // Togo
                    188 => ($provider_valeur != 51) ? 4697 : 4169, // Senegal
                    83 => ($provider_valeur != 51) ? 4745 : 4217, // Ghana
                    157 => ($provider_valeur != 51) ? 4793 : 4265, // Niger
                    91 => ($provider_valeur != 51) ? 4841 : 4313, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 5609 : 5638, // CIV
                    1 => ($provider_valeur == 51) ? 555 : null, // USA
                    218 => ($provider_valeur == 51) ? 593 : null, // Tunisia
                    199 => ($provider_valeur == 51) ? 563 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 564 : null, // France
                    179 => ($provider_valeur == 51) ? 569 : null, // Russie
                    226 => ($provider_valeur == 51) ? 567 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 568 : null, // Italie
                    82 => ($provider_valeur == 51) ? 566 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 5033 : 5573, // Cameroun
                    79 => ($provider_valeur != 51) ? 5069 : 5537, // Gabon
                    50 => ($provider_valeur != 51) ? 5105 : 5501, // COngo Braza
                    57 => ($provider_valeur != 51) ? 288 : 335, // RDC
                    122 => ($provider_valeur != 51) ? 336 : 289, // Liberia
                    43 => ($provider_valeur != 51) ? 290 : 337, // Thacd
                    42 => ($provider_valeur != 51) ?  291 : 338, // Afrique centrale
                    // New countries 
                    92 => ($provider_valeur != 51) ? 4889 : 4361, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 730 : 702, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 836 : 791, // Rwanda 
                    158 => ($provider_valeur != 51) ? 4985 : 4457, // Nigeria
                    191 => ($provider_valeur != 51) ? 4937 : 4409, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2633 : 2589, // Koweit
                    212 => ($provider_valeur != 51) ? 1329 : 1374, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2679 : 2724, // Quatar
                    241 => ($provider_valeur != 51) ? 1599 : 1644, // Zambie
                    100 => ($provider_valeur != 51) ? 2949 : 2994, // Inde
                    69 => ($provider_valeur != 51) ? 1419 : 1464, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2065 : 2020, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1689 : 1734, // Angola
                    225 => ($provider_valeur != 51) ? 2769 : 2814, // Dubai
                    112 => ($provider_valeur != 51) ? 1509 : 1554, // Keneya
                    45 => ($provider_valeur != 51) ? 2859 : 2904, // China
                    208 => ($provider_valeur != 51) ? 2139 : 2184, // Suisse
                    23 => ($provider_valeur != 51) ? 2229 : 2274, // Belgique
                    173 => ($provider_valeur != 51) ? 2319 : 2364, // Portugal
                    219 => ($provider_valeur != 51) ? 2409 : 2454, // Turquie
                    152 => ($provider_valeur != 51) ? 2499 : 2544, // Hollande
                ],

                // Is Egypte
                64 => [

                    25 => ($provider_valeur != 51) ?  4516 : 3989, // Benin
                    36 => ($provider_valeur != 51) ? 4612 : 4084, // BF
                    133 => ($provider_valeur != 51) ? 4564 : 4037, // Mali
                    214 => ($provider_valeur != 51) ?  4660 : 4132, // Togo
                    188 => ($provider_valeur != 51) ? 281 : 305, // Senegal
                    83 => ($provider_valeur != 51) ? 4756 : 4228, // Ghana
                    157 => ($provider_valeur != 51) ? 4804 : 4276, // Niger
                    91 => ($provider_valeur != 51) ? 4852 : 4324, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 311 : 309, // CIV
                    1 => ($provider_valeur == 51) ? 555 : null, // USA
                    218 => ($provider_valeur == 51) ? 593 : null, // Tunisia
                    199 => ($provider_valeur == 51) ? 563 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 564 : null, // France
                    179 => ($provider_valeur == 51) ? 569 : null, // Russie
                    226 => ($provider_valeur == 51) ? 567 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 568 : null, // Italie
                    82 => ($provider_valeur == 51) ? 566 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 5041 : 5581, // Cameroun
                    79 => ($provider_valeur != 51) ? 5076 : 5545, // Gabon
                    50 => ($provider_valeur != 51) ? 5113 : 5509, // COngo Braza
                    57 => ($provider_valeur != 51) ? 5256 : 53625, // RDC
                    122 => ($provider_valeur != 51) ? 336 : 289, // Liberia
                    43 => ($provider_valeur != 51) ? 290 : 337, // Thacd
                    42 => ($provider_valeur != 51) ?  291 : 338, // Afrique centrale
                    92 => ($provider_valeur != 51) ? 4900 : 4372, // Guinnée Buisso 

                    // New countries
                    223 => ($provider_valeur != 51) ? 730 : 702, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 836 : 791, // Rwanda 
                    158 => ($provider_valeur != 51) ?  4996 : 4468, // Nigeria
                    191 => ($provider_valeur != 51) ? 4948 : 4420, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2633 : 2589, // Koweit
                    212 => ($provider_valeur != 51) ? 1329 : 1374, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2679 : 2724, // Quatar
                    241 => ($provider_valeur != 51) ? 1599 : 1644, // Zambie
                    100 => ($provider_valeur != 51) ? 2949 : 2994, // Inde
                    69 => ($provider_valeur != 51) ? 1419 : 1464, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2065 : 2020, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1689 : 1734, // Angola
                    225 => ($provider_valeur != 51) ? 2769 : 2814, // Dubai
                    112 => ($provider_valeur != 51) ? 1509 : 1554, // Keneya
                    45 => ($provider_valeur != 51) ? 2859 : 2904, // China
                    208 => ($provider_valeur != 51) ? 2139 : 2184, // Suisse
                    23 => ($provider_valeur != 51) ? 2229 : 2274, // Belgique
                    173 => ($provider_valeur != 51) ? 2319 : 2364, // Portugal
                    219 => ($provider_valeur != 51) ? 2409 : 2454, // Turquie
                    152 => ($provider_valeur != 51) ? 2499 : 2544, // Hollande

                ],

                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif (robus()->country->name_groupe == 'AFE') {

            $countriesLevels = [
                // Is OUGANDA
                223 => [

                    25 => ($provider_valeur != 51) ? 718 : 677, // Benin
                    36 => ($provider_valeur != 51) ? 719 : 678, // BF
                    133 => ($provider_valeur != 51) ? 700 : 728, // Mali
                    214 => ($provider_valeur != 51) ? 714 : 742, // Togo
                    188 => ($provider_valeur != 51) ? 741 : 709, // Senegal
                    83 => ($provider_valeur != 51) ? 749 : 691, // Ghana
                    157 => ($provider_valeur != 51) ? 704 : 736, // Niger
                    91 => ($provider_valeur != 51) ? 697 : 750, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 633 : 655, // CIV
                    1 => ($provider_valeur != 51) ? 731 : 673, // USA
                    // Update Id Send
                    218 => ($provider_valeur == 51) ? 3069 : null, // Tunisia

                    146 => ($provider_valeur == 51) ? 702 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 711 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 687 : null, // France
                    179 => ($provider_valeur == 51) ? 6102 : 5787, // Russie
                    226 => ($provider_valeur == 51) ? 717 : 761, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 694 : null, // Italie
                    82 => ($provider_valeur == 51) ? 690 : null, // Allemagne
                    39 => ($provider_valeur != 51) ?  721 : 680, // Cameroun
                    79 => ($provider_valeur != 51) ?   5101 : 5569, // Gabon
                    50 => ($provider_valeur != 51) ? 684 : 724, // COngo Braza
                    57 => ($provider_valeur != 51) ? 685 : 725, // RDC
                    122 => ($provider_valeur != 51) ? 699 : 727, // Liberia
                    43 => ($provider_valeur != 51) ? 699 : 727, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ? 681 : 721, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3255 : 3275, // Guinnée Buisso 
                    // 223 => ($provider_valeur != 51) ? 3256 : 3756, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3256 : 3276, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3257 : 3277, // Nigeria
                    191 => ($provider_valeur != 51) ? 3258 : 3278, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3259 : 3279, // Koweit
                    212 => ($provider_valeur != 51) ?  3260 : 3280, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3261 : 3281, // Quatar
                    241 => ($provider_valeur != 51) ? 3262 : 3282, // Zambie
                    66 => ($provider_valeur != 51) ?  3263 : 3283, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?  3264 : 3284, // Angola
                    225 => ($provider_valeur != 51) ? 3265 : 3285, // Dubai
                    112 => ($provider_valeur != 51) ? 3266 : 3286, // Keneya
                    45 => ($provider_valeur != 51) ? 3267 : 3287, // China
                    208 => ($provider_valeur != 51) ? 3268 : 3288, // Suisse
                    23 => ($provider_valeur != 51) ?  3269 : 3289, // Belgique
                    173 => ($provider_valeur != 51) ?  3270 : 3290, // Portugal
                    219 => ($provider_valeur != 51) ? 3271 : 3291, // Turquie
                    152 => ($provider_valeur != 51) ? 3272 : 3292, // Hollande
                    100 => ($provider_valeur != 51) ? 3273 : 3293, // Inde
                    69 => ($provider_valeur != 51) ? 3274 : 3294, // Ethiopie
                ],
                // Is RWANDA
                180 => [

                    25 => ($provider_valeur != 51) ? 811 : 766, // RWanda

                    36 => ($provider_valeur != 51) ? 812 : 767, // BF
                    133 => ($provider_valeur != 51) ? 4589 : 4062, // Mali
                    214 => ($provider_valeur != 51) ? 847 : 803, // Togo
                    188 => ($provider_valeur != 51) ? 742 : 798, // Senegal
                    83 => ($provider_valeur != 51) ? 4781 : 4253, // Ghana
                    157 => ($provider_valeur != 51) ? 1330 : 1376, // Niger
                    91 => ($provider_valeur != 51) ? 826 : 781, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 634 : 656, // CIV
                    1 => ($provider_valeur == 51) ? 555 : null, // USA
                    218 => ($provider_valeur == 51) ? 6333 : 6334, // Tunisia
                    146 => ($provider_valeur == 51) ? 791 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 800 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 776 : null, // France
                    179 => ($provider_valeur == 51) ? 3032 : null, // Russie
                    226 => ($provider_valeur == 51) ? 806 : 850, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 784 : 829, // Italie
                    82 => ($provider_valeur == 51) ? 779 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 814 : 769, // Cameroun
                    79 => ($provider_valeur != 51) ? 5096 : 5564, // Gabon
                    50 => ($provider_valeur != 51) ? 818 : 773, // COngo Braza
                    57 => ($provider_valeur != 51) ? 819 : 774, // RDC
                    122 => ($provider_valeur != 51) ? 833 : 788, // Liberia
                    43 => ($provider_valeur != 51) ? 816 : 771, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  815 : 770, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3295 : 3315, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3296 : 3316, // Ouganda 
                    // Voir rwanda
                    // 180 => ($provider_valeur != 51) ? 3256 : 3276, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3297 : 3317, // Nigeria
                    191 => ($provider_valeur != 51) ? 3298 : 3318, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3299 : 3319, // Koweit
                    212 => ($provider_valeur != 51) ?  3300 : 3320, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3301 : 3321, // Quatar
                    241 => ($provider_valeur != 51) ? 3302 : 3322, // Zambie
                    66 => ($provider_valeur != 51) ?  3303 : 3323, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?  3304 : 3324, // Angola
                    225 => ($provider_valeur != 51) ? 3305 : 3325, // Dubai
                    112 => ($provider_valeur != 51) ?  3306 : 3326, // Keneya
                    45 => ($provider_valeur != 51) ? 3307 : 3327, // China
                    208 => ($provider_valeur != 51) ? 3308 : 3328, // Suisse
                    23 => ($provider_valeur != 51) ?  3309 : 3329, // Belgique
                    173 => ($provider_valeur != 51) ?  3310 : 3330, // Portugal
                    219 => ($provider_valeur != 51) ?  3311 : 3331, // Turquie
                    152 => ($provider_valeur != 51) ? 3312 : 3332, // Hollande

                    100 => ($provider_valeur != 51) ? 3313 : 3333, // Inde
                    69 => ($provider_valeur != 51) ? 3314 : 3334, // Ethiopie
                ],
                // Is TANZANIE
                212 => [

                    25 => ($provider_valeur != 51) ? 1304 : 1349, // Benin

                    36 => ($provider_valeur != 51) ? 1305 : 1350, // BF
                    133 => ($provider_valeur != 51) ? 1327 : 1372, // Mali
                    214 => ($provider_valeur != 51) ? 1341 : 1386, // Togo
                    188 => ($provider_valeur != 51) ? 1336 : 1381, // Senegal
                    83 => ($provider_valeur != 51) ? 1318 : 1363, // Ghana
                    157 => ($provider_valeur != 51) ? 1331 : 1376, // Niger
                    91 => ($provider_valeur != 51) ? 1319 : 1364, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 639 : 657, // CIV
                    1 => ($provider_valeur == 51) ? 1345 : null, // USA
                    // Update Id send
                    218 => ($provider_valeur == 51) ? 3074 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 1374 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 1338 : 1383, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 1359 : null, // France
                    179 => ($provider_valeur == 51) ? 6101 : 5786, // Russie
                    226 => ($provider_valeur == 51) ? 1389 : 1344, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1367 : 1322, // Italie
                    82 => ($provider_valeur != 51) ? 1317 : 6038, // Allemagne
                    39 => ($provider_valeur != 51) ? 1307 : 1352, // Cameroun
                    79 => ($provider_valeur != 51) ? 5099 : 5567, // Gabon
                    50 => ($provider_valeur != 51) ? 1311 : 1356, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1312 : 1357, // RDC
                    122 => ($provider_valeur != 51) ? 1326 : 1371, // Liberia
                    43 => ($provider_valeur != 51) ? 1309 : 1354, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  1308 : 1353, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3335 : 3355, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3336 : 3356, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3337 : 3357, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3338 : 3358, // Nigeria
                    191 => ($provider_valeur != 51) ? 3339 : 3359, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3340 : 3360, // Koweit
                    // 212 => ($provider_valeur != 51) ?  3300 : 3320, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3341 : 3361, // Quatar
                    241 => ($provider_valeur != 51) ? 3342 : 3362, // Zambie
                    66 => ($provider_valeur != 51) ?  3343 : 3363, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?  3344 : 3364, // Angola
                    225 => ($provider_valeur != 51) ? 3345 : 3365, // Dubai
                    112 => ($provider_valeur != 51) ?  3346 : 3366, // Keneya
                    45 => ($provider_valeur != 51) ? 3347 : 3367, // China
                    208 => ($provider_valeur != 51) ? 3348 : 3368, // Suisse
                    23 => ($provider_valeur != 51) ?  3349 : 3369, // Belgique
                    173 => ($provider_valeur != 51) ?  3350 : 3370, // Portugal
                    219 => ($provider_valeur != 51) ?  3351 : 3371, // Turquie
                    152 => ($provider_valeur != 51) ? 3352 : 3372, // Hollande

                    100 => ($provider_valeur != 51) ? 3353 : 3373, // Inde
                    69 => ($provider_valeur != 51) ? 3354 : 3374, // Ethiopie
                ],
                // Is ETHIOPIE
                69 => [
                    25 => ($provider_valeur != 51) ? 4518 : 3991, // Benin
                    36 => ($provider_valeur != 51) ? 1395 : 1440, // BF
                    133 => ($provider_valeur != 51) ? 4566 : 4039, // Mali
                    214 => ($provider_valeur != 51) ? 1431 : 1476, // Togo
                    188 => ($provider_valeur != 51) ? 4710 : 4182, // Senegal
                    83 => ($provider_valeur != 51) ? 1408 : 1453, // Ghana
                    157 => ($provider_valeur != 51) ? 1421 : 1466, // Niger
                    91 => ($provider_valeur != 51) ? 1409 : 1454, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 641 : 658, // CIV
                    1 => ($provider_valeur == 51) ? 1435 : null, // USA
                    218 => ($provider_valeur == 51) ? 3090 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 1464 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 1473 : 1428, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 1449 : 1404, // France
                    179 => ($provider_valeur == 51) ? 6091 : 5776, // Russie
                    226 => ($provider_valeur == 51) ? 1479 : 1434, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1457 : 1412, // Italie
                    82 => ($provider_valeur != 51) ? 1407 : 1452, // Allemagne
                    39 => ($provider_valeur != 51) ? 1397 : 1442, // Cameroun
                    79 => ($provider_valeur != 51) ? 5079 : 5547, // Gabon
                    50 => ($provider_valeur != 51) ? 1401 : 1446, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1402 : 1447, // RDC
                    122 => ($provider_valeur != 51) ? 1416 : 1461, // Liberia
                    43 => ($provider_valeur != 51) ? 1399 : 1444, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  1398 : 1443, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3375 : 3395, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3376 : 3396, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3377 : 3397, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3378 : 3398, // Nigeria
                    191 => ($provider_valeur != 51) ? 3379 : 3399, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3380 : 3400, // Koweit
                    212 => ($provider_valeur != 51) ?  3381 : 3401, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3382 : 3402, // Quatar
                    241 => ($provider_valeur != 51) ? 3383 : 3403, // Zambie
                    66 => ($provider_valeur != 51) ?  3384 : 3404, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?   3385 : 3405, // Angola
                    225 => ($provider_valeur != 51) ? 3386 : 3406, // Dubai
                    112 => ($provider_valeur != 51) ?  3387 : 3407, // Keneya
                    45 => ($provider_valeur != 51) ? 3388 : 3408, // China
                    208 => ($provider_valeur != 51) ? 3389 : 3409, // Suisse
                    23 => ($provider_valeur != 51) ?  3390 : 3410, // Belgique
                    173 => ($provider_valeur != 51) ?  3391 : 3411, // Portugal
                    219 => ($provider_valeur != 51) ?  3392 : 3412, // Turquie
                    152 => ($provider_valeur != 51) ? 3393 : 3413, // Hollande

                    100 => ($provider_valeur != 51) ? 3394 : 3414, // Inde
                    // 69 => ($provider_valeur != 51) ? 3354 : 3374, // Ethiopie
                ],
                // Is KENEYA
                146 => [
                    25 => ($provider_valeur != 51) ?  1484 : 1529, // Benin
                    36 => ($provider_valeur != 51) ? 1485 : 1530, // BF
                    133 => ($provider_valeur != 51) ? 4577 : 4050, // Mali
                    214 => ($provider_valeur != 51) ? 1521 : 1566, // Togo
                    188 => ($provider_valeur != 51) ? 1516 : 1561, // Senegal
                    83 => ($provider_valeur != 51) ? 1498 : 1543, // Ghana
                    157 => ($provider_valeur != 51) ? 1511 : 1556, // Niger
                    91 => ($provider_valeur != 51) ? 1499 : 1544, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 645 : 659, // CIV
                    1 => ($provider_valeur == 51) ? 1525 : null, // USA
                    218 => ($provider_valeur == 51) ? 3080 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 1554 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 1508 : 1563, // ESPAGNE
                    74 => ($provider_valeur != 51) ?  1494 : 1539, // France
                    179 => ($provider_valeur == 51) ? 6094 : 5779, // Russie
                    226 => ($provider_valeur == 51) ? 1569 : 1524, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1547 : 1502, // Italie
                    82 => ($provider_valeur != 51) ? 1497 : 1542, // Allemagne
                    39 => ($provider_valeur != 51) ? 1487 : 1532, // Cameroun
                    79 => ($provider_valeur != 51) ? 5087 : 5555, // Gabon
                    50 => ($provider_valeur != 51) ? 1491 : 1536, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1492 : 1537, // RDC
                    122 => ($provider_valeur != 51) ? 1506 : 1551, // Liberia
                    43 => ($provider_valeur != 51) ? 1489 : 1534, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  1488 : 1533, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3415 : 3435, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3416 : 3436, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3417 : 3437, // Rwanda 
                    158 => ($provider_valeur != 51) ?  3418 : 3438, // Nigeria
                    191 => ($provider_valeur != 51) ? 3419 : 3439, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3420 : 3440, // Koweit
                    212 => ($provider_valeur != 51) ?  3421 : 3441, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3422 : 3442, // Quatar
                    241 => ($provider_valeur != 51) ? 3423 : 3443, // Zambie
                    66 => ($provider_valeur != 51) ?  3424 : 3444, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?   3425 : 3445, // Angola
                    225 => ($provider_valeur != 51) ? 3426 : 3446, // Dubai
                    45 => ($provider_valeur != 51) ? 3427 : 3447, // China
                    208 => ($provider_valeur != 51) ? 3428 : 3448, // Suisse
                    23 => ($provider_valeur != 51) ?  3429 : 3449, // Belgique
                    173 => ($provider_valeur != 51) ?  3430 : 3450, // Portugal
                    219 => ($provider_valeur != 51) ?  3431 : 3451, // Turquie
                    152 => ($provider_valeur != 51) ? 3432 : 3452, // Hollande

                    100 => ($provider_valeur != 51) ? 3433 : 3453, // Inde
                    69 => ($provider_valeur != 51) ? 3434 : 3454, // Ethiopie
                ],
                // Is ZAMBIE
                241 => [
                    25 => ($provider_valeur != 51) ? 1574 : 1619, // Benin
                    36 => ($provider_valeur != 51) ? 1575 : 1620, // BF
                    // Ajouter vise versa
                    133 => ($provider_valeur != 51) ? 1597 : 1620, // Mali
                    214 => ($provider_valeur != 51) ? 1611 : 1634, // Togo
                    188 => ($provider_valeur != 51) ? 1606 : 1651, // Senegal
                    83 => ($provider_valeur != 51) ? 1588 : 1633, // Ghana
                    157 => ($provider_valeur != 51) ? 1601 : 1646, // Niger
                    91 => ($provider_valeur != 51) ? 1589 : 1634, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 637 : 660, // CIV
                    1 => ($provider_valeur == 51) ? 1615 : null, // USA
                    218 => ($provider_valeur == 51) ? 3076 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 1644 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 1653 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 1629 : null, // France
                    // Update Id send
                    179 => ($provider_valeur == 51) ? 3038 : null, // Ruissie
                    226 => ($provider_valeur == 51) ? 1695 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1637 : 1592, // Italie
                    82 => ($provider_valeur == 51) ? 1632 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 1577 : 1622, // Cameroun
                    79 => ($provider_valeur != 51) ? 3103 : 3124, // Gabon
                    50 => ($provider_valeur != 51) ? 1581 : 1626, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1582 : 1627, // RDC
                    122 => ($provider_valeur != 51) ? 1596 : 1641, // Liberia
                    43 => ($provider_valeur != 51) ? 1579 : 1624, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  1578 : 1623, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3455 : 3475, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3456 : 3476, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3457 : 3477, // Rwanda 
                    158 => ($provider_valeur != 51) ?  3458 : 3478, // Nigeria
                    191 => ($provider_valeur != 51) ? 3459 : 3479, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3460 : 3480, // Koweit
                    212 => ($provider_valeur != 51) ?  3461 : 3481, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3462 : 3482, // Quatar
                    66 => ($provider_valeur != 51) ?  3463 : 3483, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?   3464 : 3484, // Angola
                    225 => ($provider_valeur != 51) ? 3465 : 3485, // Dubai
                    112 => ($provider_valeur != 51) ?  3466 : 3486, // Keneya
                    45 => ($provider_valeur != 51) ? 3467 : 3487, // China
                    208 => ($provider_valeur != 51) ? 3468 : 3488, // Suisse
                    23 => ($provider_valeur != 51) ?  3469 : 3489, // Belgique
                    173 => ($provider_valeur != 51) ?  3470 : 3490, // Portugal
                    219 => ($provider_valeur != 51) ?  3471 : 3491, // Turquie
                    152 => ($provider_valeur != 51) ? 3472 : 3492, // Hollande

                    100 => ($provider_valeur != 51) ? 3473 : 3493, // Inde
                    69 => ($provider_valeur != 51) ? 3474 : 3494, // Ethiopie
                ],
                // Is ANGOLA
                8 => [

                    25 => ($provider_valeur != 51) ? 1664 : 1709, // Benin
                    36 => ($provider_valeur != 51) ? 1665 : 1710, // BF
                    133 => ($provider_valeur != 51) ? 4554 : 4027, // Mali
                    214 => ($provider_valeur != 51) ? 1679 : 1724, // Togo
                    188 => ($provider_valeur != 51) ? 1674 : 1719, // Senegal
                    83 => ($provider_valeur != 51) ? 1656 : 1701, // Ghana
                    157 => ($provider_valeur != 51) ? 1669 : 1714, // Niger
                    91 => ($provider_valeur != 51) ? 1679 : 1724, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 643 : 661, // CIV
                    1 => ($provider_valeur == 51) ? 1705 : null, // USA
                    218 => ($provider_valeur == 51) ? 3078 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 1734 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 1743 : null, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 1674 : 1719, // France
                    179 => ($provider_valeur == 51) ? 6088 : 5773, // Russie
                    226 => ($provider_valeur == 51) ? 1749 : 1704, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1727 : 1682, // Italie
                    82 => ($provider_valeur != 51) ? 1677 : 1722, // Allemagne
                    39 => ($provider_valeur != 51) ? 1667 : 1712, // Cameroun
                    79 => ($provider_valeur != 51) ? 3105 : 3126, // Gabon
                    50 => ($provider_valeur != 51) ? 1671 : 1716, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1672 : 1717, // RDC
                    122 => ($provider_valeur != 51) ? 1686 : 1731, // Liberia
                    43 => ($provider_valeur != 51) ? 1669 : 1714, // Thacd
                    42 => ($provider_valeur != 51) ?  1668 : 1713, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3495 : 3515, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3496 : 3516, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3497 : 3517, // Rwanda 
                    158 => ($provider_valeur != 51) ?  3498 : 3518, // Nigeria
                    191 => ($provider_valeur != 51) ? 3499 : 3519, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3500 : 3520, // Koweit
                    212 => ($provider_valeur != 51) ?  3501 : 3521, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3502 : 3522, // Quatar
                    241 => ($provider_valeur != 51) ? 3503 : 3523, // Zambie
                    66 => ($provider_valeur != 51) ?  3504 : 3524, // Guinnée Equatoriale
                    // 8 => ($provider_valeur != 51) ?   3464 : 3484, // Angola
                    225 => ($provider_valeur != 51) ? 3505 : 3525, // Dubai
                    112 => ($provider_valeur != 51) ?  3506 : 3526, // Keneya
                    45 => ($provider_valeur != 51) ? 3507 : 3527, // China
                    208 => ($provider_valeur != 51) ? 3508 : 3528, // Suisse
                    23 => ($provider_valeur != 51) ?  3509 : 3529, // Belgique
                    173 => ($provider_valeur != 51) ?  3510 : 3530, // Portugal
                    219 => ($provider_valeur != 51) ?  3511 : 3531, // Turquie
                    152 => ($provider_valeur != 51) ? 3512 : 3532, // Hollande

                    100 => ($provider_valeur != 51) ? 3513 : 3533, // Inde
                    69 => ($provider_valeur != 51) ? 3514 : 3534, // Ethiopie
                ],
                // Is Mauritanie
                8 => [

                    25 => ($provider_valeur != 51) ?  4534 : 4007, // Benin
                    36 => ($provider_valeur != 51) ? 4630 : 4102, // BF
                    133 => ($provider_valeur != 51) ? 4582 : 4055, // Mali
                    214 => ($provider_valeur != 51) ? 1679 : 1724, // Togo
                    188 => ($provider_valeur != 51) ? 1674 : 1719, // Senegal
                    83 => ($provider_valeur != 51) ? 1656 : 1701, // Ghana
                    157 => ($provider_valeur != 51) ? 1669 : 1714, // Niger
                    91 => ($provider_valeur != 51) ? 1679 : 1724, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 643 : 661, // CIV
                    1 => ($provider_valeur == 51) ? 1705 : null, // USA
                    218 => ($provider_valeur == 51) ? 3078 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 1734 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 1743 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 1719 : null, // France
                    179 => ($provider_valeur == 51) ? 3040 : null, // Russie
                    226 => ($provider_valeur == 51) ? 1749 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1727 : null, // Italie
                    82 => ($provider_valeur == 51) ? 1722 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 1667 : 1712, // Cameroun
                    79 => ($provider_valeur != 51) ? 5091 : 5559, // Gabon
                    50 => ($provider_valeur != 51) ?  5127 : 5523, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1672 : 1717, // RDC
                    122 => ($provider_valeur != 51) ? 1686 : 1731, // Liberia
                    43 => ($provider_valeur != 51) ? 5235 : 5415, // Thacd
                    42 => ($provider_valeur != 51) ?  1668 : 1713, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3495 : 3515, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3496 : 3516, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3497 : 3517, // Rwanda 
                    158 => ($provider_valeur != 51) ?  3498 : 3518, // Nigeria
                    191 => ($provider_valeur != 51) ? 3499 : 3519, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3500 : 3520, // Koweit
                    212 => ($provider_valeur != 51) ?  3501 : 3521, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3502 : 3522, // Quatar
                    241 => ($provider_valeur != 51) ? 3503 : 3523, // Zambie
                    66 => ($provider_valeur != 51) ?  3504 : 3524, // Guinnée Equatoriale
                    225 => ($provider_valeur != 51) ? 3505 : 3525, // Dubai
                    112 => ($provider_valeur != 51) ?  3506 : 3526, // Keneya
                    45 => ($provider_valeur != 51) ? 3507 : 3527, // China
                    208 => ($provider_valeur != 51) ? 3508 : 3528, // Suisse
                    23 => ($provider_valeur != 51) ?  3509 : 3529, // Belgique
                    173 => ($provider_valeur != 51) ?  3510 : 3530, // Portugal
                    219 => ($provider_valeur != 51) ?  3511 : 3531, // Turquie
                    152 => ($provider_valeur != 51) ? 3512 : 3532, // Hollande

                    100 => ($provider_valeur != 51) ? 3513 : 3533, // Inde
                    69 => ($provider_valeur != 51) ? 3514 : 3534, // Ethiopie
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif (robus()->country->name_groupe == 'ASIE') {

            $countriesLevels = [
                // Is KOWEIT
                116 => [


                    25 => ($provider_valeur != 51) ? 2609 : 2564, // Benin

                    36 => ($provider_valeur != 51) ? 2610 : 2565, // BF
                    133 => ($provider_valeur != 51) ? 4578 : 4051, // Mali
                    214 => ($provider_valeur != 51) ? 2646 : 2601, // Togo
                    188 => ($provider_valeur != 51) ? 2641 : 2596, // Senegal
                    83 => ($provider_valeur != 51) ? 2623 : 2578, // Ghana
                    157 => ($provider_valeur != 51) ? 2636 : 2591, // Niger
                    91 => ($provider_valeur != 51) ? 2624 : 2579, // Guinne conakry
                    107 => ($provider_valeur != 51) ?  638 : 668, // CIV
                    1 => ($provider_valeur != 51) ? 731 : 2560, // USA
                    // Upodate ID send
                    218 => ($provider_valeur == 51) ? 3073 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2589 : null, // Maroc
                    199 => ($provider_valeur == 51) ?  2642 : 2598, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 2574 : null, // France
                    179 => ($provider_valeur == 51) ? 6095 : 5780, // Russie
                    226 => ($provider_valeur == 51) ? 2604 : 2649, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2582 : 2626, // Italie
                    82 => ($provider_valeur != 51) ?  2621 : 2577, // Allemagne
                    39 => ($provider_valeur != 51) ? 2612 : 2567, // Cameroun
                    79 => ($provider_valeur != 51) ? 5088 : 5556, // Gabon
                    50 => ($provider_valeur != 51) ? 2616 : 2571, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2617 : 2572, // RDC
                    122 => ($provider_valeur != 51) ? 2630 : 2586, // Liberia
                    43 => ($provider_valeur != 51) ? 2613 : 2569, // Thacd
                    42 => ($provider_valeur != 51) ? 2612 : 2568, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3775 : 3795, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3776 : 3796, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3777 : 3797, // Rwanda 
                    158 => ($provider_valeur != 51) ?  3778 : 3798, // Nigeria
                    191 => ($provider_valeur != 51) ? 3779 : 3799, // Sierria Leone
                    // 116 => ($provider_valeur != 51) ? 3500 : 3520, // Koweit
                    212 => ($provider_valeur != 51) ?  3780 : 3800, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3781 : 3801, // Quatar
                    241 => ($provider_valeur != 51) ? 3782 : 3802, // Zambie
                    66 => ($provider_valeur != 51) ?  3783 : 3803, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?   3784 : 3804, // Angola
                    225 => ($provider_valeur != 51) ? 3785 : 3805, // Dubai
                    112 => ($provider_valeur != 51) ?  3786 : 3806, // Keneya
                    45 => ($provider_valeur != 51) ? 3787 : 3807, // China
                    208 => ($provider_valeur != 51) ? 3788 : 3808, // Suisse
                    23 => ($provider_valeur != 51) ?  3789 : 3809, // Belgique
                    173 => ($provider_valeur != 51) ?  3790 : 3810, // Portugal
                    219 => ($provider_valeur != 51) ?  3791 : 3811, // Turquie
                    152 => ($provider_valeur != 51) ? 2635 : 2590, // Hollande
                    100 => ($provider_valeur != 51) ? 3793 : 3813, // Inde
                    69 => ($provider_valeur != 51) ? 3794 : 3814, // Ethiopie
                ],
                // Is QATAR
                175 => [

                    25 => ($provider_valeur != 51) ? 2654 : 2699, // Benin
                    36 => ($provider_valeur != 51) ? 2655 : 2700, // BF
                    133 => ($provider_valeur != 51) ? 4588 : 4061, // Mali
                    214 => ($provider_valeur != 51) ? 2691 : 2736, // Togo
                    188 => ($provider_valeur != 51) ? 2686 : 2731, // Senegal
                    83 => ($provider_valeur != 51) ? 2668 : 2713, // Ghana
                    157 => ($provider_valeur != 51) ? 2681 : 2726, // Niger
                    91 => ($provider_valeur != 51) ? 2669 : 2714, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 3010 : 669, // CIV
                    1 => ($provider_valeur == 51) ? 2695 : null, // USA
                    218 => ($provider_valeur == 51) ? 3075 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2724 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2688 : 2733, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 2664 : 2709, // France
                    179 => ($provider_valeur == 51) ? 6099 : 5784, // Russie
                    226 => ($provider_valeur == 51) ? 2739 : 2694, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2717 : 2672, // Italie
                    82 => ($provider_valeur != 51) ? 2667 : 2712, // Allemagne
                    39 => ($provider_valeur != 51) ? 2657 : 2702, // Cameroun
                    79 => ($provider_valeur != 51) ? 5095 : 5563, // Gabon
                    50 => ($provider_valeur != 51) ? 2661 : 2706, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2662 : 2707, // RDC
                    122 => ($provider_valeur != 51) ? 2676 : 2721, // Liberia
                    43 => ($provider_valeur != 51) ? 2659 : 2704, // Thacd
                    42 => ($provider_valeur != 51) ?  2658 : 2703, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3815 : 3835, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3816 : 3836, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3817 : 3837, // Rwanda 
                    158 => ($provider_valeur != 51) ?  3818 : 3838, // Nigeria
                    191 => ($provider_valeur != 51) ?  3819 : 3839, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3820 : 3840, // Koweit
                    212 => ($provider_valeur != 51) ?  3821 : 3841, // Tanzanie
                    241 => ($provider_valeur != 51) ? 3822 : 3842, // Zambie
                    66 => ($provider_valeur != 51) ?  3823 : 3843, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?   3824 : 3844, // Angola
                    225 => ($provider_valeur != 51) ? 3825 : 3845, // Dubai
                    112 => ($provider_valeur != 51) ?  3826 : 3846, // Keneya
                    45 => ($provider_valeur != 51) ? 3827 : 3847, // China
                    208 => ($provider_valeur != 51) ? 3828 : 3848, // Suisse
                    23 => ($provider_valeur != 51) ?  3829 : 3849, // Belgique
                    173 => ($provider_valeur != 51) ?  3830 : 3850, // Portugal
                    219 => ($provider_valeur != 51) ?  3831 : 3851, // Turquie
                    152 => ($provider_valeur != 51) ?  3828 : 3848, // Hollande
                    100 => ($provider_valeur != 51) ? 3833 : 3853, // Inde
                    69 => ($provider_valeur != 51) ? 3834 : 3854, // Ethiopie
                ],
                // Is DUBAI
                225 => [
                    25 => ($provider_valeur != 51) ? 2744 : 2789, // Dubai

                    36 => ($provider_valeur != 51) ? 2745 : 2790, // BF
                    133 => ($provider_valeur != 51) ? 2767 : 2812, // Mali
                    214 => ($provider_valeur != 51) ? 2781 : 2826, // Togo
                    188 => ($provider_valeur != 51) ? 2776 : 2821, // Senegal
                    83 => ($provider_valeur != 51) ? 2758 : 2803, // Ghana
                    157 => ($provider_valeur != 51) ? 2771 : 2816, // Niger
                    91 => ($provider_valeur != 51) ? 2759 : 2804, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 644 : 670, // CIV
                    1 => ($provider_valeur == 51) ? 2785 : null, // USA
                    218 => ($provider_valeur == 51) ? 3079 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2814 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2823 : null, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 2754 : 2799, // France
                    179 => ($provider_valeur == 51) ? 6103 : 5788, // Russie
                    226 => ($provider_valeur == 51) ? 2829 : 2784, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2807 : 2762, // Italie
                    82 => ($provider_valeur != 51) ? 2757 : 2802, // Allemagne
                    39 => ($provider_valeur != 51) ? 2747 : 2792, // Cameroun
                    79 => ($provider_valeur != 51) ? 5102 : 5570, // Gabon
                    50 => ($provider_valeur != 51) ? 2751 : 2796, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2752 : 2797, // RDC
                    122 => ($provider_valeur != 51) ? 2766 : 2811, // Liberia
                    43 => ($provider_valeur != 51) ? 2749 : 2794, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  2748 : 2793, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3855 : 3875, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ?  3856 : 3876, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3857 : 3877, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3858 : 3878, // Nigeria
                    191 => ($provider_valeur != 51) ?  3859 : 3879, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3860 : 3880, // Koweit
                    212 => ($provider_valeur != 51) ?  3861 : 3881, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3862 : 3882, // Quatar
                    241 => ($provider_valeur != 51) ? 3863 : 3883, // Zambie
                    66 => ($provider_valeur != 51) ?  3864 : 3884, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?   3865 : 3885, // Angola
                    // 225 => ($provider_valeur != 51) ? 3345 : 3365, // Dubai
                    112 => ($provider_valeur != 51) ?  3866 : 3886, // Keneya
                    45 => ($provider_valeur != 51) ? 3867 : 3887, // China
                    208 => ($provider_valeur != 51) ? 3868 : 3888, // Suisse
                    23 => ($provider_valeur != 51) ?  3869 : 3889, // Belgique
                    173 => ($provider_valeur != 51) ?  3870 : 3890, // Portugal
                    219 => ($provider_valeur != 51) ?  3871 : 3891, // Turquie
                    152 => ($provider_valeur != 51) ?  3868 : 3888, // Hollande

                    100 => ($provider_valeur != 51) ? 3873 : 3893, // Inde
                    69 => ($provider_valeur != 51) ? 3874 : 3894, // Ethiopie
                ],
                // Is CHINE
                45 => [

                    25 => ($provider_valeur != 51) ? 2834 : 2879, // Benin
                    36 => ($provider_valeur != 51) ? 2835 : 2880, // BF
                    133 => ($provider_valeur != 51) ? 4561 : 4034, // Mali
                    214 => ($provider_valeur != 51) ? 2871 : 2916, // Togo
                    188 => ($provider_valeur != 51) ? 4705 : 4177, // Senegal
                    83 => ($provider_valeur != 51) ? 2848 : 2893, // Ghana
                    157 => ($provider_valeur != 51) ? 2861 : 2906, // Niger
                    91 => ($provider_valeur != 51) ? 2849 : 2894, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 646 : 671, // CIV
                    1 => ($provider_valeur == 51) ? 2875 : null, // USA
                    218 => ($provider_valeur == 51) ? 3081 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2904 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2903 : null, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 2844 : 2889, // France
                    179 => ($provider_valeur == 51) ? 6090 : 5775, // Russie
                    226 => ($provider_valeur == 51) ? 2919 : 2874, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2897 : 2852, // Italie
                    82 => ($provider_valeur != 51) ? 2847 : 2892, // Allemagne
                    39 => ($provider_valeur != 51) ? 2837 : 2882, // Cameroun
                    79 => ($provider_valeur != 51) ? 5074 : 5542, // Gabon
                    50 => ($provider_valeur != 51) ? 2841 : 2886, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2842 : 2887, // RDC
                    122 => ($provider_valeur != 51) ? 2856 : 2901, // Liberia
                    43 => ($provider_valeur != 51) ? 2839 : 2884, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  2838 : 2883, // Afrique centrale
                    // 45 => ($provider_valeur != 51) ? 3149 : 3169, // China

                    // New countries
                    92 => ($provider_valeur != 51) ? 3895 : 3915, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ?  3896 : 3916, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3897 : 3917, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3898 : 3918, // Nigeria
                    191 => ($provider_valeur != 51) ?  3899 : 3919, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3900 : 3920, // Koweit
                    212 => ($provider_valeur != 51) ?  3901 : 3921, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3902 : 3922, // Quatar
                    241 => ($provider_valeur != 51) ? 3903 : 3923, // Zambie
                    66 => ($provider_valeur != 51) ?  3904 : 3924, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?   3905 : 3925, // Angola
                    225 => ($provider_valeur != 51) ? 3906 : 3926, // Dubai
                    112 => ($provider_valeur != 51) ?  3907 : 3927, // Keneya
                    // 45 => ($provider_valeur != 51) ? 3867 : 3887, // China
                    208 => ($provider_valeur != 51) ? 3908 : 3928, // Suisse
                    23 => ($provider_valeur != 51) ?  3909 : 3929, // Belgique
                    173 => ($provider_valeur != 51) ?  3910 : 3930, // Portugal
                    219 => ($provider_valeur != 51) ?  3911 : 3931, // Turquie
                    152 => ($provider_valeur != 51) ?  2860 : 2905, // Hollande

                    100 => ($provider_valeur != 51) ? 3913 : 3933, // Inde
                    69 => ($provider_valeur != 51) ? 3914 : 3934, // Ethiopie
                ],
                // Is INDE
                100 => [

                    25 => ($provider_valeur != 51) ? 2924 : 2969, // Benin
                    36 => ($provider_valeur != 51) ? 2925 : 2970, // BF
                    133 => ($provider_valeur != 51) ? 2947 : 2992, // Mali
                    214 => ($provider_valeur != 51) ? 2961 : 3006, // Togo
                    188 => ($provider_valeur != 51) ? 2956 : 3001, // Senegal
                    83 => ($provider_valeur != 51) ? 2938 : 2983, // Ghana
                    157 => ($provider_valeur != 51) ? 2951 : 2996, // Niger
                    91 => ($provider_valeur != 51) ? 2939 : 2984, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 640 : 672, // CIV
                    1 => ($provider_valeur == 51) ? 2965 : null, // USA
                    218 => ($provider_valeur == 51) ? 3088 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2994 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 3003 : null, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 2934 : 2979, // France
                    179 => ($provider_valeur == 51) ? 6093 : 5778, // Russie
                    226 => ($provider_valeur == 51) ? 3009 : 2964, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2987 : 2942, // Italie
                    82 => ($provider_valeur != 51) ? 2937 : 2982, // Allemagne
                    39 => ($provider_valeur != 51) ? 2927 : 2972, // Cameroun
                    79 => ($provider_valeur != 51) ? 5084 : 5552, // Gabon
                    50 => ($provider_valeur != 51) ? 2931 : 2976, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2932 : 2977, // RDC
                    122 => ($provider_valeur != 51) ? 2946 : 2991, // Liberia
                    43 => ($provider_valeur != 51) ? 2929 : 2974, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  2928 : 2973, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3935 : 3955, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ?  3936 : 3956, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3937 : 3957, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3938 : 3958, // Nigeria
                    191 => ($provider_valeur != 51) ?  3939 : 3959, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3940 : 3960, // Koweit
                    212 => ($provider_valeur != 51) ?  3941 : 3961, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3942 : 3962, // Quatar
                    241 => ($provider_valeur != 51) ? 3943 : 3963, // Zambie
                    66 => ($provider_valeur != 51) ?  3944 : 3964, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?   3945 : 3965, // Angola
                    225 => ($provider_valeur != 51) ? 3946 : 3966, // Dubai
                    112 => ($provider_valeur != 51) ?  3947 : 3967, // Keneya
                    45 => ($provider_valeur != 51) ?  3948 : 3968, // China
                    208 => ($provider_valeur != 51) ? 3949 : 3969, // Suisse
                    23 => ($provider_valeur != 51) ?  3950 : 3970, // Belgique
                    173 => ($provider_valeur != 51) ?  3951 : 3971, // Portugal
                    219 => ($provider_valeur != 51) ?  3952 : 3972, // Turquie
                    152 => ($provider_valeur != 51) ?  2950 : 2995, // Hollande

                    69 => ($provider_valeur != 51) ? 3954 : 3974, // Ethiopie
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } else {
            return null;
        }


        $robusId = robus()->country->id; // Pour éviter les appels répétitifs

        if (isset($countriesLevels[$robusId])) {

            $specificCountry = $countriesLevels[$robusId][$countrie->id];

            if ($specificCountry) {
                $levelValeur = $specificCountry;
            } else {
                return null; // La levelValeur pour $provider_valeur n'est pas définie pour $countrie
            }
        } else {
            return null; // Aucune correspondance pour $countrie
        }

        return  LevelCountry::whereId($levelValeur)->first();
    }

    public function getRobusPercentageInterWeb($countrie)
    {


        $countriesLevels = [

            // Web Visitors
            25 =>  605 ?? null, // BENIN
            36 =>  606 ?? null, // BF
            133 =>  607 ?? null, // Mali
            188 =>  608 ?? null, // Senegal
            214 =>  609 ?? null, // Togo
            39 =>  610 ?? null, // Cameroun
            91 =>  611 ?? null, // Guinne conakry
            157 =>  612 ?? null, // Niger
            57 =>  613 ?? null, // RDC
            50 =>  614 ?? null, // Congo Braza
            122 =>  615 ?? null, // Liberia
            83 =>  616 ?? null, // Ghana
            42 =>  617 ?? null, // Afrique centrale
            79 =>  618 ?? null, // Gabon
            43 =>  619 ?? null, // Thacd
            66 =>  620 ?? null, // Guinne Equa
            69 =>  621 ?? null, // Ethiopie
            223 =>  622 ?? null, // Ouganda
            191 =>  623 ?? null, // Serria Lionne
            180 =>  624 ?? null, // Rwanda
            112 =>  625 ?? null, // Keneya
            92 =>  626 ?? null, // Guinnéé Bissau
            80 =>  627 ?? null, // Gambie
            137 =>  628 ?? null, // Mauritanie
            37 =>  629 ?? null, // Burundi
            8 =>  630 ?? null, // Angola
            212 =>  631 ?? null, // Tanzanie

        ];

        if (isset($countriesLevels[$countrie])) {
            $levelValeur = $countriesLevels[$countrie];
        } else {
            return null; // Aucune correspondance pour $countrie
        }

        return  LevelCountry::whereId($levelValeur)->select('percent', 'percent_admin')->first();
    }
}
