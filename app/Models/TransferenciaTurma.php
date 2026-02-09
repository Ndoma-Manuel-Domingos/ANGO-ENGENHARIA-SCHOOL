<?php

namespace App\Models;

use App\Models\web\estudantes\Estudante;
use App\Models\web\turmas\Turma;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferenciaTurma extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tb_transferencias_turmas";

    protected $fillable = [
        'estudantes_id',
        'org_turmas_id',
        'des_turmas_id',
        'status',
        'documento',
        'motivo',
        'user_id',
        'shcools_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudantes_id', 'id');
    }

    public function origem()
    {
        return $this->belongsTo(Turma::class, 'org_turmas_id', 'id');
    }

    public function destino()
    {
        return $this->belongsTo(Turma::class, 'des_turmas_id', 'id');
    }
}
