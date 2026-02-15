@extends('layouts.layout')
@section('title', 'Gestione utenti' . " | " . config("app.name"))

@section('content')
<div class="container py-5">
    <x-breadcrumb :items="$breadcrumb" />
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white fw-bold text-uppercase">Gestione Utenti</h2>
    </div>

    {{-- Sezione Filtri --}}
    <div class="card bg-dark border-secondary mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control bg-black text-white border-secondary" placeholder="Cerca nome, cognome o email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select bg-black text-white border-secondary">
                        <option value="">Tutti i ruoli</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="coach" {{ request('role') == 'coach' ? 'selected' : '' }}>Coach</option>
                        <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Cliente</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-light w-100">Filtra</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabella Utenti --}}
    <div class="table-responsive shadow">
        <table class="table table-dark table-hover border-secondary align-middle">
            <thead class="table-black text-secondary">
                <tr>
                    <th>NOME</th>
                    <th>COGNOME</th>
                    <th>EMAIL</th>
                    <th>RUOLO</th>
                    <th>DATA REGISTRAZIONE</th>
                    <th class="text-center">AZIONI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="fw-bold">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-white text-decoration-none link-anagrafica">{{ $user->first_name }}</a>
                    </td>
                    <td>{{ $user->last_name }}</td>
                    <td class="text-info">{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : ($user->role == 'coach' ? 'bg-info text-dark' : 'bg-secondary') }}">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>
                    <td class="small">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            {{-- Pulsante Modifica --}}
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil-square"></i> Modifica
                            </a>

                            {{-- Form Elimina con Messaggio di Conferma --}}
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                  onsubmit="return confirm('Sei sicuro di voler eliminare l\'utente {{ $user->email }}? Questa azione è irreversibile.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Elimina
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-secondary">Nessun utente trovato con questi criteri.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection