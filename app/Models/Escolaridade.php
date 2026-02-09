<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Escolaridade extends Model
{
    use SoftDeletes;

    protected $table = "tb_escolaridades";

    protected $fillable = [
        'nome',
        'status',
        'shcools_id',
    ];
}
