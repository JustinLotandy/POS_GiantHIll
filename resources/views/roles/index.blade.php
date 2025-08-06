<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Role & Permission</h2>
    </x-slot>
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="mb-4 flex justify-between items-center">
            <span class="text-lg font-bold">Daftar Role</span>
            <a href="{{ route('roles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow">+ Tambah Role</a>
        </div>
        <div class="bg-white rounded shadow p-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Role</th>
                        <th class="px-4 py-2">Permission</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-semibold text-gray-800">{{ $role->name }}</td>
                        <td class="px-4 py-2">
                            <div class="flex flex-wrap gap-1">
                                @foreach($role->permissions as $perm)
                                    <span class="bg-gray-200 text-gray-800 rounded px-2 py-1 text-xs">{{ $perm->name }}</span>
                                @endforeach
                                @if($role->permissions->isEmpty())
                                    <span class="text-gray-400 text-xs">Tidak ada permission</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-2 flex gap-2 justify-center">
                            <a href="{{ route('roles.edit', $role) }}" class="bg-yellow-400 hover:bg-yellow-500 text-xs text-white px-3 py-1 rounded shadow">Edit</a>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Yakin hapus?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-xs text-white px-3 py-1 rounded shadow" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
