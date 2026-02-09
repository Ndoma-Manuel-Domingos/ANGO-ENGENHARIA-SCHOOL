<?php

namespace App\Models\web\disciplinas;

use App\Models\Shcool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculdade extends Model
{
    use SoftDeletes;

    protected $table = "tb_faculdades";

    protected $fillable = [
        'nome',
        'abreviacao',
        'code',
        'descricao',
        'shcools_id',
    ];


    public function escola()
    {
        return $this->hasMany(Shcool::class, 'shcools_id', 'id');
    }
}
