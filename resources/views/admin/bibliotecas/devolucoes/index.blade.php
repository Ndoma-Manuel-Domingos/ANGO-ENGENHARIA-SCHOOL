@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Devoluções de Empréstimos de Livros</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('biblioteca.controle') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Devoluções</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

{{-- cadastrar principal cuross --}}
<div class="modal fade" id="modalFormCadastro">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Devoluções</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="emprestimo_id">Empréstimos </label>
                        <select name="emprestimo_id" id="emprestimo_id" class="form-control emprestimo_id select2" style="width: 100%" placeholder="emprestimo_id">
                            <option value="">Escolher</option>
                            @foreach ($emprestimos as $item)
                            <option value="{{ $item->id }}">{{ $item->codigo_referencia }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error-text emprestimo_id_error"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="status">Estado </label>
                        <select name="status" id="status" class="form-control status select2" style="width: 100%" placeholder="status">
                            <option value="">Escolher</option>
                            <option value="Bom">Bom</option>
                            <option value="Danificado">Danificado</option>
                            <option value="Extraviado">Extraviado</option>
                        </select>
                        <span class="text-danger error-text status_error"></span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="data_devolucao">Data de Devolução </label>
                        <input type="date" name="data_devolucao" id="data_devolucao" value="{{ date('Y-m-d') }}" class="form-control data_devolucao">
                        <span class="text-danger error-text data_devolucao_error"></span>
                    </div>


                    <div class="form-group col-md-12">
                        <label for="observacao">Descrição </label>
                        <textarea rows="5" name="observacao" id="observacao" class="form-control observacao"></textarea>
                        <span class="text-danger error-text observacao_error"></span>
                    </div>

                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary cadastrar">Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
<!-- /.modal -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="#" class="btn btn-primary float-end" data-toggle="modal" data-target="#modalFormCadastro">Devoluções</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabela" style="width: 100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Codigo Referência</th>
                                    <th>Data Devolução</th>
                                    <th>Estado</th>
                                    <th>Observação</th>
                                    <th style="width: 120px">Acções</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @foreach ($devolucoes as $item)
                                <tr>
                                    <td>{{ $item->emprestimo->codigo_referencia }}</td>
                                    <td>{{ $item->data_devolucao }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->observacao }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a title="Excluir" id="{{ $item->id }}" href="#" class="delelte dropdown-item"><i class="fa fa-trash"></i>
                                                    Excluir</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Outros</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
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
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    $(function() {
        // Cadastrar
        $(document).on('click', '.cadastrar', function(e) {
            e.preventDefault();
            var data = {
                'emprestimo_id': $('.emprestimo_id').val()
                , 'status': $('.status').val()
                , 'data_devolucao': $('.data_devolucao').val()
                , 'observacao': $('.observacao').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('devolucoes-emprestimo-livros.store') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                    window.location.reload();
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        // delete
        $(document).on('click', '.delelte', function(e) {
            e.preventDefault();
            var recordId = $(this).attr('id');

            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas remover esta informação"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Sim, Apagar Estes dados!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "DELETE"
                        , url: `{{ route('emprestimo-livros.destroy', ':id') }}`.replace(':id', recordId)
                        , beforeSend: function() {
                            // Você pode adicionar um loader aqui, se necessário
                            progressBeforeSend();
                        }
                        , success: function(response) {
                            Swal.close();
                            // Exibe uma mensagem de sucesso
                            showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                            window.location.reload();
                        }
                        , error: function(xhr) {
                            Swal.close();
                            showMessage('Erro!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        });

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
