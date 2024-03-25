<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    // use Uuid instead of usual integer incremental ID
    use HasUuids;

    //I do not use timestamp "created_at" and "updated_at"
    public $timestamps = false;

    //relationships
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, "buyer_id");
    }

    public function facturationAdress(): BelongsTo
    {
        return $this->belongsTo(Adress::class, "facturation_adress");
    }

    public function deliveryAdress(): BelongsTo
    {
        return $this->belongsTo(Adress::class, "delivery_adress");
    }

    // we can use $this->products->pivot to gain access to associated table's fields
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, "orders_has_products");
    }
}
