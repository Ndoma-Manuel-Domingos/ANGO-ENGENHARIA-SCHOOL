<?php

namespace App\Models;

use App\Models\web\classes\Classe;
use App\Models\web\cursos\Curso;
use App\Models\web\disciplinas\Disciplina;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitacaoProfessor extends Model
{
    use HasFactory, SoftDeletes;
            
    protected $table = "tb_solicitacao_professores";

    protected $fillable = [
        'professor_id',
        'classes_id',
        'cursos_id',
        'disciplinas_id',
        'instituicao_id',
        'solicitacao',
        'processo',
        'level_origem',
        'level_destino',
        'resposta_descricao',
        'resposta_opcao',
        'resposta_user_id',
        'resposta_escola',
        'resposta_instituicao_id',
        'escola_destino_level',
        'escola_transferencia_id',
        'level_respondido',
        'level_destino',
        'documento_pdf',
        'efeito',
        'descricao',
        'status',
        'links'
    ];
    
    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'resposta_user_id', 'id');
    }
    
    public function instituicao1()
    {
        return $this->belongsTo(Shcool::class, 'instituicao_id', 'id');
    }
        
    public function instituicao_resposta()
    {
        return $this->belongsTo(Shcool::class, 'resposta_instituicao_id', 'id');
    }
        
    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplinas_id', 'id');
    }
       
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'cursos_id', 'id');
    }
    
       
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classes_id', 'id');
    }
    
    
    public function instituicao_destino($level, $instituicao_id)
    {
    
        if($level == '2'){
            $data = DireccaoProvincia::find($instituicao_id); 
        }else{
            if($level == '3'){
                $data = DireccaoMunicipal::find($instituicao_id);
            }
            else{
                if($level == '4'){
                    $data = Shcool::find($instituicao_id); 
                }
            }
        }
        
        return $data->nome;
    }
    
    public function aprovado($level, $instituicao_id)
    {
    
        if($level == '2'){
            $data = DireccaoProvincia::find($instituicao_id); 
        }else{
            if($level == '3'){
                $data = DireccaoMunicipal::find($instituicao_id);
            }
            else{
                if($level == '4'){
                    $data = Shcool::find($instituicao_id); 
                }
            }
        }
        
        return $data->nome;
    }
}
