<?php

namespace App\Models\web\funcionarios;

use App\Models\Distrito;
use App\Models\Municipio;
use App\Models\Paise;
use App\Models\Provincia;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Funcionarios extends Model
{
    use SoftDeletes;

    protected $table = "tb_funcionarios";

    protected $fillable = [
        'nome',
        'sobre_nome',
        'pai',
        'mae',
        'nascimento',
        'genero',
        'estado_civil',
        'bilheite',
        'emissiao_bilheite',
        'pais_id',
        'provincia_id',
        'municipio_id',
        'distrito_id',
        'level',
        'status',
        'telefone',
        'email',
        'instagram',
        'whatsapp',
        'outras_redes',
        'facebook',
        'endereco',
        'codigo',
        'image',
        'shcools_id',
    ];
    
    public function academico()
    {
        return $this->hasOne(FuncionariosAcademico::class, 'funcionarios_id', 'id');
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
    
    public function contrato()
    {
        return $this->hasOne(FuncionariosControto::class, 'funcionarios_id', 'id');
    }
    
    
    public function idade($data)
    {
        $dataAtual = new DateTime();
        $dataNascimento = new DateTime($data);
        $diferenca = $dataNascimento->diff($dataAtual);
        return $diferenca->y;
    }
}
