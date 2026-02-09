@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Listagem de Estagiarios </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('instituicoes_estagios.instituicao-estagio') }}">Listagem</a></li>
                    <li class="breadcrumb-item active">Detalhe</li>
                </ol>
            </div><!-- /.col -->
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <form action="{{ route('instituicoes_estagios.instituicao-listar-estagiarios') }}" method="get">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-3 col-12">
                                    <label for="instituicao_id">Instituição <span class="text-danger">*</span></label>
                                    <select name="instituicao_id" id="" class="form-control select2" style="width: 100%">
                                        <option value="">Selecionar Instituição</option>
                                        @foreach ($instituicoes as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('instituicao_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="estagio_id">Estagio <span class="text-danger">*</span></label>
                                    <select name="estagio_id" id="" class="form-control select2" style="width: 100%">
                                        <option value="">Selecionar Estagio</option>
                                        @foreach ($estagios as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('estagio_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-primary">Pesquisar</button>
                        </div>
                    </form>
                </div>

            </div>

            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('web.estudante-atribuir-estagio') }}" class="btn btn-primary">Atribuir Estagio</a>

                        <a href="{{ route('estudantes-estagiarios-imprmir', ['instituicao_id' => $filtros['instituicao_id'], 'estagio_id' => $filtros['estagio_id']]) }}" class="float-end btn-danger btn mx-1" target="_blink"><i class="fas fa-file-pdf"></i> Imprimir</a>
                        <a href="{{ route('estudantes-estagiarios-imprmir-excel', ['instituicao_id' => $filtros['instituicao_id'], 'estagio_id' => $filtros['estagio_id']]) }}" class="float-end btn-success btn mx-1" target="_blink"><i class="fas fa-file-excel"></i> Imprimir</a>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Id</th>
                                    <th>Nº Processo</th>
                                    <th>Estudante</th>
                                    <th>Idade</th>
                                    <th>Estagio</th>
                                    <th>Instituição</th>
                                    <th>Tipo Instituição</th>
                                    <th>Data Inicio</th>
                                    <th>Data Final</th>
                                    <th>Estado</th>
                                    <th width="10%" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estagiarios as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->estudante->numero_processo }}</td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome ?? '' }} {{ $item->estudante->sobre_nome ?? '' }}</a></td>
                                    <td>{{ $item->estudante->idade($item->estudante->nascimento)  }} </td>
                                    <td>{{ $item->estagio->nome ?? '' }}</td>
                                    <td>{{ $item->instituicao->nome ?? '' }}</td>
                                    <td>{{ $item->instituicao->tipo ?? '' }}</td>
                                    <td>{{ $item->data_inicio ?? '' }}</td>
                                    <td>{{ $item->data_final ?? '' }}</td>
                                    @if ($item->status == "activo")
                                    <td class="text-success text-uppercase">{{ $item->status ?? '' }}</td>
                                    @else
                                    <td class="text-danger text-uppercase">{{ $item->status ?? '' }}</td>
                                    @endif
                                    <td class="text-end">

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">

                                                {{-- @if (Auth::user()->can('update: estudante')) --}}
                                                <a href="{{ route('web.estudante-editar-estagiario-estagio', Crypt::encrypt($item->id)) }}" title="Editar Estagio" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" title="Visualizar Estagiario" class="dropdown-item"><i class="fa fa-edit"></i> Visualizar</a>
                                                <a href="{{ route('instituicoes_estagios.instituicao-remover-estagio-estagiario', Crypt::encrypt($item->id)) }}" title="Eliminar Estagio" class="dropdown-item text-danger"><i class="fa fa-trash"></i> Remover Estagio</a>
                                                {{-- @endif --}}

                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#"><i class="fas fa-outdent"></i> Outros</a>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- /.content-header -->

@endsection
@section('scripts')
<script>
    const tabelas = [
        "#carregarTabela"
    , ];
    tabelas.forEach(inicializarTabela);

</script>
@endsection
