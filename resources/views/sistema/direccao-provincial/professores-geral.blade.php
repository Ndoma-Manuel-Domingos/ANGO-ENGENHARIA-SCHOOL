@extends('layouts.provinciais')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Professores da Província</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                  <li class="breadcrumb-item active">Professores</li>
                </ol>
            </div><!-- /.col -->
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Listagem geral dos professores da província.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('app.professores-provincial') }}" method="get">
                    @csrf
                    <div class="card">
                        <div class="card-body row">

                            <div class="form-group mb-3 col-md-3 col-12">
                                <label for="" class="form-label">Municípios</label>
                                <select name="municipio_id" class="form-control select2" id="municipio_id">
                                    <option value="">Todos</option>
                                    @foreach ($municipios as $item)
                                    <option value="{{ $item->id }}" {{ $requests['municipio_id']==$item->id ? 'selected'
                                        : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3 col-md-3 col-12">
                                <label for="" class="form-label">Distritos</label>
                                <select name="distrito_id" class="form-control select2" id="distrito_id">
                                    <option value="">Todos</option>
                                    @foreach ($distritos as $item)
                                    <option value="{{ $item->id }}" {{ $requests['distrito_id']==$item->id ? 'selected'
                                        : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3 col-md-3 col-12">
                                <label for="" class="form-label">Escolas</label>
                                <select name="shcools_id" class="form-control select2" id="escola_id">
                                    <option value="">Todos</option>
                                    @foreach ($escolas as $item)
                                    <option value="{{ $item->id }}" {{ $requests['shcools_id']==$item->id ? 'selected' :
                                        '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3 col-md-3 col-12">
                                <label for="status" class="form-label">Funcionários</label>
                                <select name="status" id="status" class="form-control status select2">
                                    <option value="">Todos</option>
                                    <option value="activo" {{ $requests['status']=='activo' ? 'selected' : '' }}>Activo
                                    </option>
                                    <option value="desactivo" {{ $requests['status']=='desactivo' ? 'selected' : '' }}>
                                        Desactivo
                                    </option>
                                </select>
                                @error('status')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-3 col-12">
                                <label for="universidade_id" class="form-label">Universidade</label>
                                <select name="universidade_id" id="universidade_id"
                                    class="form-control universidade_id select2">
                                    <option value="">Todos</option>
                                    @foreach ($universidades as $item)
                                    <option value="{{ $item->id }}" {{ $requests['universidade_id']==$item->id ?
                                        'selected' : '' }}>{{
                                        $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('universidade_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-3 col-12">
                                <label for="especialidade_id" class="form-label">Especialidades</label>
                                <select name="especialidade_id" id="especialidade_id"
                                    class="form-control especialidade_id select2">
                                    <option value="">Todos</option>
                                    @foreach ($especialidades as $item)
                                    <option value="{{ $item->id }}" {{ $requests['especialidade_id']==$item->id ?
                                        'selected' : '' }}>{{
                                        $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('especialidade_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-md-2 col-12">
                                <label for="categora_id" class="form-label">Categorias</label>
                                <select name="categora_id" id="categora_id" class="form-control categora_id select2">
                                    <option value="">Todos</option>
                                    @foreach ($categorias as $item)
                                    <option value="{{ $item->id }}" {{ $requests['categora_id']==$item->id ? 'selected'
                                        : '' }}>{{
                                        $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('categora_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group mb-3 col-md-2 col-12">
                                <label for="escolaridade_id" class="form-label">Nível Academico</label>
                                <select name="escolaridade_id" id="escolaridade_id"
                                    class="form-control escolaridade_id select2">
                                    <option value="">Todos</option>
                                    @foreach ($escolaridade as $item)
                                    <option value="{{ $item->id }}" {{ $requests['escolaridade_id']==$item->id ?
                                        'selected' : '' }}>{{
                                        $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('escolaridade_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3 col-12 col-md-2">
                                <label for="formacao_id" class="form-label">Formação</label>
                                <select name="formacao_id" id="formacao_id" class="form-control formacao_id select2">
                                    <option value="">Todos</option>
                                    @foreach ($formacao_academicos as $item)
                                    <option value="{{ $item->id }}" {{ $requests['formacao_id']==$item->id ? 'selected'
                                        : '' }}>{{
                                        $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('formacao_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>


                           {{--<div class="form-group mb-3 col-md-2 col-12">
                                <label for="" class="form-label">Ano Lectivo</label>
                                <select name="ano_lectivos_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($anos_lectivos as $ano)
                                    <option value="{{ $ano->id }}" {{ $requests['ano_lectivos_id']==$ano->id ?
                                        'selected' : '' }}>{{ $ano->ano }}</option>
                                    @endforeach
                                </select>
                            </div>--}} 
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                @if(session()->has('danger'))
                <div class="alert alert-warning">
                    {{ session()->get('danger') }}
                </div>
                @endif

                @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
                @endif
            </div>

            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <a href="" target="_blink" class="btn btn-primary">Imprimir</a>
                        <a href="{{ route('web.professores-provincial-create') }}" class="btn btn-primary">Novo Professor</a>
                        <h5 class="text-info  float-right">Registro Encontrados Total: {{ count($professores) }}</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaPrpfessores" style="width: 100%"
                            class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Id</th>
                                    <th width="">Nome</th>
                                    <th width="">Data Nascimento</th>
                                    <th width="">Genero</th>
                                    <th width="">Idade</th>
                                    <th width="">Bilhete</th>
                                    <th width="">Estado</th>
                                    <th width="">Escola</th>
                                    <th width="">Província</th>
                                    <th width="">Município</th>
                                    <th>Total Escola</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @if ($professores)
                                @foreach ($professores as $professor)
                                <tr>
                                    <td>{{ $professor->id }}</td>
                                    <td>
                                        @if (Auth::user()->can('read: professores'))
                                        <a
                                            href="{{ route('app.informacao-professores-provincial', Crypt::encrypt($professor->funcionario->id)) }}">{{
                                            $professor->funcionario->nome }} {{ $professor->funcionario->sobre_nome
                                            }}</a>
                                        @else
                                        {{ $professor->funcionario->nome }} {{ $professor->funcionario->sobre_nome }}
                                        @endif
                                    </td>
                                    <td>{{ $professor->funcionario->nascimento }}</td>
                                    <td>{{ $professor->funcionario->genero }}</td>
                                    <td>{{ $professor->funcionario->idade($professor->funcionario->nascimento) }}</td>
                                    <td>{{ $professor->funcionario->bilheite }}</td>
                                    <td>{{ $professor->funcionario->status }}</td>
                                    <td>{{ $professor->escola->nome }}</td>
                                    <td>{{ $professor->escola->provincia->nome }}</td>
                                    <td>{{ $professor->escola->municipio->nome }}</td>
                                    <td>{{ $professor->funcionario->total_escola($professor->funcionario->id) }}</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">

                    </div>
                </div>
                <!-- /.card -->
            </div>


        </div>
    </div>
</div>
<!-- /.content-header -->

@endsection


@section('scripts')
<script>
    $("#municipio_id").change(()=>{
      let id = $("#municipio_id").val();
      $.get('carregar-distritos/'+id, function(data){
          $("#distrito_id").html("")
          $("#distrito_id").html(data)
      })
      
      $.get('carregar-escolas-municipio/'+id, function(data){
          $("#escola_id").html("")
          $("#escola_id").html(data)
      })
    });
    
    $("#distrito_id").change(()=>{
      let id = $("#distrito_id").val();
      $.get('carregar-escolas-distrito/'+id, function(data){
          $("#escola_id").html("")
          $("#escola_id").html(data)
      })
    });
  
    $(function () {
      $("#carregarTabelaPrpfessores").DataTable({
        language: {
            url: "{{ asset('plugins/datatables/pt_br.json') }}"
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
</script>
@endsection