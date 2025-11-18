<x-app-layout>
    <div class="main full-center">
        <div class="container-form full-center">
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-white dark:text-gray-200 leading-tight">
                    {{ __('Perfil') }}
                </h2>
            </x-slot>
            <div class="w-full max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
