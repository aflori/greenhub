<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Company extends Model
{
    use HasFactory;

    // use Uuid instead of usual integer incremental ID
    use HasUuids;

    //I do not use timestamp "created_at" and "updated_at"
    public $timestamps = false;

    //relationships
    public function members(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable', 'coment_on_table', 'table_key');
    }
}
