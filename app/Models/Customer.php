<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\Payment;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'observation'
    ];

    protected $appends = ['debts','sum_sales','sum_payments'];

    public function getSumSalesAttribute($value)
    {
        return $this->sales()->sum('total');
    }

    public function getSumPaymentsAttribute($value)
    {
        return Payment::whereIn('sale_id',$this->sales()->get()->pluck('id'))->sum('amount');
    }

    public function getCreatedAtAttribute($value){
        
        return Carbon::parse($value)->timezone('America/Mexico_City')->format('d/m/Y h:i:s a');
    }

    public function getDebtsAttribute()
    {
        return $this->sales()->sum('total');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class)->latest();
    }


}
