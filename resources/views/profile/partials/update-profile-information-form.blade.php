<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Información del perfil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Actualizá tu nombre, correo electrónico y foto de perfil.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Nombre --}}
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Tu dirección de correo no está verificada.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Hacé clic acá para reenviar el email de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Se envió un nuevo enlace de verificación a tu correo.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Celular --}}
        <div>
            <x-input-label for="celular" value="Número de celular" />
            <x-text-input id="celular" name="celular" type="text" class="mt-1 block w-full"
                :value="old('celular', $user->celular)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('celular')" />
        </div>

        {{-- Foto de perfil --}}
        <div>
            <x-input-label for="foto" value="Foto de perfil" />
            <input id="foto" name="foto" type="file" accept="image/*"
                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                        file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100" />
            <x-input-error class="mt-2" :messages="$errors->get('foto')" />

            @if ($user->foto)
                <div class="mt-2">
                    <p class="text-sm text-gray-600">Foto actual:</p>
                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto de perfil actual" class="h-20 w-20 rounded-full mt-1 object-cover shadow-md">
                </div>
            @endif
        </div>

        {{-- Botón Guardar --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
