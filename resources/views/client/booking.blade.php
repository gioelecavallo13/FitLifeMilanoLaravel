@extends('layouts.layout')
@section('title', 'Prenota Corsi' . " | " . config("app.name"))
@section('content')
<div class="container py-5">
    <div class="row justify-content-center text-center">
        <div class="col-lg-8">
            <h1 class="text-white fw-bold mb-3">PRENOTAZIONE CORSI</h1>
            <p class="text-secondary mb-4 text-white">
                Seleziona il corso e procedi con la prenotazione.
            </p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card bg-dark border-warning text-white shadow">
                <div class="card-body py-5 text-center">
                    <i class="bi bi-calendar-check display-4 text-warning mb-3"></i>
                    <h4 class="mb-2">CORSI DISPONIBILI</h4>
                    <p class="text-secondary small mb-4">
                        Vai alla pagina corsi per scegliere la tua attività.
                    </p>
                    <a href="{{ route('corsi') }}" class="btn btn-warning w-100">VAI AI CORSI</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
