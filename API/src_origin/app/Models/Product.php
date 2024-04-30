<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;

    // use Uuid instead of usual integer incremental ID
    use HasUuids;

    //I do not use timestamp "created_at" and "updated_at"
    public $timestamps = false;

    //relationships
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }
    public function soldTo(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, "orders_has_products");
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, "products_has_categories");
    }
    public function blogArticle(): BelongsToMany
    {
        return $this->belongsToMany(BlogArticle::class, "products_has_blog_articles");
    }
    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class, "labels_has_products");
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
