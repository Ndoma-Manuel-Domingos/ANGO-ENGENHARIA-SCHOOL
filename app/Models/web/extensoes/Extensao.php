<?php

namespace App\Models\web\extensoes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Extensao extends Model
{
    use SoftDeletes;

    protected $table = "tb_extensoes";

    protected $fillable = [
        'extensao',
        'sufix',
        'status',
        'tipo',
        'shcools_id',
    ];

}
