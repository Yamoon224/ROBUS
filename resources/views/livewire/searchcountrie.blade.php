<div class="flex flex-col relative" x-data="{ open: true }">
    <input @click.away="{ open = false; @this.resetIndex(); }" @click= "{ open = true }" class="focus:outline-none border  focus:border-green-400 border-2 w-56 rounded-full  md:mr-5 py-1 px-2" wire:model="query"
    wire:keydown.arrow-down.prevent="incrementIndex"
    wire:keydown.arrow-up.prevent="decrementIndex"
    wire:keydown.enter.prevent="showOrders"
    wire:keydown.backspace="resetIndex"
    >
  <i class="w-4 h-4 absolute mysort_codeUniq-auto inset-y-0 mr-3 mt-2 right-0" data-feather="search"></i>
  @if (strlen($query) >= 2)
        <div class="z-10 bg-white border border-gray-400 rounded w-56 px-2 py-1 mt-10 flex flex-col absolute" x-show="open">
        @if (count($orders) > 0)
            @foreach ($orders as $index => $order)
                <p class="{{ ($index === $selectedIndex) ? 'clignoteSearch' : '' }} my-2"> {{$order->uniqId }}
                </p>
            @endforeach 
        @else
        <span>0 résultat pour "{{ $query }}"</span>
        @endif
        </div>
    @endif
      
</div>