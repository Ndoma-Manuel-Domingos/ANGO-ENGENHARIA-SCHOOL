<?php

namespace App\Models\web\estudantes;

use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivoGlobal;
use App\Models\Distrito;
use App\Models\Municipio;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\web\anolectivo\EscolaFilhar;
use App\Models\web\calendarios\Matricula;
use App\Models\web\calendarios\ServicoTurma;
use App\Models\web\turmas\Bolseiro;
use App\Models\web\turmas\EstudantesTurma;
use App\Models\web\turmas\NotaPauta;
use App\Models\web\turmas\Turma;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Estudante extends Model
{
    use HasFactory, SoftDeletes;
    use TraitHelpers;

    protected $table = "tb_estudantes";

    protected $fillable = [
        'documento',
        'numero_processo',
        'nome',
        'sobre_nome',
        'nome_completo',
        'shcools_filhar_id',
        'nascimento',
        'data_emissao',
        'genero',
        'estado_civil',
        'nacionalidade',
        'bilheite',
        'data_emissao_documento',
        'status',
        'dificiencia',
        'provincia_id',
        'municipio_id',
        'distrito_id',
        'pais_id',
        'naturalidade',
        'pai',
        'mae',
        'telefone_estudante',
        'telefone_pai',
        'telefone_mae',
        'endereco',
        'image',
        'whatsapp',
        'instagram',
        'facebook',
        'email',
        'registro',
        'finalista',
        'ano_lectivos_id',
        'ano_lectivos_final_id',
        'saldo',
        'saldo_anterior',
        'shcools_id',
        'conta_corrente',
        'ano_lectivo_global_id',
    ];
    
    public function qr($estudante)
    {
        $url = Crypt::encrypt($estudante); //route('shcools.mais-informacao-estudante', $estudante->id); // URL para abrir os detalhes do estudante
        $qrCode = QrCode::size(200)->generate($url);
        
        return $qrCode;
    }
    
    public function escola_fihar()
    {
        return $this->belongsTo(EscolaFilhar::class, 'shcools_filhar_id', 'id');
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

    // Método para buscar notas específicas por disciplina, trimestre e ano letivo
    public function getNotasPorTurmaDisciplinaTrimestreAno($turmaId, $disciplinaId, $trimestreId, $anoLectivoId)
    {
        return $this->notas()
            ->where("turmas_id", $turmaId)
            ->where("disciplinas_id", $disciplinaId)
            ->where("controlo_trimestres_id", $trimestreId)
            ->where("ano_lectivos_id", $anoLectivoId)
            ->first();
    }
    
    // Método para buscar notas específicas por disciplina, trimestre e ano letivo
    public function getAllNotasPorTurmaDisciplinaTrimestreAno($turmaId, $disciplinaId, $trimestreId, $anoLectivoId)
    {
        return $this->notas()
            ->where("turmas_id", $turmaId)
            ->where("disciplinas_id", $disciplinaId)
            ->where("controlo_trimestres_id", $trimestreId)
            ->where("ano_lectivos_id", $anoLectivoId)
            ->get();
    }
    
    public function notas()
    {
        return $this->hasMany(NotaPauta::class, "estudantes_id", "id");
    }
    
    public function ano()
    {
        return $this->belongsTo(AnoLectivoGlobal::class, 'ano_lectivo_global_id');
    }
    
    public function turma()
    {
        return $this->hasOne(EstudantesTurma::class, 'estudantes_id', 'id')->where('ano_lectivos_id', $this->anolectivoActivo());
    }
    
    public function matricula_activa()
    {
        return $this->hasOne(Matricula::class, 'estudantes_id', 'id')->where('ano_lectivos_id', $this->anolectivoActivo());
    }
    
    public function matricula()
    {
        return $this->hasOne(Matricula::class, 'estudantes_id', 'id');
    }

    public function escola()
    {
        return $this->belongsTo(Shcool::class, 'shcools_id', 'id');
    }
    
    public function valor_propinas($classe, $curso, $ano)
    {
        $turma = Turma::where('classes_id', $classe)->where('cursos_id', $curso)->where('ano_lectivos_id', $ano)->first();
        
        if($turma){
            return $turma->valor_propina;
        }else {
            return 0;
        }
    
        return 0;
    }
    
    public function valor_do_servico($servico_id, $turmas_id, $ano_lectivo)
    {
        $turma = ServicoTurma::where('servicos_id', $servico_id)
            ->where('ano_lectivos_id', $ano_lectivo ?? $this->anolectivoActivo())
            ->where('turmas_id', $turmas_id)
            ->where('shcools_id', $this->escolarLogada())
            ->first();
        
        if($turma){
            return $turma->preco;
        }else {
            return 0;
        }
    
        return 0;
    }
    
    public function cartao_estudante($id, $ano_lectivo, $servico_id = "")
    {
        $total_multa = CartaoEstudante::where([
            ['estudantes_id', '=', $id],
            ['servicos_id', '=', $servico_id],
            ['ano_lectivos_id', '=', $ano_lectivo],
        ])->sum('multa');
        
        $mesesPago = CartaoEstudante::where([
            ['estudantes_id', '=', $id],
            ['servicos_id', '=', $servico_id],
            ['ano_lectivos_id', '=', $ano_lectivo],
            ['status', '=', 'Pago'],
        ])->count();
        
        $mesesExcepto = CartaoEstudante::where([
            ['estudantes_id', '=', $id],
            ['servicos_id', '=', $servico_id],
            ['ano_lectivos_id', '=', $ano_lectivo],
            ['status', '=', 'excepto'],
        ])->count();
        
        $mesesNaoPago = CartaoEstudante::where([
            ['estudantes_id', '=', $id],
            ['servicos_id', '=', $servico_id],
            ['ano_lectivos_id', '=', $ano_lectivo],
            ['status', '=', 'Nao Pago'],
        ])->count();
        
        $mesesDividas = CartaoEstudante::where([
            ['estudantes_id', '=', $id],
            ['servicos_id', '=', $servico_id],
            ['ano_lectivos_id', '=', $ano_lectivo],
            ['status', '=', 'divida'],
        ])->count();

        return [
            "total_multa" => $total_multa,
            "mesesPago" => $mesesPago,
            "mesesExcepto" => $mesesExcepto,
            "mesesNaoPago" => $mesesNaoPago,
            "mesesDividas" => $mesesDividas,
        ];
    }

    public function idade($data)
    {
        $dataAtual = new DateTime();
        $dataNascimento = new DateTime($data);
        $diferenca = $dataNascimento->diff($dataAtual);
        return $diferenca->y;
    }
    
    public function bolseiro($id, $ano = null)
    {
        $estudante = Estudante::with('escola.ensino')->findOrFail($id);
        
        if($ano == null){
            $ano = $this->anolectivoActivo();
        }else {
            $ano = $ano;
        }
        
        $bolseiro = Bolseiro::with(['instituicao','bolsa', 'instituicao_bolsa', 'ano', 'periodo', 'estudante', 'escola'])
            ->where('ano_lectivos_id', $ano)
            ->where('status', 'activo')
            ->where('estudante_id', $estudante->id)
        ->first();
      
        return $bolseiro;
    }

    public function desconto($id, $ano = null)
    {
        if($ano == null){
            $ano = $this->anolectivoActivo();
        }
    
        $desconto = EstudanteDesconto::with(['desconto'])->where('estudante_id', $id)->where('ano_lectivos_id', $ano)->where('shcools_id', $this->escolarLogada())->first();
        
        if($desconto){
            return $desconto;
        }
        
        return false;
    }
    
    public function sigla_genero($genero)
    {
        if($genero == "Masculino"){
            return "M";
        }
        if($genero == "Femenino"){
            return "F";
        }
        if($genero == "F"){
            return "Femenino";
        }
        if($genero == "M"){
            return "Masculino";
        }
    }
    
    public function descricao_mes($string)
    {
        if($string == "Nov"){
            return "Novembro";
        }
        if($string == "Dec"){
            return "Dezembro";
        }
        if($string == "Jan"){
            return "Janeiro";
        }
        if($string == "Feb"){
            return "Fevereiro";
        }
        if($string == "Mar"){
            return "Março";
        }
        if($string == "Apr"){
            return "Abril";
        }
        if($string == "May"){
            return "Maio";
        }
        if($string == "Jun"){
            return "Junho";
        }
        if($string == "Jul"){
            return "Julho";
        }
        if($string == "Aug"){
            return "Agosto";
        }
        if($string == "Sep"){
            return "Setembro";
        }
        if($string == "Oct"){
            return "Outumbro";
        }
    }

    function valor_por_extenso( $v )
    {
		
        $v = filter_var($v, FILTER_SANITIZE_NUMBER_INT);
       
        $sin = array("Centavo", "Zeal", "Mil", "Milhão", "Bilhão", "Trilhão", "Quatrilhão");
        $plu = array("Centavos", "", "Mil", "Milhões", "Bilhões", "Trilhões","Quatrilhões");

        $c = array("", "Cem", "duzentos", "Trezentos", "Quatrocentos","Quinhentos", "Seiscentos", "Setecentos", "Oitocentos", "Novecentos");
        $d = array("", "Dez", "Vinte", "Trinta", "Quarenta", "Cinquenta","Sessenta", "Setenta", "Oitenta", "Noventa");
        $d10 = array("Dez", "Onze", "Doze", "Treze", "Quatorze", "Quinze","Dezesseis", "Dezesete", "Dezoito", "Dezenove");
        $u = array("", "Um", "Dois", "Três", "Quatro", "Cinco", "Seis","Sete", "Oito", "Nove");

        $z = 0;
    
        $v = number_format( $v, 2, ".", "." );
        $int = explode( ".", $v );
    
        for ( $i = 0; $i < count( $int ); $i++ ) 
        {
            for ( $ii = mb_strlen( $int[$i] ); $ii < 3; $ii++ ) 
            {
                $int[$i] = "0" . $int[$i];
            }
        }
    
        $rt = null;
        $fim = count( $int ) - ($int[count( $int ) - 1] > 0 ? 1 : 2);
        for ( $i = 0; $i < count( $int ); $i++ )
        {
            $v = $int[$i];
            $rc = (($v > 100) && ($v < 200)) ? "Cento" : $c[$v[0]];
            $rd = ($v[1] < 2) ? "" : $d[$v[1]];
            $ru = ($v > 0) ? (($v[1] == 1) ? $d10[$v[2]] : $u[$v[2]]) : "";
    
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count( $int ) - 1 - $i;
            $r .= $r ? " " . ($v > 1 ? $plu[$t] : $sin[$t]) : "";
            if ( $v == "000")
                $z++;
            elseif ( $z > 0 )
                $z--;
                
            if ( ($t == 1) && ($z > 0) && ($int[0] > 0) )
                $r .= ( ($z > 1) ? " de " : "") . $plu[$t];
                
            if ( $r )
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($int[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }
     
        $rt = mb_substr( $rt, 1 );
    
        return($rt ? trim( $rt ) : "Zero");
     
    }

}
