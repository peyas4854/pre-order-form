<?php

namespace Peyas\PreOrderForm\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Peyas\PreOrderForm\Traits\SoftDeletesWithDeletedBy;

class PreOrder extends Model
{
    use HasFactory,SoftDeletes,SoftDeletesWithDeletedBy;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'product_id',
        'deleted_at',
        'deleted_by_id'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
