<x-app-layout>
    <div class="max-w-xl mx-auto mt-10 bg-white rounded shadow p-6">
        <h2 class="font-bold mb-4 text-gray-700">Tambah User</h2>
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Password</label>
                <input type="password" name="password" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Role</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($roles as $role)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded border-gray-300">
                            <span class="ml-2">{{ ucfirst($role->name) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Simpan</button>
                <a href="{{ route('users.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 shadow">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
