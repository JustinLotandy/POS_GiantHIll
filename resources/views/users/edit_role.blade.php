<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-5 text-gray-700">Edit Role</h2>

        <form method="POST" action="{{ route('roles.update', $user->id) }}">
            @csrf
            @method('PUT')

            {{-- Nama Role --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1 text-gray-700">Nama Role</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border rounded px-3 py-2">
            </div>

            {{-- Pilih Semua --}}
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" id="select-all-global" class="rounded border-gray-300">
                    <span class="ml-2 text-sm font-semibold text-blue-600">Pilih Semua Permission</span>
                </label>
            </div>

            {{-- Grouped Permissions --}}
            @foreach ($permissions as $group => $perms)
                <div class="mb-4 p-4 bg-gray-50 border border-gray-200 rounded group-permission">
                    <label class="inline-flex items-center mb-3">
                        <input type="checkbox" class="select-all-group rounded border-gray-300">
                        <span class="ml-2 text-sm font-semibold text-indigo-600 capitalize">Pilih Semua {{ str_replace('_', ' ', $group) }}</span>
                    </label>

                    <div class="flex flex-wrap gap-4 mt-2">
                        @foreach ($perms as $permission)
                            <label class="inline-flex items-center w-40">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                    class="rounded border-gray-300 permission-checkbox"
                                    {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-800 capitalize">
                                    {{ str_replace('_', ' ', explode('.', $permission->name)[1] ?? $permission->name) }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Tombol --}}
            <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                    Update
                </button>
                <a href="{{ route('roles.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 shadow">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Script --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllGlobal = document.getElementById('select-all-global');
            const allPermissionCheckboxes = document.querySelectorAll('input.permission-checkbox');
            const groupPermissions = document.querySelectorAll('.group-permission');

            // Global Select All
            selectAllGlobal.addEventListener('change', function () {
                const checked = selectAllGlobal.checked;
                allPermissionCheckboxes.forEach(cb => cb.checked = checked);
                document.querySelectorAll('.select-all-group').forEach(g => g.checked = checked);
            });

            // Per Group Select All
            groupPermissions.forEach(group => {
                const groupSelectAll = group.querySelector('.select-all-group');
                const groupCheckboxes = group.querySelectorAll('.permission-checkbox');

                if (groupSelectAll) {
                    groupSelectAll.addEventListener('change', function () {
                        groupCheckboxes.forEach(cb => cb.checked = groupSelectAll.checked);
                    });

                    groupCheckboxes.forEach(cb => {
                        cb.addEventListener('change', function () {
                            const allChecked = [...groupCheckboxes].every(cb => cb.checked);
                            groupSelectAll.checked = allChecked;
                        });
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
