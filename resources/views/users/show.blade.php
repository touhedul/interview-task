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

                            <p class="text-yellow-300 text-xl">Show user </p>
                            <form method="POST" action="#" enctype="multipart/form-data">
                                <!-- Name -->
                                <div>
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input disabled id="name" class="block mt-1 w-full" type="text"
                                        name="name" :value="$user->name" required autofocus autocomplete="name" />
                                </div>

                                <!-- Email Address -->
                                <div class="mt-4">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input disabled id="email" class="block mt-1 w-full" type="email"
                                        name="email" :value="$user->email" required autocomplete="username" />
                                </div>

                                <!-- location  -->
                                <div class="mt-4">
                                    <x-input-label for="location" :value="__('Location')" />
                                    <x-text-input disabled id="location" class="block mt-1 w-full" type="text"
                                        name="location" :value="implode(',', $user->addresses->pluck('location')->toArray())" required autocomplete="username" />
                                </div>

                                @if($user->image)
                                <!-- image Address -->
                                <div class="mt-4">
                                    <x-input-label for="image" :value="__('Image')" />
                                    <img src="{{ asset('storage/images/' . $user->image) }}" /> <br>
                                </div>
                                @endif

                            </form>
                        </div>
                    </div>
        </div>
    </div>
</x-app-layout>
