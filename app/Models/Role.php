<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const ROOT = 1;
    const BOSS = 2;
    const ADMIN = 3;
    const SUBADMIN = 4;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'description'
    ];
}
