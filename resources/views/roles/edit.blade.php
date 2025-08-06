<x-app-layout>
    <div class="max-w-2xl mx-auto mt-10 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-5 text-gray-700">Edit Role</h2>
        <form method="POST" action="{{ route('roles.update', $role->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block text-sm mb-1 font-semibold">Nama Role</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ $role->name }}" required>
                @error('name') <div class="text-red-600 text-xs">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block font-semibold mb-2">Permissions:</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($permissions as $group => $perms)
                        <div class="border rounded-lg p-3 bg-gray-50">
                            <div class="font-semibold mb-1 text-blue-600 uppercase text-xs">{{ ucfirst($group) }}</div>
                            @foreach($perms as $perm)
                                <label class="flex items-center mb-1">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                                        class="mr-2"
                                        {{ in_array($perm->name, $rolePermissions) ? 'checked' : '' }}>
                                    <span>{{ ucwords(str_replace([$group.'.', '_'], ['', ' '], $perm->name)) }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Update</button>
                <a href="{{ route('roles.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
