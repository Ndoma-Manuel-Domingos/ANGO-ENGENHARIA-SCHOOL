<?php

namespace App\Models;

use App\Http\Controllers\TraitHelpers;
use App\Models\web\calendarios\MapaEfectividade;
use App\Models\web\funcionarios\FuncionariosControto;
use App\Models\web\turmas\Horario;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Professor extends Model
{
    use HasFactory, SoftDeletes;
    
    use TraitHelpers;

    protected $table = "tb_professores";

    protected $fillable = [
        'nome',
        'sobre_nome',
        'pai',
        'mae',
        'nascimento',
        'codigo',
        'genero',
        'estado_civil',
        'pais_id',
        'provincia_id',
        'municipio_id',
        'bilheite',
        'emissiao_bilheite',
        'status',
        'telefone',
        'endereco',
        'image',
        'distrito_id',
        'level',
        'email',
        'instagram',
        'whatsapp',
        'outras_redes',
        'facebook',
        'ano_lectivo_global_id',
    ];
    
    public function contrato()
    {
        return $this->hasOne(FuncionariosControto::class, 'funcionarios_id', 'id');
    }
    
    public function mapa_efectividade()
    {
        return $this->hasMany(MapaEfectividade::class, 'funcionarios_id', 'id');
    }


    public function academico()
    {
        return $this->hasOne(ProfessorAcedemico::class, 'professor_id', 'id');
    }

    public function nacionalidade()
    {
        return $this->belongsTo(Paise::class, 'pais_id', 'id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id', 'id');
    }
    

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id', 'id');
    }
    
    
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id');
    }
    

    public function idade($data)
    {
        $dataAtual = new DateTime();
        $dataNascimento = new DateTime($data);
        $diferenca = $dataNascimento->diff($dataAtual);
        return $diferenca->y;
    }

    public function total_escola($id)
    {
        return FuncionariosControto::where('level', '4')->whereIn('funcionarios_id', [$id])->distinct()->orderBy('shcools_id', "desc")->count();
    }
    
    public function total_tempos_professor($professor)
    {
        $total_horarios = Horario::where('shcools_id', $this->escolarLogada())->where('ano_lectivos_id', '=', $this->anolectivoActivo())->where('professor_id', $professor)->count();
    
        return $total_horarios;
    }

}
