@extends("layouts.{$loyout}")

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Laboratórios</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="">Voltar</a></li>
                    <li class="breadcrumb-item active">Laboratórios</li>
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
                    <h5><i class="fas fa-info"></i> Cadastro, listar, editar e eliminar de Laboratórios. Busca avançada para melhorar na navegação do software.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if (Auth::user()->can('create: laboratorio'))
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalClasses">Novo Laboratório</a>
                        @endif
                        <a href="{{ route('web.laboratorios-escolas-pdf', ['shcool_id' => $escola->id]) }}" class="btn-danger btn float-end mx-1" target="_blink">Imprimir PDF</a>
                        <a href="{{ route('web.laboratorios-escolas-excel', ['shcool_id' => $escola->id]) }}" class="btn-success btn float-end mx-1" target="_blink">Imprimir Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Cod</th>
                                    <th>Designação</th>
                                    <th style="width: 170px;">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($laboratorios) != 0)
                                @foreach ($laboratorios as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->laboratorio->id }}</td>
                                    <td>{{ $item->laboratorio->nome }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('delete: laboratorio'))
                                                <a href="{{ route('web.laboratorios-escolas-eliminar', $item->id) }}" title="Excluir Laboratorio" class="dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
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
            <form action="{{ route('web.laboratorios-escolas-cadastrar') }}" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cadastrar Laboratórios</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="disciplina_id">Disciplinas <span class="text-danger">*</span></label>
                                <select name="disciplina_id[]" class="form-control disciplina_id select2" id="disciplina_id" style="width: 100%;" data-placeholder="Selecione um conjunto de Laboratórios" multiple="multiple">
                                    <option value="">Selecione Disciplinas</option>
                                    @foreach ($lista_laboratorios as $item)
                                    <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text disciplina_id_error"></span>
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
        $("#carregarTabela").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });

</script>
@endsection
