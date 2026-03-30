<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'produit_id',
        'quantite',
        'total',
        'statut',
    ];

    const STATUTS = [
        'en_attente',
        'en_preparation',
        'prete',
        'livree',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cancel(): void
    {
        if ($this->statut !== 'en_attente') {
            throw new \Exception('Impossible d\'annuler une commande déjà en traitement.');
        }
        $this->update(['statut' => 'annulee']);
    }

    public function markReady(): void
    {
        $this->update(['statut' => 'prete']);
    }
}
