<?php

namespace Peyas\PreOrderForm\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
