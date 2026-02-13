@extends('layouts.layout')
@section('title', 'Admin Dashboard' . " | " . config("app.name"))
@section('content')
<div class="container py-5">
    <h1 class="text-white mb-5 fw-bold">DASHBOARD AMMINISTRATORE</h1>
    <div class="row g-4 text-center justify-content-center">
        
        <div class="col-md-4 col-lg-2.4" style="flex: 0 0 auto; width: 20%;">
            <div class="card bg-dark border-warning text-white h-100 shadow">
                <div class="card-body py-5">
                    <i class="bi bi-envelope-paper display-4 text-warning mb-3"></i>
                    <h4>MESSAGGI</h4>
                    <p class="text-secondary small">Visualizza e rispondi ai contatti.</p>
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-warning w-100 mt-3">APRI</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-2.4" style="flex: 0 0 auto; width: 20%;">
            <div class="card bg-dark border-success text-white h-100 shadow">
                <div class="card-body py-5">
                    <i class="bi bi-people display-4 text-success mb-3"></i>
                    <h4>LISTA UTENTI</h4>
                    <p class="text-secondary small">Gestisci il database completo.</p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-success w-100 mt-3">VISUALIZZA</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-2.4" style="flex: 0 0 auto; width: 20%;">
            <div class="card bg-dark border-secondary text-white h-100 shadow">
                <div class="card-body py-5">
                    <i class="bi bi-person-plus display-4 mb-3"></i>
                    <h4>CLIENTI</h4>
                    <p class="text-secondary small">Registra nuovi utenti.</p>
                    <a href="{{ route('admin.clients.create') }}" class="btn btn-outline-light w-100 mt-3">INSERISCI</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-2.4" style="flex: 0 0 auto; width: 20%;">
            <div class="card bg-dark border-info text-white h-100 shadow">
                <div class="card-body py-5">
                    <i class="bi bi-person-badge display-4 text-info mb-3"></i>
                    <h4>COACH</h4>
                    <p class="text-secondary small">Registra nuovi istruttori.</p>
                    <a href="{{ route('admin.coaches.create') }}" class="btn btn-outline-info w-100 mt-3">INSERISCI</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-lg-2.4" style="flex: 0 0 auto; width: 20%;">
            <div class="card bg-dark border-primary text-white h-100 shadow">
                <div class="card-body py-5">
                    <i class="bi bi-calendar-plus display-4 text-primary mb-3"></i>
                    <h4>CORSI</h4>
                    <p class="text-secondary small">Crea e gestisci i corsi fitness.</p>
                    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary w-100 mt-3">AGGIUNGI</a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Piccolo fix per gestire 5 colonne su schermi grandi */
    @media (min-width: 992px) {
        .col-lg-2\.4 {
            flex: 0 0 auto;
            width: 20%;
        }
    }
</style>
@endsection