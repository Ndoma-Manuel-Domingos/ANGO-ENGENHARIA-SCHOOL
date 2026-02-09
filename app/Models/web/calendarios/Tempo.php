<?php

namespace App\Models\web\calendarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tempo extends Model
{
    use SoftDeletes;

    protected $table = "tb_tempos";

    protected $fillable = [
        'status',
        'nome',
    ];

}
