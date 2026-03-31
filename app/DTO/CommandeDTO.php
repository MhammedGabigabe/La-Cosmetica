<?php

namespace App\DTO;

class CommandeDTO
{
    public function __construct(
        public readonly string $slug,
        public readonly int    $quantite,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            slug:     $data['slug'],
            quantite: $data['quantite'],
        );
    }

    public static function rules(): array
    {
        return [
            'slug'     => ['required', 'string', 'exists:produits,slug'],
            'quantite' => ['required', 'integer', 'min:1'],
        ];
    }
}