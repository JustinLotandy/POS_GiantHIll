<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar User</h2>
    </x-slot>
    <div class="max-w-4xl mx-auto py-8">
        

        <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 mb-4 transition">+ Tambah User</a>
        <div class="bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-3 font-bold text-gray-700">ID</th>
                        <th class="px-4 py-3 font-bold text-gray-700">Nama</th>
                        <th class="px-4 py-3 font-bold text-gray-700">Email</th>
                        <th class="px-4 py-3 font-bold text-gray-700">Role</th>
                        <th class="px-4 py-3 font-bold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="px-4 py-2 font-mono">{{ $user->id }}</td>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                             <td class="px-4 py-2">
                                {{-- Ambil dan tampilkan semua role --}}
                                @foreach($user->getRoleNames() as $role)
                                    <span class="inline-block bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs">{{ ucfirst($role) }}</span>
                                @endforeach
                            </td>
                            <td class="px-4 py-2 flex gap-1">
                                <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-xs rounded px-3 py-1 text-white">Edit</a>
                                
                                <!-- <a href="{{ route('users.editRole', $user->id) }}" class="bg-indigo-600 text-white px-3 py-1 rounded text-xs hover:bg-indigo-700">Atur Role</a> -->

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-500 hover:bg-red-600 text-xs rounded px-3 py-1 text-white" type="submit">Hapus</button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-400 italic">Belum ada user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
