@extends('layouts.layout')

@section('title', 'Client Dashboard' . " | " . config("app.name"))

@section('content')
<div class="container py-5">
    <h1 class="text-white mb-5 fw-bold text-uppercase">Dashboard Cliente</h1>

    {{-- Azioni Rapide --}}
    <div class="row g-4 text-center mb-5">
        <div class="col-md-4 offset-md-4">
            <div class="card bg-dark border-warning text-white h-100 shadow">
                <div class="card-body py-5">
                    <i class="bi bi-calendar-plus display-4 text-warning mb-3"></i>
                    <h4 class="fw-bold">PRENOTA NUOVO CORSO</h4>
                    <p class="text-secondary small">Scegli tra le attività disponibili e assicurati il tuo posto.</p>
                    <a href="{{ route('client.booking') }}" class="btn btn-warning w-100 mt-3 fw-bold text-uppercase">Vedi Corsi</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Sezione Corsi Prenotati --}}
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark border-secondary text-white shadow-lg">
                <div class="card-header border-secondary bg-black p-3">
                    <h5 class="mb-0 fw-bold text-uppercase"><i class="bi bi-list-stars text-info me-2"></i>Le Mie Prenotazioni</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="bg-black text-info text-uppercase small">
                                <tr>
                                    <th class="ps-4">Corso</th>
                                    <th>Coach</th>
                                    <th>Giorno e Orario</th>
                                    <th class="pe-4 text-end">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- $myCourses viene passato dal metodo index() del ClientController --}}
                                @isset($myCourses)
                                    @forelse($myCourses as $course)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-warning">{{ $course->name }}</div>
                                            </td>
                                            <td>{{ $course->coach->first_name ?? 'N/D' }} {{ $course->coach->last_name ?? '' }}</td>
                                            <td>
                                                <span class="badge bg-outline-secondary border border-secondary text-white small">
                                                    {{ $course->day_of_week }} | {{ \Carbon\Carbon::parse($course->start_time)->format('H:i') }}
                                                </span>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <form action="{{ route('client.cancel', $course->id) }}" method="POST" onsubmit="return confirm('Vuoi davvero annullare la prenotazione?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                                                        <i class="bi bi-x-circle me-1"></i> Annulla
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-secondary italic">
                                                Non hai ancora effettuato alcuna prenotazione.
                                            </td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-warning">
                                            Caricamento prenotazioni...
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