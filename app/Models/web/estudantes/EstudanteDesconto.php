<?php

namespace App\Models\web\estudantes;

use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivoGlobal;
use App\Models\Shcool;
use App\Models\web\turmas\Desconto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstudanteDesconto extends Model
{
    use HasFactory, SoftDeletes;
    use TraitHelpers;

    protected $table = "tb_estudantes_descontos";

    protected $fillable = [
        'status',
        'desconto_id',
        'ano_lectivos_id',
        'estudante_id',
        'shcools_id',
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudante_id', 'id');
    }

    public function desconto()
    {
        return $this->belongsTo(Desconto::class, 'desconto_id', 'id');
    }

    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    

    public function ano()
    {
        return $this->belongsTo(AnoLectivoGlobal::class, 'ano_lectivos_id');
    }

}
