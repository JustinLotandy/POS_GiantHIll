<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User</h2>
    </x-slot>
    <div class="max-w-md mx-auto py-8">
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="bg-white rounded shadow p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block mb-1 font-semibold">Nama</label>
                <input type="text" name="name" class="w-full border border-gray-300 rounded px-3 py-2"
                    value="{{ old('name', $user->name) }}" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded px-3 py-2"
                    value="{{ old('email', $user->email) }}" required>
            </div>
            <div>
                <label class="block mb-1 font-semibold">Password (biarkan kosong jika tidak ingin diubah)</label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1 font-semibold">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Update</button>
            <a href="{{ route('users.index') }}" class="ml-2 text-gray-700">Batal</a>
        </form>
    </div>
</x-app-layout>
