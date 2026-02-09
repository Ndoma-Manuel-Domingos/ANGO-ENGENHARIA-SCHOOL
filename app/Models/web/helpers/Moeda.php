<?php

namespace App\Models\web\helpers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Moeda extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_moedas";

    protected $fillable = [
        'designacao',
        'codigo',
        'cambio',
    ];

}
