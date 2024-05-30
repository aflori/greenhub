<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    // use Uuid instead of usual integer incremental ID
    use HasUuids;

    //I do not use timestamp "created_at" and "updated_at"
    public $timestamps = false;

    //relationships
    public function describe(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'table', 'table_key');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, __FUNCTION__, 'table', 'table_key');
    }
}
