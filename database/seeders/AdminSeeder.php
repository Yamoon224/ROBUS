<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sid_users')->delete();

        $time = Carbon::now()->toDateTimeString();

    

            // -USA
            // -Algerie
            // - Angola
            // - Benin
            // - BF
            // - Burundi
            // - Cameroun
            // - Afrique centrale
            // - Thacd
            // - China
            // - COngo Braza
            // - RDC
            // - Egypte
            // - Guinnée Equatoriale
            // - Ethiopie
            // - France
            // - Gabon
            // - Gambie
            // - Allemagne
            // - Ghana
            // - Inde
            // - Italie
            // - CIV
            // - Keneya 
            // -Koweit 
            // - Liban 
            // - Liberia 
            // - Mali 
            // - Mauritanie 
            // - Maroc
            // - Hollande 
            // - Niger 
            // - Nigeria 
            // - Portugal 
            // - Quatar 
            // - RWanda 
            // - Senegal 
            // - Sierria Leone
            // - ESPAGNE
            // - Suisse 
            // - Tanzanie 
            // - Togo 
            // - Turquie
            // - Ouganda
            // - Dubai 
            // - Royaume Unie
            // - Tunisia
            // - Russie 
            // - Zambie
            // - Belgique

            // 483313210.97302
            // 483311278.9730161
            // 483313210.9730161
        DB::table('sid_users')->insert([
            'firstname' => 'Sabdy',
            'lastname' => 'Dev',
            'image' => 'data:image/;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAyVBMVEX///8EU30AUXwAT3sASXgATXoAS3kARnYAQ3QAQnSltsUAQ3EAP28AR3b29fP//vzh4+W0trgANWH5+PYAOmrO0NK/wcNKcY8AOmGyub+usLLKysuvu8aDkJyIlqLx8O9hg57r6ec9YHpQan8ARGosWnrb2dcAPWqYpbEAMGFkeosdUXReepF1iZlJa4VvgZE9Z4cPSm3By9RVb4TNysierLhbb4AuXoAeV33j4N1DY3yAjJcERWdOY3U+aIeSnaagpqySorBsh52WOrSvAAANIUlEQVR4nO1da1fiOhSFJE3baYFioVJQGJCHl4cOMirqHcH5/z/qgjqjtGlJc04fdy33WvqRdjfJeZ+TUukLX/jCF77whWLArVadpldvt7/t0W7XvaZTrbp5vxYK+p2z8+nprGUbFcYYZ+z9f8WwW7PT6flZp5/3Kyqj6vjdwYmhM40SUhaBEKox3TgZdH2nmvfrJoTbvL0oW4yLmYWYcmaVL27H/5td++DPhzqnUuQ+QLkxnPsPeb/8cYw3S5MlZfeXJTOXm3HeFOLgvIwsyZ0ZBcKt0YuTNxExav7ABNL7Q9J89Gt50wnBWZwob84wKDtZFGshvStLQ6P3Bs268vKm9RftkY6xO4Mg+qieN7U9au0WS4PfK8dKq537gWzPKinRe0Nl1s6Vn5fO/vyM3V7t5MbPeTTS5vfK0XzMR65Wnyw89RAPaj3lYLN6S54Rvz14OWvV0bszs9ign2De9bIk6A+xFfxxaMPsltGd2pnz28OeZnQam+gWmiw03syC4MbK+AR+ArE2qfNzV3pu/PbQr1LeqU4OIuYQ2r+pqn8PxcWFgfAUZeplI3+CO4qN27QIXud7BD9gXKfCr/aL5c3sL/ivFNzG6o+8Zcxn8Gf0IHlvnZUjIQdthGym9mbFIrjzqGaoFHujohHcUcRcxeKt4B6Iq1gt4AruQe+RLLhaoaToZ2jPOErjV1EJ7vUiBsHr4ij6MDiCdXNZFFNNDP0SSrDeyJvDETSAnoaTZchQCYSD/EV3iOouEUL3iCjNUP3RfyE64wpNjBKN6ZPl/fz79+/z++VEZxoaTW2lTnCDJGU0Zg0W/th139RXzXXH/mJkMaTvpyuHp5oWxvMpa8x9UeFT3583cDLjlmKQ0cUIylDzeRtteLjbZxOBI+FqR3EK30TEejyW/Os8IsRfNSXF78ND92wmo6y8GVwl2QpasTeEPpVKB6g38EzkMLkndQfdo2wgr4qdAXQZtWlSgp4JfKS9SPS8BfRMmAmz/e4MWJ5m+ckeWGoDBQ6ZJZOn57BdQ4zkGqpjgB5ZZt0kT3OARTJKKhhoYJBKEhP8ESTbSEXNxmjCinPohfyjOjAxo6KcXuHBxI0p/2FHoG9pbBUJlkpb0FkkA9nn+CCXgv9WJlgq/QZJOF1SgNdAmiKp1D4ETEuRmVx0sQ0SpAkOgwhNkAioyBWkgiIX/AlEsFR6guxTMpR5RB20hASa1+uBojhSiwgSpBV4iv0WEoGWEac3EIEte9TjABN0xs3RB1xAzBmGUSUBWsTjho0DMg4bGLkgFxRmt45ZpwuI46tBBekbnkDvcMQvdU8AP162cLqyxqB9dBK/j3yQIBuhEASKcxZvuoHcJu0FiWEXsk3pY9xPOyDbXseqb+2ATH87Tta8gEz7CVbppzuBvAaPS5qCDgA9RSJYKp1CDkucOIAJsWNyOgFAOitOpG9Am5SdoTE8A1VH8GiJtwRFL3S8DiyYqCHLqN99gAWgdLwu7DEsNWtGzWkAqfudWY83/6EPiw5HKv05LANkoxEs1WBhRToX/6wLzKchMizBGJKhWDM3gXUJxWFYjkibXAJzeAViGGHWgLz7HUy84vIqMHsp9vTdNexXywbeYJIHYKatvBYdRJjJtoP+E43hT2ipkiX62kBtWC5Xjoe5ZHEDbfIXasQutFiA4/UGbqBlElyUEB5AC3e0CD2rgDmUoTAyDIpBvf7qCKsZCRYUfsVJ+FeBpuAeNlb/Qx9ejiUwkmEOyys41pCONrwMTODKwZzOV1BAKesBVvB6RYE7fo5QiajhbNM+Qv2wdh762SlCneeRYKwsELZTmYbr3E4RymWRgt6wUpD3VwkF/qCFbG8wMTrJx9CiwT3CBRPVFsLPivZGcgBDDe9oBT2dHs4wDwO+iLDMwl/YQYZIv4tgueEsYfhbQ0MYf2BBBx54KC0QO08nGMjwkHrwyBJmnNZgUekPsOCnrmN1GfKwqk0CYOnuB1iwsgbBFHwHqF3OQ2sGZEEjGY8hoeqx7z5F6/cKuQHf8HphqbKfWEPsimff0mNY1k7VKNbwmgFTZljmalpxjtmwmi7DMlOhOMd9hXQZlvlV0gB49Rm35TjEEE+WvkEbJbNQnRHyXIOQLMVmWKaJ+oJ8G3sCVUgfotk0HzCmskGN3hTH7v+MkE2DZZd+hjaUW8Z2GkPgQnYplm9xAKI/HzfhvB+VNGakhXwLJP8wCKrf+3G1YK5/r6cz/CbkHyL5+GGQyrLbFNs4tWZ3mcr67RHy8XHiNEIQboyevKA53veeRkaKQwpDcRqcWFsUiFYxW/OXrd90HKfpb1/mLbOCNzdC9MRwcxJGvDQeVGNMNwxDZ0xLffCUoFASI+ZdIAjimgh5iyJBkATGSBYUCILcE0L+sEgQ5A8RcsBFgqgAE5zHLxQEeXx4LUaRIGy6ANfTFAnCehpwTVSRIMxGPyClREQgmsYqum6Ypmnv/gxdrzAtTcNG2KMHrk0UglDOjNbzdfes83Pcf73y0K32xz87Z93r55bBOF6U+/NThbWJ4PpSwYO4QR9f6k6Uh1h16i+PNAUPI6KTFFojHHwKN2cvzeM5jH7zZWYmvmovHlzcrYsZyCB8crWVr6h9uL2aYK5kRJ033pBEao1ukyag+rcjtJt5omr1sTLoWmOq1h/UmTZwHJyofgsUjUi4dQ7IH55D7098RWRtVh9eqsOH57Datt75EGE+XeQ3hsZqaOMJ3vvUf2oAT0t07xpQXxB7hdPb1V/BshgxbbKggQp8iVerD7sQLG6sgnpVIGng9cjusVC/DiW2QlJ5m/IZ9k1MzbXyu8T1cjuKbeLGFP/W6apqym0Sm5u9UhFj5B+c2uAg/H9Udiq9iv9RBaWvrdO6hnm8VrBxjpRi15JvU/6Y3rV9vcfkh3FypJgnca+/jtcLJMI8qcNztHIwqUq0E83UVEA3YWLz6IyhhJ6+vU2ZYKm0SURRoqsl0ayvDAiWStskFGVGwyaIDGdCMBFFKjP+Un7ypZH+dYtv2EgLB11qcOK/kotYwbVE47CQVNMxftNn1OUWUbHEUg0rOb0ot4SlmpSHQQdZ3npeG8iIeOkuVpmTSCaZXkBc6k0kPrsuXUQvIU5Vr5RQhsQsbClB+v5rR8Uz/BKixDh+LZOd4Kv/OrLrtXgPJR0c8+xokgu8nPhyM1LBd3iPo3rspRJVJXdjFRC4fUsN8U1fyebql9x1zPdCHJ6QDHFph8RTtmMGzxOF+0Bw0IvJHSW93yLuHp0K1mCB5NhGzjxJfkdJ9PcisxReXRZReQelfRV1UQHaDE8VRGVx1a5juBZau2ijIdQgHiihOJTSFZZJNNIKHcphLGrBJFRxcqpochRKxz0Eokpf9QnNAlPQzncJd589LB4sgJEcmmZ0JGSeBULmKcgACfWOK9xyhI1mIBgI7I93DgsH5G9ySRGH3ivRgIMqDtvHyX1eBtsHevcHDKF3yQav7yEEbwyrGvr04BwCLib6i8M7nckaYwSNOpxDp4ehRDTvDmwbSvPUF+PDFeR3KL8aaJMn/+Tj/+4RyAprF0gRzdr9oVo0sg9DveHyUFHQe7SQbS9AUc/FdKtND00sDVOwVwO95HyWvbxxAheyaiPUcFiQItWyPoxeoPAdmWB4o5LGU6Z5i0Wg1A11i77BvQo4xHyWndoYB68M5lhS9DNqd4EYKrFi7qNGffI2eAsru0vnyYtgip/9yGIZmz+C4WkztdzsNlgwSM1zrJtXouA+Be8mJ41teo/zQlXYvJxu9LRdDsbDCE9VjDvLUKBYH6S3VceDUBxFW6asit1wGRa1L9LhOP4Vroa25mkfi51xGL5emlpTfI7jabjDhECCTgkeLegXoDYyx/HUDocN+TIjFewGDYzX82H+8NDmeXs/zHBiiNiL7MwoTzQzh+rrDYYp1dusRRNdtGGmpnB1agpC/oTbK+BC1ryVLRoJQswUSsnj0VkLO5So3vjtqb5L1fvdYKLUC2EzvMukpOFuhC+zJ1letXtJl7LWa6/KEfOGKN+kryNEcC4E8u7tlZg9erqRDzz2bxYjO+KD7fVtfiHM5sCIyqy/Dt35vbl5iP/67sPN5nfcOB5iDPLNJHgjPaZqg3J9uB5c37Y7TrXqfmzcmlutOp327fVgPdTjeoCJPsovsvfnZevL+GldhGic6Uajtbx/Pv2+x+nz/bLVMHTGtfgrqqm+rGcZSYiE92hI1UYSQt9ApO7epsZV7uv3F82VhT2iSLNW+WfyPsPpTqJkoQIIm3TzzY+IUPOReuoJn6z8Qhy/MJzLEbQXm3BrdFm85fsE53JpMsVZc0Rj5rLY9N7Q9+czI+nMEkK5MZv7eedfpeE2by/WFpPcsYQza31x28zH9lRH7cHvDk52el2jEepvpx+1nS1wMuj6DwWVLBLod87Op6ezlm1UGGOcsff/FcNuzU6n52ed/83GjMXeDG169Xb72x7tdt1r7g3VvF/rC1/4whe+8IV3/Af1VRIbet9CwAAAAABJRU5ErkJggg==',
            'phone' => '+2250546963728',
            'email' => 'abdoulsdb92@gmail.com',
            'email_distributer' => 'sabdydevv@gmail.com',
            'email_verified_at' => $time,
            'password' => Hash::make('k9z!C}#e,kRKmoz]TXpaS'),
            'created_by' => 1,
            'country_id' => 107,
            // 490 439 000,39
            'city_id' => 1,
            'remember_token' => null,
            'created_at' => $time,
            'updated_at' => $time
        ]);
    }
}

// admin = 55,00000000000001
// Pourcentage S-agent retrait *  = 30
// Depot 15
// 1
