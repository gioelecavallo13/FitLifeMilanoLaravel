<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Mostra la pagina del profilo dell'utente corrente (propria anagrafica)
     */
    public function show()
    {
        $user = Auth::user()->load(['courses.coach', 'createdCourses']);

        $breadcrumb = [
            ['label' => 'Dashboard', 'url' => route('dashboard.selector')],
            ['label' => 'Profilo', 'url' => null],
        ];

        return view('profile.show', compact('user', 'breadcrumb'));
    }

    /**
     * Aggiorna la foto profilo dell'utente corrente
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user = Auth::user();

        // Elimina la vecchia foto se presente
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Salva la nuova foto
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->update(['profile_photo' => $path]);

        return redirect()->route('profile.show')->with('success', 'Foto profilo aggiornata con successo!');
    }
}
