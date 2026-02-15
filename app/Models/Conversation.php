<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = ['coach_id', 'client_id'];

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    /**
     * Numero di messaggi non letti in questa conversazione per l'utente $userId
     * (messaggi inviati dall'altro partecipante con read_at null).
     */
    public function unreadCountFor($userId): int
    {
        return $this->messages()->unreadBy($userId)->count();
    }

    public function isParticipant($user): bool
    {
        return (int) $user->id === (int) $this->coach_id || (int) $user->id === (int) $this->client_id;
    }

    public function otherParticipant($user): ?User
    {
        if ((int) $user->id === (int) $this->coach_id) {
            return $this->client;
        }
        if ((int) $user->id === (int) $this->client_id) {
            return $this->coach;
        }
        return null;
    }
}
