<?php

namespace App\Models\web\anolectivo;

use App\Models\Distrito;
use App\Models\Ensino;
use App\Models\Municipio;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\Shcool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EscolaFilhar extends Model
{
    use SoftDeletes;

    protected $table = "tb_escolas_filhares";

    protected $fillable = [
        'nome',
        'status',
        'director',
        'sector',
        'ensino_id',
        'pais_id',
        'provincia_id',
        'municipio_id',
        'distrito_id',
        'endereco',
        'telefone1',
        'telefone2',
        'logotipo',
        'shcools_id',
    ];

    public function escola()
    {
        return $this->hasOne(Shcool::class, 'id', 'shcools_id');
    }
    public function ensino()
    {
        return $this->belongsTo(Ensino::class, 'ensino_id', 'id');
    }
    
    public function distrito()
    {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id');
    }

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
}
