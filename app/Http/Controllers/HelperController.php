<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\DireccaoMunicipal;
use App\Models\DireccaoProvincia;
use App\Models\Distrito;
use App\Models\Instituicao;
use App\Models\Municipio;
use App\Models\Paise;
use App\Models\Provincia;
use App\Models\Shcool;
use App\Models\web\anolectivo\AnoLectivo;
use App\Models\web\classes\AnoLectivoClasse;
use App\Models\web\cursos\AnoLectivoCurso;
use App\Models\web\turmas\BolsaInstituicao;
use App\Models\web\turmas\DisciplinaTurma;
use App\Models\web\turmas\FuncionariosTurma;
use App\Models\web\turmas\InstituicaoEducacional;
use App\Models\web\turmas\Turma;
use App\Models\web\turnos\AnoLectivoTurno;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    //

    use TraitHelpers;
    
    public function getBolsasInstituicao($id)
    {
        $instituicao = InstituicaoEducacional::findOrFail($id);
        $bolsas = BolsaInstituicao::with(['bolsa'])->where('instituicao_id', $instituicao->id)->get();
        
        $option = "<option value=''>Selecione a Bolsa</option>";
        foreach($bolsas as $bolsa){
            $option .= '<option value="'.$bolsa->bolsa->id.'">'.$bolsa->bolsa->nome.'<option>';
        }
        return $option;
    }
    
    
    public function getEnderecoEstrageiro($id)
    {
        $paises = Paise::findOrFail($id);
        $provincias = Provincia::get();
        $provincia = Provincia::where('abreviacao', 'NHU')->first();
        $municipios = Municipio::where('provincia_id', $provincia->id)->get();
        $distritos = Distrito::where('nome', 'Nenhum')->get();
        
        $pros = "";
        // $pros = "<option value=''>Selecione a Munícipios</option>";
        $pros .= '<option value="'.$provincia->id.'">'.$provincia->nome.'<option>';
        
        $provin = "";
        // $provin = "<option value=''>Selecione a Munícipios</option>";
        foreach($provincias as $state){
            $provin .= '<option value="'.$state->id.'">'.$state->nome.'<option>';
        }
        
        $munis = "";
        // $munis = "<option value=''>Selecione a Munícipios</option>";
        foreach($municipios as $state){
            $munis .= '<option value="'.$state->id.'">'.$state->nome.'<option>';
        }
        
        $dists = "";
        // $dists = "<option value=''>Selecione a Munícipios</option>";
        foreach($distritos as $state){
            $dists .= '<option value="'.$state->id.'">'.$state->nome.'<option>';
        }
        
        $datas =  [
            'provincias' => $pros,
            'municipios' => $munis,
            'distritos' => $dists,
            'provinciass' => $provin,
        ];
        
        return $datas;
    }
    
    public function getMunicipio($id)
    {
        $states = Provincia::findOrFail($id);
        $municipios = Municipio::where('provincia_id', $states->id)->get();
        
        $option = "<option value=''>Selecione a Munícipios</option>";
        foreach($municipios as $state){
            $option .= '<option value="'.$state->id.'">'.$state->nome.'<option>';
        }
        return $option;
    }
    
    public function getEscolaDistritos($id)
    {
        $escolas = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('distrito_id', $id)->get();
        
        $option = "<option value=''>Selecione a Escolas</option>";
        foreach($escolas as $escola){
            $option .= '<option value="'.$escola->id.'">'.$escola->nome.'<option>';
        }
        return $option;
    }
    
    public function getEscolaMunicipio($id)
    {
        $escolas = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('municipio_id', $id)->get();
        
        $option = "<option value=''>Selecione a Escolas</option>";
        foreach($escolas as $escola){
            $option .= '<option value="'.$escola->id.'">'.$escola->nome.'<option>';
        }
        return $option;
    }
    
    
    
    public function getDistritos($id)
    {
        $municipio = Municipio::findOrFail($id);
        $distritos = Distrito::where('municipio_id', $municipio->id)->get();
        
        $option = "<option value=''>Selecione Distritos</option>";
        foreach($distritos as $distrito){
            $option .= '<option value="'.$distrito->id.'">'.$distrito->nome.'<option>';
        }
        return $option;
    }    
    
    
    public function getAnoLectivoEscola($id)
    {
        $anos = AnoLectivo::where('shcools_id', $id)->where('status', 'activo')->get();
        
        $option = "<option value=''>Selecione a Ano Lectivo</option>";
        foreach($anos as $item){
            $option .= '<option value="'.$item->id.'">'.$item->ano.'<option>';
        }
        return $option;
    } 
    
    public function getTurmasProfessorEscola($id, $prof = "")
    {
        
        $turmas = FuncionariosTurma::where('shcools_id', $id)
        ->when($prof, function($query, $value){
            $query->where('funcionarios_id', $value);
        })
        ->join('tb_turmas', 'tb_turmas_funcionarios.turmas_id', '=', 'tb_turmas.id')
        ->select('tb_turmas.turma', 'tb_turmas.id')
        ->get();
        
        $option = "<option value=''>Selecione a Turmas</option>";
        foreach($turmas as $item){
            $option .= '<option value="'.$item->id.'">'.$item->turma.'<option>';
        }
        return $option;
    } 
    
    public function getDisciplinaTurmasProfessorEscola($id)
    {
        $disciplinas = DisciplinaTurma::with('disciplina')->where('turmas_id', $id)->get();
        
        $option = "<option value=''>Selecione a Disciplina</option>";
        foreach($disciplinas as $item){
            $option .= '<option value="'.$item->disciplina->id.'">'.$item->disciplina->disciplina.'<option>';
        }
        return $option;
    } 

    public function getStates($country_id)
    {
        $states = Provincia::where('country_id', $country_id)->get();
        $option = "<option value=''>Selecione a Província</option>";
        foreach($states as $state){
            $option .= '<option value="'.$state->id.'">'.$state->name.'<option>';
        }
        return $option;
    }

    public function getStatesName($provincia_id)
    {
        $states = Provincia::where('id', $provincia_id)->first();
        return $states->name;
    }
    
    public function getAnoLectivoDados($id)
    {
        $ano_lectivo = AnoLectivo::findOrFail($id);

        $classes = AnoLectivoClasse::where('ano_lectivos_id', '=', $ano_lectivo->id)
            ->with(['classe'])
        ->get();

        $turnos = AnoLectivoTurno::where('ano_lectivos_id', '=', $ano_lectivo->id)
        ->with(['turno'])
        ->get();

        $cursos = AnoLectivoCurso::where('ano_lectivos_id', '=', $ano_lectivo->id)
        ->with(['curso'])
        ->get();

        return response()->json(
            [
                'cursos' => $cursos,
                'classes' => $classes,
                'turnos' => $turnos,
        ], 200);
    }
    

    public function getEscolaDados($id)
    {
        $escola = Shcool::findOrFail($id);

        $classes = AnoLectivoClasse::where([
            ['ano_lectivos_id', '=', $this->anoLectivoActivoEscola($escola->id)],
            ['shcools_id', '=', $escola->id],
        ])
        ->with('classe')
        ->get();

        $turnos = AnoLectivoTurno::where([
            ['ano_lectivos_id', '=', $this->anoLectivoActivoEscola($escola->id)],
            ['shcools_id', '=', $escola->id],
        ])
        ->with('turno')
        ->get();

        $cursos = AnoLectivoCurso::where([
            ['ano_lectivos_id', '=', $this->anoLectivoActivoEscola($escola->id)],
            ['shcools_id', '=', $escola->id],
        ])
        ->with('curso')
        ->get();

        $anos = AnoLectivo::where('shcools_id', $escola->id)->get();
        return response()->json(
            [
                'cursos' => $cursos,
                'classes' => $classes,
                'turnos' => $turnos,
                'anos' => $anos,
            ], 200);
    }
    
    public function getCargoDepartamento($id)
    {
        $cargos = Cargo::where('departamento_id', $id)->get();
        $option = "<option value=''>Selecione Cargos</option>";
        foreach($cargos as $cargo){
            $option .= '<option value="'.$cargo->id.'">'.$cargo->cargo.'<option>';
        }
        return $option;
    }
    
    
    public function getDestinoFuncionario($string)
    {
        $instituicao = Instituicao::findOrFail($string);
    
        if($instituicao->nome == "MINISTERIO"){
            $option = "";
            $option .= '<option value="NULL">MINISTÉRIO<option>';
            return $option;
        }
        
        if($instituicao->nome == "PROVINCIAS"){
            $provinciais = DireccaoProvincia::where('status', 'activo')->get();
            $option = "<option value=''>Selecione Direcções Provínciais</option>";
            foreach($provinciais as $provincia){
                $option .= '<option value="'.$provincia->id.'">'.$provincia->nome.'<option>';
            }
            return $option;
        }
        
        if($instituicao->nome == "MUNICIPAIS"){
            $municipais = DireccaoMunicipal::where('status', 'activo')->get();
            $option = "<option value=''>Selecione Direcções Municípais</option>";
            foreach($municipais as $municipio){
                $option .= '<option value="'.$municipio->id.'">'.$municipio->nome.'<option>';
            }
            return $option;
        }
        
        if($instituicao->nome == "ESCOLAS"){
            $escolas = Shcool::with(['ensino', 'distrito', 'pais', 'provincia', 'municipio'])->where('status', 'activo')->get();
            $option = "<option value=''>Selecione a Escola</option>";
            foreach($escolas as $escola){
                $option .= '<option value="'.$escola->id.'">'.$escola->nome.'<option>';
            }
            return $option;
        }
    }
    
    
    public function carregarDisciplinaTurma($id)
    {
        $turmaId = Turma::findOrFail($id);

        $disciplinasTurma = DisciplinaTurma::where('turmas_id', '=', $turmaId->id)->with('disciplina')->get();
        $option = "<option value=''>Selecione Disciplinas</option>";
        foreach($disciplinasTurma as $disciplinas){
            $option .= '<option value="'.$disciplinas->disciplina->id.'">'.$disciplinas->disciplina->disciplina.'<option>';
        }
        return $option;

    }  
    
    
    public function carregarTodosAnoLectivosEscola($id)
    {
        $escola = Shcool::findOrFail($id);
        $anos = AnoLectivo::where('shcools_id', $escola->id)->get();

        $option = "<option value=''>Selecione Ano Lectivo</option>";
        foreach($anos as $ano){
            $option .= '<option value="'.$ano->id.'">'.$ano->ano.'<option>';
        }
        return $option;

    } 
    
    
    public function carregarTodasTurmasAnoLectivosEscola($id)
    {
        $ano_lectivo = AnoLectivo::findOrFail($id);
        $turmas = Turma::where('ano_lectivos_id', $ano_lectivo->id)->get();

        $option = "<option value=''>Selecione Turmas</option>";
        foreach($turmas as $turma){
            $option .= '<option value="'.$turma->id.'">'.$turma->turma.'<option>';
        }
        return $option;

    } 
    
    
    public function route_back_all_page($route = null)
    {
        if($route){
            return redirect()->route($route);
        }
        return back()->back();
    }
    
}
