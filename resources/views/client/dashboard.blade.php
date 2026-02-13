@extends('layouts.layout')
@section('title', 'Client Dashboard' . " | " . config("app.name"))
@section('content')
<div class="container py-5">
    <h1 class="text-white mb-5 fw-bold">DASHBOARD CLIENTE</h1>
    <div class="row g-4 text-center">
        <div class="col-md-4 offset-md-4">
            <div class="card bg-dark border-warning text-white h-100 shadow">
                <div class="card-body py-5">
                    <i class="bi bi-calendar-check display-4 text-warning mb-3"></i>
                    <h4>PRENOTA CORSI</h4>
                    <p class="text-secondary small">Iscriviti ai corsi disponibili.</p>
                    <a href="{{ route('client.booking') }}" class="btn btn-warning w-100 mt-3">PRENOTA</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
