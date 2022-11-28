<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'amount',
        'concept',
        'user_id'
    ];

    protected $table = 'expenses';

    protected $append = ['created_at'];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('America/Mexico_City')->format('d/m/Y h:i:s a');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
