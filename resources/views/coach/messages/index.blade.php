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
                            <th class="ps-4 py-3">Da / A</th>
                            <th class="py-3">Oggetto</th>
                            <th class="py-3">Data</th>
                            <th class="pe-4 text-end">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-secondary">
                                <i class="bi bi-info-circle display-6 d-block mb-2"></i>
                                La messaggistica tra coach e clienti sarà disponibile prossimamente.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 text-secondary small">
        <button type="button" class="btn btn-outline-secondary btn-sm" disabled>Nuovo messaggio</button>
    </div>
</div>
@endsection
