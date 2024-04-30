<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

// class User extends Model
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    public $incrementing = false;
    // use Uuid instead of usual integer incremental ID
    use HasUuids;

    //relationships
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, "author_id");
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, "buyer_id");
    }
    public function company():BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }
    public function registeredAdress(): BelongsToMany
    {
        return $this->belongsToMany(Adress::class, "registered_adresses");
    }
    public function profilePicture(): MorphMany
    {
        return $this->morphMany(Image::class, __FUNCTION__, 'table', 'table_key');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    static protected $rolesName = ["admin", "company", "client"];
    static public function getRoleNames(): array
    {
        return User::$rolesName;
    }
}
