<ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">


        @if (sabdy()->country->code == 'CI')
            <li id="transfert-visitors-tab" class="nav-item" role="presentation">
                <a wire:ignore.self href="javascript:;" class="nav-link py-4 flex items-center" id="transfert-visitors-nav" data-tw-target="#transfert-visitors"
                    aria-controls="transfert-visitors" aria-selected="false" role="tab"><svg xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-shield w-4 h-4 mr-2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg> Transfers d'argent </a>
            </li>
           
            <li id="carte-visitors-tab" class="nav-item" role="presentation">
                <a wire:ignore.self href="javascript:;" class="nav-link py-4 flex items-center" id="carte-visitors-nav" data-tw-target="#carte-visitors"
                    aria-controls="carte-visitors" aria-selected="false" role="tab"> <svg xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-shield w-4 h-4 mr-2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg> Rechargement
                    carte</a>
            </li>
 
            <li id="phone-visitors-tab" class="nav-item" role="presentation">
                <a wire:ignore.self href="javascript:;" class="nav-link py-4 flex items-center" id="phone-visitors-nav" data-tw-target="#phone-visitors"
                    aria-controls="phone-visitors" aria-selected="false" role="tab"> <svg xmlns="http://www.w3.org/2000/svg"
                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-shield w-4 h-4 mr-2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg> Credit
                    telephonique</a>
            </li>
            <li id="xbet-visitors-tab" class="nav-item" role="presentation">
                <a wire:ignore.self href="javascript:;" class="nav-link py-4 flex items-center" id="xbet-visitors-nav" data-tw-target="#xbet-visitors"
                    aria-controls="xbet-visitors" aria-selected="false" role="tab"> <svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-shield w-4 h-4 mr-2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg> 1Xbet
                </a>
            </li>
            <li id="inter-visitors-tab" class="nav-item" role="presentation">
                <a wire:ignore.self href="javascript:;" class="nav-link py-4 flex items-center" id="inter-visitors-nav" data-tw-target="#inter-visitors"
                    aria-controls="inter-visitors" aria-selected="false" role="tab"> <svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-shield w-4 h-4 mr-2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    Internationnal</a>
            </li>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-shield w-4 h-4 mr-2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg> Transfers validez que en CIV
        @endif
 
    </ul>
 