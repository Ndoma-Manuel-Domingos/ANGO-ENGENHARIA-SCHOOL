<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnsinoClasse extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "tb_ensinos_classes";

    protected $fillable = [
        'nome',
        'status',
    ];
    
}
