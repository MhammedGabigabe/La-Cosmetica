<?php

namespace App\Http\Controllers;

use App\DAO\CategorieDAO;
use App\DTO\CategorieDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function __construct(
        private readonly CategorieDAO $categorieDAO
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'data' => $this->categorieDAO->getAll(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        return response()->json([
            'data' => $this->categorieDAO->findBySlug($slug),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(CategorieDTO::rules());
        $dto       = CategorieDTO::fromRequest($validated);
        $categorie = $this->categorieDAO->create($dto);

        return response()->json([
            'message' => 'Catégorie créée avec succès.',
            'data'    => $categorie,
        ], 201);
    }

    public function update(Request $request, string $slug): JsonResponse
    {
        $validated = $request->validate(CategorieDTO::updateRules());
        $dto       = CategorieDTO::fromRequest($validated);
        $categorie = $this->categorieDAO->update($slug, $dto);

        return response()->json([
            'message' => 'Catégorie mise à jour.',
            'data'    => $categorie,
        ]);
    }

    public function destroy(string $slug): JsonResponse
    {
        $this->categorieDAO->delete($slug);

        return response()->json([
            'message' => 'Catégorie supprimée.',
        ], 204);
    }
}