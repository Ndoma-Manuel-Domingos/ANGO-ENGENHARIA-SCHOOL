<?php

namespace App\Models\web\calendarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mes extends Model
{
    use SoftDeletes;

    protected $table = "tb_meses";

    protected $fillable = [
        'meses',
        'abreviacao',
        'abreviacao2',
    ];
}

