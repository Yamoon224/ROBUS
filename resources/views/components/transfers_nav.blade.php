   <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">


       @if (sabdy()->country->code == 'CI')

           @if (Auth::guard('web')->user()->hasPermissionTo('utilisateurs'))
           @endif
           <li id="transfers-tab" class="nav-item" role="presentation">
               <a href="javascript:;" class="nav-link py-4 flex items-center" id="transfers-nav" data-tw-target="#transfers"
                   aria-controls="transfers" aria-selected="false" role="tab"><svg xmlns="http://www.w3.org/2000/svg"
                       width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                       stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                       class="feather feather-shield w-4 h-4 mr-2">
                       <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                   </svg> Transfers d'argent </a>
           </li>
           <li id="paiement-tab" class="nav-item" role="presentation">
               <a href="javascript:;" class="nav-link py-4 flex items-center" id="paiement-nav"
                   data-tw-target="#paiement" aria-controls="paiement" aria-selected="false" role="tab"><svg
                       xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                       fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                       stroke-linejoin="round" class="feather feather-shield w-4 h-4 mr-2">
                       <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                   </svg> Paiements </a>
           </li>
           <li id="virement-tab" class="nav-item" role="presentation">
               <a href="javascript:;" class="nav-link py-4 flex items-center " id="virement-nav"
                   data-tw-target="#virement" aria-controls="virement" aria-selected="false" role="tab"><svg
                       xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                       fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                       stroke-linejoin="round" class="feather feather-shield w-4 h-4 mr-2">
                       <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                   </svg> Virements </a>
           </li>
           <li id="carte-tab" class="nav-item" role="presentation">
               <a href="javascript:;" class="nav-link py-4 flex items-center" id="carte-nav" data-tw-target="#carte"
                   aria-controls="carte" aria-selected="false" role="tab"> <svg xmlns="http://www.w3.org/2000/svg"
                       width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                       stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                       class="feather feather-shield w-4 h-4 mr-2">
                       <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                   </svg> Rechargement
                   carte</a>
           </li>

           <li id="phone-tab" class="nav-item" role="presentation">
               <a href="javascript:;" class="nav-link py-4 flex items-center" id="phone-nav" data-tw-target="#phone"
                   aria-controls="phone" aria-selected="false" role="tab"> <svg xmlns="http://www.w3.org/2000/svg"
                       width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                       stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                       class="feather feather-shield w-4 h-4 mr-2">
                       <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                   </svg> Credit
                   telephonique</a>
           </li>
           <li id="xbet-tab" class="nav-item" role="presentation">
               <a href="javascript:;" class="nav-link py-4 flex items-center" id="xbet-nav" data-tw-target="#xbet"
                   aria-controls="xbet" aria-selected="false" role="tab"> <svg
                       xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                       fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                       stroke-linejoin="round" class="feather feather-shield w-4 h-4 mr-2">
                       <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                   </svg> 1Xbet
               </a>
           </li>
           <li id="inter-tab" class="nav-item" role="presentation">
               <a href="javascript:;" class="nav-link py-4 flex items-center" id="inter-nav" data-tw-target="#inter"
                   aria-controls="inter" aria-selected="false" role="tab"> <svg
                       xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                       fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                       stroke-linejoin="round" class="feather feather-shield w-4 h-4 mr-2">
                       <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                   </svg>
                   Internationnal</a>
           </li>
       @else
           @if (sabdy()->is_under_distributer == 1)
               <li id="transfers-tab" class="nav-item" role="presentation">
                   <a href="javascript:;" class="nav-link py-4 flex items-center" id="transfers-nav"
                       data-tw-target="#transfers" aria-controls="transfers" aria-selected="false"
                       role="tab"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                           viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                           stroke-linecap="round" stroke-linejoin="round"
                           class="feather feather-shield w-4 h-4 mr-2">
                           <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                       </svg> Transfers d'argent </a>
               </li>
               <li id="inter-tab" class="nav-item" role="presentation">
                   <a href="javascript:;" class="nav-link py-4 flex items-center" id="inter-nav"
                       data-tw-target="#inter" aria-controls="inter" aria-selected="false" role="tab"> <svg
                           xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                           fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                           stroke-linejoin="round" class="feather feather-shield w-4 h-4 mr-2">
                           <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                       </svg>
                       Internationnal</a>
               </li>
           @else
               <li id="transfers-tab" class="nav-item" role="presentation">
                   <a href="javascript:;" class="nav-link py-4 flex items-center" id="transfers-nav"
                       data-tw-target="#transfers" aria-controls="transfers" aria-selected="false"
                       role="tab"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                           viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                           stroke-linecap="round" stroke-linejoin="round"
                           class="feather feather-shield w-4 h-4 mr-2">
                           <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                       </svg> Transfers d'argent </a>
               </li>
               <li id="inter-tab" class="nav-item" role="presentation">
                   <a href="javascript:;" class="nav-link py-4 flex items-center" id="inter-nav"
                       data-tw-target="#inter" aria-controls="inter" aria-selected="false" role="tab"> <svg
                           xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                           fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                           stroke-linejoin="round" class="feather feather-shield w-4 h-4 mr-2">
                           <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                       </svg>
                       Internationnal</a>
               </li>
           @endif
       @endif

   </ul>
