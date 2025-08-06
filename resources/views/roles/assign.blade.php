<x-app-layout>
    <h2>Assign Role untuk {{ $user->name }}</h2>
    <form action="{{ route('roles.assign', $user) }}" method="POST">
        @csrf
        @foreach($roles as $role)
            <label>
                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                    {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }}>
                {{ $role->name }}
            </label><br>
        @endforeach
        <button type="submit">Simpan</button>
    </form>
</x-app-layout>
