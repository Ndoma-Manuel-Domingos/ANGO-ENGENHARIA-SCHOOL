<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instituicao extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "tb_instituicoes";

    protected $fillable = [
        'nome',
        'status',
    ];
}
