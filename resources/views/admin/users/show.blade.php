@extends('layouts.layout')
@section('title', 'Anagrafica: ' . $user->first_name . ' ' . $user->last_name . " | " . config("app.name"))
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Torna alla gestione utenti
                </a>
                <h1 class="text-white fw-bold text-uppercase mb-0 h4">Anagrafica: {{ $user->first_name }} {{ $user->last_name }}</h1>
            </div>

            {{-- Card Dati utente --}}
            <div class="card bg-dark border-primary text-white shadow-lg mb-4">
                <div class="card-header border-primary bg-black p-4">
                    <h3 class="mb-0 text-primary h4">Dati utente</h3>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="text-secondary small text-uppercase fw-bold d-block">Nome</label>
                            <span class="fs-5 fw-bold">{{ $user->first_name }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary small text-uppercase fw-bold d-block">Cognome</label>
                            <span class="fs-5 fw-bold">{{ $user->last_name }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="text-secondary small text-uppercase fw-bold d-block">Email</label>
                            <a href="mailto:{{ $user->email }}" class="text-primary text-decoration-none fs-5">{{ $user->email }}</a>
                        </div>
                        <div class="col-md-6">
                            <label class="text-secondary small text-uppercase fw-bold d-block">Ruolo</label>
                            <span class="badge {{ $user->role == 'admin' ? 'bg-danger' : ($user->role == 'coach' ? 'bg-info text-dark' : 'bg-secondary') }}">
                                {{ strtoupper($user->role) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="text-secondary small text-uppercase fw-bold d-block">Data registrazione</label>
                        <span>{{ $user->created_at->timezone('Europe/Rome')->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            @php
                $giorni = ['Monday' => 'Lunedì', 'Tuesday' => 'Martedì', 'Wednesday' => 'Mercoledì', 'Thursday' => 'Giovedì', 'Friday' => 'Venerdì', 'Saturday' => 'Sabato', 'Sunday' => 'Domenica'];
            @endphp

            @if($user->role === 'coach')
            {{-- Card Corsi di cui è personal trainer --}}
            <div class="card bg-dark border-warning text-white shadow-lg">
                <div class="card-header border-warning bg-black p-3">
                    <h5 class="mb-0 fw-bold text-uppercase text-warning">
                        <i class="bi bi-person-badge me-2"></i>Corsi di cui è personal trainer ({{ $user->createdCourses->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="bg-black text-warning text-uppercase small">
                                <tr>
                                    <th class="ps-4 py-3">Corso</th>
                                    <th class="py-3">Giorno</th>
                                    <th class="py-3">Orario</th>
                                    <th class="pe-4 py-3 text-end">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->createdCourses as $course)
                                <tr>
                                    <td class="ps-4 fw-bold text-warning">{{ $course->name }}</td>
                                    <td>{{ $giorni[$course->day_of_week] ?? $course->day_of_week }}</td>
                                    <td>{{ \Carbon\Carbon::parse($course->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($course->end_time)->format('H:i') }}</td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-eye"></i> Apri
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-secondary italic">
                                        Nessun corso assegnato.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @elseif($user->role === 'client')
            {{-- Card Corsi a cui è prenotato (solo clienti) --}}
            <div class="card bg-dark border-warning text-white shadow-lg">
                <div class="card-header border-warning bg-black p-3">
                    <h5 class="mb-0 fw-bold text-uppercase text-warning">
                        <i class="bi bi-calendar-check me-2"></i>Corsi a cui è prenotato ({{ $user->courses->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <thead class="bg-black text-warning text-uppercase small">
                                <tr>
                                    <th class="ps-4 py-3">Corso</th>
                                    <th class="py-3">Coach</th>
                                    <th class="py-3">Giorno</th>
                                    <th class="py-3">Orario</th>
                                    <th class="py-3">Data prenotazione</th>
                                    <th class="pe-4 py-3 text-end">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->courses as $course)
                                <tr>
                                    <td class="ps-4 fw-bold text-warning">{{ $course->name }}</td>
                                    <td>{{ $course->coach ? $course->coach->first_name . ' ' . $course->coach->last_name : 'N/D' }}</td>
                                    <td>{{ $giorni[$course->day_of_week] ?? $course->day_of_week }}</td>
                                    <td>{{ \Carbon\Carbon::parse($course->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($course->end_time)->format('H:i') }}</td>
                                    <td class="text-secondary small">
                                        {{ $course->pivot->created_at ? $course->pivot->created_at->timezone('Europe/Rome')->format('d/m/Y H:i') : '—' }}
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-calendar-check"></i> Apri corso
                                            </a>
                                            @if($course->coach)
                                                <a href="{{ route('admin.users.show', $course->coach->id) }}" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-person-badge"></i> Apri coach
                                                </a>
                                            @else
                                                <span class="text-secondary small align-self-center">N/D</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-secondary italic">
                                        Nessuna prenotazione.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
