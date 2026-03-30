<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Produit;


class Categorie extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'description',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')      
            ->saveSlugsTo('slug')            
            ->slugsShouldBeNoLongerThan(50); 
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
