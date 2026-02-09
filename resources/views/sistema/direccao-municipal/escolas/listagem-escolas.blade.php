@extends('layouts.municipal')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-8">
                <h1 class="m-0 text-dark">Listagem de todas as Escolas</h1>
            </div><!-- /.col -->
            <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('home-municipal') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Escolas</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <form action="{{ route('listagem-escola-municipal') }}" method="get">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                <div class="form-group col-md-3">
                                    <label for="provincia_id">Provincias</label>
                                    <select name="provincia_id" class="form-control select2 editar_status_ano" id="provincia_id">
                                        <option value="">Todas</option>
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id }}" {{ $requests['provincia_id'] ==  $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('provincia_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label for="ensino_id">Ensinos</label>
                                    <select name="ensino_id" class="form-control select2 editar_status_ano" id="ensino_id">
                                        <option value="">Todas</option>
                                        @foreach ($ensinos as $item)
                                        <option value="{{ $item->id }}" {{ $requests['ensino_id'] ==  $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('ensino_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="categoria" class="form-label">Sector</label>
                                    <select name="categoria" id="categoria" class="form-control categoria select2">
                                        <option value="">Todas</option>
                                        <option value="Publico" {{ $requests['categoria'] == 'Publico' ? : '' }}>Publico</option>
                                        <option value="Publico-Privado" {{ $requests['categoria'] == 'Publico-Privado' ? : '' }}>Público Privado</option>
                                    </select>
                                    @error('categoria')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            @if (count($escolas) == 0)
            <div class="col-12 col-md-12">
                <div class="callout callout-danger">
                    <h5 class="text-danger"><i class="fas fa-info"></i> Sem registro Encontrados. <a href="{{ route('criar-escola-municipal') }}" class="btn btn-primary mx-1 float-end">Nova Escola</a></h5>
                </div>
            </div>
            @else
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="text-info  float-start">Registro Encontrados. Total: {{ count($escolas) }}</h5>
                        <a href="{{ route('print.municipio-listagem-escola-imprmir', ['ensino_id' => $requests['ensino_id'] ,  'provincia_id' => $requests['provincia_id'] , 'categoria' => $requests['categoria']]) }}" target="_blink" class="btn btn-primary float-end">Imprimir</a>
                        <a href="{{ route('criar-escola-municipal') }}" class="btn btn-primary mx-1 float-end">Nova Escola</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarEscolasMunicipal" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Nº</th>
                                    <th style="width: 5%">NIF</th>
                                    <th width="">Escolas</th>
                                    <th width="">Sistema Ensino</th>
                                    <th width="">Sector</th>
                                    <th width="">Dias Lecença</th>
                                    <th width="">Modulo</th>
                                    <th style="width: 7%">T. Alunos</th>
                                    <th style="width: 7%">T. Professores</th>
                                    <th style="width: 5%">Status</th>
                                    <th style="width: 5%">Acções</th>
                                </tr>
                            </thead>
                            <tbody id="">
                                @if ($escolas)
                                @foreach ($escolas as $key => $escola)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $escola->documento }}</td>
                                    <td><a href="{{ route('web.informacao-escola-municipal', $escola->id) }}">{{ $escola->nome }}</a></td>
                                    <td>{{ $escola->ensino->nome ?? "" }}</td>
                                    <td>{{ $escola->categoria }}</td>
                                    @if ($escola->dias_licencas($escola->id) <= 30) 
                                      <td class="text-danger">{{ $escola->dias_licencas($escola->id) ?? 0 }} dias</td>
                                    @else
                                      <td class="text-success">{{ $escola->dias_licencas($escola->id) ?? 0 }} dias</td>
                                    @endif
                                    <td>{{ $escola->modulo }}</td>
                                    <td>{{ $escola->total_estudantes($escola->id) }}</td>
                                    <td>{{ $escola->total_professores($escola->id) }}</td>
                                    <td>{{ $escola->status }}</td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('update: escola'))
                                                <a href="{{  route('web.editar-escola-municipal', $escola->id ) }}" title="Editar Escola" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif
                                                @if (Auth::user()->can('update: escola'))
                                                <a href="{{  route('web.mudar-status-escola-municipal', $escola->id ) }}" title="Mudar Status Escola" class="dropdown-item"><i class="fa fa-eye"></i> Mudar Status</a>
                                                @endif
                                                @if (Auth::user()->can('read: escola'))
                                                <a href="{{  route('web.informacao-escola-municipal', $escola->id ) }}" title="Visualizar Escola" class="dropdown-item"><i class="fa fa-eye"></i> Visualizar</a>
                                                @endif
                                                @if (Auth::user()->can('read: escola'))
                                                <a href="{{  route('web.activar-licenca-escola-municipal', $escola->id ) }}" title="Licenciar Escola" class="dropdown-item"><i class="fa fa-cogs"></i> Licença</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            @endif
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    const tabelas = [
        "#carregarEscolasMunicipal"
    , ];
    tabelas.forEach(inicializarTabela);

    $(function() {
        $("#provincia_id").change(() => {
            let country_id = $("#provincia_id").val();
            $.get('../carregar-municipios/' + country_id, function(data) {
                $("#municipio_id").html("")
                $("#municipio_id").html(data)
            })
        })
    });

</script>
@endsection
