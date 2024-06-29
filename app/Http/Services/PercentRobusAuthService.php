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
                    36 => ($provider_valeur != 51) ? 2 : 37, // BF
                    133 => ($provider_valeur != 51) ? 3 : 38, // Mali
                    214 => ($provider_valeur != 51) ? 4 : 39, // Togo
                    188 => ($provider_valeur != 51) ? 5 : 40, // Senegal
                    83 => ($provider_valeur != 51) ? 6 : 41, // Ghana
                    157 => ($provider_valeur != 51) ? 7 : 42, // Niger
                    91 => ($provider_valeur != 51) ? 8 : 43, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 1 : 44, // CIV
                    1 => ($provider_valeur != 51) ? 248 : 241, // USA
                    218 => ($provider_valeur != 51) ? 264 : 292, // Tunisia
                    146 => ($provider_valeur != 51) ? 278 : 302, // Maroc
                    199 => ($provider_valeur != 51) ? 435 : 312, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 467 : 355, // France
                    179 => ($provider_valeur != 51) ? 531 : 419, // Russie
                    226 => ($provider_valeur != 51) ? 499 : 387, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 515 : 403, // Italie
                    82 => ($provider_valeur != 51) ? 483 : 371, // Allemagne
                    39 => ($provider_valeur != 51) ? 129 : 178, // Cameroun
                    79 => ($provider_valeur != 51) ? 130 : 179, // Gabon
                    50 => ($provider_valeur != 51) ? 131 : 180, // COngo Braza
                    57 => ($provider_valeur != 51) ? 132 : 181, // RDC
                    122 => ($provider_valeur != 51) ? 133 : 182, // Liberia
                    43 => ($provider_valeur != 51) ? 134 : 183, // Thacd
                    42 => ($provider_valeur != 51) ? 135 : 184, // Afrique centrale
                    // New countries
                    92 => ($provider_valeur != 51) ? 1754 : 1799, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 718 : 677, // Ouganda
                    158 => ($provider_valeur != 51) ? 1934 : 1979, // Nigeria
                    191 => ($provider_valeur != 51) ? 1844 : 1889, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2609 : 2564, // Koweit
                    212 => ($provider_valeur != 51) ? 1304 : 1349, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2654 : 2699, // Quatar
                    241 => ($provider_valeur != 51) ? 1574 : 1619, // Zambie
                    100 => ($provider_valeur != 51) ? 2924 : 2969, // Inde
                    69 => ($provider_valeur != 51) ? 1394 : 1439, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2069 : 2024, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1664 : 1709, // Angola
                    225 => ($provider_valeur != 51) ? 2744 : 2789, // Dubai
                    112 => ($provider_valeur != 51) ? 1484 : 1529, // Keneya
                    45 => ($provider_valeur != 51) ? 2834 : 2879, // China
                    208 => ($provider_valeur != 51) ? 2114 : 2159, // Suisse
                    23 => ($provider_valeur != 51) ? 2204 : 2249, // Belgique
                    173 => ($provider_valeur != 51) ? 2294 : 2339, // Portugal
                    219 => ($provider_valeur != 51) ? 2384 : 2429, // Turquie
                    152 => ($provider_valeur != 51) ? 2474 : 2519, // Hollande
                    180 => ($provider_valeur != 51) ? 811 : 766, // RWanda
                ],
                // Is BF
                36 =>  [
                    25 => ($provider_valeur != 51) ? 2 : 37, // Benin
                    133 => ($provider_valeur != 51) ? 9 : 45, // Mali
                    214 => ($provider_valeur != 51) ? 10 : 46, // Togo
                    188 => ($provider_valeur != 51) ? 11 : 47, // Senegal
                    83 => ($provider_valeur != 51) ? 12 : 48, // Ghana
                    157 => ($provider_valeur != 51) ? 13 : 49, // Niger
                    91 => ($provider_valeur != 51) ? 14 : 50, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 15 : 51, // CIV
                    1 => ($provider_valeur != 51) ? 249 : 242, // USA
                    218 => ($provider_valeur != 51) ? 265 : 293, // Tunisia
                    146 => ($provider_valeur != 51) ? 279 : 302, // Maroc
                    199 => ($provider_valeur != 51) ? 436 : 313, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 468 : 356, // France
                    179 => ($provider_valeur != 51) ? 532 : 420, // Russie
                    226 => ($provider_valeur != 51) ? 500 : 388, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 516 : 404, // Italie
                    82 => ($provider_valeur != 51) ? 484 : 372, // Allemagne
                    39 => ($provider_valeur != 51) ? 136 : 185, // Cameroun
                    79 => ($provider_valeur != 51) ? 137 : 186, // Gabon
                    50 => ($provider_valeur != 51) ? 138 : 187, // COngo Braza
                    57 => ($provider_valeur != 51) ? 139 : 188, // RDC
                    122 => ($provider_valeur != 51) ? 140 : 189, // Liberia
                    43 => ($provider_valeur != 51) ? 141 : 190, // Thacd
                    42 => ($provider_valeur != 51) ? 142 : 191, // Afrique centrale
                    // New countries
                    92 => ($provider_valeur != 51) ? 1755 : 1800, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 719 : 678, // Ouganda
                    158 => ($provider_valeur != 51) ? 1935 : 1980, // Nigeria
                    191 => ($provider_valeur != 51) ? 1845 : 1890, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2610 : 2565, // Koweit
                    212 => ($provider_valeur != 51) ? 1305 : 1350, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2655 : 2700, // Quatar
                    241 => ($provider_valeur != 51) ? 1575 : 1620, // Zambie
                    100 => ($provider_valeur != 51) ? 2925 : 2970, // Inde
                    69 => ($provider_valeur != 51) ? 1395 : 1440, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2070 : 2025, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1665 : 1710, // Angola
                    225 => ($provider_valeur != 51) ? 2745 : 2790, // Dubai
                    112 => ($provider_valeur != 51) ? 1485 : 1530, // Keneya
                    45 => ($provider_valeur != 51) ? 2835 : 2880, // China
                    208 => ($provider_valeur != 51) ? 2115 : 2160, // Suisse
                    23 => ($provider_valeur != 51) ? 2205 : 2250, // Belgique
                    173 => ($provider_valeur != 51) ? 2295 : 2340, // Portugal
                    219 => ($provider_valeur != 51) ? 2385 : 2430, // Turquie
                    152 => ($provider_valeur != 51) ? 2475 : 2520, // Hollande
                    180 => ($provider_valeur != 51) ? 812 : 767, // RWanda

                ],
                // Mali
                133 =>  [
                    25 => ($provider_valeur != 51) ? 3 : 38, // Benin
                    214 => ($provider_valeur != 51) ? 16 : 52, // Togo
                    36 => ($provider_valeur != 51) ? 9 : 45, // BF
                    188 => ($provider_valeur != 51) ? 17 : 53, // Senegal
                    83 => ($provider_valeur != 51) ? 18 : 54, // Ghana
                    157 => ($provider_valeur != 51) ? 19 : 55, // Niger
                    91 => ($provider_valeur != 51) ? 20 : 56, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 21 : 57, // CIV
                    1 => ($provider_valeur != 51) ? 570 : 243, // USA
                    218 => ($provider_valeur != 51) ? 574 : 294, // Tunisia
                    146 => ($provider_valeur != 51) ? 575 : 303, // Maroc
                    199 => ($provider_valeur != 51) ? 437 : 314, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 469 : 357, // France
                    179 => ($provider_valeur != 51) ? 533 : 421, // Russie
                    226 => ($provider_valeur != 51) ? 501 : 389, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 517 : 405, // Italie
                    82 => ($provider_valeur != 51) ? 485 : 373, // Allemagne
                    39 => ($provider_valeur != 51) ? 143 : 192, // Cameroun
                    79 => ($provider_valeur != 51) ? 144 : 193, // Gabon
                    50 => ($provider_valeur != 51) ? 145 : 194, // COngo Braza
                    57 => ($provider_valeur != 51) ? 146 : 195, // RDC
                    122 => ($provider_valeur != 51) ? 147 : 196, // Liberia
                    43 => ($provider_valeur != 51) ? 148 : 197, // Thacd
                    42 => ($provider_valeur != 51) ? 149 : 198, // Afrique centrale
                    // New countries
                    92 => ($provider_valeur != 51) ? 1777 : 1822, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 700 : 728, // Ouganda
                    158 => ($provider_valeur != 51) ? 1957 : 2002, // Nigeria
                    // CONTINUE
                    191 => ($provider_valeur != 51) ? 1867 : 1912, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2632 : 2587, // Koweit
                    212 => ($provider_valeur != 51) ? 1327 : 1372, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2677 : 2722, // Quatar
                    241 => ($provider_valeur != 51) ? 1597 : 1620, // Zambie
                    100 => ($provider_valeur != 51) ? 2947 : 2992, // Inde
                    69 => ($provider_valeur != 51) ? 1417 : 1462, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2092 : 2047, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1687 : 1732, // Angola
                    225 => ($provider_valeur != 51) ? 2767 : 2812, // Dubai
                    112 => ($provider_valeur != 51) ? 1507 : 1552, // Keneya
                    45 => ($provider_valeur != 51) ? 2857 : 2902, // China
                    208 => ($provider_valeur != 51) ? 2137 : 2182, // Suisse
                    23 => ($provider_valeur != 51) ? 2227 : 2272, // Belgique
                    173 => ($provider_valeur != 51) ? 2317 : 2362, // Portugal
                    219 => ($provider_valeur != 51) ? 2407 : 2452, // Turquie
                    152 => ($provider_valeur != 51) ? 2497 : 2542, // Hollande
                    180 => ($provider_valeur != 51) ? 834 : 789, // RWanda

                ],
                // Togo
                214 =>  [
                    25 => ($provider_valeur != 51) ?  4 : 39, // Benin
                    188 => ($provider_valeur != 51) ? 22 : 58, // Senegal
                    83 => ($provider_valeur != 51) ? 23 : 59, // Ghana
                    36 => ($provider_valeur != 51) ? 10 : 46, // BF
                    133 => ($provider_valeur != 51) ? 16 : 52, // Mali
                    157 => ($provider_valeur != 51) ? 24 : 60, // Niger
                    91 => ($provider_valeur != 51) ? 25 : 61, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 26 : 62, // CIV
                    1 => ($provider_valeur != 51) ? 250 : 244, // USA
                    218 => ($provider_valeur != 51) ? 266 : 295, // Tunisia
                    146 => ($provider_valeur != 51) ? 280 : 304, // Maroc
                    199 => ($provider_valeur != 51) ? 438 : 315, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 470 : 358, // France
                    179 => ($provider_valeur != 51) ? 534 : 422, // Russie
                    226 => ($provider_valeur != 51) ? 502 : 390, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 518 : 406, // Italie
                    82 => ($provider_valeur != 51) ? 486 : 374, // Allemagne
                    39 => ($provider_valeur != 51) ? 150 : 199, // Cameroun
                    79 => ($provider_valeur != 51) ? 151 : 200, // Gabon
                    50 => ($provider_valeur != 51) ? 152 : 201, // COngo Braza
                    57 => ($provider_valeur != 51) ? 153 : 202, // RDC
                    122 => ($provider_valeur != 51) ? 154 : 203, // Liberia
                    43 => ($provider_valeur != 51) ? 155 : 204, // Thacd
                    42 => ($provider_valeur != 51) ? 156 : 205, // Afrique centrale
                    // New countries
                    92 => ($provider_valeur != 51) ? 1791 : 1836, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 714 : 742, // Ouganda
                    158 => ($provider_valeur != 51) ? 1971 : 2016, // Nigeria
                    // CONTINUE
                    191 => ($provider_valeur != 51) ? 1881 : 1926, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2646 : 2601, // Koweit
                    212 => ($provider_valeur != 51) ? 1341 : 1386, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2691 : 2736, // Quatar
                    241 => ($provider_valeur != 51) ? 1611 : 1634, // Zambie
                    100 => ($provider_valeur != 51) ? 2961 : 3006, // Inde
                    69 => ($provider_valeur != 51) ? 1431 : 1476, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2106 : 2061, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1679 : 1724, // Angola
                    225 => ($provider_valeur != 51) ? 2781 : 2826, // Dubai
                    112 => ($provider_valeur != 51) ? 1521 : 1566, // Keneya
                    45 => ($provider_valeur != 51) ? 2871 : 2916, // China
                    208 => ($provider_valeur != 51) ? 2151 : 2196, // Suisse
                    23 => ($provider_valeur != 51) ? 2241 : 2286, // Belgique
                    173 => ($provider_valeur != 51) ? 2331 : 2376, // Portugal
                    219 => ($provider_valeur != 51) ? 2421 : 2466, // Turquie
                    152 => ($provider_valeur != 51) ? 2511 : 2556, // Hollande
                    180 => ($provider_valeur != 51) ? 847 : 803, // RWanda

                ],
                // Senegal
                188 =>  [

                    25 => ($provider_valeur != 51) ?  5 : 40, // Benin
                    83 => ($provider_valeur != 51) ? 27 : 63, // Ghana
                    133 => ($provider_valeur != 51) ? 28 : 64, // Niger
                    36 => ($provider_valeur != 51) ? 11 : 47, // BF
                    188 => ($provider_valeur != 51) ? 17 : 53, // Mali
                    214 => ($provider_valeur != 51) ? 22 : 58, // TOgo
                    91 => ($provider_valeur != 51) ? 29 : 65, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 30 : 66, // CIV
                    1 => ($provider_valeur != 51) ? 251 : 245, // USA
                    218 => ($provider_valeur != 51) ? 267 : 296, // Tunisia
                    146 => ($provider_valeur != 51) ? 281 : 305, // Maroc
                    199 => ($provider_valeur != 51) ? 439 : 316, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 471 : 359, // France
                    179 => ($provider_valeur != 51) ? 535 : 423, // Russie
                    226 => ($provider_valeur != 51) ? 503 : 391, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 519 : 407, // Italie
                    82 => ($provider_valeur != 51) ? 487 : 375, // Allemagne
                    39 => ($provider_valeur != 51) ? 157 : 206, // Cameroun
                    79 => ($provider_valeur != 51) ? 158 : 207, // Gabon
                    50 => ($provider_valeur != 51) ? 159 : 208, // COngo Braza
                    57 => ($provider_valeur != 51) ? 160 : 209, // RDC
                    122 => ($provider_valeur != 51) ? 161 : 210, // Liberia
                    43 => ($provider_valeur != 51) ? 162 : 211, // Thacd
                    42 => ($provider_valeur != 51) ? 163 : 212, // Afrique centrale
                    // New countries
                    92 => ($provider_valeur != 51) ? 1786 : 1831, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 741 : 709, // Ouganda
                    158 => ($provider_valeur != 51) ? 1966 : 2011, // Nigeria
                    // CONTINUE
                    191 => ($provider_valeur != 51) ? 1876 : 1921, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2641 : 2596, // Koweit
                    212 => ($provider_valeur != 51) ? 1336 : 1381, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2686 : 2731, // Quatar
                    241 => ($provider_valeur != 51) ? 1606 : 1651, // Zambie
                    100 => ($provider_valeur != 51) ? 2956 : 3001, // Inde
                    69 => ($provider_valeur != 51) ? 1426 : 1471, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2101 : 2056, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1674 : 1719, // Angola
                    225 => ($provider_valeur != 51) ? 2776 : 2821, // Dubai
                    112 => ($provider_valeur != 51) ? 1516 : 1561, // Keneya
                    45 => ($provider_valeur != 51) ? 2866 : 2911, // China
                    208 => ($provider_valeur != 51) ? 2146 : 2191, // Suisse
                    23 => ($provider_valeur != 51) ? 2236 : 2281, // Belgique
                    173 => ($provider_valeur != 51) ? 2326 : 2371, // Portugal
                    219 => ($provider_valeur != 51) ? 2416 : 2461, // Turquie
                    152 => ($provider_valeur != 51) ? 2506 : 2551, // Hollande
                    180 => ($provider_valeur != 51) ? 742 : 798, // RWanda

                ],
                // Ghana
                83 =>  [
                    25 => ($provider_valeur != 51) ?  6 : 41, // Benin
                    188 => ($provider_valeur != 51) ? 27 : 63, // Senegal
                    157 => ($provider_valeur != 51) ? 31 : 67, // Niger
                    36 => ($provider_valeur != 51) ? 12 : 48, // bf
                    133 => ($provider_valeur != 51) ? 18 : 54, // Mali
                    214 => ($provider_valeur != 51) ? 23 : 59, // Ghana
                    91 => ($provider_valeur != 51) ? 32 : 68, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 33 : 69, // CIV
                    1 => ($provider_valeur != 51) ? 252 : 571, // USA
                    218 => ($provider_valeur != 51) ? 268 : 297, // Tunisia
                    146 => ($provider_valeur != 51) ? 282 : 306, // Maroc
                    199 => ($provider_valeur != 51) ? 440 : 317, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 472 : 360, // France
                    179 => ($provider_valeur != 51) ? 536 : 424, // Russie
                    226 => ($provider_valeur != 51) ? 504 : 392, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 520 : 408, // Italie
                    82 => ($provider_valeur != 51) ? 488 : 376, // Allemagne
                    39 => ($provider_valeur != 51) ? 164 : 213, // Cameroun
                    79 => ($provider_valeur != 51) ? 165 : 214, // Gabon
                    50 => ($provider_valeur != 51) ? 166 : 215, // COngo Braza
                    57 => ($provider_valeur != 51) ? 167 : 216, // RDC
                    122 => ($provider_valeur != 51) ? 168 : 217, // Liberia
                    43 => ($provider_valeur != 51) ? 169 : 218, // Thacd
                    42 => ($provider_valeur != 51) ? 170 : 219, // Afrique centrale
                    // New countries
                    92 => ($provider_valeur != 51) ? 1768 : 1813, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 749 : 691, // Ouganda
                    158 => ($provider_valeur != 51) ? 1948 : 1993, // Nigeria
                    // CONTINUE
                    191 => ($provider_valeur != 51) ? 1858 : 1903, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2623 : 2578, // Koweit
                    212 => ($provider_valeur != 51) ? 1318 : 1363, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2668 : 2713, // Quatar
                    241 => ($provider_valeur != 51) ? 1588 : 1633, // Zambie
                    100 => ($provider_valeur != 51) ? 2938 : 2983, // Inde
                    69 => ($provider_valeur != 51) ? 1408 : 1453, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2083 : 2038, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1656 : 1701, // Angola
                    225 => ($provider_valeur != 51) ? 2758 : 2803, // Dubai
                    112 => ($provider_valeur != 51) ? 1498 : 1543, // Keneya
                    45 => ($provider_valeur != 51) ? 2848 : 2893, // China
                    208 => ($provider_valeur != 51) ? 2128 : 2173, // Suisse
                    23 => ($provider_valeur != 51) ? 2218 : 2263, // Belgique
                    173 => ($provider_valeur != 51) ? 2308 : 2353, // Portugal
                    219 => ($provider_valeur != 51) ? 2398 : 2443, // Turquie
                    152 => ($provider_valeur != 51) ? 2488 : 2533, // Hollande
                    180 => ($provider_valeur != 51) ? 825 : 780, // RWanda


                ],
                // Niger
                157 =>  [
                    25 => ($provider_valeur != 51) ? 7 : 42, // Benin
                    188 => ($provider_valeur != 51) ? 28 : 64, // Senegal
                    91 => ($provider_valeur != 51) ? 34 : 70, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 35 : 71, // CIV
                    214 => ($provider_valeur != 51) ? 24 : 60, // Togo
                    1 => ($provider_valeur != 51) ? 253 : 246, // USA
                    36 => ($provider_valeur != 51) ? 13 : 49, // BF
                    133 => ($provider_valeur != 51) ? 19 : 55, // Mali
                    218 => ($provider_valeur != 51) ? 269 : 298, // Tunisia
                    146 => ($provider_valeur != 51) ? 283 : 307, // Maroc
                    199 => ($provider_valeur != 51) ? 441 : 318, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 473 : 361, // France
                    179 => ($provider_valeur != 51) ? 537 : 425, // Russie
                    226 => ($provider_valeur != 51) ? 505 : 393, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 521 : 409, // Italie
                    82 => ($provider_valeur != 51) ? 489 : 377, // Allemagne
                    39 => ($provider_valeur != 51) ? 576 : 220, // Cameroun
                    79 => ($provider_valeur != 51) ? 577 : 221, // Gabon
                    50 => ($provider_valeur != 51) ? 578 : 222, // COngo Braza
                    57 => ($provider_valeur != 51) ? 579 : 223, // RDC
                    122 => ($provider_valeur != 51) ? 580 : 224, // Liberia
                    43 => ($provider_valeur != 51) ? 581 : 225, // Thacd
                    42 => ($provider_valeur != 51) ? 582 : 226, // Afrique centrale
                    // New countries
                    92 => ($provider_valeur != 51) ? 1781 : 1826, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 704 : 736, // Ouganda
                    158 => ($provider_valeur != 51) ? 1948 : 1993, // Nigeria
                    // CONTINUE
                    191 => ($provider_valeur != 51) ? 1871 : 1916, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2636 : 2591, // Koweit
                    212 => ($provider_valeur != 51) ? 1331 : 1376, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2681 : 2726, // Quatar
                    241 => ($provider_valeur != 51) ? 1601 : 1646, // Zambie
                    100 => ($provider_valeur != 51) ? 2951 : 2996, // Inde
                    69 => ($provider_valeur != 51) ? 1421 : 1466, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2096 : 2051, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1669 : 1714, // Angola
                    225 => ($provider_valeur != 51) ? 2771 : 2816, // Dubai
                    112 => ($provider_valeur != 51) ? 1511 : 1556, // Keneya
                    45 => ($provider_valeur != 51) ? 2861 : 2906, // China
                    208 => ($provider_valeur != 51) ? 2141 : 2186, // Suisse
                    23 => ($provider_valeur != 51) ? 2231 : 2276, // Belgique
                    173 => ($provider_valeur != 51) ? 2321 : 2366, // Portugal
                    219 => ($provider_valeur != 51) ? 2411 : 2456, // Turquie
                    152 => ($provider_valeur != 51) ? 2501 : 2546, // Hollande
                    180 => ($provider_valeur != 51) ? 1330 : 1376, // RWanda


                ],
                // Guinnée Conakry
                91 =>  [
                    25 => ($provider_valeur != 51) ? 7 : 42, // Benin
                    188 => ($provider_valeur != 51) ? 29 : 65, // Senegal
                    157 => ($provider_valeur != 51) ? 34 : 70, // Niger
                    83 => ($provider_valeur != 51) ?  32 : 68, // Ghana
                    36 => ($provider_valeur != 51) ? 14 : 50, //BF
                    133 => ($provider_valeur != 51) ? 20 : 56, // Mali
                    107 => ($provider_valeur != 51) ? 36 : 72, // CIV
                    214 => ($provider_valeur != 51) ? 25 : 61, //Togo
                    1 => ($provider_valeur != 51) ? 253 : 246, // USA
                    36 => ($provider_valeur != 51) ? 14 : 50, // BF
                    218 => ($provider_valeur != 51) ? 270 : 299, // Tunisia
                    146 => ($provider_valeur != 51) ? 284 : 308, // Maroc
                    199 => ($provider_valeur != 51) ? 442 : 319, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 474 : 362, // France
                    179 => ($provider_valeur != 51) ? 538 : 426, // Russie
                    226 => ($provider_valeur != 51) ? 506 : 394, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 522 : 410, // Italie
                    82 => ($provider_valeur != 51) ? 490 : 378, // Allemagne
                    39 => ($provider_valeur != 51) ? 171 : 227, // Cameroun
                    79 => ($provider_valeur != 51) ? 172 : 228, // Gabon
                    50 => ($provider_valeur != 51) ? 173 : 229, // COngo Braza
                    57 => ($provider_valeur != 51) ? 174 : 230, // RDC
                    122 => ($provider_valeur != 51) ? 175 : 231, // Liberia
                    43 => ($provider_valeur != 51) ? 176 : 232, // Thacd
                    42 => ($provider_valeur != 51) ? 177 : 233, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 1769 : 1814, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 697 : 750, // Ouganda
                    158 => ($provider_valeur != 51) ? 1949 : 1994, // Nigeria
                    // CONTINUE
                    191 => ($provider_valeur != 51) ? 1859 : 1904, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2624 : 2579, // Koweit
                    212 => ($provider_valeur != 51) ? 1319 : 1364, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2669 : 2714, // Quatar
                    241 => ($provider_valeur != 51) ? 1589 : 1634, // Zambie
                    100 => ($provider_valeur != 51) ? 2939 : 2984, // Inde
                    69 => ($provider_valeur != 51) ? 1409 : 1454, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2084 : 2039, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1679 : 1724, // Angola
                    225 => ($provider_valeur != 51) ? 2759 : 2804, // Dubai
                    112 => ($provider_valeur != 51) ? 1499 : 1544, // Keneya
                    45 => ($provider_valeur != 51) ? 2849 : 2894, // China
                    208 => ($provider_valeur != 51) ? 2129 : 2174, // Suisse
                    23 => ($provider_valeur != 51) ? 2219 : 2264, // Belgique
                    173 => ($provider_valeur != 51) ? 2309 : 2354, // Portugal
                    219 => ($provider_valeur != 51) ? 2399 : 2444, // Turquie
                    152 => ($provider_valeur != 51) ? 2489 : 2534, // Hollande
                    180 => ($provider_valeur != 51) ? 826 : 781, // RWanda


                ],
                // Guinnée Buisso
                92 => [

                    25 => ($provider_valeur != 51) ? 1754 : 1799, // Benin
                    36 => ($provider_valeur != 51) ? 1755 : 1800, // BF
                    133 => ($provider_valeur != 51) ? 1777 : 1822, // Mali
                    214 => ($provider_valeur != 51) ? 1791 : 1836, // Togo
                    188 => ($provider_valeur != 51) ? 1786 : 1831, // Senegal
                    83 => ($provider_valeur != 51) ? 1768 : 1813, // Ghana
                    157 => ($provider_valeur != 51) ? 1781 : 1826, // Niger
                    91 => ($provider_valeur != 51) ? 1769 : 1814, // Guinne conakry

                    107 => ($provider_valeur != 51) ? 632 : 652, // CIV
                    1 => ($provider_valeur != 51) ? 1750 : 1795, // USA
                    // 218 => ($provider_valeur == 51) ? 554 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 1824 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 1923 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 1809 : null, // France
                    179 => ($provider_valeur == 51) ? 3030 : null, // Russie
                    226 => ($provider_valeur == 51) ? 1839 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1817 : null, // Italie
                    82 => ($provider_valeur == 51) ? 1812 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 1757 : 1802, // Cameroun

                    // gabon 
                    79 => ($provider_valeur != 51) ? 3095 : 3116, // Gabon
                    50 => ($provider_valeur != 51) ? 1761 : 1806, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1762 : 1807, // RDC
                    122 => ($provider_valeur != 51) ? 1821 : 1866, // Liberia
                    43 => ($provider_valeur != 51) ? 1759 : 1804, // Thacd
                    42 => ($provider_valeur != 51) ? 1758 : 1803, // Afrique centrale

                    // New countries
                    // 92 => ($provider_valeur != 51) ? 3095 : 3116, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3137 : 3157, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3138 : 3158, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3139 : 3159, // Nigeria
                    191 => ($provider_valeur != 51) ? 3140 : 3160, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3141 : 3161, // Koweit
                    212 => ($provider_valeur != 51) ? 3142 : 3162, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3143 : 3163, // Quatar
                    241 => ($provider_valeur != 51) ? 3144 : 3164, // Zambie
                    66 => ($provider_valeur != 51) ?  3145 : 3165, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 3146 : 3166, // Angola
                    225 => ($provider_valeur != 51) ? 3147 : 3167, // Dubai
                    112 => ($provider_valeur != 51) ? 3148 : 3168, // Keneya
                    45 => ($provider_valeur != 51) ? 3149 : 3169, // China
                    208 => ($provider_valeur != 51) ? 3150 : 3170, // Suisse
                    23 => ($provider_valeur != 51) ?  3151 : 3171, // Belgique
                    173 => ($provider_valeur != 51) ? 3152 : 3172, // Portugal
                    219 => ($provider_valeur != 51) ? 3153 : 3173, // Turquie
                    152 => ($provider_valeur != 51) ? 3154 : 3174, // Hollande

                    100 => ($provider_valeur != 51) ? 3155 : 3175, // Inde
                    69 => ($provider_valeur != 51) ? 3156 : 3176, // Ethiopie
                ],

                //Sierra Leone 
                191 => [

                    25 => ($provider_valeur != 51) ? 1844 : 1889, // Benin
                    36 => ($provider_valeur != 51) ? 1845 : 1890, // BF
                    133 => ($provider_valeur != 51) ? 1867 : 1912, // Mali
                    214 => ($provider_valeur != 51) ? 1881 : 1926, // Togo
                    188 => ($provider_valeur != 51) ? 1876 : 1921, // Senegal
                    83 => ($provider_valeur != 51) ? 1858 : 1903, // Ghana
                    157 => ($provider_valeur != 51) ? 1871 : 1916, // Niger
                    91 => ($provider_valeur != 51) ? 1859 : 1904, // Guinne conakry

                    107 => ($provider_valeur != 51) ? 636 : 653, // CIV
                    1 => ($provider_valeur != 51) ? 1840 : 1885, // USA
                    // 218 => ($provider_valeur == 51) ? 554 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 1914 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 1923 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 1899 : null, // France
                    179 => ($provider_valeur == 51) ? 3034 : null, // Russie
                    226 => ($provider_valeur == 51) ? 1929 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1907 : null, // Italie
                    82 => ($provider_valeur == 51) ? 1902 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 1847 : 1892, // Cameroun

                    // gabon 
                    79 => ($provider_valeur != 51) ? 3099 : 3120, // Gabon
                    50 => ($provider_valeur != 51) ? 1851 : 1896, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1852 : 1897, // RDC
                    122 => ($provider_valeur != 51) ? 1866 : 1911, // Liberia
                    43 => ($provider_valeur != 51) ? 1759 : 1804, // Thacd
                    42 => ($provider_valeur != 51) ? 1866 : 1977, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3177 : 3975, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3178 : 3197, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3179 : 3198, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3180 : 3199, // Nigeria
                    // 191 => ($provider_valeur != 51) ? 3180 : 3200,// Sierria Leone
                    116 => ($provider_valeur != 51) ? 3181 : 3200, // Koweit
                    212 => ($provider_valeur != 51) ? 3182 : 3201, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3183 : 3202, // Quatar
                    241 => ($provider_valeur != 51) ? 3184 : 3203, // Zambie
                    66 => ($provider_valeur != 51) ?  3185 : 3204, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 3186 : 3205, // Angola
                    225 => ($provider_valeur != 51) ? 3187 : 3206, // Dubai
                    112 => ($provider_valeur != 51) ? 3188 : 3207, // Keneya
                    45 => ($provider_valeur != 51) ? 3189 : 3208, // China
                    208 => ($provider_valeur != 51) ? 3190 : 3209, // Suisse
                    23 => ($provider_valeur != 51) ?  3191 : 3210, // Belgique
                    173 => ($provider_valeur != 51) ? 3192 : 3211, // Portugal
                    219 => ($provider_valeur != 51) ? 3193 : 3212, // Turquie
                    152 => ($provider_valeur != 51) ? 3194 : 3213, // Hollande

                    100 => ($provider_valeur != 51) ? 3195 : 3214, // Inde
                    69 => ($provider_valeur != 51) ? 3196 : 3215, // Ethiopie
                ],

                //Nigeria
                158 => [

                    25 => ($provider_valeur != 51) ? 1934 : 1979, // Benin
                    36 => ($provider_valeur != 51) ? 1935 : 1980, // BF
                    133 => ($provider_valeur != 51) ? 1957 : 2002,  // Mali
                    214 => ($provider_valeur != 51) ? 1971 : 2016, // Togo
                    188 => ($provider_valeur != 51) ? 1966 : 2011, // Senegal
                    83 => ($provider_valeur != 51) ? 1948 : 1993, // Ghana
                    157 => ($provider_valeur != 51) ? 1961 : 2006, // Niger
                    91 => ($provider_valeur != 51) ? 1949 : 1994, // Guinne conakry

                    107 => ($provider_valeur != 51) ? 635 : 654, // CIV
                    1 => ($provider_valeur != 51) ? 1930 : 1975, // USA
                    218 => ($provider_valeur == 51) ? 3071 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2004 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2013 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 1989 : null, // France
                    179 => ($provider_valeur == 51) ? 3033 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2019 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1997 : null, // Italie
                    82 => ($provider_valeur == 51) ? 1992 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 1937 : 1982, // Cameroun

                    // gabon 
                    79 => ($provider_valeur != 51) ? 3098 : 3119, // Gabon
                    50 => ($provider_valeur != 51) ? 1941 : 1986, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1942 : 1987, // RDC
                    122 => ($provider_valeur != 51) ? 1956 : 2001, // Liberia
                    43 => ($provider_valeur != 51) ? 1939 : 1849, // Thacd
                    42 => ($provider_valeur != 51) ? 1938 : 1983, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3216 : 3976, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3217 : 3236, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3218 : 3237, // Rwanda 
                    //    158 => ($provider_valeur != 51) ? 3180 : 3199, // Nigeria
                    191 => ($provider_valeur != 51) ? 3219 : 3238, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3220 : 3239, // Koweit
                    212 => ($provider_valeur != 51) ? 3221 : 3240, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3222 : 3241, // Quatar
                    241 => ($provider_valeur != 51) ? 3223 : 3242, // Zambie
                    66 => ($provider_valeur != 51) ?  3224 : 3243, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 3225 : 3244, // Angola
                    225 => ($provider_valeur != 51) ? 3226 : 3245, // Dubai
                    112 => ($provider_valeur != 51) ? 3227 : 3246, // Keneya
                    45 => ($provider_valeur != 51) ? 3228 : 3247, // China
                    208 => ($provider_valeur != 51) ? 3229 : 3248, // Suisse
                    23 => ($provider_valeur != 51) ?  3230 : 3249, // Belgique
                    173 => ($provider_valeur != 51) ? 3231 : 3250, // Portugal
                    219 => ($provider_valeur != 51) ? 3232 : 3251, // Turquie
                    152 => ($provider_valeur != 51) ? 3233 : 3252, // Hollande

                    100 => ($provider_valeur != 51) ? 3234 : 3253, // Inde
                    69 => ($provider_valeur != 51) ? 3235 : 3254, // Ethiopie
                ],


                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif (robus()->country->name_groupe == 'AFC') {

            $countriesLevels = [
                // Is Cameroun
                39 => [
                    25 => ($provider_valeur != 51) ? 129 : 178, // Benin
                    36 => ($provider_valeur != 51) ? 136 : 185, // BF
                    133 => ($provider_valeur != 51) ? 143 : 192, // Mali
                    214 => ($provider_valeur != 51) ? 150 : 199, // Togo
                    188 => ($provider_valeur != 51) ? 157 : 206, // Senegal
                    83 => ($provider_valeur != 51) ? 164 : 213, // Ghana
                    157 => ($provider_valeur != 51) ? 576 : 220, // Niger
                    91 => ($provider_valeur != 51) ? 171 : 227, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 79 : 107, // CIV
                    1 => ($provider_valeur != 51) ? 255 : 234, // USA
                    218 => ($provider_valeur != 51) ? 271 : 327, // Tunisia
                    146 => ($provider_valeur != 51) ? 285 : 333, // Maroc
                    199 => ($provider_valeur != 51) ? 444 : 321, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 476 : 364, // France
                    179 => ($provider_valeur != 51) ? 540 : 428, // Russie
                    226 => ($provider_valeur != 51) ? 508 : 396, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 515 : 403, // Italie
                    82 => ($provider_valeur != 51) ? 524 : 412, // Allemagne
                    // 39 => ($provider_valeur != 51) ? 129 : 178, // Cameroun
                    79 => ($provider_valeur != 51) ? 73 : 101, // Gabon
                    50 => ($provider_valeur != 51) ? 74 : 102, // COngo Braza
                    57 => ($provider_valeur != 51) ? 75 : 103, // RDC
                    122 => ($provider_valeur != 51) ? 76 : 104, // Liberia
                    43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ? 78 : 106, // Afrique centrale
                    // New countries
                    92 => ($provider_valeur != 51) ? 1757 : 1802, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 721 : 680, // Ouganda
                    158 => ($provider_valeur != 51) ? 1937 : 1982, // Nigeria
                    // CONTINUE
                    191 => ($provider_valeur != 51) ? 1847 : 1892, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2612 : 2567, // Koweit
                    212 => ($provider_valeur != 51) ? 1307 : 1352, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2657 : 2702, // Quatar
                    241 => ($provider_valeur != 51) ? 1577 : 1622, // Zambie
                    100 => ($provider_valeur != 51) ? 2927 : 2972, // Inde
                    69 => ($provider_valeur != 51) ? 1397 : 1442, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2072 : 2027, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1667 : 1712, // Angola
                    225 => ($provider_valeur != 51) ? 2747 : 2792, // Dubai
                    112 => ($provider_valeur != 51) ? 1487 : 1532, // Keneya
                    45 => ($provider_valeur != 51) ? 2837 : 2882, // China
                    208 => ($provider_valeur != 51) ? 2117 : 2162, // Suisse
                    23 => ($provider_valeur != 51) ? 2207 : 2252, // Belgique
                    173 => ($provider_valeur != 51) ? 2297 : 2342, // Portugal
                    219 => ($provider_valeur != 51) ? 2387 : 2432, // Turquie
                    152 => ($provider_valeur != 51) ? 2477 : 2522, // Hollande
                    180 => ($provider_valeur != 51) ? 814 : 769, // RWanda

                ],

                // Gabon
                79 =>  [
                    25 => ($provider_valeur != 51) ? 130 : 179, // Benin
                    36 => ($provider_valeur != 51) ? 137 : 186, // BF
                    133 => ($provider_valeur != 51) ? 144 : 193, // Mali
                    214 => ($provider_valeur != 51) ? 151 : 200, // Togo
                    188 => ($provider_valeur != 51) ? 158 : 207, // Senegal
                    83 => ($provider_valeur != 51) ? 165 : 214, // Ghana
                    157 => ($provider_valeur != 51) ? 577 : 221, // Niger
                    91 => ($provider_valeur != 51) ? 173 : 229, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 85 : 113, // CIV
                    1 => ($provider_valeur != 51) ? 256 : 235, // USA
                    218 => ($provider_valeur != 51) ? 272 : 328, // Tunisia
                    146 => ($provider_valeur != 51) ? 286 : 334, // Maroc
                    199 => ($provider_valeur != 51) ? 445 : 322, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 477 : 365, // France
                    179 => ($provider_valeur != 51) ? 541 : 429, // Russie
                    226 => ($provider_valeur != 51) ? 509 : 397, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 525 : 413, // Italie
                    82 => ($provider_valeur != 51) ? 493 : 381, // Allemagne
                    39 => ($provider_valeur != 51) ? 73 : 101, // Cameroun
                    // 79 => ($provider_valeur != 51) ? 137 : 186, // Gabon
                    50 => ($provider_valeur != 51) ? 80 : 108, // COngo Braza
                    57 => ($provider_valeur != 51) ? 81 : 109, // RDC
                    122 => ($provider_valeur != 51) ? 82 : 110, // Liberia
                    43 => ($provider_valeur != 51) ? 83 : 111, // Thacd
                    42 => ($provider_valeur != 51) ? 84 : 112, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3095 : 3116, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3096 : 3117, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3097 : 3118, // Rwanda 

                    158 => ($provider_valeur != 51) ? 3098 : 3119, // Nigeria
                    191 => ($provider_valeur != 51) ? 3099 : 3120, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3100 : 3121, // Koweit
                    212 => ($provider_valeur != 51) ? 3101 : 3122, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3102 : 3123, // Quatar
                    241 => ($provider_valeur != 51) ? 3103 : 3124, // Zambie
                    66 => ($provider_valeur != 51) ?  3104 : 3125, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 3105 : 3126, // Angola
                    225 => ($provider_valeur != 51) ? 3106 : 3127, // Dubai
                    112 => ($provider_valeur != 51) ? 3107 : 3128, // Keneya
                    45 => ($provider_valeur != 51) ? 3108 : 3129, // China
                    208 => ($provider_valeur != 51) ? 3109 : 3130, // Suisse
                    23 => ($provider_valeur != 51) ?  3110 : 3131, // Belgique
                    173 => ($provider_valeur != 51) ? 3111 : 3132, // Portugal
                    219 => ($provider_valeur != 51) ? 3112 : 3133, // Turquie
                    152 => ($provider_valeur != 51) ? 3113 : 3134, // Hollande

                    100 => ($provider_valeur != 51) ? 3114 : 3135, // Inde
                    69 => ($provider_valeur != 51) ? 3115 : 3136, // Ethiopie

                ],
                // Congo Brazaville
                50 =>  [
                    25 => ($provider_valeur != 51) ?  131 : 180, // Benin
                    214 => ($provider_valeur != 51) ? 152 : 201, // Togo
                    36 => ($provider_valeur != 51) ? 138 : 187, // BF
                    188 => ($provider_valeur != 51) ? 159 : 208, // Senegal
                    83 => ($provider_valeur != 51) ? 166 : 215, // Ghana
                    157 => ($provider_valeur != 51) ? 578 : 222, // Niger
                    91 => ($provider_valeur != 51) ? 173 : 229, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 90 : 118, // CIV
                    1 => ($provider_valeur != 51) ? 570 : 243, // USA
                    218 => ($provider_valeur != 51) ? 574 : 294, // Tunisia
                    146 => ($provider_valeur != 51) ? 575 : 303, // Maroc
                    199 => ($provider_valeur != 51) ? 437 : 314, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 469 : 357, // France
                    179 => ($provider_valeur != 51) ? 533 : 421, // Russie
                    226 => ($provider_valeur != 51) ? 501 : 389, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 517 : 405, // Italie
                    82 => ($provider_valeur != 51) ? 485 : 373, // Allemagne
                    39 => ($provider_valeur != 51) ? 143 : 192, // Cameroun
                    79 => ($provider_valeur != 51) ? 144 : 193, // Gabon
                    // 50 => ($provider_valeur != 51) ? 145 : 194, // COngo Braza
                    57 => ($provider_valeur != 51) ? 86 : 114, // RDC
                    122 => ($provider_valeur != 51) ? 87 : 115, // Liberia
                    43 => ($provider_valeur != 51) ? 88 : 116, // Thacd
                    42 => ($provider_valeur != 51) ? 89 : 117, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 1761 : 1806, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 684 : 724, // Ouganda
                    158 => ($provider_valeur != 51) ? 1941 : 1986, // Nigeria
                    // CONTINUE
                    191 => ($provider_valeur != 51) ? 1851 : 1896, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2616 : 2571, // Koweit
                    212 => ($provider_valeur != 51) ? 1311 : 1356, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2661 : 2706, // Quatar
                    241 => ($provider_valeur != 51) ? 1581 : 1626, // Zambie
                    100 => ($provider_valeur != 51) ? 2931 : 2976, // Inde
                    69 => ($provider_valeur != 51) ? 1401 : 1446, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2076 : 2031, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1671 : 1716, // Angola
                    225 => ($provider_valeur != 51) ? 2751 : 2796, // Dubai
                    112 => ($provider_valeur != 51) ? 1491 : 1536, // Keneya
                    45 => ($provider_valeur != 51) ? 2841 : 2886, // China
                    208 => ($provider_valeur != 51) ? 2121 : 2166, // Suisse
                    23 => ($provider_valeur != 51) ? 2211 : 2256, // Belgique
                    173 => ($provider_valeur != 51) ? 2301 : 2346, // Portugal
                    219 => ($provider_valeur != 51) ? 2391 : 2436, // Turquie
                    152 => ($provider_valeur != 51) ? 2481 : 2526, // Hollande
                    180 => ($provider_valeur != 51) ? 818 : 773, // RWanda

                ],
                // RDC
                57 =>  [
                    25 => ($provider_valeur != 51) ?  132 : 181, // Benin
                    188 => ($provider_valeur != 51) ? 160 : 209, // Senegal
                    83 => ($provider_valeur != 51) ? 167 : 216, // Ghana
                    36 => ($provider_valeur != 51) ? 139 : 188, // BF
                    133 => ($provider_valeur != 51) ? 146 : 195, // Mali
                    157 => ($provider_valeur != 51) ? 579 : 223, // Niger
                    91 => ($provider_valeur != 51) ? 174 : 230, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 94 : 122, // CIV
                    1 => ($provider_valeur != 51) ? 258 : 237, // USA
                    218 => ($provider_valeur != 51) ? 374 : 329, // Tunisia
                    146 => ($provider_valeur != 51) ? 288 : 335, // Maroc
                    199 => ($provider_valeur != 51) ? 447 : 323, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 479 : 367, // France
                    179 => ($provider_valeur != 51) ? 543 : 431, // Russie
                    226 => ($provider_valeur != 51) ? 511 : 399, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 527 : 415, // Italie
                    82 => ($provider_valeur != 51) ? 495 : 383, // Allemagne
                    39 => ($provider_valeur != 51) ? 75 : 103, // Cameroun
                    79 => ($provider_valeur != 51) ? 81 : 109, // Gabon
                    50 => ($provider_valeur != 51) ? 86 : 114, // COngo Braza
                    // 57 => ($provider_valeur != 51) ? 153 : 202, // RDC
                    122 => ($provider_valeur != 51) ? 91 : 119, // Liberia
                    43 => ($provider_valeur != 51) ? 92 : 120, // Thacd
                    42 => ($provider_valeur != 51) ? 93 : 121, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 1762 : 1807, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 685 : 725, // Ouganda
                    158 => ($provider_valeur != 51) ? 1942 : 1987, // Nigeria
                    191 => ($provider_valeur != 51) ? 1852 : 1897, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2617 : 2572, // Koweit
                    212 => ($provider_valeur != 51) ? 1312 : 1357, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2662 : 2707, // Quatar
                    241 => ($provider_valeur != 51) ? 1582 : 1627, // Zambie
                    100 => ($provider_valeur != 51) ? 2932 : 2977, // Inde
                    69 => ($provider_valeur != 51) ? 1402 : 1447, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2077 : 2032, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1672 : 1717, // Angola
                    225 => ($provider_valeur != 51) ? 2752 : 2797, // Dubai
                    112 => ($provider_valeur != 51) ? 1492 : 1537, // Keneya
                    45 => ($provider_valeur != 51) ? 2842 : 2887, // China
                    208 => ($provider_valeur != 51) ? 2122 : 2167, // Suisse
                    23 => ($provider_valeur != 51) ? 2212 : 2257, // Belgique
                    173 => ($provider_valeur != 51) ? 2302 : 2347, // Portugal
                    219 => ($provider_valeur != 51) ? 2392 : 2437, // Turquie
                    152 => ($provider_valeur != 51) ? 2482 : 2527, // Hollande
                    180 => ($provider_valeur != 51) ? 819 : 774, // RWanda

                ],
                // Liberia
                122 =>  [

                    25 => ($provider_valeur != 51) ? 133 : 182, // Benin
                    83 => ($provider_valeur != 51) ? 168 : 217, // Ghana
                    133 => ($provider_valeur != 51) ?  580 : 224, // Niger
                    36 => ($provider_valeur != 51) ? 140 : 189, // BF
                    188 => ($provider_valeur != 51) ? 147 : 196, // Mali
                    214 => ($provider_valeur != 51) ? 154 : 203, // TOgo
                    91 => ($provider_valeur != 51) ?  175 : 231, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 97 : 125, // CIV
                    1 => ($provider_valeur != 51) ? 238 : 259, // USA
                    218 => ($provider_valeur != 51) ? 275 : 330, // Tunisia
                    146 => ($provider_valeur != 51) ? 336 : 289, // Maroc
                    199 => ($provider_valeur != 51) ? 448 : 324, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 368 : 480, // France
                    179 => ($provider_valeur != 51) ? 544 : 432, // Russie
                    226 => ($provider_valeur != 51) ? 512 : 400, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 416 : 528, // Italie
                    82 => ($provider_valeur != 51) ? 496 : 384, // Allemagne
                    39 => ($provider_valeur != 51) ? 157 : 206, // Cameroun
                    79 => ($provider_valeur != 51) ? 158 : 207, // Gabon
                    50 => ($provider_valeur != 51) ? 159 : 208, // COngo Braza
                    57 => ($provider_valeur != 51) ? 160 : 209, // RDC
                    // 122 => ($provider_valeur != 51) ? 161 : 210, // Liberia
                    43 => ($provider_valeur != 51) ? 95 : 123, // Thacd
                    42 => ($provider_valeur != 51) ? 96 : 124, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 1821 : 1866, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 699 : 727, // Ouganda

                    158 => ($provider_valeur != 51) ? 1956 : 2001, // Nigeria
                    191 => ($provider_valeur != 51) ? 1866 : 1911, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2630 : 2586, // Koweit
                    212 => ($provider_valeur != 51) ? 1326 : 1371, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2676 : 2721, // Quatar
                    241 => ($provider_valeur != 51) ? 1596 : 1641, // Zambie
                    100 => ($provider_valeur != 51) ? 2946 : 2991, // Inde
                    69 => ($provider_valeur != 51) ? 1416 : 1461, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2091 : 2046, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1686 : 1731, // Angola
                    // New update
                    225 => ($provider_valeur != 51) ? 2766 : 2811, // Dubai
                    112 => ($provider_valeur != 51) ? 1506 : 1551, // Keneya
                    45 => ($provider_valeur != 51) ? 2856 : 2901, // China
                    208 => ($provider_valeur != 51) ? 2136 : 2181, // Suisse
                    23 => ($provider_valeur != 51) ? 2226 : 2271, // Belgique
                    173 => ($provider_valeur != 51) ? 2316 : 2361, // Portugal
                    219 => ($provider_valeur != 51) ? 2406 : 2451, // Turquie
                    152 => ($provider_valeur != 51) ? 2496 : 2541, // Hollande
                    180 => ($provider_valeur != 51) ? 833 : 788, // RWanda

                ],
                // Tchad
                43 =>  [
                    25 => ($provider_valeur != 51) ? 134 : 183, // Benin
                    188 => ($provider_valeur != 51) ? 162 : 211, // Senegal
                    157 => ($provider_valeur != 51) ? 581 : 225, // Niger
                    36 => ($provider_valeur != 51) ? 141 : 190, // bf
                    133 => ($provider_valeur != 51) ? 148 : 197, // Mali
                    214 => ($provider_valeur != 51) ? 169 : 218, // Ghana
                    91 => ($provider_valeur != 51) ? 176 : 232, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 99 : 127, // CIV
                    1 => ($provider_valeur != 51) ? 260 : 239, // USA
                    218 => ($provider_valeur != 51) ? 276 : 331, // Tunisia
                    146 => ($provider_valeur != 51) ? 290 : 337, // Maroc
                    199 => ($provider_valeur != 51) ? 448 : 325, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 481 : 369, // France
                    179 => ($provider_valeur != 51) ? 545 : 433, // Russie
                    226 => ($provider_valeur != 51) ? 513 : 401, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 529 : 417, // Italie
                    82 => ($provider_valeur != 51) ? 497 : 385, // Allemagne
                    39 => ($provider_valeur != 51) ? 77 : 105, // Cameroun
                    79 => ($provider_valeur != 51) ? 83 : 111, // Gabon
                    50 => ($provider_valeur != 51) ?  88 : 116, // COngo Braza
                    57 => ($provider_valeur != 51) ? 92 : 120, // RDC
                    122 => ($provider_valeur != 51) ? 95 : 123, // Liberia
                    // 43 => ($provider_valeur != 51) ? 169 : 218, // Thacd
                    42 => ($provider_valeur != 51) ? 98 : 126, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 1759 : 1804, // Guinnée Buisso
                    223 => ($provider_valeur != 51) ? 699 : 727, // Ouganda

                    158 => ($provider_valeur != 51) ? 1939 : 1849, // Nigeria
                    191 => ($provider_valeur != 51) ? 1866 : 1977, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2613 : 2569, // Koweit
                    212 => ($provider_valeur != 51) ? 1309 : 1354, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2659 : 2704, // Quatar
                    241 => ($provider_valeur != 51) ? 1579 : 1624, // Zambie
                    100 => ($provider_valeur != 51) ? 2929 : 2974, // Inde
                    69 => ($provider_valeur != 51) ? 1399 : 1444, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2074 : 2029, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1669 : 1714, // Angola
                    // New update
                    225 => ($provider_valeur != 51) ? 2749 : 2794, // Dubai
                    112 => ($provider_valeur != 51) ? 1489 : 1534, // Keneya
                    45 => ($provider_valeur != 51) ? 2839 : 2884, // China
                    208 => ($provider_valeur != 51) ? 2119 : 2164, // Suisse
                    23 => ($provider_valeur != 51) ? 2209 : 2254, // Belgique
                    173 => ($provider_valeur != 51) ? 2299 : 2344, // Portugal
                    219 => ($provider_valeur != 51) ? 2389 : 2434, // Turquie
                    152 => ($provider_valeur != 51) ? 2479 : 2524, // Hollande
                    180 => ($provider_valeur != 51) ? 816 : 771, // RWanda

                ],
                // Centre Afrique
                42 =>  [
                    25 => ($provider_valeur != 51) ? 135 : 184, // Benin
                    188 => ($provider_valeur != 51) ? 163 : 212, // Senegal
                    157 => ($provider_valeur != 51) ? 582 : 226, // Niger
                    83 => ($provider_valeur != 51) ?  170 : 219, // Ghana
                    36 => ($provider_valeur != 51) ? 142 : 191, //BF
                    133 => ($provider_valeur != 51) ? 149 : 198, // Mali
                    107 => ($provider_valeur != 51) ? 100 : 128, // CIV
                    214 => ($provider_valeur != 51) ? 156 : 205, //Togo
                    1 => ($provider_valeur != 51) ? 261 : 240, // USA
                    // 36 => ($provider_valeur != 51) ? 14 : 50, // BF
                    218 => ($provider_valeur != 51) ? 277 : 332, // Tunisia
                    146 => ($provider_valeur != 51) ? 291 : 338, // Maroc
                    199 => ($provider_valeur != 51) ? 450 : 326, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 482 : 370, // France
                    179 => ($provider_valeur != 51) ? 546 : 434, // Russie
                    226 => ($provider_valeur != 51) ? 514 : 402, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 530 : 418, // Italie
                    82 => ($provider_valeur != 51) ? 498 : 386, // Allemagne
                    39 => ($provider_valeur != 51) ? 78 : 106, // Cameroun
                    79 => ($provider_valeur != 51) ?  84 : 112, // Gabon
                    50 => ($provider_valeur != 51) ? 89 : 117, // COngo Braza
                    57 => ($provider_valeur != 51) ? 93 : 121, // RDC
                    122 => ($provider_valeur != 51) ? 96 : 124, // Liberia
                    43 => ($provider_valeur != 51) ? 98 : 126, // Thacd
                    // 42 => ($provider_valeur != 51) ? 177 : 233, // Afrique centrale

                    // New countries
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
                    8 => ($provider_valeur != 51) ? 1668 : 1713, // Angola
                    225 => ($provider_valeur != 51) ? 2748 : 2793, // Dubai
                    112 => ($provider_valeur != 51) ? 1488 : 1533, // Keneya
                    45 => ($provider_valeur != 51) ? 2838 : 2883, // China
                    208 => ($provider_valeur != 51) ? 2118 : 2163, // Suisse
                    23 => ($provider_valeur != 51) ? 2208 : 2253, // Belgique
                    173 => ($provider_valeur != 51) ? 2298 : 2343, // Portugal
                    219 => ($provider_valeur != 51) ? 2405 : 2450, // Turquie
                    152 => ($provider_valeur != 51) ? 2388 : 2433, // Hollande
                    180 => ($provider_valeur != 51) ? 815 : 770, // RWanda

                ],

                // Guinnée Equatoriale 
                66 =>  [
                    25 => ($provider_valeur != 51) ? 2069 : 2024, // Benin
                    36 => ($provider_valeur != 51) ? 2070 : 2025, // BF
                    133 => ($provider_valeur != 51) ? 2092 : 2047, // Mali
                    214 => ($provider_valeur != 51) ? 2106 : 2061, // Togo
                    188 => ($provider_valeur != 51) ? 2101 : 2056, // Senegal
                    83 => ($provider_valeur != 51) ? 2083 : 2038, // Ghana
                    157 => ($provider_valeur != 51) ? 2096 : 2051, // Niger
                    91 => ($provider_valeur != 51) ? 2084 : 2039, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 642 : 662, // CIV
                    1 => ($provider_valeur == 51) ? 2020 : null, // USA

                    218 => ($provider_valeur == 51) ? 3077 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2020 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2058 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 2034 : null, // France
                    179 => ($provider_valeur == 51) ? 3039 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2064 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2042 : null, // Italie
                    82 => ($provider_valeur == 51) ? 2037 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 1847 : 1892, // Cameroun

                    79 => ($provider_valeur != 51) ? 3104 : 3125, // Gabon
                    50 => ($provider_valeur != 51) ? 2076 : 2031, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2077 : 2032, // RDC
                    122 => ($provider_valeur != 51) ? 2091 : 2046, // Liberia
                    43 => ($provider_valeur != 51) ? 2074 : 2029, // Thacd
                    42 => ($provider_valeur != 51) ? 2073 : 2028, // Afrique centrale


                    // New countries
                    92 => ($provider_valeur != 51) ? 3535 : 3555, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3536 : 3556, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3537 : 3557, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3538 : 3558, // Nigeria
                    191 => ($provider_valeur != 51) ? 3539 : 3559, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3540 : 3560, // Koweit
                    212 => ($provider_valeur != 51) ? 3541 : 3561, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3542 : 3562, // Quatar
                    241 => ($provider_valeur != 51) ? 3543 : 3563, // Zambie
                    // 66 => ($provider_valeur != 51) ?  3224 : 3243, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 3544 : 3564, // Angola
                    225 => ($provider_valeur != 51) ? 3545 : 3565, // Dubai
                    112 => ($provider_valeur != 51) ? 3546 : 3566, // Keneya
                    45 => ($provider_valeur != 51) ? 3547 : 3567, // China
                    208 => ($provider_valeur != 51) ? 3548 : 3568, // Suisse
                    23 => ($provider_valeur != 51) ?  3549 : 3569, // Belgique
                    173 => ($provider_valeur != 51) ? 3550 : 3570, // Portugal
                    219 => ($provider_valeur != 51) ? 3551 : 3571, // Turquie
                    152 => ($provider_valeur != 51) ? 3552 : 3572, // Hollande

                    100 => ($provider_valeur != 51) ? 3553 : 3573, // Inde
                    69 => ($provider_valeur != 51) ? 3554 : 3574, // Ethiopie

                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif (robus()->country->name_groupe == 'CIV') {


            $countriesLevels = [
                // Is CIV
                107 => [
                    36 => ($provider_valeur != 51) ? 15 : 51, // BF
                    25 => ($provider_valeur != 51) ? 1 : 44, // BENIN
                    133 => ($provider_valeur != 51) ? 21 : 57, // Mali
                    214 => ($provider_valeur != 51) ? 26 : 62, // Togo
                    188 => ($provider_valeur != 51) ? 30 : 66, // Senegal
                    83 => ($provider_valeur != 51) ? 33 : 69, // Ghana
                    157 => ($provider_valeur != 51) ? 35 : 71, // Niger
                    91 => ($provider_valeur != 51) ? 36 : 72, // Guinne conakry
                    // 107 => ($provider_valeur != 51) ? 1 : 44, // CIV
                    // Begin Vue
                    1 => ($provider_valeur != 51) ? 248 : 241, // USA
                    218 => ($provider_valeur != 51) ? 264 : 292, // Tunisia
                    146 => ($provider_valeur != 51) ? 278 : 302, // Maroc
                    199 => ($provider_valeur != 51) ? 435 : 312, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 467 : 355, // France
                    179 => ($provider_valeur != 51) ? 531 : 419, // Russie
                    226 => ($provider_valeur != 51) ? 499 : 387, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 515 : 403, // Italie
                    82 => ($provider_valeur != 51) ? 483 : 371, // Allemagne
                    // End view
                    39 => ($provider_valeur != 51) ? 79 : 107, // Cameroun
                    79 => ($provider_valeur != 51) ? 85 : 113, // Gabon
                    50 => ($provider_valeur != 51) ? 90 : 118, // COngo Braza
                    57 => ($provider_valeur != 51) ? 94 : 122, // RDC
                    122 => ($provider_valeur != 51) ? 97 : 125, // Liberia
                    43 => ($provider_valeur != 51) ? 99 : 127, // Thacd
                    42 => ($provider_valeur != 51) ? 100 : 128, // Afrique centrale
                    // New countries
                    92 => ($provider_valeur != 51) ? 632 : 652, // Guinnée Buisso voir
                    223 => ($provider_valeur != 51) ? 633 : 655, // Ouganda 
                    223 => ($provider_valeur != 51) ? 634 : 656, // Rwanda 

                    158 => ($provider_valeur != 51) ? 635 : 654, // Nigeria
                    191 => ($provider_valeur != 51) ? 636 : 653, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 638 : 668, // Koweit
                    212 => ($provider_valeur != 51) ? 639 : 657, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3010 : 669, // Quatar voirs
                    241 => ($provider_valeur != 51) ? 637 : 660, // Zambie
                    100 => ($provider_valeur != 51) ? 640 : 672, // Inde
                    69 => ($provider_valeur != 51) ? 641 : 658, // Ethiopie
                    66 => ($provider_valeur != 51) ? 642 : 662, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 643 : 661, // Angola
                    225 => ($provider_valeur != 51) ? 644 : 670, // Dubai
                    112 => ($provider_valeur != 51) ? 645 : 659, // Keneya
                    45 => ($provider_valeur != 51) ? 646 : 671, // China
                    208 => ($provider_valeur != 51) ? 647 : 683, // Suisse voir
                    23 => ($provider_valeur != 51) ? 664 : 648, // Belgique
                    173 => ($provider_valeur != 51) ? 665 : 649, // Portugal
                    219 => ($provider_valeur != 51) ? 666 : 650, // Turquie
                    152 => ($provider_valeur != 51) ? 667 : 651, // Hollande
                ]
            ];
        } elseif (robus()->country->name_groupe == 'URP') {

            $countriesLevels = [
                // Is Espagne
                199 => [
                    25 => ($provider_valeur != 51) ? 435 : 312, // Benin
                    36 => ($provider_valeur != 51) ? 436 : 313, // BF
                    133 => ($provider_valeur != 51) ? 437 : 314, // Mali
                    214 => ($provider_valeur != 51) ? 438 : 315, // Togo
                    188 => ($provider_valeur != 51) ? 439 : 316, // Senegal
                    83 => ($provider_valeur != 51) ?  440 : 317, // Ghana
                    157 => ($provider_valeur != 51) ? 441 : 318, // Niger
                    91 => ($provider_valeur != 51) ?  442 : 319, // Guinne conakry
                    // End vue
                    107 => ($provider_valeur != 51) ? 443 : 320, // CIV
                    1 => ($provider_valeur == 51) ? 547 : null, // USA
                    218 => ($provider_valeur == 51) ? 556 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 563 : null, // Maroc

                    // 199 => ($provider_valeur != 51) ? 444 : 321, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 584 : null, // France
                    179 => ($provider_valeur == 51) ? 587 : null, // Russie
                    226 => ($provider_valeur == 51) ? 588 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 589 : null, // Italie
                    82 => ($provider_valeur == 51) ? 586 : null, // Allemagne

                    // End vue
                    39 => ($provider_valeur != 51) ? 444 : 321, // Cameroun
                    79 => ($provider_valeur != 51) ? 445 : 322, // Gabon
                    50 => ($provider_valeur != 51) ? 437 : 314, // COngo Braza
                    57 => ($provider_valeur != 51) ? 447 : 323, // RDC
                    122 => ($provider_valeur != 51) ? 448 : 324, // Liberia
                    43 => ($provider_valeur != 51) ? 448 : 325, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  450 : 326, // Afrique centrale
                    // New countries
                    92 => ($provider_valeur != 51) ? 1788 : 1833, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 743 : 711, // Ouganda 
                    // Voir rwanda
                    223 => ($provider_valeur != 51) ? 634 : 656, // Rwanda 

                    158 => ($provider_valeur != 51) ? 1968 : 2013, // Nigeria
                    191 => ($provider_valeur != 51) ? 1878 : 1923, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2642 : 2598, // Koweit
                    212 => ($provider_valeur != 51) ? 1338 : 1383, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2688 : 2733, // Quatar 
                    241 => ($provider_valeur != 51) ? 1608 : 1653, // Zambie
                    100 => ($provider_valeur != 51) ? 2958 : 3003, // Inde
                    69 => ($provider_valeur != 51) ? 1428 : 1463, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2103 : 2058, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1698 : 1743, // Angola
                    225 => ($provider_valeur != 51) ? 2778 : 2823, // Dubai
                    112 => ($provider_valeur != 51) ? 1508 : 1563, // Keneya
                    45 => ($provider_valeur != 51) ? 2858 : 2903, // China
                    208 => ($provider_valeur != 51) ? 2148 : 2193, // Suisse
                    23 => ($provider_valeur != 51) ? 2238 : 2283, // Belgique
                    173 => ($provider_valeur != 51) ? 2328 : 2373, // Portugal
                    219 => ($provider_valeur != 51) ? 2418 : 2463, // Turquie
                    152 => ($provider_valeur != 51) ? 2508 : 2553, // Hollande

                ],
                // France
                74 =>  [
                    25 => ($provider_valeur != 51) ?  467 : 355, // Benin
                    36 => ($provider_valeur != 51) ? 468 : 356, // BF
                    133 => ($provider_valeur != 51) ? 469 : 357, // Mali
                    214 => ($provider_valeur != 51) ? 470 : 358, // Togo
                    188 => ($provider_valeur != 51) ? 471 : 359, // Senegal
                    83 => ($provider_valeur != 51) ? 472 : 360, // Ghana
                    157 => ($provider_valeur != 51) ? 473 : 361, // Niger
                    91 => ($provider_valeur != 51) ? 474 : 362, // Guinne conakry
                    // vue France Ancien
                    107 => ($provider_valeur != 51) ? 85 : 113, // CIV
                    // En ancien
                    1 => ($provider_valeur == 51) ? 548 : null, // USA
                    218 => ($provider_valeur == 51) ? 557 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 564 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 584 : null, // ESPAGNE
                    // 74 => ($provider_valeur != 51) ? 477 : 365, // France

                    179 => ($provider_valeur == 51) ? 594 : null, // Russie
                    226 => ($provider_valeur == 51) ? 592 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 593 : null, // Italie
                    82 => ($provider_valeur == 51) ? 591 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 476 : 364, // Cameroun
                    79 => ($provider_valeur != 51) ? 477 : 365, // Gabon
                    50 => ($provider_valeur != 51) ? 469 : 357, // COngo Braza
                    57 => ($provider_valeur != 51) ? 479 : 367, // RDC
                    122 => ($provider_valeur != 51) ? 368 : 480, // Liberia
                    43 => ($provider_valeur != 51) ? 481 : 369, // Thacd
                    42 => ($provider_valeur != 51) ? 482 : 370, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 1764 : 1809, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 745 : 687, // Ouganda 
                    // Voir rwanda
                    223 => ($provider_valeur != 51) ? 634 : 656, // Rwanda 

                    158 => ($provider_valeur != 51) ? 1944 : 1989, // Nigeria
                    191 => ($provider_valeur != 51) ? 1854 : 1899, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2618 : 2574, // Koweit
                    212 => ($provider_valeur != 51) ? 821 : 776, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2664 : 2709, // Quatar 
                    241 => ($provider_valeur != 51) ? 1584 : 1629, // Zambie
                    100 => ($provider_valeur != 51) ? 2934 : 2979, // Inde
                    69 => ($provider_valeur != 51) ? 1404 : 1449, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2079 : 2034, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1674 : 1719, // Angola
                    225 => ($provider_valeur != 51) ? 2754 : 2799, // Dubai
                    112 => ($provider_valeur != 51) ? 1494 : 1539, // Keneya
                    45 => ($provider_valeur != 51) ? 2844 : 2889, // China
                    208 => ($provider_valeur != 51) ? 2124 : 2169, // Suisse
                    23 => ($provider_valeur != 51) ? 2214 : 2259, // Belgique
                    173 => ($provider_valeur != 51) ? 2304 : 2349, // Portugal
                    219 => ($provider_valeur != 51) ? 2418 : 2463, // Turquie
                    152 => ($provider_valeur != 51) ? 2394 : 2439, // Hollande
                ],
                // Allemagne
                82 =>  [
                    25 => ($provider_valeur != 51) ?  483 : 371, // Benin
                    36 => ($provider_valeur != 51) ? 484 : 372, // BF
                    133 => ($provider_valeur != 51) ? 485 : 373, // Mali
                    214 => ($provider_valeur != 51) ? 486 : 374, // Togo
                    188 => ($provider_valeur != 51) ? 487 : 375, // Senegal
                    83 => ($provider_valeur != 51) ? 488 : 376, // Ghana
                    157 => ($provider_valeur != 51) ? 489 : 377, // Niger
                    91 => ($provider_valeur != 51) ? 490 : 378, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 491 : 379, // CIV

                    1 => ($provider_valeur == 51) ? 550 : null, // USA
                    218 => ($provider_valeur == 51) ? 559 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 566 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 584 : null, // ESPAGNE
                    // 74 => ($provider_valeur != 51) ? 477 : 365, // France

                    179 => ($provider_valeur == 51) ? 601 : null, // Russie
                    226 => ($provider_valeur == 51) ? 599 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 600 : null, // Italie
                    // 82 => ($provider_valeur == 51) ? 591 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 524 : 412, // Cameroun
                    79 => ($provider_valeur != 51) ? 493 : 381, // Gabon
                    50 => ($provider_valeur != 51) ? 485 : 373, // COngo Braza
                    57 => ($provider_valeur != 51) ? 495 : 383, // RDC
                    122 => ($provider_valeur != 51) ? 496 : 384, // Liberia
                    43 => ($provider_valeur != 51) ? 497 : 385, // Thacd
                    42 => ($provider_valeur != 51) ? 498 : 386, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 1767 : 1812, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 748 : 690, // Ouganda 
                    // Voir rwanda
                    223 => ($provider_valeur != 51) ? 634 : 656, // Rwanda 

                    158 => ($provider_valeur != 51) ? 1947 : 1992, // Nigeria
                    191 => ($provider_valeur != 51) ? 1857 : 1902, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2621 : 2577, // Koweit
                    212 => ($provider_valeur != 51) ? 824 : 779, // Tanzanie

                    175 => ($provider_valeur != 51) ? 2667 : 2712, // Quatar
                    241 => ($provider_valeur != 51) ? 1587 : 1632, // Zambie
                    100 => ($provider_valeur != 51) ? 2937 : 2982, // Inde
                    69 => ($provider_valeur != 51) ? 1407 : 1452, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2082 : 2037, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1677 : 1722, // Angola
                    225 => ($provider_valeur != 51) ? 2757 : 2802, // Dubai
                    112 => ($provider_valeur != 51) ? 1497 : 1542, // Keneya
                    45 => ($provider_valeur != 51) ? 2847 : 2892, // China
                    208 => ($provider_valeur != 51) ? 2127 : 2172, // Suisse
                    23 => ($provider_valeur != 51) ? 2217 : 2262, // Belgique
                    173 => ($provider_valeur != 51) ? 2307 : 2352, // Portugal
                    219 => ($provider_valeur != 51) ? 2421 : 2466, // Turquie
                    152 => ($provider_valeur != 51) ? 2397 : 2442, // Hollande

                ],
                // Italie
                106 =>  [
                    25 => ($provider_valeur != 51) ?  515 : 403, // Benin
                    36 => ($provider_valeur != 51) ? 516 : 404, // BF
                    133 => ($provider_valeur != 51) ? 517 : 405, // Mali
                    214 => ($provider_valeur != 51) ? 518 : 406, // Togo
                    188 => ($provider_valeur != 51) ? 519 : 407, // Senegal
                    83 => ($provider_valeur != 51) ? 520 : 408, // Ghana
                    157 => ($provider_valeur != 51) ? 521 : 409, // Niger
                    91 => ($provider_valeur != 51) ? 522 : 410, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 523 : 411, // CIV

                    1 => ($provider_valeur == 51) ? 552 : null, // USA
                    218 => ($provider_valeur == 51) ? 561 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 568 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 589 : null, // ESPAGNE
                    // 74 => ($provider_valeur != 51) ? 477 : 365, // France

                    179 => ($provider_valeur == 51) ? 604 : null, // Russie
                    226 => ($provider_valeur == 51) ? 602 : null, // Royaume Unie
                    // 106 => ($provider_valeur == 51) ? 600 : null, // Italie
                    82 => ($provider_valeur == 51) ? 591 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 515 : 403, // Cameroun
                    79 => ($provider_valeur != 51) ? 525 : 413, // Gabon
                    50 => ($provider_valeur != 51) ? 517 : 405, // COngo Braza
                    57 => ($provider_valeur != 51) ? 527 : 415, // RDC
                    122 => ($provider_valeur != 51) ? 416 : 528, // Liberia
                    43 => ($provider_valeur != 51) ? 529 : 417, // Thacd
                    42 => ($provider_valeur != 51) ? 530 : 418, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 1772 : 1817, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 753 : 694, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 829 : 784, // Rwanda 

                    158 => ($provider_valeur != 51) ? 1952 : 1997, // Nigeria
                    191 => ($provider_valeur != 51) ? 1862 : 1907, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2626 : 2582, // Koweit
                    212 => ($provider_valeur != 51) ? 1322 : 1367, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2672 : 2717, // Quatar
                    241 => ($provider_valeur != 51) ? 1592 : 1637, // Zambie
                    100 => ($provider_valeur != 51) ? 2942 : 2987, // Inde
                    69 => ($provider_valeur != 51) ? 1412 : 1457, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2087 : 2042, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1682 : 1727, // Angola
                    225 => ($provider_valeur != 51) ? 2762 : 2807, // Dubai
                    112 => ($provider_valeur != 51) ? 1502 : 1547, // Keneya
                    45 => ($provider_valeur != 51) ? 2852 : 2897, // China
                    208 => ($provider_valeur != 51) ? 2132 : 2177, // Suisse
                    23 => ($provider_valeur != 51) ? 2222 : 2267, // Belgique
                    173 => ($provider_valeur != 51) ? 2312 : 2357, // Portugal
                    219 => ($provider_valeur != 51) ? 2426 : 2471, // Turquie
                    152 => ($provider_valeur != 51) ? 2402 : 2447, // Hollande

                ],
                // Royaume Unie
                226 =>  [
                    25 => ($provider_valeur != 51) ?  499 : 387, // Benin
                    36 => ($provider_valeur != 51) ? 500 : 388, // BF
                    133 => ($provider_valeur != 51) ? 501 : 389, // Mali
                    214 => ($provider_valeur != 51) ? 502 : 390, // Togo
                    188 => ($provider_valeur != 51) ? 503 : 391, // Senegal
                    83 => ($provider_valeur != 51) ? 504 : 392, // Ghana
                    157 => ($provider_valeur != 51) ? 505 : 393, // Niger
                    91 => ($provider_valeur != 51) ? 506 : 394, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 507 : 395, // CIV

                    1 => ($provider_valeur == 51) ? 551 : null, // USA
                    218 => ($provider_valeur == 51) ? 560 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 567 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 588 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 592 : null, // France

                    179 => ($provider_valeur == 51) ? 603 : null, // Russie
                    // 226 => ($provider_valeur == 51) ? 602 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 602 : null, // Italie
                    82 => ($provider_valeur == 51) ? 599 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 508 : 396, // Cameroun
                    79 => ($provider_valeur != 51) ? 509 : 397, // Gabon
                    50 => ($provider_valeur != 51) ? 501 : 389, // COngo Braza
                    57 => ($provider_valeur != 51) ? 511 : 399, // RDC
                    122 => ($provider_valeur != 51) ? 512 : 400, // Liberia
                    43 => ($provider_valeur != 51) ? 513 : 401, // Thacd
                    42 => ($provider_valeur != 51) ? 514 : 402, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 1794 : 1839, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 761 : 717, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 850 : 806, // Rwanda 

                    158 => ($provider_valeur != 51) ? 1974 : 2019, // Nigeria
                    191 => ($provider_valeur != 51) ? 1884 : 1929, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2648 : 2604, // Koweit
                    212 => ($provider_valeur != 51) ? 1344 : 1389, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2694 : 2739, // Quatar
                    241 => ($provider_valeur != 51) ? 1614 : 1695, // Zambie
                    100 => ($provider_valeur != 51) ? 2964 : 3009, // Inde
                    69 => ($provider_valeur != 51) ? 1434 : 1479, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2109 : 2064, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1704 : 1749, // Angola
                    225 => ($provider_valeur != 51) ? 2784 : 2829, // Dubai
                    112 => ($provider_valeur != 51) ? 1524 : 1569, // Keneya
                    45 => ($provider_valeur != 51) ? 2874 : 2919, // China
                    208 => ($provider_valeur != 51) ? 2154 : 2199, // Suisse
                    23 => ($provider_valeur != 51) ? 2244 : 2289, // Belgique
                    173 => ($provider_valeur != 51) ? 2334 : 2379, // Portugal
                    219 => ($provider_valeur != 51) ? 2448 : 2493, // Turquie
                    152 => ($provider_valeur != 51) ? 2424 : 2469, // Hollande

                ],
                // Ruissie
                179 =>  [
                    25 => ($provider_valeur != 51) ? 531 : 419, // Benin
                    36 => ($provider_valeur != 51) ? 532 : 420, // BF
                    133 => ($provider_valeur != 51) ? 533 : 421, // Mali
                    214 => ($provider_valeur != 51) ? 534 : 422, // Togo
                    188 => ($provider_valeur != 51) ? 535 : 423, // Senegal
                    83 => ($provider_valeur != 51) ? 536 : 424, // Ghana
                    157 => ($provider_valeur != 51) ? 537 : 425, // Niger
                    91 => ($provider_valeur != 51) ?  538 : 426, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 539 : 427, // CIV

                    1 => ($provider_valeur == 51) ? 554 : null, // USA
                    218 => ($provider_valeur == 51) ? 562 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 593 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 587 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 594 : null, // France

                    // 179 => ($provider_valeur == 51) ? 603 : null, // Russie
                    226 => ($provider_valeur == 51) ? 603 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 604 : null, // Italie
                    82 => ($provider_valeur == 51) ? 601 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 540 : 428, // Cameroun
                    79 => ($provider_valeur != 51) ? 541 : 429, // Gabon
                    50 => ($provider_valeur != 51) ? 533 : 421, // COngo Braza
                    57 => ($provider_valeur != 51) ? 543 : 431, // RDC
                    122 => ($provider_valeur != 51) ? 544 : 432, // Liberia
                    43 => ($provider_valeur != 51) ?  545 : 433, // Thacd
                    42 => ($provider_valeur != 51) ? 546 : 434, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3011 : 3030, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3012 : 3031, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3013 : 3032, // Rwanda 

                    158 => ($provider_valeur != 51) ? 3014 : 3033, // Nigeria
                    191 => ($provider_valeur != 51) ? 3015 : 3034, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3016 : 3035, // Koweit
                    212 => ($provider_valeur != 51) ? 3017 : 3036, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3018 : 3037, // Quatar
                    241 => ($provider_valeur != 51) ? 3019 : 3038, // Zambie
                    66 => ($provider_valeur != 51) ?  3020 : 3039, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 3021 : 3040, // Angola
                    225 => ($provider_valeur != 51) ? 3022 : 3041, // Dubai
                    112 => ($provider_valeur != 51) ? 3023 : 3042, // Keneya
                    45 => ($provider_valeur != 51) ? 3024 : 3043, // China
                    208 => ($provider_valeur != 51) ? 3025 : 3044, // Suisse
                    23 => ($provider_valeur != 51) ?  3026 : 3045, // Belgique
                    173 => ($provider_valeur != 51) ? 3027 : 3046, // Portugal
                    219 => ($provider_valeur != 51) ? 3028 : 3047, // Turquie
                    152 => ($provider_valeur != 51) ? 3029 : 3048, // Hollande

                    100 => ($provider_valeur != 51) ? 3092 : 3091, // Inde
                    69 => ($provider_valeur != 51) ? 3094 : 3093, // Ethiopie
                ],
                // Suisse
                208 =>  [

                    25 => ($provider_valeur != 51) ? 2114 : 2159, // Benin
                    36 => ($provider_valeur != 51) ? 2115 : 2160, // BF
                    133 => ($provider_valeur != 51) ? 2137 : 2182, // Mali
                    214 => ($provider_valeur != 51) ? 2151 : 2196, // Togo
                    188 => ($provider_valeur != 51) ? 2146 : 2191, // Senegal
                    83 => ($provider_valeur != 51) ? 2128 : 2173, // Ghana
                    157 => ($provider_valeur != 51) ? 2141 : 2186, // Niger
                    91 => ($provider_valeur != 51) ?  2129 : 2174, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 647 : 683, // CIV

                    1 => ($provider_valeur == 51) ? 2155 : null, // USA
                    218 => ($provider_valeur == 51) ? 3082 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2184 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2193 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 2169 : null, // France

                    179 => ($provider_valeur == 51) ? 3044 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2199 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2177 : null, // Italie
                    82 => ($provider_valeur == 51) ? 2172 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 2117 : 2162, // Cameroun
                    79 => ($provider_valeur != 51) ? 3109 : 3130, // Gabon
                    50 => ($provider_valeur != 51) ? 2121 : 2166, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2122 : 2167, // RDC
                    122 => ($provider_valeur != 51) ? 2136 : 2181, // Liberia
                    43 => ($provider_valeur != 51) ?  2119 : 2164, // Thacd
                    42 => ($provider_valeur != 51) ? 2118 : 2163, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3575 : 3595, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3576 : 3596, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3577 : 3597, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3578 : 3598, // Nigeria
                    191 => ($provider_valeur != 51) ? 3579 : 3599, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3580 : 3600, // Koweit
                    212 => ($provider_valeur != 51) ? 3581 : 3601, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3582 : 3602, // Quatar
                    241 => ($provider_valeur != 51) ? 3583 : 3603, // Zambie
                    66 => ($provider_valeur != 51) ?  3584 : 3604, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?  3585 : 3605, // Angola
                    225 => ($provider_valeur != 51) ? 3586 : 3606, // Dubai
                    112 => ($provider_valeur != 51) ? 3587 : 3607, // Keneya
                    45 => ($provider_valeur != 51) ? 3588 : 3568, // China
                    //   208 => ($provider_valeur != 51) ? 3548 : 3568, // Suisse
                    23 => ($provider_valeur != 51) ?  3589 : 3609, // Belgique
                    173 => ($provider_valeur != 51) ?  3590 : 3610, // Portugal
                    219 => ($provider_valeur != 51) ? 3591 : 3611, // Turquie
                    152 => ($provider_valeur != 51) ? 3592 : 3612, // Hollande

                    100 => ($provider_valeur != 51) ? 3593 : 3613, // Inde
                    69 => ($provider_valeur != 51) ? 3594 : 3614, // Ethiopie
                ],
                // Belgique
                23 =>  [

                    25 => ($provider_valeur != 51) ? 2204 : 2249, // Benin
                    36 => ($provider_valeur != 51) ? 2205 : 2250, // BF
                    133 => ($provider_valeur != 51) ?  2227 : 2272, // Mali
                    214 => ($provider_valeur != 51) ? 2241 : 2286, // Togo
                    188 => ($provider_valeur != 51) ? 2236 : 2281, // Senegal
                    83 => ($provider_valeur != 51) ? 2218 : 2263, // Ghana
                    157 => ($provider_valeur != 51) ? 2231 : 2276, // Niger
                    91 => ($provider_valeur != 51) ? 2219 : 2264, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 664 : 648, // CIV

                    1 => ($provider_valeur == 51) ? 2245 : null, // USA
                    218 => ($provider_valeur == 51) ? 3083 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2274 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2283 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 2259 : null, // France

                    179 => ($provider_valeur == 51) ? 3045 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2289 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2267 : null, // Italie
                    82 => ($provider_valeur == 51) ? 2262 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 2207 : 2252, // Cameroun
                    79 => ($provider_valeur != 51) ? 3110 : 3131, // Gabon
                    50 => ($provider_valeur != 51) ? 2211 : 2256, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2212 : 2257, // RDC
                    122 => ($provider_valeur != 51) ? 2226 : 2271, // Liberia
                    43 => ($provider_valeur != 51) ? 2209 : 2254, // Thacd
                    42 => ($provider_valeur != 51) ? 2208 : 2253, // Afrique centrale


                    // New countries
                    92 => ($provider_valeur != 51) ? 3615 : 3635, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3616 : 3636, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3617 : 3637, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3618 : 3638, // Nigeria
                    191 => ($provider_valeur != 51) ? 3619 : 3639, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3620 : 3640, // Koweit
                    212 => ($provider_valeur != 51) ?  3621 : 3641, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3622 : 3642, // Quatar
                    241 => ($provider_valeur != 51) ? 3623 : 3643, // Zambie
                    66 => ($provider_valeur != 51) ?  3624 : 3644, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?  3625 : 3645, // Angola
                    225 => ($provider_valeur != 51) ? 3626 : 3646, // Dubai
                    112 => ($provider_valeur != 51) ? 3627 : 3647, // Keneya
                    45 => ($provider_valeur != 51) ? 3628 : 3648, // China
                    208 => ($provider_valeur != 51) ? 3629 : 3649, // Suisse
                    // 23 => ($provider_valeur != 51) ?  3589 : 3609, // Belgique
                    173 => ($provider_valeur != 51) ?  3630 : 3650, // Portugal
                    219 => ($provider_valeur != 51) ? 3631 : 3651, // Turquie
                    152 => ($provider_valeur != 51) ? 3632 : 3652, // Hollande

                    100 => ($provider_valeur != 51) ? 3633 : 3653, // Inde
                    69 => ($provider_valeur != 51) ? 3634 : 3654, // Ethiopie
                ],
                // PORTUGAL
                173 =>  [

                    25 => ($provider_valeur != 51) ? 2294 : 2339, // Benin
                    36 => ($provider_valeur != 51) ? 2295 : 2340, // BF
                    133 => ($provider_valeur != 51) ?  2317 : 2362, // Mali
                    214 => ($provider_valeur != 51) ? 2331 : 2376, // Togo
                    188 => ($provider_valeur != 51) ? 2326 : 2371, // Senegal
                    83 => ($provider_valeur != 51) ? 2308 : 2353, // Ghana
                    157 => ($provider_valeur != 51) ? 2231 : 2276, // Niger
                    91 => ($provider_valeur != 51) ? 2309 : 2354, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 665 : 649, // CIV

                    1 => ($provider_valeur == 51) ? 2335 : null, // USA
                    218 => ($provider_valeur == 51) ? 3084 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2364 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2373 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 2349 : null, // France

                    179 => ($provider_valeur == 51) ? 3046 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2379 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2357 : null, // Italie
                    82 => ($provider_valeur == 51) ? 2352 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 2297 : 2342, // Cameroun
                    79 => ($provider_valeur != 51) ? 3111 : 3132, // Gabon
                    50 => ($provider_valeur != 51) ? 2301 : 2346, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2302 : 2347, // RDC
                    122 => ($provider_valeur != 51) ? 2316 : 2361, // Liberia
                    43 => ($provider_valeur != 51) ? 2299 : 2344, // Thacd
                    42 => ($provider_valeur != 51) ? 2298 : 2343, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3655 : 3675, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3656 : 3676, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3657 : 3677, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3658 : 3678, // Nigeria
                    191 => ($provider_valeur != 51) ? 3659 : 3679, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3660 : 3680, // Koweit
                    212 => ($provider_valeur != 51) ?  3661 : 3681, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3662 : 3682, // Quatar
                    241 => ($provider_valeur != 51) ? 3663 : 3683, // Zambie
                    66 => ($provider_valeur != 51) ?  3664 : 3684, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?  3665 : 3685, // Angola
                    225 => ($provider_valeur != 51) ? 3666 : 3686, // Dubai
                    112 => ($provider_valeur != 51) ? 3667 : 3687, // Keneya
                    45 => ($provider_valeur != 51) ? 3668 : 3688, // China
                    208 => ($provider_valeur != 51) ? 3669 : 3689, // Suisse
                    23 => ($provider_valeur != 51) ?  3670 : 3609, // Belgique
                    // 173 => ($provider_valeur != 51) ?  3670 : 3690, // Portugal
                    219 => ($provider_valeur != 51) ? 3671 : 3691, // Turquie
                    152 => ($provider_valeur != 51) ? 3672 : 3692, // Hollande

                    100 => ($provider_valeur != 51) ? 3673 : 3693, // Inde
                    69 => ($provider_valeur != 51) ? 3674 : 3694, // Ethiopie
                ],
                // TURQUIE
                219 =>  [

                    25 => ($provider_valeur != 51) ? 2384 : 2429, // Benin
                    36 => ($provider_valeur != 51) ? 2385 : 2430, // BF
                    133 => ($provider_valeur != 51) ? 2407 : 2452, // Mali
                    214 => ($provider_valeur != 51) ? 2421 : 2466, // Togo
                    188 => ($provider_valeur != 51) ? 2416 : 2461, // Senegal
                    83 => ($provider_valeur != 51) ? 2398 : 2443, // Ghana
                    157 => ($provider_valeur != 51) ? 2411 : 2456, // Niger
                    91 => ($provider_valeur != 51) ? 2399 : 2444, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 666 : 650, // CIV

                    1 => ($provider_valeur == 51) ? 2425 : null, // USA
                    218 => ($provider_valeur == 51) ? 3085 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2454 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2373 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 2463 : null, // France

                    179 => ($provider_valeur == 51) ? 3047 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2493 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2471 : null, // Italie
                    82 => ($provider_valeur == 51) ? 2466 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 2387 : 2432, // Cameroun
                    79 => ($provider_valeur != 51) ? 3112 : 3133, // Gabon
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
                    // 219 => ($provider_valeur != 51) ? 3712 : 3691, // Turquie
                    152 => ($provider_valeur != 51) ? 3712 : 3732, // Hollande

                    100 => ($provider_valeur != 51) ? 3713 : 3733, // Inde
                    69 => ($provider_valeur != 51) ? 3714 : 3734, // Ethiopie
                ],

                // PAYS BAS(HOLLANDE)
                152 =>  [

                    25 => ($provider_valeur != 51) ? 2474 : 2519, // Benin
                    36 => ($provider_valeur != 51) ? 2475 : 2520, // BF
                    133 => ($provider_valeur != 51) ? 2497 : 2542, // Mali
                    214 => ($provider_valeur != 51) ? 2511 : 2556, // Togo
                    188 => ($provider_valeur != 51) ? 2506 : 2551, // Senegal
                    83 => ($provider_valeur != 51) ? 2488 : 2533, // Ghana
                    157 => ($provider_valeur != 51) ? 2501 : 2546, // Niger
                    91 => ($provider_valeur != 51) ? 2489 : 2534, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 667 : 651, // CIV

                    1 => ($provider_valeur == 51) ? 2515 : null, // USA
                    218 => ($provider_valeur == 51) ? 3086 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2544 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2553 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 2439 : null, // France

                    179 => ($provider_valeur == 51) ? 3048 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2469 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2447 : null, // Italie
                    82 => ($provider_valeur == 51) ? 2442 : null, // Allemagne

                    39 => ($provider_valeur != 51) ? 2477 : 2522, // Cameroun
                    79 => ($provider_valeur != 51) ? 3113 : 3134, // Gabon
                    50 => ($provider_valeur != 51) ? 2481 : 2526, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2482 : 2527, // RDC
                    122 => ($provider_valeur != 51) ?  2496 : 2541, // Liberia
                    43 => ($provider_valeur != 51) ? 2479 : 2524, // Thacd
                    42 => ($provider_valeur != 51) ? 2388 : 2433, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3735 : 3755, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3736 : 3756, // Ouganda 
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
                    208 => ($provider_valeur != 51) ? 3749 : 3769, // Suisse
                    23 => ($provider_valeur != 51) ?  3750 : 3770, // Belgique
                    173 => ($provider_valeur != 51) ?  3751 : 3771, // Portugal
                    219 => ($provider_valeur != 51) ? 3752 : 3772, // Turquie
                    //   152 => ($provider_valeur != 51) ? 3712 : 3732, // Hollande

                    100 => ($provider_valeur != 51) ? 3753 : 3773, // Inde
                    69 => ($provider_valeur != 51) ? 3754 : 3774, // Ethiopie
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif (robus()->country->name_groupe == 'USA') {

            $countriesLevels = [
                // Is USA
                1 => [
                    25 => ($provider_valeur != 51) ? 248 : 241, // Benin
                    36 => ($provider_valeur != 51) ? 249 : 242, // BF
                    133 => ($provider_valeur != 51) ? 570 : 243, // Mali
                    214 => ($provider_valeur != 51) ? 250 : 244, // Togo
                    188 => ($provider_valeur != 51) ? 251 : 245, // Senegal
                    83 => ($provider_valeur != 51) ? 252 : 571, // Ghana
                    157 => ($provider_valeur != 51) ? 253 : 246, // Niger
                    91 => ($provider_valeur != 51) ? 35 : 71, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 79 : 107, // CIV
                    // 1 => ($provider_valeur != 51) ? 255 : 234, // USA
                    218 => ($provider_valeur == 51) ? 554 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 555 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 547 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 548 : null, // France
                    179 => ($provider_valeur == 51) ? 553 : null, // Russie
                    226 => ($provider_valeur == 51) ? 551 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 552 : null, // Italie
                    82 => ($provider_valeur == 51) ? 550 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 255 : 234, // Cameroun
                    79 => ($provider_valeur != 51) ? 256 : 235, // Gabon
                    50 => ($provider_valeur != 51) ? 570 : 243, // COngo Braza
                    57 => ($provider_valeur != 51) ? 258 : 237, // RDC
                    122 => ($provider_valeur != 51) ? 238 : 259, // Liberia
                    43 => ($provider_valeur != 51) ? 260 : 239, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ? 261 : 240, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 1750 : 1795, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 731 : 673, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 837 : 792, // Rwanda 

                    158 => ($provider_valeur != 51) ? 1930 : 1975, // Nigeria
                    191 => ($provider_valeur != 51) ? 1840 : 1885, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 2604 : 2560, // Koweit
                    212 => ($provider_valeur != 51) ? 1851 : 1345, // Tanzanie
                    175 => ($provider_valeur != 51) ? 2650 : 2695, // Quatar
                    241 => ($provider_valeur != 51) ? 1570 : 1615, // Zambie
                    100 => ($provider_valeur != 51) ? 2920 : 2965, // Inde
                    69 => ($provider_valeur != 51) ? 1390 : 1435, // Ethiopie
                    66 => ($provider_valeur != 51) ? 2065 : 2020, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 1660 : 1705, // Angola
                    225 => ($provider_valeur != 51) ? 2740 : 2785, // Dubai
                    112 => ($provider_valeur != 51) ? 1480 : 1525, // Keneya
                    45 => ($provider_valeur != 51) ? 2830 : 2875, // China
                    208 => ($provider_valeur != 51) ? 2110 : 2155, // Suisse
                    23 => ($provider_valeur != 51) ? 2200 : 2245, // Belgique
                    173 => ($provider_valeur != 51) ? 2290 : 2335, // Portugal
                    219 => ($provider_valeur != 51) ? 2380 : 2425, // Turquie
                    152 => ($provider_valeur != 51) ? 2470 : 2515, // Hollande
                ],
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif (robus()->country->name_groupe == 'MAG') {

            $countriesLevels = [
                // Is Tunisia
                218 => [
                    25 => ($provider_valeur != 51) ? 264 : 292, // Benin
                    36 => ($provider_valeur != 51) ? 265 : 293, // BF
                    133 => ($provider_valeur != 51) ? 574 : 294, // Mali
                    214 => ($provider_valeur != 51) ? 266 : 295, // Togo
                    188 => ($provider_valeur != 51) ? 267 : 296, // Senegal
                    83 => ($provider_valeur != 51) ? 268 : 297, // Ghana
                    157 => ($provider_valeur != 51) ? 269 : 298, // Niger
                    91 => ($provider_valeur != 51) ? 270 : 299, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 310 : 300, // CIV
                    1 => ($provider_valeur != 51) ? 255 : 234, // USA
                    // 218 => ($provider_valeur == 51) ? 554 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 593 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 556 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 557 : null, // France
                    179 => ($provider_valeur == 51) ? 562 : null, // Russie
                    226 => ($provider_valeur == 51) ? 560 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 561 : null, // Italie
                    82 => ($provider_valeur == 51) ? 559 : null, // Allemagne
                    39 => ($provider_valeur != 51) ?  271 : 327, // Cameroun
                    79 => ($provider_valeur != 51) ? 272 : 328, // Gabon
                    50 => ($provider_valeur != 51) ? 574 : 294, // COngo Braza
                    57 => ($provider_valeur != 51) ? 374 : 329, // RDC
                    122 => ($provider_valeur != 51) ? 275 : 330, // Liberia
                    43 => ($provider_valeur != 51) ? 276 : 331, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ? 277 : 332, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3049 : 3068, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3050 : 3069, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3051 : 3070, // Rwanda 

                    158 => ($provider_valeur != 51) ? 3052 : 3071, // Nigeria
                    191 => ($provider_valeur != 51) ? 3053 : 3072, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3054 : 3073, // Koweit
                    212 => ($provider_valeur != 51) ? 3055 : 3074, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3056 : 3075, // Quatar
                    241 => ($provider_valeur != 51) ? 3057 : 3076, // Zambie
                    66 => ($provider_valeur != 51) ?  3058 : 3077, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ? 3059 : 3078, // Angola
                    225 => ($provider_valeur != 51) ? 3060 : 3079, // Dubai
                    112 => ($provider_valeur != 51) ? 3061 : 3080, // Keneya
                    45 => ($provider_valeur != 51) ? 3062 : 3081, // China
                    208 => ($provider_valeur != 51) ? 3063 : 3082, // Suisse
                    23 => ($provider_valeur != 51) ?  3064 : 3083, // Belgique
                    173 => ($provider_valeur != 51) ? 3065 : 3084, // Portugal
                    219 => ($provider_valeur != 51) ? 3066 : 3085, // Turquie
                    152 => ($provider_valeur != 51) ? 3067 : 3086, // Hollande

                    100 => ($provider_valeur != 51) ? 3087 : 3088, // Inde
                    69 => ($provider_valeur != 51) ? 3089 : 3090, // Ethiopie
                ],
                // Is Maroc
                146 => [
                    25 => ($provider_valeur != 51) ?  278 : 302, // Benin
                    36 => ($provider_valeur != 51) ? 279 : 302, // BF
                    133 => ($provider_valeur != 51) ? 575 : 303, // Mali
                    214 => ($provider_valeur != 51) ? 280 : 304, // Togo
                    188 => ($provider_valeur != 51) ? 281 : 305, // Senegal
                    83 => ($provider_valeur != 51) ? 282 : 306, // Ghana
                    157 => ($provider_valeur != 51) ? 283 : 307, // Niger
                    91 => ($provider_valeur != 51) ? 284 : 308, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 311 : 309, // CIV
                    1 => ($provider_valeur == 51) ? 555 : null, // USA
                    218 => ($provider_valeur == 51) ? 593 : null, // Tunisia
                    // 146 => ($provider_valeur == 51) ? 593 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 563 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 564 : null, // France
                    179 => ($provider_valeur == 51) ? 569 : null, // Russie
                    226 => ($provider_valeur == 51) ? 567 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 568 : null, // Italie
                    82 => ($provider_valeur == 51) ? 566 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 285 : 333, // Cameroun
                    79 => ($provider_valeur != 51) ? 286 : 334, // Gabon
                    50 => ($provider_valeur != 51) ? 575 : 303, // COngo Braza
                    57 => ($provider_valeur != 51) ? 288 : 335, // RDC
                    122 => ($provider_valeur != 51) ? 336 : 289, // Liberia
                    43 => ($provider_valeur != 51) ? 290 : 337, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  291 : 338, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 1779 : 1824, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 730 : 702, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 836 : 791, // Rwanda 

                    158 => ($provider_valeur != 51) ? 1959 : 2004, // Nigeria
                    191 => ($provider_valeur != 51) ? 1869 : 1914, // Sierria Leone
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
                    218 => ($provider_valeur == 51) ? 3069 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 702 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 711 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 687 : null, // France
                    179 => ($provider_valeur == 51) ? 3031 : null, // Russie
                    226 => ($provider_valeur == 51) ? 717 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 694 : null, // Italie
                    82 => ($provider_valeur == 51) ? 690 : null, // Allemagne
                    39 => ($provider_valeur != 51) ?  721 : 680, // Cameroun
                    79 => ($provider_valeur != 51) ? 3096 : 3117, // Gabon
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

                    25 => ($provider_valeur != 51) ?  811 : 766, // Benin
                    36 => ($provider_valeur != 51) ? 812 : 767, // BF
                    133 => ($provider_valeur != 51) ? 834 : 789, // Mali
                    214 => ($provider_valeur != 51) ? 847 : 803, // Togo
                    188 => ($provider_valeur != 51) ? 742 : 798, // Senegal
                    83 => ($provider_valeur != 51) ? 825 : 780, // Ghana
                    157 => ($provider_valeur != 51) ? 1330 : 1376, // Niger
                    91 => ($provider_valeur != 51) ? 826 : 781, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 634 : 656, // CIV
                    1 => ($provider_valeur == 51) ? 555 : null, // USA
                    218 => ($provider_valeur == 51) ? 593 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 791 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 800 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 776 : null, // France
                    179 => ($provider_valeur == 51) ? 3032 : null, // Russie
                    226 => ($provider_valeur == 51) ? 806 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 784 : null, // Italie
                    82 => ($provider_valeur == 51) ? 779 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 814 : 769, // Cameroun
                    79 => ($provider_valeur != 51) ? 3097 : 3118, // Gabon
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

                    25 => ($provider_valeur != 51) ?  1304 : 1349, // Benin
                    36 => ($provider_valeur != 51) ? 1305 : 1350, // BF
                    133 => ($provider_valeur != 51) ? 1327 : 1372, // Mali
                    214 => ($provider_valeur != 51) ? 1341 : 1386, // Togo
                    188 => ($provider_valeur != 51) ? 1336 : 1381, // Senegal
                    83 => ($provider_valeur != 51) ? 1318 : 1363, // Ghana
                    157 => ($provider_valeur != 51) ? 1331 : 1376, // Niger
                    91 => ($provider_valeur != 51) ? 1319 : 1364, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 639 : 657, // CIV
                    1 => ($provider_valeur == 51) ? 1345 : null, // USA
                    218 => ($provider_valeur == 51) ? 3074 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 1374 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 1383 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 776 : null, // France
                    179 => ($provider_valeur == 51) ? 3036 : null, // Russie
                    226 => ($provider_valeur == 51) ? 1389 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1367 : null, // Italie
                    82 => ($provider_valeur == 51) ? 779 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 1307 : 1352, // Cameroun
                    79 => ($provider_valeur != 51) ? 3101 : 3122, // Gabon
                    50 => ($provider_valeur != 51) ? 1311 : 1356, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1312 : 1357, // RDC
                    122 => ($provider_valeur != 51) ? 1866 : 1911, // Liberia
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
                    25 => ($provider_valeur != 51) ? 1394 : 1439, // Benin
                    36 => ($provider_valeur != 51) ? 1395 : 1440, // BF
                    133 => ($provider_valeur != 51) ? 1417 : 1462, // Mali
                    214 => ($provider_valeur != 51) ? 1431 : 1476, // Togo
                    188 => ($provider_valeur != 51) ? 1426 : 1471, // Senegal
                    83 => ($provider_valeur != 51) ? 1408 : 1453, // Ghana
                    157 => ($provider_valeur != 51) ? 1421 : 1466, // Niger
                    91 => ($provider_valeur != 51) ? 1409 : 1454, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 641 : 658, // CIV
                    1 => ($provider_valeur == 51) ? 1435 : null, // USA
                    218 => ($provider_valeur == 51) ? 3090 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 1464 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 1463 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 1449 : null, // France
                    179 => ($provider_valeur == 51) ? 3093 : null, // Russie
                    226 => ($provider_valeur == 51) ? 1479 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1457 : null, // Italie
                    82 => ($provider_valeur == 51) ? 1452 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 1397 : 1442, // Cameroun
                    79 => ($provider_valeur != 51) ? 3115 : 3136, // Gabon
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
                    133 => ($provider_valeur != 51) ? 1507 : 1552, // Mali
                    214 => ($provider_valeur != 51) ? 1521 : 1566, // Togo
                    188 => ($provider_valeur != 51) ? 1516 : 1561, // Senegal
                    83 => ($provider_valeur != 51) ? 1498 : 1543, // Ghana
                    157 => ($provider_valeur != 51) ? 1511 : 1556, // Niger
                    91 => ($provider_valeur != 51) ? 1499 : 1544, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 645 : 659, // CIV
                    1 => ($provider_valeur == 51) ? 1525 : null, // USA
                    218 => ($provider_valeur == 51) ? 3080 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 1554 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 1563 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 1539 : null, // France
                    179 => ($provider_valeur == 51) ? 3042 : null, // Russie
                    226 => ($provider_valeur == 51) ? 1569 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1547 : null, // Italie
                    82 => ($provider_valeur == 51) ? 1542 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 1487 : 1532, // Cameroun
                    79 => ($provider_valeur != 51) ? 3107 : 3128, // Gabon
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
                    // 112 => ($provider_valeur != 51) ?  3387 : 3407, // Keneya
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
                    179 => ($provider_valeur == 51) ? 3038 : null, // Russie
                    226 => ($provider_valeur == 51) ? 1695 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1637 : null, // Italie
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
                    // 241 => ($provider_valeur != 51) ? 3423 : 3443, // Zambie
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

                    25 => ($provider_valeur != 51) ?  1664 : 1709, // Benin
                    36 => ($provider_valeur != 51) ? 1665 : 1710, // BF
                    133 => ($provider_valeur != 51) ? 1687 : 1732, // Mali
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
                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } elseif (robus()->country->name_groupe == 'ASIE') {

            $countriesLevels = [
                // Is KOWEIT
                116 => [

                    25 => ($provider_valeur != 51) ? 2609 : 2564, // Benin
                    36 => ($provider_valeur != 51) ? 2610 : 2565, // BF
                    133 => ($provider_valeur != 51) ? 2632 : 2587, // Mali
                    214 => ($provider_valeur != 51) ? 2646 : 2601, // Togo
                    188 => ($provider_valeur != 51) ? 2641 : 2596, // Senegal
                    83 => ($provider_valeur != 51) ? 2623 : 2578, // Ghana
                    157 => ($provider_valeur != 51) ? 2636 : 2591, // Niger
                    91 => ($provider_valeur != 51) ? 2624 : 2579, // Guinne conakry
                    107 => ($provider_valeur != 51) ?  638 : 668, // CIV
                    1 => ($provider_valeur != 51) ? 731 : 2560, // USA
                    218 => ($provider_valeur == 51) ? 3073 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2589 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2598 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 2574 : null, // France
                    179 => ($provider_valeur == 51) ? 3035 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2604 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2582 : null, // Italie
                    82 => ($provider_valeur == 51) ? 2577 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 2612 : 2567, // Cameroun
                    79 => ($provider_valeur != 51) ? 3100 : 3121, // Gabon
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
                    152 => ($provider_valeur != 51) ? 3792 : 3812, // Hollande

                    100 => ($provider_valeur != 51) ? 3793 : 3813, // Inde
                    69 => ($provider_valeur != 51) ? 3794 : 3814, // Ethiopie
                ],
                // Is QATAR
                175 => [

                    25 => ($provider_valeur != 51) ?  2654 : 2699, // Benin
                    36 => ($provider_valeur != 51) ? 2655 : 2700, // BF
                    133 => ($provider_valeur != 51) ? 2677 : 2722, // Mali
                    214 => ($provider_valeur != 51) ? 2691 : 2736, // Togo
                    188 => ($provider_valeur != 51) ? 2686 : 2731, // Senegal
                    83 => ($provider_valeur != 51) ? 2668 : 2713, // Ghana
                    157 => ($provider_valeur != 51) ? 2681 : 2726, // Niger
                    91 => ($provider_valeur != 51) ? 2669 : 2714, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 3010 : 669, // CIV
                    1 => ($provider_valeur == 51) ? 2695 : null, // USA
                    218 => ($provider_valeur == 51) ? 3075 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2724 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2733 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 2709 : null, // France
                    179 => ($provider_valeur == 51) ? 3037 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2739 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2717 : null, // Italie
                    82 => ($provider_valeur == 51) ? 2712 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 2657 : 2702, // Cameroun
                    79 => ($provider_valeur != 51) ? 3102 : 3123, // Gabon
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
                    // 175 => ($provider_valeur != 51) ? 3781 : 3801, // Quatar
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
                    152 => ($provider_valeur != 51) ?  3832 : 3852, // Hollande

                    100 => ($provider_valeur != 51) ? 3833 : 3853, // Inde
                    69 => ($provider_valeur != 51) ? 3834 : 3854, // Ethiopie
                ],
                // Is TANZANIE
                212 => [

                    25 => ($provider_valeur != 51) ?  1304 : 1349, // Benin
                    36 => ($provider_valeur != 51) ? 1305 : 1350, // BF
                    133 => ($provider_valeur != 51) ? 1327 : 1372, // Mali
                    214 => ($provider_valeur != 51) ? 1341 : 1386, // Togo
                    188 => ($provider_valeur != 51) ? 1336 : 1381, // Senegal
                    83 => ($provider_valeur != 51) ? 1318 : 1363, // Ghana
                    157 => ($provider_valeur != 51) ? 1331 : 1376, // Niger
                    91 => ($provider_valeur != 51) ? 1319 : 1364, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 639 : 657, // CIV
                    1 => ($provider_valeur == 51) ? 1345 : null, // USA
                    218 => ($provider_valeur == 51) ? 3074 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 1374 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 1383 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 776 : null, // France
                    179 => ($provider_valeur == 51) ? 3036 : null, // Russie
                    226 => ($provider_valeur == 51) ? 1389 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 1367 : null, // Italie
                    82 => ($provider_valeur == 51) ? 779 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 1307 : 1352, // Cameroun
                    79 => ($provider_valeur != 51) ? 3101 : 3122, // Gabon
                    50 => ($provider_valeur != 51) ? 1311 : 1356, // COngo Braza
                    57 => ($provider_valeur != 51) ? 1312 : 1357, // RDC
                    122 => ($provider_valeur != 51) ? 1866 : 1911, // Liberia
                    43 => ($provider_valeur != 51) ? 1309 : 1354, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  1308 : 1353, // Afrique centrale

                    // New countries
                    92 => ($provider_valeur != 51) ? 3335 : 3355, // Guinnée Buisso 
                    223 => ($provider_valeur != 51) ? 3336 : 3356, // Ouganda 
                    // Voir rwanda
                    180 => ($provider_valeur != 51) ? 3337 : 3357, // Rwanda 
                    158 => ($provider_valeur != 51) ? 3338 : 3358, // Nigeria
                    191 => ($provider_valeur != 51) ?  3339 : 3359, // Sierria Leone
                    116 => ($provider_valeur != 51) ? 3340 : 3360, // Koweit
                    // 212 => ($provider_valeur != 51) ?  3821 : 3841, // Tanzanie
                    175 => ($provider_valeur != 51) ? 3341 : 3361, // Quatar
                    241 => ($provider_valeur != 51) ? 3342 : 3362, // Zambie
                    66 => ($provider_valeur != 51) ?  3343 : 3363, // Guinnée Equatoriale
                    8 => ($provider_valeur != 51) ?   3344 : 3364, // Angola
                    225 => ($provider_valeur != 51) ? 3345 : 3365, // Dubai
                    112 => ($provider_valeur != 51) ?  3346 : 3366, // Keneya
                    45 => ($provider_valeur != 51) ? 3347 : 3367, // China
                    208 => ($provider_valeur != 51) ? 3348 : 3368, // Suisse
                    23 => ($provider_valeur != 51) ?  3349 : 3369, // Belgique
                    173 => ($provider_valeur != 51) ?  3350 : 3370, // Portugal
                    219 => ($provider_valeur != 51) ?  3351 : 3371, // Turquie
                    152 => ($provider_valeur != 51) ?  3352 : 3372, // Hollande

                    100 => ($provider_valeur != 51) ? 3353 : 3373, // Inde
                    69 => ($provider_valeur != 51) ? 3354 : 3374, // Ethiopie
                ],
                // Is DUBAI
                225 => [
                    25 => ($provider_valeur != 51) ? 2744 : 2789, // Benin
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
                    74 => ($provider_valeur == 51) ? 2799 : null, // France
                    179 => ($provider_valeur == 51) ? 3041 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2829 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2807 : null, // Italie
                    82 => ($provider_valeur == 51) ? 2802 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 2747 : 2792, // Cameroun
                    79 => ($provider_valeur != 51) ? 2751 : 2796, // Gabon
                    50 => ($provider_valeur != 51) ? 1401 : 1446, // COngo Braza
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
                    152 => ($provider_valeur != 51) ?  3872 : 3892, // Hollande

                    100 => ($provider_valeur != 51) ? 3873 : 3893, // Inde
                    69 => ($provider_valeur != 51) ? 3874 : 3894, // Ethiopie
                ],
                // Is CHINE
                45 => [
                    25 => ($provider_valeur != 51) ?  2834 : 2879, // Benin
                    36 => ($provider_valeur != 51) ? 2835 : 2880, // BF
                    133 => ($provider_valeur != 51) ? 2857 : 2902, // Mali
                    214 => ($provider_valeur != 51) ? 2871 : 2916, // Togo
                    188 => ($provider_valeur != 51) ? 2866 : 2911, // Senegal
                    83 => ($provider_valeur != 51) ? 2848 : 2893, // Ghana
                    157 => ($provider_valeur != 51) ? 2861 : 2906, // Niger
                    91 => ($provider_valeur != 51) ? 2849 : 2894, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 646 : 671, // CIV
                    1 => ($provider_valeur == 51) ? 2875 : null, // USA
                    218 => ($provider_valeur == 51) ? 3081 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 2904 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 2903 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 2889 : null, // France
                    179 => ($provider_valeur == 51) ? 3043 : null, // Russie
                    226 => ($provider_valeur == 51) ? 2919 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2897 : null, // Italie
                    82 => ($provider_valeur == 51) ? 2892 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 2837 : 2882, // Cameroun
                    79 => ($provider_valeur != 51) ? 3108 : 3129, // Gabon
                    50 => ($provider_valeur != 51) ? 2841 : 2886, // COngo Braza
                    57 => ($provider_valeur != 51) ? 2842 : 2887, // RDC
                    122 => ($provider_valeur != 51) ? 2856 : 2901, // Liberia
                    43 => ($provider_valeur != 51) ? 2839 : 2884, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ?  2838 : 2883, // Afrique centrale

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
                    152 => ($provider_valeur != 51) ?  3912 : 3932, // Hollande

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
                    74 => ($provider_valeur == 51) ? 2979 : null, // France
                    179 => ($provider_valeur == 51) ? 3091 : null, // Russie
                    226 => ($provider_valeur == 51) ? 3009 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 2987 : null, // Italie
                    82 => ($provider_valeur == 51) ? 2982 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 2927 : 2972, // Cameroun
                    79 => ($provider_valeur != 51) ? 3114 : 3135, // Gabon
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
                    152 => ($provider_valeur != 51) ?  3953 : 3973, // Hollande

                    // 100 => ($provider_valeur != 51) ? 3913 : 3933, // Inde
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
