<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto py-8">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div class="mb-4">
                <label for="name" class="block font-medium">Nama</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                    class="w-full border rounded px-3 py-2" required>
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block font-medium">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                    class="w-full border rounded px-3 py-2" required>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password" class="block font-medium">Password (Kosongkan jika tidak ganti)</label>
                <input type="password" name="password" id="password"
                    class="w-full border rounded px-3 py-2" autocomplete="new-password">
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-4">
                <label for="password_confirmation" class="block font-medium">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full border rounded px-3 py-2" autocomplete="new-password">
            </div>

            {{-- Role --}}
            <div class="mb-4">
                <label for="roles" class="block font-semibold mb-1 text-gray-700">Pilih Role</label>
                <select name="roles[]" id="roles" multiple
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                    required>
                    
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ in_array($role->name, old('roles', $userRoles ?? [])) ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Submit --}}
            <div class="mt-6">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
