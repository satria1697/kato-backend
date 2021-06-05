<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;
    protected $table = "verification";

    public function id_status()
    {
        return $this->belongsTo(VerificationStatus::class, 'id_card_status');
    }

    public function company_status()
    {
        return $this->belongsTo(VerificationStatus::class, 'company_card_status');
    }
}
