@extends('layouts.layout')
@section('title', 'Messaggi' . ' | ' . config('app.name'))
@section('content')
<div class="container py-5">
    <x-breadcrumb :items="$breadcrumb" />
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="text-white fw-bold text-uppercase mb-0">Messaggi con i coach</h1>
        @isset($coaches)
            <div class="dropdown">
                <button class="btn btn-info dropdown-toggle" type="button" id="nuovoMessaggioDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-plus-lg me-1"></i> Nuovo messaggio
                </button>
                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="nuovoMessaggioDropdown">
                    @foreach($coaches as $coach)
                        <li>
                            <a class="dropdown-item" href="{{ route('client.messages.startWithCoach', $coach->id) }}">
                                Scrivi a {{ $coach->first_name }} {{ $coach->last_name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endisset
    </div>

    <div class="card bg-dark border-info shadow-lg text-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead class="bg-black text-info text-uppercase small">
                        <tr>
                            <th class="ps-4 py-3">Coach</th>
                            <th class="py-3">Ultimo messaggio</th>
                            <th class="py-3 pe-4">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conversations as $conv)
                            @php
                                $other = $conv->otherParticipant(auth()->user());
                                $lastMsg = $conv->messages->first();
                            @endphp
                            <tr class="table-row-chat cursor-pointer" data-href="{{ route('client.messages.show', $conv->id) }}" role="button" tabindex="0">
                                <td class="ps-4 py-3">{{ $other->first_name }} {{ $other->last_name }}</td>
                                <td class="py-3 text-secondary">
                                    @if($lastMsg)
                                        {{ Str::limit($lastMsg->body, 40) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="py-3 pe-4 text-secondary small">
                                    @if($lastMsg)
                                        {{ $lastMsg->created_at->timezone('Europe/Rome')->format('d/m/Y H:i') }}
                                    @else
                                        {{ $conv->updated_at->timezone('Europe/Rome')->format('d/m/Y') }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-secondary">
                                    <i class="bi bi-chat-dots display-6 d-block mb-2"></i>
                                    Nessuna conversazione. Usa "Nuovo messaggio" per scrivere a un coach.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.table-row-chat { cursor: pointer; }
.table-row-chat:hover { background-color: rgba(255,255,255,0.05); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.table-row-chat[data-href]').forEach(function(row) {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
        row.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                window.location.href = this.dataset.href;
            }
        });
    });
});
</script>
@endpush
@endsection
