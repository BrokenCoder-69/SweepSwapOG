<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Swap extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposer_id',
        'proposee_id',
        'proposer_product_id',
        'proposee_product_id',
        'status',
    ];

    public function proposer()
    {
        return $this->belongsTo(User::class, 'proposer_id');
    }

    public function proposee()
    {
        return $this->belongsTo(User::class, 'proposee_id');
    }

    public function proposerProduct()
    {
        return $this->belongsTo(Product::class, 'proposer_product_id');
    }

    public function proposeeProduct()
    {
        return $this->belongsTo(Product::class, 'proposee_product_id');
    }
}