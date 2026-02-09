@extends('layouts.estudantes')

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
              <li class="breadcrumb-item"><a href="{{ route("est.home-estudante") }}">Voltar</a></li>
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
            <div class="card">
              <div class="card-body">
                <form action="{{ route('est.minhas-materias-estudante') }}" method="get" id="formulario">
                  @csrf
                  <div class="row">
                    <div class="col-12 col-md-3 mb3">
                      <label for="" class="form-label">Data Inicio</label>
                      <input type="date" name="data_inicio" class="form-control">
                    </div>
                    
                    <div class="col-12 col-md-3 mb3">
                      <label for="" class="form-label">Data Final</label>
                      <input type="date" name="data_final" class="form-control">
                    </div>
                    
                    <div class="col-12 col-md-3 mb3">
                      <label for="" class="form-label">Professores</label>
                      <select name="professor_id" class="form-control">
                        <option value="">TODOS</option>
                        @foreach ($professores as $item)
                          <option value="{{ $item->professor->id }}">{{ $item->professor->nome }} {{ $item->professor->sobre_nome }}</option>
                        @endforeach
                      </select>
                    </div>
                    
                    <div class="col-12 col-md-3 mb3">
                      <label for="" class="form-label">Disciplinas</label>
                      <select name="disciplinas_id" class="form-control">
                        <option value="">TODAS</option>
                        @foreach ($disciplinas as $item)
                          <option value="{{ $item->disciplina->id }}">{{ $item->disciplina->disciplina }}</option>
                        @endforeach
                      </select>
                    </div>
               
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <button type="submit" form="formulario" class="btn btn-primary">Filtrar</button>
              </div>
            </div>
          </div>  
        
          <div class="col-12 col-md-12">
            <div class="card">
              <div class="card-header">
                  <h5>Listar matérias</h5>
              </div>
              <div class="card-body">
                <table id="carregarTabelaMatricula"
                   style="width: 100%" class="table table-bordered  ">
                  <thead>
                    <tr>
                      <th>Professor</th>
                      <th>Disciplina</th>
                      <th>Data Envio</th>
                      <th>Data Entrega</th>
                      <th>Estado</th>
                      <th>Titulo</th>
                      <th>Descrição</th>
                      <th>Acções</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($materias as $item)
                    <tr>
                      <td> {{ $item->professor->nome }}  {{ $item->professor->sobre_nome }}</td>
                      <td> {{ $item->disciplina->disciplina }}</td>
                      <td> {{  date("d-m-Y", strtotime($item->created_at) ) }}</td>
                      <td> {{ date("d-m-Y", strtotime($item->data_limite) ) }}</td>
                      <td> {{ $item->status }}</td>
                      <td title="{{ $item->titulo }}"> {{ Str::limit($item->titulo, 30)  }}</td>
                      <td title="{{ $item->descricao }}"> {{ Str::limit($item->descricao, 50) }} </td>
                      <td>
                        <a href="{{ route('est.minhas-materias-estudante-apresentar', $item->id) }}" class="btn btn-success"><i class="fas fa-eye"></i></a>
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

@endsection