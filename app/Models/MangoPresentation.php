<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MangoPresentation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "mango_presentations";

    protected $fillable = [
        'name',
        'current_price'
    ];

    protected $appends = ["price"];

    public function getPriceAttribute(){
        return '$'.number_format($this->current_price,2);
    }

    public function unitPresentationPrice(){
        return $this->belongsToMany(UnitOfMeasurement::class,'unit_presentation_price')->withPivot('current_price','unit_of_measurement_id');
    }
}
