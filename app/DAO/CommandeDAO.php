<?php

namespace App\DAO;

use App\DTO\CommandeDTO;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CommandeDAO
{
    public function create(User $user, CommandeDTO $dto): Commande
    {
        $produit = Produit::where('slug', $dto->slug)->firstOrFail();

        if ($produit->stock < $dto->quantite) {
            throw new \Exception(
                "Stock insuffisant : {$produit->stock}.",
                422
            );
        }

        $total = $produit->prix * $dto->quantite;

        $commande = Commande::create([
            'user_id'    => $user->id,
            'produit_id' => $produit->id,
            'quantite'   => $dto->quantite,
            'total'      => $total,
            'statut'     => 'en_attente',
        ]);

        $produit->decrementStock($dto->quantite);

        return $commande->load('produit');
    }

    public function getByUser(User $user): Collection
    {
        return Commande::with('produit')
                       ->where('user_id', $user->id)
                       ->latest()
                       ->get();
    }

    public function findById(int $id, User $user): Commande
    {
        return Commande::with('produit')
                       ->where('id', $user->id)
                       ->where('user_id', $user->id)
                       ->firstOrFail();
    }

    public function getAll(): Collection
    {
        return Commande::with(['produit', 'user'])
                       ->latest()
                       ->get();
    }
}