@extends('layouts.layout')
@section('title', 'Messaggi' . " | " . config("app.name"))
@section('content')
<div class="container py-5">
    <x-breadcrumb :items="$breadcrumb" />
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white fw-bold text-uppercase">Messaggistica con i clienti</h1>
    </div>

    <div class="card bg-dark border-warning shadow-lg text-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead class="bg-black text-warning text-uppercase small">
                        <tr>
                            <th class="ps-4 py-3">Cliente</th>
                            <th class="py-3">Ultimo messaggio</th>
                            <th class="py-3">Data</th>
                            <th class="pe-4 text-end">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conversations as $conv)
                            @php
                                $other = $conv->otherParticipant(auth()->user());
                                $lastMsg = $conv->messages->first();
                            @endphp
                            <tr>
                                <td class="ps-4 py-3">{{ $other->first_name }} {{ $other->last_name }}</td>
                                <td class="py-3 text-secondary">
                                    @if($lastMsg)
                                        {{ Str::limit($lastMsg->body, 40) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="py-3 text-secondary small">
                                    @if($lastMsg)
                                        {{ $lastMsg->created_at->timezone('Europe/Rome')->format('d/m/Y H:i') }}
                                    @else
                                        {{ $conv->updated_at->timezone('Europe/Rome')->format('d/m/Y') }}
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('coach.messages.show', $conv->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-chat-dots"></i> Apri chat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-secondary">
                                    <i class="bi bi-chat-dots display-6 d-block mb-2"></i>
                                    Nessuna conversazione. Apri una chat da un cliente (anagrafica o corso).
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
