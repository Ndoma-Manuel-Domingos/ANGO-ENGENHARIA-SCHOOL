@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Disciplinas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Painel de controle</a></li>
                    <li class="breadcrumb-item active">Disciplinas</li>
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
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalClasses">Nova Disciplinas</a>
                        @endif
                        <a href="{{ route('web.disciplina-pdf-ano-lectivo') }}" class="btn-danger btn float-end mx-1" target="_blink">Imprimir PDF</a>
                        <a href="{{ route('web.disciplina-excel-ano-lectivo') }}" class="btn-success btn float-end mx-1" target="_blink">Imprimir Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Cod</th>
                                    <th>Disciplina</th>
                                    <th>Abreviação</th>
                                    <th>Code</th>
                                    <th style="width: 170px;">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($disciplinas) != 0)
                                @foreach ($disciplinas as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->disciplina->disciplina }}</td>
                                    <td>{{ $item->disciplina->abreviacao }}</td>
                                    <td>{{ $item->disciplina->code }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('delete: disciplina'))
                                                <a href="#" id="{{ $item->id }}" title="Excluir Disciplina" class="deleteModal dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
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


    <div class="modal fade" id="modalClasses">
        <div class="modal-dialog modal-xl">
            <form action="{{ route('web.cadastrar-disciplinas-ano-lectivo') }}" id="formCreate" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cadastrar Disciplinas</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="disciplina_id">Disciplinas <span class="text-danger">*</span></label>
                                <select name="disciplina_id[]" class="form-control disciplina_id select2" id="disciplina_id" style="width: 100%;" data-placeholder="Selecione um conjunto de Disciplinas" multiple="multiple">
                                    <option value="">Selecione Disciplinas</option>
                                    @foreach ($lista_disciplinas as $item)
                                    <option value="{{ $item->id }}">{{ $item->disciplina }}</option>
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

    excluirRegistro('.deleteModal', `{{ route('web.disciplina-eliminar-ano-lectivo', ':id') }}`);
  });
</script>
@endsection
