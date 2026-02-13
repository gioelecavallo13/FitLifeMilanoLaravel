<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Dashboard del Cliente
     */
    public function index()
    {
        // Recuperiamo i corsi a cui l'utente è già iscritto per mostrarli in dashboard
        $myCourses = Auth::user()->courses()->with('coach')->get();
        return view('client.dashboard', compact('myCourses'));
    }

    /**
     * Mostra la pagina di prenotazione con la lista dei corsi
     */
    public function booking()
    {
        // Carichiamo tutti i corsi con il coach e il conteggio degli iscritti
        $courses = Course::with('coach')->withCount('users')->get();
        
        return view('client.booking', compact('courses'));
    }

    /**
     * Gestisce l'iscrizione di un utente a un corso
     */
    public function enroll(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = Auth::user();

        // 1. Controllo se l'utente è già iscritto
        if ($user->courses()->where('course_id', $courseId)->exists()) {
            return redirect()->back()->with('error', 'Sei già iscritto a questo corso!');
        }

        // 2. Controllo disponibilità posti
        if ($course->users()->count() >= $course->capacity) {
            return redirect()->back()->with('error', 'Spiacenti, il corso è al completo!');
        }

        // 3. Iscrizione (inserimento nella tabella pivot)
        $user->courses()->attach($courseId);

        return redirect()->route('client.dashboard')->with('success', 'Prenotazione effettuata con successo! Ti aspettiamo in sala.');
    }

    /**
     * Consente all'utente di annullare una prenotazione
     */
    public function cancelBooking($courseId)
    {
        $user = Auth::user();
        $user->courses()->detach($courseId);

        return redirect()->back()->with('success', 'Prenotazione annullata.');
    }
}