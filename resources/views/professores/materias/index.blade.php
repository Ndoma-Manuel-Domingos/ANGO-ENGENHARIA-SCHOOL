@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Minhas Matérias</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('prof.home-profs') }}">Voltar</a></li>
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
          <div class="callout callout-info">
            <h5><i class="fas fa-info"></i> Todas as matérias compartilhadas com alunos de todas as escolas</h5>
          </div>
        </div>
      </div>

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
          <form action="{{ route('portal-professor-minhas-materias.index') }}" method="get">
          @csrf
            <div class="card">
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
                  
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Filtrar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      
      <div class="row mt-3">
        <div class="col-12 col-md-12">
          <div class="card">
            <div class="card-header">
                <a href="{{ route('portal-professor-minhas-materias.create') }}" class="btn btn-primary">Adicionar Nova Matéria</a>
            </div>
            <div class="card-body">
              <table id="carregarTabelaMatricula"
                 style="width: 100%" class="table table-bordered  ">
                <thead>
                  <tr>
                    <th>Escola</th>
                    <th>Turma</th>
                    <th>Disciplina</th>
                    <th>Ano Lectivo</th>
                    <th>Data Limite</th>
                    <th>Titulo</th>
                    <th>Descrição</th>
                    <th>Estado</th>
                    <th>Acções</th>
                  </tr>
                </thead>

                <tbody>
                  @foreach ($materias as $item)
                  <tr>
                    <td> {{ $item->escola->nome }} </td>
                    <td> {{ $item->turma->turma }}</td>
                    <td> {{ $item->disciplina->disciplina }}</td>
                    <td> {{ $item->ano->ano }}</td>
                    <td> {{ $item->data_limite }}</td>
                    <td title="{{ $item->titulo }}"> {{ Str::limit($item->titulo, 30)  }}</td>
                    <td title="{{ $item->descricao }}"> {{ Str::limit($item->descricao, 50) }} </td>
                    <td> {{ $item->status }}</td>
                    <td>
                      <a href="{{ route('portal-professor-minhas-materias.edit', $item->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                      <a href="{{ route('portal-professor-minhas-materias.show', $item->id) }}" class="btn btn-success"><i class="fas fa-eye"></i></a>
                      <a href="{{ route('portal-professor-minhas-materias.destroy', $item->id) }}" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>

              </table>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection

@section('scripts')
    <script>
        $("#escola_id").change(()=>{
          let id = $("#escola_id").val();
          $.get('carregar-ano-lectivos-escolas/'+id, function(data){
              $("#ano_lectivos_id").html("")
              $("#ano_lectivos_id").html(data)
          })
        })
    </script>
    
    <script>
        $("#escola_id").change(()=>{
          let id = $("#escola_id").val();
          let professor_id = $("#professor_id").val();
          $.get('carregar-turmas-professores-escolas/'+id + "/"+professor_id, function(data){
              $("#turmas_id").html("")
              $("#turmas_id").html(data)
          })
        })
    </script>
    
    <script>
        $("#turmas_id").change(()=>{
          let id = $("#turmas_id").val();
          $.get('carregar-disciplinas-turmas-professores-escolas/'+id, function(data){
              $("#disciplinas_id").html("")
              $("#disciplinas_id").html(data)
          })
        })
    </script>
@endsection