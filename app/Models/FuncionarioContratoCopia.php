<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuncionarioContratoCopia extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $connection = "mysql2";
    protected $table = "tb_contratos_copy";

    protected $fillable = [
        'status',
        'documento',
        'salario',
        'subcidio',
        'subcidio_alimentacao',
        'subcidio_transporte',
        'subcidio_ferias',
        'subcidio_natal',
        'subcidio_abono_familiar',
        'falta_por_dia',
        'data_inicio_contrato',
        'data_final_contrato',
        'hora_entrada_contrato',
        'hora_saida_contrato',
        'cargo',
        'cargo_geral',
        'numero_identifcador',
        'conta_bancaria',
        'status_contrato',
        'iban',
        'clausula',
        'level',
        'pais_id',
        'provincia_id',
        'municipio_id',
        'distrito_id',
        'nif',
        'data_at',
        'funcionarios_id',
        'ano_lectivos_id',
        'departamento_id',
        'cargo_id',
        'shcools_id',
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
    
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id', 'id');
    }
    
    public function cargos()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id', 'id');
    }

    public function funcionario()
    {
        return $this->belongsTo(Professor::class, 'funcionarios_id', 'id');
    }
    
    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
}
