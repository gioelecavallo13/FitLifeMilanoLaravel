<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use App\Models\User; // Importa il modello User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash; // Importa per criptare le password

class AdminController extends Controller
{
    // Dashboard con le 3 card
    public function index()
    {
        return view('admin.dashboard');
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
                $mail->to($message->email)
                     ->subject('Risposta FitLife Milano: ' . $message->subject);
            });

            return redirect()->route('admin.messages.index')->with('success', 'Risposta inviata correttamente!');

        } catch (\Exception $e) {
            return redirect()->route('admin.messages.index')
                             ->with('error', 'Stato aggiornato, ma l\'invio email è fallito.');
        }
    }

    /* --- GESTIONE CLIENTI --- */

    public function createClient()
    {
        return view('admin.clients.create');
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
        'name'       => $request->first_name . ' ' . $request->last_name, // Opzionale, se hai ancora la colonna 'name'
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'role'       => 'client',
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'Cliente registrato con successo!');
}
    /* --- GESTIONE COACH --- */

    public function createCoach()
    {
        return view('admin.coaches.create');
    }

public function storeCoach(Request $request)
{
    // 1. Validazione atomica
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name'  => 'required|string|max:255',
        'email'      => 'required|string|email|max:255|unique:users',
        'password'   => 'required|string|min:8|confirmed',
    ]);

    // 2. Inserimento nel Database
    User::create([
        'first_name' => $request->first_name,
        'last_name'  => $request->last_name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'role'       => 'coach', // Forza il ruolo coach
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'Coach inserito correttamente!');
}

public function usersIndex(Request $request)
{
    $query = User::query();

    // Filtro per Ruolo
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    // Ricerca per Nome, Cognome o Email
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
// Mostra il form di modifica
// 1. Carica la pagina di modifica
public function userEdit($id)
{
    $user = User::findOrFail($id);
    return view('admin.users.edit', compact('user'));
}

// 2. Elabora l'aggiornamento
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

    return redirect()->route('admin.users.index')
                     ->with('success', "L'utente {$user->email} è stato aggiornato correttamente.");
}

// Elimina l'utente
public function userDestroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->route('admin.users.index')->with('success', 'Utente rimosso dal database.');
}
}