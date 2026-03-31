<?php

namespace App\DAO;

use App\DTO\CategorieDTO;
use App\Models\Categorie;
use Illuminate\Database\Eloquent\Collection;

class CategorieDAO
{
    public function getAll(): Collection
    {
        return Categorie::all();
    }

    public function findBySlug(string $slug): Categorie
    {
        return Categorie::where('slug', $slug)
                        ->firstOrFail();
    }

    public function create(CategorieDTO $dto): Categorie
    {
        return Categorie::create([
            'name'        => $dto->name,
            'description' => $dto->description,
        ]);
    }

    public function update(string $slug, CategorieDTO $dto): Categorie
    {
        $categorie = $this->findBySlug($slug);

        $categorie->update([
            'name'        => $dto->name        ?? $categorie->name,
            'description' => $dto->description ?? $categorie->description,
        ]);

        return $categorie->fresh();
    }

    public function delete(string $slug): void
    {
        $categorie = $this->findBySlug($slug);
        $categorie->delete();
    }
}