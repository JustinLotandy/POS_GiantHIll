<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah User</h2>
    </x-slot>

    <div class="max-w-xl mx-auto py-8">
        <form action="{{ route('users.store') }}" method="POST" autocomplete="off">
            @csrf

            {{-- Trik anti autofill --}}
            <input type="text" name="fakeusernameremembered" style="display:none">
            <input type="password" name="fakepasswordremembered" style="display:none">

            {{-- Nama --}}
            <div class="mb-4">
                <label for="name" class="block font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                    autocomplete="off" required>
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                    autocomplete="off" required>
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password" class="block font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                    autocomplete="new-password" required>
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-4">
                <label for="password_confirmation" class="block font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                    autocomplete="new-password" required>
            </div>

            {{-- Role --}}
            <div class="mb-4">
                <label for="roles" class="block font-medium text-gray-700">Pilih Role</label>
               <select name="roles[]" id="roles"
                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Submit --}}
            <div class="mt-6">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
