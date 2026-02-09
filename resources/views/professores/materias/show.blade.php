@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Visualizar Matéria</h1>
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
        <div class="card">
            <div class="card-header bg-light">
                <h5>Detalhe da Matéria</h5>
            </div>
            <div class="card-body">
                <table style="width: 100%"  style="width: 100%" class="table table-bordered  ">
                    <thead>
                        <tr>
                            <th>Nome da Escola</th>
                            <th>Turma</th>
                            <th>Disciplina</th>
                            <th>Ano Lectivo</th>
                            <th>Data Final</th>
                            <th>Data Criação</th>
                            <th>Estado</th>
                            <th>Nome do Professor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $materia->escola->nome }}</td>
                            <td>{{ $materia->turma->turma }}</td>
                            <td>{{ $materia->disciplina->disciplina }}</td>
                            <td>{{ $materia->ano->ano }}</td>
                            <td>{{ $materia->data_limite }}</td>
                            <td>{{ date("d-m-Y", strtotime($materia->created_at))  }} Ás {{ date("H:i:s", strtotime($materia->created_at))  }}</td>
                            <td>{{ $materia->status }}</td>
                            <td>{{ $materia->professor->nome }} {{ $materia->professor->sobre_nome }}</td>
                        </tr>
                                                
                        <tr>
                            <th>Titulo</th>
                            <th style="width: 80%" colspan="7">Descrição</th>
                        </tr>
                        
                        <tr>
                            <td>{{ $materia->titulo }}</td>
                            <td colspan="7">{{ $materia->descricao }}</td>
                        </tr>
                        
                        <tr>
                            <th colspan="3">1º Documentos</th>
                            <th colspan="2">2º Documentos</th>
                            <th colspan="3">3º Documentos</th>
                        </tr>
                        
                        <tr>
                          <td colspan="3">
                            <a href='{{ asset("assets/materias/{$materia->documento1}") }}' target="_blink">{{ $materia->documento1 }}</a>
                          </td>
                          <td colspan="2">
                            <a href='{{ asset("assets/materias/{$materia->documento2}") }}' target="_blink">{{ $materia->documento2 }}</a>
                          </td>
                          <td colspan="3">
                            <a href='{{ asset("assets/materias/{$materia->documento3}") }}' target="_blink">{{ $materia->documento3 }}</a>
                          </td>
                        </tr>

                    </tbody>
                </table>
            </div>
            
            <div class="card-footer">
                <a href="{{ route('portal-professor-minhas-materias.edit', $materia->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Editar</a>
                <a href="{{ route('portal-professor-minhas-materias.destroy', $materia->id) }}" class="btn btn-danger"><i class="fas fa-trash"></i> Eliminar</a>
            </div>
        </div>
        </div>
      </div>
      
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>


@endsection
