@extends('layouts.layout')

@section('title', 'Gestione Coach | ' . config('app.name'))

@section('content')
<div class="container py-5">
    <x-breadcrumb :items="$breadcrumb" />
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white fw-bold text-uppercase mb-0">Gestione Staff Coach</h1>
    </div>

    <div class="row g-4">
        {{-- COLONNA SINISTRA: FORM DI REGISTRAZIONE --}}
        <div class="col-lg-4">
            <div class="card bg-dark text-white border-light shadow-lg">
                <div class="card-header border-light bg-black p-3 text-center">
                    <h5 class="mb-0 fw-bold text-uppercase text-info">Registra Nuovo Coach</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.coaches.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase">Nome</label>
                                <input type="text" name="first_name" class="form-control bg-black text-white border-secondary @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase">Cognome</label>
                                <input type="text" name="last_name" class="form-control bg-black text-white border-secondary @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-secondary fw-bold text-uppercase">Email Professionale</label>
                            <input type="email" name="email" class="form-control bg-black text-white border-secondary @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-secondary fw-bold text-uppercase">Password Temp.</label>
                            <input type="password" name="password" class="form-control bg-black text-white border-secondary @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small text-secondary fw-bold text-uppercase">Conferma Password</label>
                            <input type="password" name="password_confirmation" class="form-control bg-black text-white border-secondary" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-info fw-bold py-2 text-uppercase">
                                <i class="bi bi-person-plus-fill"></i> Registra Coach
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- COLONNA DESTRA: LISTA COACH REGISTRATI --}}
        <div class="col-lg-8">
            <div class="card bg-dark border-secondary shadow-lg text-white">
                <div class="card-header border-secondary bg-black p-3">
                    <h5 class="mb-0 fw-bold text-uppercase text-center">Anagrafica Staff</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="bg-black text-info text-uppercase small">
                                <tr>
                                    <th class="ps-4">Coach</th>
                                    <th>Email</th>
                                    <th>Data Reg.</th>
                                    <th class="pe-4 text-end">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($coaches)
                                    @forelse($coaches as $coach)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold">
                                                <a href="{{ route('admin.users.show', $coach->id) }}" class="text-white text-decoration-none link-anagrafica">{{ $coach->first_name }} {{ $coach->last_name }}</a>
                                            </div>
                                            <span class="badge bg-outline-info border border-info text-info" style="font-size: 0.65rem;">COACH</span>
                                        </td>
                                        <td>{{ $coach->email }}</td>
                                        <td class="small text-secondary">
                                            {{ $coach->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('admin.users.edit', $coach->id) }}" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"> Modifica</i>
                                                </a>
                                                <form action="{{ route('admin.users.destroy', $coach->id) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo coach?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"> Elimina</i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-secondary italic">
                                            Nessun coach presente nel database.
                                        </td>
                                    </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-warning">
                                            Caricamento lista... (Verifica variabile $coaches nel controller)
                                        </td>
                                    </tr>
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection