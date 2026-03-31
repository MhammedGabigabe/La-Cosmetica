<?php

namespace App\Http\Controllers;

use App\DAO\CommandeDAO;
use App\DTO\CommandeDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function __construct(
        private readonly CommandeDAO $commandeDAO
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(CommandeDTO::rules());
        $dto       = CommandeDTO::fromRequest($validated);
        $commande  = $this->commandeDAO->create($request->user(), $dto);

        return response()->json([
            'message' => 'Commande passée avec succès.',
            'data'    => $commande,
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $commandes = $this->commandeDAO->getByUser($request->user());

        return response()->json([
            'data' => $commandes,
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $commande = $this->commandeDAO->findById($id, $request->user());

        return response()->json([
            'data' => $commande,
        ]);
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        $commande = $this->commandeDAO->cancel($id, $request->user());

        return response()->json([
            'message' => 'Commande annulée avec succès.',
            'data'    => $commande,
        ]);
    }

    public function markReady(int $id): JsonResponse
    {
        $commande = $this->commandeDAO->markReady($id);

        return response()->json([
            'message' => 'Commande marquée comme prête.',
            'data'    => $commande,
        ]);
    }

    public function all(): JsonResponse
    {
        return response()->json([
            'data' => $this->commandeDAO->getAll(),
        ]);
    }
}