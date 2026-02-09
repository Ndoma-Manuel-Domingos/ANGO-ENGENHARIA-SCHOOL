<?php

namespace App\Models;

use App\Models\web\funcionarios\FuncionariosControto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Efeito extends Model
{
    use SoftDeletes;

    protected $table = "tb_efeitos";

    protected $fillable = [
        'nome',
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'id');
    }
    
    public function total_funcionarios_cargo_activo($level, $cargo, $instituicao = "")
    {
        return FuncionariosControto::where('shcools_id', $instituicao)->where('level', $level)->where('status','activo')->where('cargo_id', $cargo)->count();
    }
    
    public function total_funcionarios_cargo_desactivo($level, $cargo, $instituicao = "")
    {
        return FuncionariosControto::where('shcools_id', $instituicao)->where('level', $level)->where('status','desactivo')->where('cargo_id', $cargo)->count();
    }
}
