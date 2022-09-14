<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subadminsociety extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'superadminid',
        'subadminid',
        'societyid',
        
        

    ];
}
