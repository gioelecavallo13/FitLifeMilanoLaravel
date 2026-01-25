<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute; // Necessario per il nuovo stile degli Accessors

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * I campi che possono essere popolati (Mass Assignment)
     * Abbiamo rimosso 'name' a favore della struttura atomica.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    /**
     * I campi da nascondere nelle risposte JSON o array.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting dei tipi di dati.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ACCESSOR: Nome Completo
     * Permette di usare $user->full_name nelle View senza avere una colonna 'name' nel DB.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->first_name} {$this->last_name}",
        );
    }

    /**
     * RELAZIONE: I corsi a cui un CLIENTE è iscritto
     * (Molti-a-Molti tramite la tabella pivot course_user)
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    /**
     * RELAZIONE: I corsi gestiti/creati da un COACH
     * (Uno-a-Molti: un coach crea molti corsi)
     */
    public function createdCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'user_id');
    }
}