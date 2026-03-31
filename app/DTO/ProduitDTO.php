<?php

namespace App\DTO;

class ProduitDTO
{
    public function __construct(
        public readonly string  $name,
        public readonly string  $description,
        public readonly float   $prix,
        public readonly int     $stock,
        public readonly int     $categorie_id,
        public readonly ?array  $images = null, 
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name:         $data['name'] ?? '',
            description:  $data['description'] ?? '',
            prix:         $data['prix'] ?? 0,
            stock:        $data['stock'] ?? 0,
            categorie_id: $data['categorie_id'] ?? 0,
            images:       $data['images'] ?? null,
        );
    }

    public static function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:150'],
            'description'  => ['required', 'string'],
            'prix'         => ['required', 'numeric', 'min:0'],
            'stock'        => ['required', 'integer', 'min:0'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'images'       => ['nullable'],
            'images.*'     => ['image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }

    public static function updateRules(): array
    {
        return [
            'name'         => ['sometimes', 'string', 'max:150'],
            'description'  => ['sometimes', 'string'],
            'prix'         => ['sometimes', 'numeric', 'min:0'],
            'stock'        => ['sometimes', 'integer', 'min:0'],
            'categorie_id' => ['sometimes', 'exists:categories,id'],
            'images'       => ['nullable', 'array', 'max:4'],
            'images.*'     => ['image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }
}