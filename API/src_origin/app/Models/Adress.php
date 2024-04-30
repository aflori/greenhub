<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Adress extends Model
{
    use HasFactory;

    // use Uuid instead of usual integer incremental ID
    use HasUuids;

    //I do not use timestamp "created_at" and "updated_at"
    public $timestamps = false;
}
