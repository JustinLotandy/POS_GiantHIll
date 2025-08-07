<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-5 text-gray-700">Edit Role</h2>

        <form method="POST" action="{{ route('roles.update', $role->id) }}">
            @csrf
            @method('PUT')

            {{-- Nama Role --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Nama Role</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" required class="w-full border rounded px-3 py-2">
            </div>

            {{-- Grouped Permissions (Tanpa Select All) --}}
            @foreach ($permissions as $group => $perms)
                <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded">
                    <p class="font-semibold text-indigo-600 capitalize mb-2">
                        {{ str_replace('_', ' ', $group) }}
                    </p>

                    <div class="flex flex-wrap gap-4">
                        @foreach ($perms as $permission)
                            <label class="inline-flex items-center w-40">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    class="rounded border-gray-300"
                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-800 capitalize">
                                    {{ str_replace('_', ' ', explode('.', $permission->name)[1] ?? $permission->name) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Tombol --}}
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                    Update
                </button>
                <a href="{{ route('roles.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 shadow">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
