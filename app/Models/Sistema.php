<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sistema extends Model
{
    use SoftDeletes;

    protected $table = 'tb_termos_politicas';

    protected $fillable = [
        'termos',
        'politicas',
        'telefone1',
        'telefone2',
        'telefone3',
        'telefone4',
        'facebook',
        'instagram',
        'twetter',
        'youtube',
        'whatsapp',
    ];

}
