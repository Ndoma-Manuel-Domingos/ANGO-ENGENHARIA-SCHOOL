<?php

namespace App\Models\web\calendarios;

use App\Models\Motivo;
use App\Models\Web\calendarios\TaxaSaft;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servico extends Model
{
    use SoftDeletes;

    protected $table = "tb_servicos";

    protected $fillable = [
        'servico',
        'status',
        'unidade',
        'motivo_id',
        'taxa_id',
        'tipo',
        'conta',
        'ordem',
        'contas',
        'shcools_id',
    ];

    public function taxa()
    {
       return $this->belongsTo(TaxaSaft::class, 'taxa_id', 'id');
    }

    public function motivo()
    {
       return $this->belongsTo(Motivo::class, 'motivo_id', 'id');
    }
}
