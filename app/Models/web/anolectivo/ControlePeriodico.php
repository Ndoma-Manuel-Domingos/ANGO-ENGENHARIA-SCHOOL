<?php

namespace App\Models\web\anolectivo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlePeriodico extends Model
{
    use SoftDeletes;

    protected $table = "tb_controle_periodicos";

    protected $fillable = [
        'trimestre',
        'inicio',
        'final',
        'status',
        'ensino_status',
        'ano_lectivos_id',
        'shcools_id',
    ];
}
