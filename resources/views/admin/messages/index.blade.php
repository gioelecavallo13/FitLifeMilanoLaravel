@extends('layouts.layout')
@section('title', 'Messaggi ricevuti' . " | " . config("app.name"))
@section('content')
<div class="container py-5">
    <x-breadcrumb :items="$breadcrumb" />
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white fw-bold text-uppercase">Messaggi Ricevuti</h1>
    </div>

    {{-- BARRA DEI FILTRI --}}
    <div class="card bg-dark border-secondary mb-4 shadow">
        <div class="card-body">
            <form action="{{ route('admin.messages.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="text-secondary small">Filtra per Email</label>
                    <input type="text" name="email" class="form-control bg-black text-white border-secondary" 
                           placeholder="esempio@mail.com" value="{{ request('email') }}">
                </div>
                <div class="col-md-4">
                    <label class="text-secondary small">Stato Messaggio</label>
                    <select name="status" class="form-select bg-black text-white border-secondary">
                        <option value="">Tutti gli stati</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Nuovi messaggi</option>
                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Letti</option>
                        <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Risposti</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-warning w-100 fw-bold">
                        <i class="bi bi-filter"></i> APPLICA FILTRI
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABELLA MESSAGGI --}}
    <div class="card bg-dark border-warning shadow-lg text-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle">
                    <thead class="bg-black text-warning">
                        <tr>
                            <th class="ps-4 py-3">Stato</th>
                            <th class="py-3">Data</th>
                            <th class="py-3">Utente / Email</th>
                            <th class="py-3">Oggetto</th>
                            <th class="pe-4 text-end">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $item)
                        <tr>
                            <td class="ps-4">
                                @if($item->status == 'new')
                                    <span class="badge bg-danger rounded-pill px-3">Nuovo</span>
                                @elseif($item->status == 'read')
                                    <span class="badge bg-secondary rounded-pill px-3">Letto</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3">Risposto</span>
                                @endif
                            </td>
                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="fw-bold">{{ $item->first_name }} {{ $item->last_name }}</div>
                                <div class="small text-secondary">{{ $item->email }}</div>
                            </td>
                            <td>
                                <span class="text-warning small text-uppercase fw-bold">{{ $item->subject }}</span>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.messages.show', $item->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-eye"></i> Apri
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary italic">
                                Nessun messaggio trovato con i criteri selezionati.
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