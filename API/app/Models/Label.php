<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Label extends Model
{
    use HasFactory;

    // use Uuid instead of usual integer incremental ID
    use HasUuids;

    //I do not use timestamp "created_at" and "updated_at"
    public $timestamps = false;

    // relationships
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, "labels_has_products");
    }
    public function logo(): MorphMany
    {
        return $this->morphMany(Image::class, 'logo', 'table', 'table_key');
    }
}
