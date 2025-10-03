 <div class="robus-container">
     <!-- Colonne gauche - Informations sécurité -->
    <div class="robus-side-decoration">
        {{-- <div class="robus-security-badge robus-floating">
            <h3>🛡️ Protection des Actifs</h3>
            <p>Cryptage bancaire de niveau institutionnel</p>
        </div>

        <div class="robus-security-badge robus-floating" style="animation-delay: 0.5s;">
            <h3>💎 Intégrité des Opérations</h3>
            <p>Surveillance transactionnelle permanente</p>
        </div>

        <div class="robus-security-badge robus-floating" style="animation-delay: 1s;">
            <h3>🔐 Vérification d'Identité</h3>
            <p>Authentification multi-facteurs disponible</p>
        </div> --}}
    </div>
     <div class="robus-login-card robus-floating">
        <div class="robus-card-header">
             <div class="robus-logo">
                 <!-- Remplacez par votre logo -->
                 <a href="{{ route('/') }}" class="brand-logo">
                     <img src="{{ asset_url('dist/images/logo_robus.png') }}" width="100" alt="logo">
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
             <div
                 style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 10px; padding: 1rem; margin-top: 1rem;">
                 <div style="display: flex; align-items: start; gap: 0.5rem;">
                     <span style="color: #856404;">⚠️</span>
                     <div>
                         <strong style="color: #856404; font-size: 0.9rem;">Sécurité</strong>
                         <p style="color: #856404; font-size: 0.8rem; margin: 0.25rem 0 0 0;">
                             Ne partagez jamais vos identifiants.
                         </p>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     {{-- <div class="robus-side-decoration">
         <div class="robus-security-badge">
             <h3>📊 Statut du système</h3>
             <div style="margin-top: 1rem;">
                 <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 0.5rem;">
                     <span>Transactions</span>
                     <span style="color: var(--robus-success);">● Actif</span>
                 </div>
                 <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 0.5rem;">
                     <span>Paiements</span>
                     <span style="color: var(--robus-success);">● Opérationnel</span>
                 </div>
                 <div style="display: flex; justify-content: between; align-items: center;">
                     <span>Sécurité</span>
                     <span style="color: var(--robus-success);">● Optimal</span>
                 </div>
             </div>
         </div>

         <div class="robus-security-badge robus-pulse">
             <h3>🕐 Horaires</h3>
             <p>Service 24h/24</p>
             <p style="margin-top: 0.5rem; font-size: 0.8rem; opacity: 0.8;">
                 {{ now()->format('d/m/Y H:i') }}
             </p>
         </div>
     </div> --}}
 </div>
