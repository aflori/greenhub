<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class BlogArticle extends Model
{
    use HasFactory;

    // use Uuid instead of usual integer incremental ID
    use HasUuids;

    //I do not use timestamp "created_at" and "updated_at"
    public $timestamps = false;

    //relationships
    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, "products_has_blog_articles");
    }
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable', 'coment_on_table', 'table_key');
    }
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, __FUNCTION__, 'table', 'table_key');
    }
}
