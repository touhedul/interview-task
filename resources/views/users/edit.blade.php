<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <a class="hover:underline text-emerald-500 text-xl" href="{{ route('users.index') }}"> <-Back to user list</a>
                    <div class="p-4 sm:p-12 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                        <div class="max-w-xl">

                            <p class="text-yellow-300 text-xl">Update </p>
                            <form method="POST" action="{{ route('users.update', $user->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                        :value="$user->name" required autofocus autocomplete="name" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <!-- Email Address -->
                                <div class="mt-4">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="$user->email" required autocomplete="username" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- image Address -->
                                <div class="mt-4">
                                    <x-input-label for="image" :value="__('Image')" />
                                    <img src="{{ asset('images/' . $user->image) }}" /> <br>
                                    <input type="file" class="block mt-1 w-full" name="image">
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>

                                <!-- Password -->
                                <div class="mt-4">
                                    <x-input-label for="password" :value="__('Password')" />

                                    <x-text-input id="password" class="block mt-1 w-full" type="password"
                                        name="password" autocomplete="new-password" />

                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end mt-4">

                                    <x-primary-button class="ms-4">
                                        {{ __('Update') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
        </div>
    </div>
</x-app-layout>
