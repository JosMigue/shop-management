<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasurement extends Model
{
    use HasFactory;

    protected $table = "unit_of_measurements";

    protected $fillable = [
        'name'
    ];
}
