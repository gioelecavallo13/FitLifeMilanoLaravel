@extends('layouts.layout')
@section('title', 'I miei corsi' . " | " . config("app.name"))
@section('content')
<div class="container py-5">
    <x-breadcrumb :items="$breadcrumb" />
    <h1 class="text-white mb-4 fw-bold text-uppercase">I miei corsi</h1>
    @php
        $giorni = ['Monday' => 'Lunedì', 'Tuesday' => 'Martedì', 'Wednesday' => 'Mercoledì', 'Thursday' => 'Giovedì', 'Friday' => 'Venerdì', 'Saturday' => 'Sabato', 'Sunday' => 'Domenica'];
    @endphp
    <div class="card bg-dark border-primary shadow-lg text-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead class="bg-black text-primary text-uppercase small">
                        <tr>
                            <th class="ps-4 py-3">Corso</th>
                            <th class="py-3">Giorno</th>
                            <th class="py-3">Orario</th>
                            <th class="py-3">Capacità</th>
                            <th class="py-3 text-center">Iscritti</th>
                            <th class="pe-4 py-3 text-end">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('coach.courses.show', $course->id) }}" class="text-white text-decoration-none link-anagrafica fw-bold text-uppercase">{{ $course->name }}</a>
                                <div class="small text-secondary">Max {{ $course->capacity }} persone</div>
                            </td>
                            <td>{{ $giorni[$course->day_of_week] ?? $course->day_of_week }}</td>
                            <td>{{ \Carbon\Carbon::parse($course->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($course->end_time)->format('H:i') }}</td>
                            <td>{{ $course->capacity }}</td>
                            <td class="text-center">{{ $course->users_count }}</td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('coach.courses.show', $course->id) }}" class="btn btn-sm btn-primary"><i class="bi bi-eye"></i> Apri</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-secondary italic">
                                Nessun corso creato.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
