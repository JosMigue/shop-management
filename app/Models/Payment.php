<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'payment_type',
        'sale_id',
        'user_id',
        'amount',
        'notes'
    ];

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->timezone('America/Mexico_City')->format('d/m/Y h:i:s a');
    }

    public function getPaymentTypeAttribute($value){
        return $value == 1 ? 'Efectivo' : 'Electrónico';
    }

    public function sale(){
        return $this->belonsTo(Sale::class);
    }
}
