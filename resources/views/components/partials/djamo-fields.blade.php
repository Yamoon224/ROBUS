 <div class="col-span-12 flex flex-col items-center gap-4">

     <!-- Preview avec Base64 ou image existante -->
     <div class="w-full flex justify-center">
         @if ($image_preview)
             <div
                 class="relative group w-80 h-50 rounded-lg overflow-hidden border-2 border-green-300 shadow hover:shadow-lg transition">
                 <img src="{{ $image_preview }}" alt="Preview" class="h-full w-full object-cover">
                 <div
                     class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-25 flex items-center justify-center transition">
                     <span class="text-white font-medium text-sm opacity-0 group-hover:opacity-100">Aperçu</span>
                 </div>
             </div>
         @elseif(is_string($image) && $image)
             <div
                 class="relative group w-80 h-50 rounded-lg overflow-hidden border-2 border-blue-300 shadow hover:shadow-lg transition">
                 <img src="{{ Storage::url($image) }}" alt="Image existante" class="h-full w-full object-cover">
                 <div
                     class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-25 flex items-center justify-center transition">
                     <span class="text-white font-medium text-sm opacity-0 group-hover:opacity-100">Image
                         actuelle</span>
                 </div>
             </div>
         @else
             <div
                 class="w-40 h-40 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 text-gray-400">
                 <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                     </path>
                 </svg>
                 <span class="text-xs text-center">Télecharger le code QR</span>
             </div>
         @endif
     </div>

     <!-- Input file -->
     <div class="w-full flex justify-center">
         <label class="cursor-pointer">
             <div
                 class="bg-primary hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium flex items-center space-x-2 shadow hover:shadow-md transition">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                         d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                 </svg>
                 <span>{{ $image ? 'Changer l\'image' : 'Choisir une image' }}</span>
             </div>
             <input type="file" wire:model="image" accept="image/*" class="hidden">
         </label>
     </div>

     <!-- Erreurs -->
     @error('image')
         <div
             class="flex items-center space-x-2 text-red-600 bg-red-50 px-4 py-2 rounded-lg border border-red-200 w-full max-w-md">
             <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
             </svg>
             <span class="text-sm">{{ $message }}</span>
         </div>
     @enderror

     <!-- Chargement -->
     <div wire:loading wire:target="image" class="flex items-center gap-2 text-blue-600 w-full justify-center">
         <div class="animate-spin rounded-full h-5 w-5 border-2 border-blue-600 border-t-transparent">
         </div>
         <span class="font-medium">Chargement de l'image...</span>
     </div>

 </div>


 <div class="col-span-6 mt-1">
     @include('components.transaction-text-input', [
         'id' => 'name_custumer',
         'model' => 'name_custumer',
         'label' => 'Nom et Prénom',
         'error' => 'name_custumer',
     ])
 </div>

 <div class="col-span-6 mt-1">
     @include('components.transaction-amount-input', [
         'id' => 'amount_djamo',
         'model' => 'amount_djamo',
         'label' => 'Montant',
         'error' => 'amount_djamo',
     ])
 </div>
