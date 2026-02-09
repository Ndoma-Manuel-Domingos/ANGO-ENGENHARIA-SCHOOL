@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Faculdades</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
                    <li class="breadcrumb-item active">Faculdades</li>
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
                <div class="card">
                    <div class="card-header">
                        @if (Auth::user()->can('create: disciplina'))
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalFaculdades">Nova Faculdade</a>
                        @endif
                        <a href="{{ route('web.disciplina-pdf-ano-lectivo') }}" class="btn-danger btn float-end mx-1" target="_blink">Imprimir PDF</a>
                        <a href="{{ route('web.disciplina-excel-ano-lectivo') }}" class="btn-success btn float-end mx-1" target="_blink">Imprimir Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Faculdade</th>
                                    <th>Abreviação</th>
                                    <th>Code</th>
                                    <th>Decano</th>
                                    <th style="width: 170px;">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($faculdades) != 0)
                                @foreach ($faculdades as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->faculdade->nome }}</td>
                                    <td>{{ $item->faculdade->abreviacao }}</td>
                                    <td>{{ $item->faculdade->code }}</td>
                                    <td>{{ $item->decano->nome ?? "" }} {{ $item->decano->sobre_nome ?? "" }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('delete: disciplina'))
                                                <a href="{{ route('web.faculdade-eliminar-ano-lectivo', ) }}" id="{{ $item->id }}" title="Excluir Disciplina" class="deleteModal dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
                                                @endif
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Outros</a>
                                            </div>
                                        </div>
                                    </td>
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


    <div class="modal fade" id="modalFaculdades">
        <div class="modal-dialog modal-xl">
            <form action="{{ route('web.cadastrar-faculdade-ano-lectivo') }}" id="formCreate" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cadastrar Faculdade</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12 col-12">
                                <label for="faculdade_id">Faculdade <span class="text-danger">*</span></label>
                                <select name="faculdade_id[]" class="form-control faculdade_id select2" id="faculdade_id" style="width: 100%;" data-placeholder="Selecione um conjunto de faculdade" multiple="multiple">
                                    <option value="">Selecione Faculdade</option>
                                    @foreach ($lista_faculdades as $item)
                                    <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12 col-12">
                                <label for="decano_id">Decano <span class="text-danger">*</span> <a href="{{ route('web.outro-funcionarios-create') }}" class="float-right text-right">Cadastrar Decano</a></label>
                                <select name="decano_id" class="form-control decano_id select2" id="decano_id" style="width: 100%;" data-placeholder="Escolher o Decano" multiple="multiple">
                                    <option value="">Selecione Decano</option>
                                    @foreach ($lista_funcionarios as $item)
                                    <option value="{{ $item->id }}">{{ $item->nome }} {{ $item->sobre_nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    $(function() {
        const tabelas = [
            "#carregarTabela"
        , ];
        tabelas.forEach(inicializarTabela);

        ajaxFormSubmit('#formCreate');

        excluirRegistro('.deleteModal', `{{ route('web.faculdade-eliminar-ano-lectivo', ':id') }}`);
    });

</script>
@endsection
