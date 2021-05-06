<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationStatus extends Model
{
    use HasFactory;
    protected $table="verification_status";

    public function verfication() {
        return $this->hasMany(VerificationStatus::class, 'id_card_status', 'company_card_status');
    }
}
