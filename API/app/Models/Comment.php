<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
// use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    // use Uuid instead of usual integer incremental ID
    use HasUuids;

    //relationships
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, "author_id");
    }
    public function commentable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'coment_on_table', 'table_key');
    }

    //I do not use timestamp "created_at" and "updated_at"
    public $timestamps = false;
}
