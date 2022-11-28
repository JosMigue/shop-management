<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'sum_transfers',
        'sum_deposits',
        'sum_payments'
    ];

    public function isNotAdmin(){
        return $this->role_id == 4 ? true : false;
    }

    public function isRoot(){
        return $this->role_id == Role::ROOT ? true : false;
    }

    public function isLeader(){
        return $this->role_id == Role::BOSS ? true : false;
    }

    public function getSumTransfersAttribute(){
        return $this->transfers()->sum('amount');
    }

    public function getSumDepositsAttribute(){
        return $this->deposits()->sum('amount');
    }

    public function getSumPaymentsAttribute(){
        return $this->payments()->sum('amount');
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->timezone('America/Mexico_City')->format('d/m/Y h:i:s a');
    }
    
    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function sales(){
        return $this->hasMany(Sale::class);
    }

    public function expenses(){
        return $this->hasMany(Expense::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class, 'receiver_id');
    }

    public function deposits()
    {
        return $this->hasMany(Transfer::class, 'sender_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
