<?php

namespace App\DTO;

class CategorieDTO
{
    public function __construct(
        public readonly string  $name,
        public readonly ?string $description = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name:        $data['name'],
            description: $data['description'] ?? null,
        );
    }

    public static function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public static function updateRules(): array
    {
        return [
            'name'        => ['sometimes', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}