@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Adicionar Novas Matérias</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('prof.meus-comunicados') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Matérias</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12 col-md-12">
          @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
          @endif
        </div>
      </div>
      
      <div class="row">
        <div class="col-12 col-md-12">
          <form action="{{ route('portal-professor-minhas-materias.store') }}" method="post" enctype="multipart/form-data" id="formulario_cadastro_professor">
            @csrf
            <div class="card">
                <div class="card-header bg-light">
                    <h5>Criar nova matéria</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                    
                      <div class="form-group col-12 col-md-3">
                        <label for="" class="form-label">Escolas</label>
                        <select type="text" class="form-control select2" placeholder="Escolas" name="escola_id" id="escola_id">
                          <option value="">TODAS</option>
                          @foreach ($escolas as $item)
                            <option value="{{ $item->id }}">{{ $item->nome }}</option>  
                          @endforeach
                        </select>
                      </div>
                      
                      <div class="form-group col-12 col-md-3">
                        <label for="" class="form-label">Turmas</label>
                        <select type="text" class="form-control select2" placeholder="Turmas" name="turmas_id" id="turmas_id">
                          <option value="">TODAS</option>
                          @foreach ($turmas as $item)
                            <option value="{{ $item->idTurma }}">{{ $item->turma }}</option>  
                          @endforeach
                        </select>
                      </div>
                      
                      <div class="form-group col-12 col-md-3">
                        <label for="" class="form-label">Disciplinas</label>
                        <select type="text" class="form-control select2" placeholder="Disciplinas" name="disciplinas_id" id="disciplinas_id">
                          <option value="">TODAS</option>
                          @foreach ($turmas as $item)
                            <option value="{{ $item->idDis }}">{{ $item->disciplina }}</option>  
                          @endforeach
                        </select>
                      </div>
                      
                      <div class="form-group col-12 col-md-3">
                        <label for="" class="form-label">Ano Lectivos</label>
                        <select type="text" class="form-control select2" placeholder="Ano Lectivos" name="ano_lectivos_id" id="ano_lectivos_id">
                          <option value="">TODAS</option>
                          @foreach ($anos_lectivos as $item)
                            <option value="{{ $item->id }}">{{ $item->ano }}</option>  
                          @endforeach
                        </select>
                      </div>
                      
                      
                      <div class="form-group col-12 col-md-3">
                        <label for="" class="form-label">Data Final</label>
                        <input type="date" class="form-control" name="data_limite" placeholder="Informe o titulo da Materia">
                      </div>
                      
                      <div class="form-group col-12 col-md-3">
                        <label for="" class="form-label">Primeiro Documento</label>
                        <input type="file" class="form-control" name="documento1" placeholder="Informe o titulo da Materia">
                      </div>
                      
                      <div class="form-group col-12 col-md-3">
                        <label for="" class="form-label">Segundo Documento</label>
                        <input type="file" class="form-control" name="documento2" placeholder="Informe o titulo da Materia">
                      </div>
                      
                      <div class="form-group col-12 col-md-3">
                        <label for="" class="form-label">Terceiro Documento</label>
                        <input type="file" class="form-control" name="documento3" placeholder="Informe o titulo da Materia">
                      </div>
                      
                      
                      <div class="form-group col-12 col-md-6">
                        <label for="" class="form-label">Titulo</label>
                        <textarea cols="30" rows="5" class="form-control" name="titulo" placeholder="Informe o titulo da Materia"></textarea>
                      </div>
                      
                      
                      <div class="form-group col-12 col-md-6">
                        <label for="" class="form-label">Descrição</label>
                        <textarea cols="30" rows="5" class="form-control" name="descricao" placeholder="Informe o titulo da Materia"></textarea>
                      </div>
                      
                      <input type="hidden" value="{{ $professor->id }}" name="professor_id" id="professor_id" class="professor_id">
                      
                     </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Criar</button>
                </div>
            </div>
          </form>
        </div>
      </div>
      
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>


@endsection

@section('scripts')
    <script>
        $("#escola_id").change(()=>{
          let id = $("#escola_id").val();
          $.get('../carregar-ano-lectivos-escolas/'+id, function(data){
              $("#ano_lectivos_id").html("")
              $("#ano_lectivos_id").html(data)
          })
        })
    </script>
    
    <script>
        $("#escola_id").change(()=>{
          let id = $("#escola_id").val();
          let professor_id = $("#professor_id").val();
          $.get('../carregar-turmas-professores-escolas/'+id + "/"+professor_id, function(data){
              $("#turmas_id").html("")
              $("#turmas_id").html(data)
          })
        })
    </script>
    
    <script>
        $("#turmas_id").change(()=>{
          let id = $("#turmas_id").val();
          $.get('../carregar-disciplinas-turmas-professores-escolas/'+id, function(data){
              $("#disciplinas_id").html("")
              $("#disciplinas_id").html(data)
          })
        })
    </script>
@endsection