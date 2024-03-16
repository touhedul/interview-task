<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-12 bg-white dark:bg-gray-600 shadow sm:rounded-lg">
                <a class="hover:underline text-emerald-500 text-xl" href="{{ route('users.create') }}">Create new User</a>
                <a class="hover:underline text-red-500 text-xl" href="{{ route('trashedUsers.list') }}">Trashed Users</a>
                <br>
                <br>
                <div class="max-w-xl">

                    <div class="overflow-x-auto">
                        <table class="table-auto w-full">
                            <thead>
                                <tr>
                                    <th class="border px-4 py-2">Name</th>
                                    <th class="border px-4 py-2">Email</th>
                                    <th class="border px-4 py-2">Photo</th>
                                    <th class="border px-4 py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $user->name }}
                                        </td>
                                        <td class="border px-4 py-2">{{ $user->email }}</td>
                                        <td class="border px-4 py-2">
                                            <img src="{{ asset('storage/images/' . $user->image) }}" alt="No image"
                                                srcset="">
                                        </td>
                                        <td class="border px-4 py-2">
                                            <a class="hover:underline text-emerald-500" href="{{route('users.show',$user->id)}}">View</a> |
                                            <a class="hover:underline text-yellow-500" href="{{route('users.edit',$user->id)}}">Edit</a> |
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline ml-2">Delete</button>
                                            </form>
                                            {{-- <a class="hover:underline text-red-500" href="#">Delete</a> --}}
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td>No data found</td>
                                    </tr>
                                @endforelse
                                <!-- More rows here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
