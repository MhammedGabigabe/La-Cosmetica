<?php

namespace App\DAO;

use App\DTO\ProduitDTO;
use App\Models\Image;
use App\Models\Produit;
use Illuminate\Support\Facades\Storage;

class ProduitDAO
{
    public function getAll()
    {
        return Produit::with(['categorie', 'images'])
                      ->get();
    }

    public function findBySlug(string $slug): Produit
    {
        return Produit::with(['categorie', 'images'])
                      ->where('slug', $slug)
                      ->firstOrFail(); 
    }

    private function storeImages(Produit $produit, array $images): void
    {
        $existingCount = $produit->images()->count();

        if ($existingCount + count($images) > 4) {
            throw new \Exception('Limite de 4 images par produit dépassée.', 422);
        }

        foreach ($images as $image) {
            $path = $image->store('produits', 'public');

            Image::create([
                'produit_id' => $produit->id,
                'path'       => $path,
            ]);
        }
    }

    public function create(ProduitDTO $dto): Produit
    {
        $produit = Produit::create([
            'name'         => $dto->name,
            'description'  => $dto->description,
            'prix'         => $dto->prix,
            'stock'        => $dto->stock,
            'categorie_id' => $dto->categorie_id,
        ]);

        if ($dto->images) {
            $this->storeImages($produit, $dto->images);
        }

        return $produit->load(['categorie', 'images']);
    }

    // public function update(string $slug, ProduitDTO $dto): Produit
    // {
    //     $produit = $this->findBySlug($slug);

    //     $fields = [];

    //     if (!empty($dto->name))         $fields['name']         = $dto->name;
    //     if (!empty($dto->description))  $fields['description']  = $dto->description;
    //     if ($dto->prix > 0)             $fields['prix']         = $dto->prix;
    //     if ($dto->stock >= 0)           $fields['stock']        = $dto->stock;
    //     if (!empty($dto->categorie_id)) $fields['categorie_id'] = $dto->categorie_id;

    //     if (!empty($fields)) {
    //         $produit->update($fields);
    //     }

    //     if ($dto->images) {
    //         $this->deleteImages($produit);
    //         $this->storeImages($produit, $dto->images);
    //     }

    //     return $produit->load(['categorie', 'images']);
    // }

    public function delete(string $slug): void
    {
        $produit = $this->findBySlug($slug);
        $this->deleteImages($produit);
        $produit->delete();
    }

    private function deleteImages(Produit $produit): void
    {
        foreach ($produit->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }
}