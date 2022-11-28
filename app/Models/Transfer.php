<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illminate\Support\Facades\Auth;
use Carbon\Carbon;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_id',
        'sender_id',
        'amount',
        'observation'
    ];

    protected $table = 'transfers';

    protected $appends = ['date'];

    public function getDateAttribute()
    {
        return Carbon::parse($this->created_at)->timezone('America/Mexico_City')->format('d/m/Y h:i:s a');
    }

    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id')->withTrashed();
    }

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id')->withTrashed();
    }
}
