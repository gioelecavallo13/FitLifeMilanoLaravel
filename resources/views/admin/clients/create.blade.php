@extends('layouts.layout')

@section('title', 'Inserisci Cliente | ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white border-light shadow-lg">
                <div class="card-header border-light bg-black p-4 text-center">
                    <h3 class="mb-0 fw-bold text-uppercase">Registra Nuovo Cliente</h3>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.clients.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            {{-- Nome --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">NOME</label>
                                <input type="text" name="first_name" class="form-control bg-black text-white border-secondary @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Cognome --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">COGNOME</label>
                                <input type="text" name="last_name" class="form-control bg-black text-white border-secondary @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label small text-secondary fw-bold">INDIRIZZO EMAIL</label>
                            <input type="email" name="email" class="form-control bg-black text-white border-secondary @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="mario.rossi@esempio.com" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label class="form-label small text-secondary fw-bold">PASSWORD TEMPORANEA</label>
                            <input type="password" name="password" class="form-control bg-black text-white border-secondary @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Conferma Password --}}
                        <div class="mb-4">
                            <label class="form-label small text-secondary fw-bold">CONFERMA PASSWORD</label>
                            <input type="password" name="password_confirmation" class="form-control bg-black text-white border-secondary" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-light fw-bold py-2 text-uppercase">Registra nel Database</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-link text-secondary text-decoration-none small">Torna alla Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection