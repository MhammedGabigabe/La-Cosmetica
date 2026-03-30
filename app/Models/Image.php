<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use app\Models\Produit;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'path',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public static function validateLimit(int $produitId): bool
    {
        return self::where('produit_id', $produitId)->count() < 4;
    }
}
