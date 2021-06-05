<?php

namespace App\Models\Data;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table="cart";
    protected $fillable=[
        'status'
    ];

    public function goods()
    {
        return $this->hasOne(Goods::class, 'id', 'goods_id');
    }

    public function status()
    {
        return $this->hasOne(CartStatus::class, 'id', 'status');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
