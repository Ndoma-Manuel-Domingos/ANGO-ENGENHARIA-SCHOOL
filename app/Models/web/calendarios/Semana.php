<?php

namespace App\Models\web\calendarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semana extends Model
{
    use SoftDeletes;

    protected $table = "tb_semanas";

    protected $fillable = [
        'status',
        'nome',
    ];

}
