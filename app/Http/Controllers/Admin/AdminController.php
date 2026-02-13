<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Dashboard principale
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /* --- FUNZIONI DI RECUPERO DATI (Interne) --- */

    private function getClientsList()
    {
        return User::where('role', 'client')->latest()->get();
    }

    private function getCoachesList()
    {
        return User::where('role', 'coach')->latest()->get();
    }

    private function getCoursesList()
    {
        return Course::with('coach')->withCount('users')->latest()->get();
    }

    /* --- GESTIONE MESSAGGI --- */

    public function messages(Request $request)
    {
        $query = ContactRequest::query();
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $requests = $query->latest()->get();
        return view('admin.messages.index', compact('requests'));
    }

    public function messageShow($id)
    {
        $message = ContactRequest::findOrFail($id);
        if ($message->status === 'new') {
            $message->update(['status' => 'read']);
        }
        return view('admin.messages.show-message', compact('message'));
    }

    public function messageReply(Request $request, $id)
    {
        $request->validate(['reply_text' => 'required|min:5']);
        $message = ContactRequest::findOrFail($id);
        $message->update(['status' => 'replied']);

        try {
            $emailData = [
                'subject' => $message->subject,
                'replyText' => $request->reply_text,
                'first_name' => $message->first_name
            ];
            Mail::send('emails.contact-response', $emailData, function($mail) use ($message) {
                $mail->to($message->email)->subject('Risposta FitLife Milano: ' . $message->subject);
            });
            return redirect()->route('admin.messages.index')->with('success', 'Risposta inviata!');
        } catch (\Exception $e) {
            return redirect()->route('admin.messages.index')->with('error', 'Invio email fallito.');
        }
    }

    /* --- GESTIONE COACH --- */

    public function createCoach()
    {
        $coaches = $this->getCoachesList();
        return view('admin.coaches.create', compact('coaches'));
    }

    public function storeCoach(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'coach',
        ]);

        return redirect()->route('admin.coaches.create')->with('success', 'Coach inserito correttamente!');
    }

    /* --- GESTIONE CLIENTI --- */

    public function createClient()
    {
        // Correzione Immagine 1 e 3: Carica la variabile $clients per la tabella
        $clients = $this->getClientsList();
        return view('admin.clients.create', compact('clients'));
    }

    public function storeClient(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'name'       => $request->first_name . ' ' . $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'client',
        ]);

        return redirect()->route('admin.clients.create')->with('success', 'Cliente registrato correttamente!');
    }

    /* --- GESTIONE CORSI --- */

    public function courseCreate()
    {
        $coaches = User::where('role', 'coach')->get();
        $courses = $this->getCoursesList();
        return view('admin.courses.create', compact('coaches', 'courses'));
    }

    public function courseStore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'user_id'     => 'required|exists:users,id', 
            'price'       => 'required|numeric|min:0',
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'capacity'    => 'required|integer|min:1',
        ]);

        Course::create($validated);
        return redirect()->route('admin.courses.create')->with('success', 'Corso aggiunto!');
    }

    public function courseEdit($id)
    {
        $course = Course::findOrFail($id);
        $coaches = User::where('role', 'coach')->get();
        return view('admin.courses.edit', compact('course', 'coaches'));
    }

    public function courseUpdate(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'user_id'     => 'required|exists:users,id', 
            'price'       => 'required|numeric|min:0',
            'day_of_week' => 'required|string',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'capacity'    => 'required|integer|min:1',
        ]);

        $course->update($validated);
        return redirect()->route('admin.courses.create')->with('success', 'Corso aggiornato correttamente!');
    }

    public function courseDestroy(Request $request)
    {
        $course = Course::findOrFail($request->id);
        $course->users()->detach(); 
        $course->delete();

        return redirect()->route('admin.courses.create')->with('success', 'Corso eliminato!');
    }

    /* --- ANAGRAFICA UTENTI (Correzione Immagini 2 e 5) --- */

    public function usersIndex(Request $request)
    {
        $query = User::query();
        if ($request->filled('role')) $query->where('role', $request->role);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $users = $query->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'       => 'required|in:admin,coach,client',
        ]);

        $user->update($validated);
        return redirect()->route('admin.users.index')->with('success', 'Utente aggiornato!');
    }

    public function userDestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        // Se l'URL da cui proveniamo contiene "inserisci-clienti", torniamo lì
        if (str_contains(url()->previous(), 'inserisci-clienti')) {
            return redirect()->route('admin.clients.create')->with('success', 'Cliente rimosso correttamente!');
        }
        
        // Se l'URL da cui proveniamo contiene "inserisci-coach", torniamo lì
        if (str_contains(url()->previous(), 'inserisci-coach')) {
            return redirect()->route('admin.coaches.create')->with('success', 'Coach rimosso correttamente!');
        }

        return redirect()->route('admin.users.index')->with('success', 'Utente rimosso!');
    }
}