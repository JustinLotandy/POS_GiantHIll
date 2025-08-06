<x-app-layout>
    <form method="POST" action="{{ route('roles.store') }}">
        @csrf
        <label>Nama Role</label>
        <input type="text" name="name" required>
        <button type="submit">Simpan</button>
    </form>
</x-app-layout>
