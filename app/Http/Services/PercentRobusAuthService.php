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
                    8 => ($provider_valeur != 51) ? 1665 : 1710, // Angola
                    225 => ($provider_valeur != 51) ? 2767 : 2812, // Dubai
                    112 => ($provider_valeur != 51) ? 1507 : 1552, // Keneya
                    45 => ($provider_valeur != 51) ? 2857 : 2902, // China
                    208 => ($provider_valeur != 51) ? 2137 : 2182, // Suisse
                    23 => ($provider_valeur != 51) ? 2227 : 2272, // Belgique
                    173 => ($provider_valeur != 51) ? 2317 : 2362, // Portugal
                    219 => ($provider_valeur != 51) ? 2407 : 2452, // Turquie
                    152 => ($provider_valeur != 51) ? 2497 : 2542, // Hollande
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


                ]

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

                ],

                // Ajouter les new pays
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
                    219 => ($provider_valeur != 51) ? 2406 : 2451, // Turquie
                    152 => ($provider_valeur != 51) ? 2389 : 2434, // Hollande
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

                ]
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
                    92 => ($provider_valeur != 51) ? 1788 : 1923, // Guinnée Buisso 
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

                    // Pas encore regle
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
                    116 => ($provider_valeur != 51) ? 2604: 2560, // Koweit
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
