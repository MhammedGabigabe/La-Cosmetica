<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Categorie;
use App\Models\Image;
use App\Models\Commande;

class Produit extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'description',
        'prix',
        'stock',
        'categorie_id',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(60);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public static function findBySlug(string $slug): ?self
    {
        return self::where('slug', $slug)->firstOrFail();
    }

    public function decrementStock(int $quantite): void
    {
        $this->decrement('stock', $quantite);
    }
}
