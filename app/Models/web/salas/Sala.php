<?php

namespace App\Models\web\salas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sala extends Model
{
    use SoftDeletes;

    protected $table = "tb_salas";

    protected $fillable = [
        'salas',
        'tipo',
        'status',
        'descricao',
        'shcools_id',
    ];
}
