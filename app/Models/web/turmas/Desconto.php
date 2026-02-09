<?php

namespace App\Models\web\turmas;

use App\Models\Shcool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Desconto extends Model
{
    use SoftDeletes;

    protected $table = "tb_descontos";

    protected $fillable = [
        'nome',
        'status',
        'desconto',
        'shcools_id',
    ];

    public function escola()
    {
        return $this->belongsToMany(Shcool::class, 'shcools_id', 'id');
    }
}
