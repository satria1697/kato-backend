<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    use HasFactory;
    protected $table="goods";

    public function category() {
        return $this->hasOne(Categories::class, 'id', 'category_id');
    }
}
