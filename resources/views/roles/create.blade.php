<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Role</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-10 bg-white rounded shadow p-6">
        <form method="POST" action="{{ route('roles.store') }}">
            @csrf

            {{-- Nama Role --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Nama Role</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:border-blue-300">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Permission --}}
            <div class="mb-6">
                <label class="block font-semibold mb-2 text-gray-700">Permission</label>

                {{-- Grouped Permissions (tanpa select all) --}}
                @forelse($permissions as $group => $perms)
                    <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded">
                        <p class="mb-2 font-semibold text-indigo-600 capitalize">{{ str_replace('_', ' ', $group) }}</p>

                        <div class="flex flex-wrap gap-4">
                            @foreach($perms as $permission)
                                <label class="inline-flex items-center w-40">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded border-gray-300 text-blue-600 focus:ring-0">
                                    <span class="ml-2 text-sm text-gray-800 capitalize">
                                        {{ str_replace('_', ' ', explode('.', $permission->name)[1] ?? $permission->name) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Belum ada permission tersedia.</p>
                @endforelse
            </div>

            {{-- Tombol --}}
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                    Simpan
                </button>
                <a href="{{ route('roles.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 shadow">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
