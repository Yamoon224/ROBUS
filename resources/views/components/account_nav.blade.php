     <ul class="nav nav-link-tabs flex-col sm:flex-row justify-center lg:justify-start text-center" role="tablist">
         <li id="doc-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center active" data-tw-target="#doc" aria-controls="doc"
                 aria-selected="true" role="tab"> <i class="w-4 h-4 mr-2" data-feather="shield"></i> Docs </a>
         </li>
         <li id="reload-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#reload"
                 aria-controls="reload" aria-selected="true" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i>UV </a>
         </li>
         <li id="transfers-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#transfers"
                 aria-controls="transfers" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i> Transferts </a>
         </li>
         <li id="paiement-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#paiement"
                 aria-controls="paiement" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i> Paiements </a>
         </li>
         <li id="order-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#order"
                 aria-controls="order" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i> Commande carte </a>
         </li>
         <li id="virement-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#virement"
                 aria-controls="virement" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i> Versement
             </a>
         </li>
         <li id="carte-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#carte"
                 aria-controls="carte" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i> Cartes</a>
         </li>
         <li id="inter-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#inter"
                 aria-controls="inter" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i> International </a>
         </li>
         <li id="phone-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#phone"
                 aria-controls="phone" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i> Credit </a>
         </li>
         <li id="xbet-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#xbet"
                 aria-controls="xbet" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i> Xbet </a>
         </li>
         <li id="push-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#push"
                 aria-controls="push" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i> Push </a>
         </li>
         <li id="tresort-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#tresort"
                 aria-controls="tresort" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i> Tresort Money </a>
         </li>

         <li id="djamo-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#djamo"
                 aria-controls="djamo" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i> Djamo </a>
         </li>
          <li id="versments-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#versments"
                 aria-controls="versments" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
                     data-feather="shield"></i> Versements </a>
         </li>
         <li id="avis-tab" class="nav-item" role="presentation">
             <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#avis"
                 aria-controls="avis" aria-selected="false" role="tab">
                 <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                 </svg>
                 Avis
                 @if(isset($comments) && $comments->count() > 0)
                     <span style="margin-left:6px;background:#f59e0b;color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:10px;">{{ $comments->count() }}</span>
                 @endif
             </a>
         </li>

     </ul>
