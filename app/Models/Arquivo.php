<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arquivo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_aquivos";

    protected $fillable = [
        'model_id',
        'model_type',
        'certificado',
        'bilheite',
        'atestado',
        'outros',
        'codigo',
        'level',
    ];
}
