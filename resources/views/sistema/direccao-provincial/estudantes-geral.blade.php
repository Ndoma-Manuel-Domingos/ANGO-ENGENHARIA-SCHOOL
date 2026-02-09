@extends('layouts.provinciais')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Estudantes</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                    <li class="breadcrumb-item active">Estudantes</li>
                  </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info"></i> Listagem geral dos estudantes da província.</h5>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <form action="{{ route('app.listagem-estudantes-provincial-geral') }}" method="get">
                        @csrf
                        <div class="card">
                            <div class="card-body row">
                        
                                <div class="col-12 col-md-2 mb3">
                                  <label for="" class="form-label">Municipios</label>
                                  <select name="municipio_id" id="municipio_id" class="form-control select2" style="width: 100%">
                                      <option value="">Todos</option>
                                      @foreach ($munucipios as $item)
                                          <option value="{{ $item->id }}"
                                          {{ $requests['municipio_id'] == $item->id ? 'selected' : '' }}>
                                          {{ $item->nome }}</option>
                                      @endforeach
                                  </select>
                                </div>
                                
                                <div class="col-12 col-md-2 mb3">
                                  <label for="" class="form-label">Distritos</label>
                                  <select name="distrito_id" id="distrito_id" class="form-control select2" style="width: 100%">
                                      <option value="">Todos</option>
                                      @foreach ($distritos as $item)
                                          <option value="{{ $item->id }}"
                                          {{ $requests['distrito_id'] == $item->id ? 'selected' : '' }}>
                                          {{ $item->nome }}</option>
                                      @endforeach
                                  </select>
                                </div>


                                <div class="col-12 col-md-2 mb3">
                                    <label for="" class="form-label">Escolas</label>
                                    <select name="shcools_id" id="shcools_id" class="form-control select2" style="width: 100%">
                                        <option value="">Todos</option>
                                        @foreach ($escolas as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $requests['shcools_id'] == $item->id ? 'selected' : '' }}>
                                                {{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-2 mb3">
                                    <label for="" class="form-label">Ano Lectivo</label>
                                    <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control select2" style="width: 100%">
                                        <option value="">Todos</option>
                                        @foreach ($anos_lectivos as $ano)
                                            <option value="{{ $ano->id }}"
                                                {{ $requests['ano_lectivos_id'] == $ano->id ? 'selected' : '' }}>
                                                {{ $ano->ano }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-12 col-md-2 mb3">
                                    <label for="" class="form-label">Generos</label>
                                    <select name="genero" id="genero" class="form-control select2" style="width: 100%">
                                        <option value="">Todos</option>
                                        <option value="Masculino"
                                            {{ $requests['genero'] == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ $requests['genero'] == 'Femenino' ? 'selected' : '' }}>
                                            Femenino</option>
                                    </select>
                                </div>



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
                    <div class="card">
                        <div class="card-header">
                            <h6 class="float-start">Total registros ({{ count($estudantes) }})</h6>
                            <a href="{{ route('print.listagem-todos-estudantes-imprmir', ['ano_lectivos_id' => $requests['ano_lectivos_id'] ?? "", 'municipio_id' => $requests['municipio_id'] ?? "", 'distrito_id' => $requests['distrito_id'] ?? ""]) }}"
                                class="btn btn-primary float-end mx-2" target="_blink">Imprimir</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="carregarTabela" style="width: 100%"
                                class="table  table-bordered table-striped table-striped">
                                <thead>
                                    <tr>
                                        {{-- <th>Nº</th> --}}
                                        <th>Nome</th>
                                        <th>Bilhete</th>
                                        <th>Genero</th>
                                        <th>Status</th>
                                        <th>Escola</th>
                                        <th>Província</th>
                                        <th>Município.</th>
                                        <th>Ano Lectivo</th>
                                        {{-- <th style="width: 70px">Acções</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($estudantes) != 0)
                                        @foreach ($estudantes as $item)
                                            <tr>
                                                {{-- <td>
                                    @if (Auth::user()->can('read: estudante'))
                                      <a href="{{ route('app.informacao-estudante-provincial', Crypt::encrypt($item->id)) }}" class="text-secondary">{{ $item->numero_processo }}</a>
                                    @else  
                                      {{ $item->numero_processo }}
                                    @endif
                                  </td> --}}

                                                <td>
                                                    @if (Auth::user()->can('read: estudante'))
                                                        <a href="{{ route('app.informacao-estudante-provincial', Crypt::encrypt($item->id)) }}"
                                                            class="text-primary">{{ $item->nome }}
                                                            {{ $item->sobre_nome }}</a>
                                                    @else
                                                        {{ $item->nome }} {{ $item->sobre_nome }}
                                                    @endif
                                                </td>

                                                <td>{{ $item->bilheite }}</td>
                                                <td>{{ $item->genero }}</td>
                                                <td>{{ $item->status }}</td>
                                                <td>{{ $item->escola->nome }}</td>
                                                <td>{{ $item->escola->provincia->nome }}</td>
                                                <td>{{ $item->escola->municipio->nome }}</td>
                                                <td>{{ $item->escola->ano->ano }}</td>
                                                {{-- <td>
                                    @if (Auth::user()->can('read: estudante'))
                                      <a href="{{ route('app.informacao-estudante-provincial', Crypt::encrypt($item->id)) }}" 
                                      title="Visualizar Estudante" id="{{ $item->id }}" class="btn btn-primary">
                                      <i class="fa fa-eye"></i></a>
                                    @endif
                                  </td> --}}
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection


@section('scripts')
  <script>
    
    const tabelas = [
        "#carregarTabela"
    , ];
    tabelas.forEach(inicializarTabela);
   
    $("#provincia_id").change(() => {
            let id = $("#provincia_id").val();
            $.get('carregar-municipios/' + id, function(data) {
                $("#municipio_id").html("")
                $("#municipio_id").html(data)
            })
        })

        $("#municipio_id").change(() => {
            let id = $("#municipio_id").val();
            $.get('carregar-distritos/' + id, function(data) {
                $("#distrito_id").html("")
                $("#distrito_id").html(data)
            })
        })

        $("#municipio_id").change(() => {
            let id = $("#municipio_id").val();
            $.get('carregar-escolas-municipio/' + id, function(data) {
                $("#shcools_id").html("")
                $("#shcools_id").html(data)
            })
        })

        $("#distrito_id").change(() => {
            let id = $("#distrito_id").val();
            $.get('carregar-escolas-distrito/' + id, function(data) {
                $("#shcools_id").html("")
                $("#shcools_id").html(data)
            })
        })

        $("#shcools_id").change(() => {
            let id = $("#shcools_id").val();
            $.get('carregar-ano-lectivos-escolas/' + id, function(data) {
                $("#ano_lectivo_id").html("")
                $("#ano_lectivo_id").html(data)
            })
        })

  </script>
@endsection
