<?php

namespace App\Models;

use App\Models\Data\Cart;
use App\Models\Data\CartStatus;
use App\Models\Data\Verification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verification_code',
        'email_verified_at',
        'level_id',
        'token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cart() {
        return $this->hasMany(Cart::class);
    }

    public function profile() {
        return $this->hasOne(Profile::class);
    }
    public function verification() {
        return $this->hasOne(Verification::class);
    }

    public function level() {
        return $this->hasOne(Level::class, 'id', 'level_id');
    }
}
