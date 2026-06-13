<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Brand extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = ['name', 'slug', 'logo', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
