@extends('layouts.layout')

@section('title', 'Inserisci Coach | ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white border-info shadow-lg">
                <div class="card-header border-info bg-black p-4 text-center">
                    <h3 class="mb-0 fw-bold text-uppercase text-info">Nuovo Staff Coach</h3>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.coaches.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            {{-- Nome --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-secondary fw-bold text-uppercase">Nome</label>
                                <input type="text" name="first_name" class="form-control bg-black text-white border-secondary @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Cognome --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-secondary fw-bold text-uppercase">Cognome</label>
                                <input type="text" name="last_name" class="form-control bg-black text-white border-secondary @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label small text-secondary fw-bold text-uppercase">Email Professionale</label>
                            <input type="email" name="email" class="form-control bg-black text-white border-secondary @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            {{-- Password --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label small text-secondary fw-bold text-uppercase">Password</label>
                                <input type="password" name="password" class="form-control bg-black text-white border-secondary @error('password') is-invalid @enderror" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Conferma Password --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label small text-secondary fw-bold text-uppercase">Conferma password</label>
                                <input type="password" name="password_confirmation" class="form-control bg-black text-white border-secondary" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-info fw-bold py-2 text-uppercase">Registra Coach</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-link text-secondary text-decoration-none small">Annulla e torna indietro</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection