<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motivo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_motivos";

    protected $fillable = [
        'codigo',
        'descricao',
    ];
}
