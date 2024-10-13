<div class="grid grid-cols-12 gap-6 mt-6">

    <!-- BEGIN: Sales Report -->
    <div class="col-span-12 sm:col-span-8 lg:col-span-4 mt-8">
        <div class="intro-y block sm:flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                Activités d'aujourd'hui en {{ $usersCountry }}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-12 sm:mt-5">
            <canvas id="sum"    virement="{{ json_encode($sum_bank) }}"
                deposit="{{ json_encode($sum_deposit) }}" payment="{{ json_encode($sum_payment) }}"
                reload="{{ json_encode($sum_reload) }}" transaction="{{ json_encode($sum_transaction) }}"
                inter="{{ json_encode($sum_inter) }}" phone="{{ json_encode($sum_phoneCredit) }}" 
                phone="{{ json_encode($sum_phoneCredit) }}"
                ></canvas>

        </div>
    </div>
    <!-- END: Sales Report -->
    <!-- BEGIN: Sales Report -->
    <!-- BEGIN: Sales Report -->
    <div class="col-span-12 sm:col-span-8 lg:col-span-8 mt-8">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                Les activités des 30 derniers jours en {{ $usersCountry }}
            </h2>
        </div>
        {{-- <div class="intro-y box p-5 mt-12 sm:mt-5">
            <canvas id="sum_month" virement_month="{{ json_encode($sum_bank_month) }}"
                deposit_month="{{ json_encode($sum_deposit_month) }}"
                payment_month="{{ json_encode($sum_payment_month) }}"
                reload_month="{{ json_encode($sum_reload_month) }}"
                transaction_month="{{ json_encode($sum_transaction_month) }}"
                inter_month="{{ json_encode($sum_inter_month) }}"
                phone_month="{{ json_encode($sum_phoneCredit_month) }}"></canvas>
        </div> --}}
    </div>
    <!-- END: Sales Report -->
    <!-- END: Sales Report -->
</div>