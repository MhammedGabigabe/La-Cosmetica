<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DAO\ProduitDAO;
use App\DTO\ProduitDTO;
use Illuminate\Http\JsonResponse;

class ProduitController extends Controller
{
    public function __construct(
        private readonly ProduitDAO $produitDAO
    ) {}

    public function index(): JsonResponse
    {
        $produits = $this->produitDAO->getAll();

        return response()->json([
            'data' => $produits,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $produit = $this->produitDAO->findBySlug($slug);

        return response()->json([
            'data' => $produit,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(ProduitDTO::rules());
        $validated['images'] = $request->file('images');
        $dto       = ProduitDTO::fromRequest($validated);
        $produit   = $this->produitDAO->create($dto);

        return response()->json([
            'message' => 'Produit créé avec succès.',
            'data'    => $produit,
        ], 201);
    }

    public function update(Request $request, string $slug): JsonResponse
    {
        $validated = $request->validate(ProduitDTO::updateRules());
        $validated['images'] = $request->file('images');
        $dto       = ProduitDTO::fromRequest($validated);
        $produit   = $this->produitDAO->update($slug, $dto);

        return response()->json([
            'message' => 'Produit mis à jour.',
            'data'    => $produit,
        ]);
    }

    public function destroy(string $slug): JsonResponse
    {
        $this->produitDAO->delete($slug);

        return response()->json([
            'message' => 'Produit supprimé.',
        ], 204);
    }
}
