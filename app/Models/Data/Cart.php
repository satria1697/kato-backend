<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table="cart";

    public function goods() {
        return $this->hasOne(Goods::class, 'id', 'goods_id');
    }
}
