<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DireccaoMunicipal extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'tb_direccoes_municipais';

    protected $fillable = [
        'nome',
        'cabecalho1',
        'director',
        'documento',
        'site',
        'sigla',
        'status',
        'pais_id',
        'provincia_id',
        'municipio_id',
        'distrito_id',
        'endereco',
        
        'agua',
        'electricidade',
        'cantina',
        'farmacia',
        'biblioteca',
        'campo_desportivo',
        'internet',
        'zip',
        'computadores',
        'laboratorio',
        'casas_banho',
        'transporte',
        
        
        'decreto',
        'telefone1',
        'telefone2',
        'logotipo',
        'logotipo_assinatura_director',
        'ano_lectivo_global_id',
    ];
    
    public function pais()
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
}
