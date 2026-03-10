<div>

    <div class="grid grid-cols-12 gap-6 mt-3">
        <div class="intro-y col-span-12 lg:col-span-2">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">


            </div>
        </div>

        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="intro-y box">
                <div
                    class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        <a href="{{ URL::previous() }}" class="btn btn-sm">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                        </a>
                    </h2>
                </div>
                <div
                    class="mt-6 relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $currentStep != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Information
                            Sous-distributeur
                        </div>
                    </div>
                    <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                        <a href="#step-2"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $currentStep != 2 ? 'btn-default' : 'btn-primary' }}">2</a>
                        <div class="lg:w-32 text-base lg:mt-3 ml-3 lg:mx-auto text-slate-600 dark:text-slate-400">
                            Validation et Inscription</div>
                    </div>

                </div>
                <div class="p-5">
                    <div class="{{ $currentStep != 1 ? 'hidden' : '' }}" id="step-1">
                        <div class="w-full max-w-sm  mb-1">

                            <div class="flex flex-col items-center pb-10">
                                <img class="w-20 h-20 mb-3 rounded-full shadow-lg"
                                    src="https://robus.home.ci/robus/public/dist/images/logo_orange.png"
                                    alt="Robus" />

                                <div>

                                    <p class="font-bold mt-3 mr-3 ml-3"> Vous êtes actuellement sur le service
                                        d'inscription
                                        des sous distributeurs Robus.

                                    </p>
                                    <p class="font-bold mt-3 mr-3 ml-3">
                                        Merci de saisir votre nom et votre adresse e-mail pour suivre l'inscription.

                                    </p>


                                </div>

                            </div>
                        </div>
                        <form wire:submit.prevent="submitFirstStep">
                            <div class="grid grid-cols-12 gap-6 mt-3 items-center">
                                <div class="col-span-12 lg:col-span-6">
                                    <label class="form-label">Nom & Prénom(Agent)</label>
                                    <input type="text" wire:model="name_ground"
                                        class="form-control @error('name_ground') is-invalid @enderror" required>
                                    @error('name_ground')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-12 lg:col-span-6">
                                    <label class="form-label">Email(Agent) pour la validation</label>
                                    <input type="email" wire:model="email_agent"
                                        class="form-control @error('email_agent') is-invalid @enderror" required>
                                    @error('email_agent')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-24 mb-2 mt-6">Suivant</button>
                            </div>
                        </form>
                    </div>

                    <div class="{{ $currentStep != 2 ? 'hidden' : '' }}" id="step-2">
                        <form wire:submit.prevent="submitSecondStep">
                            <div class="mx-auto xl:mr-0 xl:ml-6">
                                <img class="object-none object-center bg-yellow-300 w-30 h-30"
                                    src="{{ old('image') ?? asset('public/dist/images/food-beverage-1.jpg') }}"
                                    alt="image" id="profileImage">
                                <div class="mx-auto cursor-pointer relative mt-5">
                                    <input id="imageUpload" type="file" name="image" placeholder="Photo"
                                        style="display: none" accept="image/*">
                                    <h6 class="text-center">Image de profile</h6>
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-6 mt-3 items-center">
                                <!-- Ajoutez les autres champs comme avant -->
                                <div class="col-span-12 lg:col-span-6">
                                    <label class="form-label">Nom</label>
                                    <input type="text" wire:model="lastname"
                                        class="form-control @error('lastname') is-invalid @enderror" required>
                                    @error('lastname')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- Continuez avec les autres champs du formulaire -->
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary w-24 mb-2 mt-6">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
