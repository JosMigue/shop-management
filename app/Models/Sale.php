<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Enums\ShippingTypes;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'user_id',
        'customer_id',
        'code',
        'status',
        'freight_amount',
        'payment_method',
        'total'
    ];

    protected $dates = ['created_at'];

    protected $appends = ['payments_sum','current_status'];

    public function getPaymentMethodAttribute($value){
        return $value == 1 ? 'Contado' : 'Crédito';
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->timezone('America/Mexico_City')->format('d/m/Y h:i:s a');
    }
    
    public function getCurrentStatusAttribute(){
        return ShippingTypes::getDescription($this->status);
    }

    public function getPaymentsSumAttribute(){
        return $this->payments()->sum('amount');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function products(){
        return $this->belongsToMany(MangoPresentation::class, 'sale_details')->withPivot(['quantity', 'price', 'unit_of_measurement_id']);
    }

    public function payments(){
        return $this->hasMany(Payment::class)->latest();
    }
}
