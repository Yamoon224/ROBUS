 <div class="robus-container">
     <!-- Colonne gauche - Informations sécurité -->
     <div class="robus-side-decoration">

     </div>
     <div class="robus-login-card robus-floating">
         <div class="robus-card-header">
             <div>
                 <!-- Remplacez par votre logo -->
                 <a href="{{ route('/') }}" class="brand-logo">
                     <img src="{{ asset_url('dist/images/logo_orange.jpeg') }}" width="100" alt="logo">
                 </a>
             </div>
             <h1 class="robus-card-title">Nous sommes heureux
                 de vous revoir</h1>
             <p class="robus-card-subtitle mt-3">Veuillez vous connecter à votre compte pour consulter et gérer vos
                 transactions</p>
         </div>
         <div class="robus-form-container">
             <form wire:submit.prevent="login">
                 <!-- Email -->
                 <div class="robus-form-group">
                     <label class="robus-form-label" for="email">
                         📧 Adresse email
                     </label>
                     <input wire:model="email" type="email" id="email" class="robus-form-input"
                         placeholder="votre@email.com" required>
                     @error('email')
                         <div style="color: var(--robus-danger); font-size: 0.8rem; margin-top: 0.5rem;">
                             {{ $message }}
                         </div>
                     @enderror
                 </div>

                 <!-- Mot de passe -->
                 <div class="robus-form-group" x-data="{ showPassword: false }">
                     <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 0.5rem;">
                         <label class="robus-form-label" for="password">
                             🔒 Mot de passe
                         </label>
                     </div>
                     <div style="position: relative;">
                         <input wire:model="password" :type="showPassword ? 'text' : 'password'" id="password"
                             class="robus-form-input" placeholder="Votre mot de passe" required>
                         <button type="button" @click="showPassword = !showPassword" class="robus-password-toggle">
                             <span x-show="!showPassword">👁️</span>
                             <span x-show="showPassword" style="display: none;">🔒</span>
                         </button>
                     </div>
                     @error('password')
                         <div style="color: var(--robus-danger); font-size: 0.8rem; margin-top: 0.5rem;">
                             {{ $message }}
                         </div>
                     @enderror
                 </div>

                 <!-- Bouton de connexion -->
                 <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed"
                     class="robus-btn-primary h16 btn btn-primary w-full flex items-center justify-center gap-2 rounded-lg px-5 py-2 font-medium bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 transition">
                     <!-- Texte normal -->
                     <span wire:loading.remove wire:target="login">
                         Se connecter
                     </span>

                     <!-- Animation dots -->
                     <div class="loading-dots flex space-x-1" wire:loading wire:target="login">
                         <div class="loading-dot"></div>
                         <div class="loading-dot"></div>
                         <div class="loading-dot"></div>
                     </div>

                     <!-- Spinner (optionnel, en plus des dots) -->
                     <svg wire:loading wire:target="login" class="w-4 h-4 animate-spin text-white" fill="none"
                         viewBox="0 0 24 24">
                         <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                             stroke-width="4"></circle>
                         <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2
                 5.291A7.962 7.962 0 014 12H0c0 3.042
                 1.135 5.824 3 7.938l3-2.647z"></path>
                     </svg>
                 </button>


             </form>


             <!-- Avertissement de sécurité -->

             <div class="text-center mt-6 pt-4 border-t border-gray-200">
                 <p class="text-gray-600 mb-3">
                     Vous n'avez pas encore de compte ?
                 </p>

                 <a href="{{ route('web.prospect_registers') }}"
                     class="inline-flex items-center justify-center gap-2 px-6 py-3 border-2 border-blue-500 text-blue-500 rounded-lg font-semibold hover:bg-blue-500 hover:text-white transition-all duration-300 shadow-sm hover:shadow-md">
                     <i class="fas fa-user-plus"></i>
                     Créer un compte gratuit
                 </a>
             </div>
         </div>
     </div>

     <div class="robus-side-decoration">
         <div class="space-y-4">
             <!-- Statut du Service -->
             <div class="bg-white rounded-xl border border-gray-200 p-10 shadow-sm">
                 <div class="flex items-center justify-between mb-3">
                     <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                         <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                             </path>
                         </svg>
                         Statut du Service
                     </h3>
                     <span
                         class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">
                         <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                         Indisponible
                     </span>
                 </div>
                 <p class="text-sm text-gray-600">Service en maintenance - Réouverture prochaine</p>
                 <!-- Lien vers l'inscription -->
                 <a href="{{ route('admin.login_integration') }}"
                     class="inline-flex items-center justify-center gap-2 mt-6 px-6 py-3 border-2 border-blue-500 text-blue-500 rounded-lg font-semibold hover:bg-blue-500 hover:text-white transition-all duration-300 shadow-sm hover:shadow-md">
                     <i class="fas fa-user-plus"></i>
                     Panel compte Api
                 </a>
                 <div
                     style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 10px; padding: 1rem; margin-top: 1rem;">
                     <div style="display: flex; align-items: start; gap: 0.5rem;">
                         <span style="color: #856404;">⚠️</span>
                         <div>
                             <strong style="color: #856404; font-size: 0.9rem;">Sécurité</strong>
                             <p style="color: #856404; font-size: 0.8rem; margin: 0.25rem 0 0 0;">
                                 Ne partagez jamais vos identifiants de connexion.
                                 PayFlow ne vous demandera jamais votre mot de passe par email ou téléphone.
                             </p>
                         </div>
                     </div>
                 </div>
                 <div class="mt-4 grid grid-cols-2 gap-4 text-center mt-6">

                     <div class="text-xs text-gray-500">
                         <i class="fas fa-shield-alt text-blue-500"></i>
                         <div>Sécurisé</div>
                     </div>
                     <div class="text-xs text-gray-500">
                         <i class="fas fa-headset text-purple-500"></i>
                         <div>Support 24/7</div>
                     </div>
                 </div>
             </div>


             <!-- Horaires -->
             <div class="bg-white rounded-xl border border-blue-200 p-5 shadow-sm">
                 <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                     <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                     </svg>
                     Prochaine Ouverture
                 </h3>

                 <div class="grid grid-cols-2 gap-3">
                     <div class="text-center">
                         <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                             <div class="text-xs text-orange-600 font-medium mb-1">JOUR</div>
                             <div class="text-orange-700 font-semibold">{{ getAppCloseWeb()->day_of_week ?? 'N/A' }}
                             </div>
                         </div>
                     </div>
                     <div class="text-center">
                         <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                             <div class="text-xs text-blue-600 font-medium mb-1">HORAIRE</div>
                             <div class="text-blue-700 font-semibold text-sm">
                                 {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_start ?? '00:00:00')->format('H:i') }}
                                 -
                                 {{ \Carbon\Carbon::createFromFormat('H:i:s', getAppCloseWeb()->time_end ?? '00:00:00')->format('H:i') }}
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="mt-3 text-center">
                     <p class="text-xs text-gray-500">
                         Mise à jour : {{ now()->format('d/m/Y H:i') }}
                     </p>
                 </div>
             </div>
         </div>
     </div>
 </div>
