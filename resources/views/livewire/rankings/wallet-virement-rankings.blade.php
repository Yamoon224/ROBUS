<div>

    <x-headerRankings title="Classement des Sous-Distributeurs pour les services des virements" />

    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- En-tête avec filtres améliorés -->

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">


            <div class="hidden md:block mx-auto text-slate-500"></div>

            @include('components.searchRankings')


        </div>

        @forelse ($classment_transactions as $country => $subDistributors)
            @include('components.searchRankingsTable')

        @empty
            @include('components.nullRankings')
        @endforelse
    </div>

</div>
