@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Gestão de Encarregados de Educação</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Encarregados</li>
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
                        @if (Auth::user()->can('create: encarregado'))
                        <a href="{{ route('encarregados.create') }}" class="btn-primary btn float-end">Novo Encarregado</a>
                        @endif
                        <a href="{{ route('encarregados-imprmir') }}" target="_blink" class="btn btn-primary mx-2 float-end">Imprimir</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table id="carregarTabelaEstudantes" style="width: 100%" class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Nome</th>
                                    <th>Sobre Nome</th>
                                    <th>Bilhete</th>
                                    <th>Genero</th>
                                    <th>Profissão</th>
                                    <th>Telefone</th>
                                    <th>Total Educandos</th>
                                    <th>Acções</th>
                                </tr>
                            </thead>
                            <tbody class="tableEncarregados">
                                @foreach ($listarEncarregado as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><a href="{{ route('encarregados.show', $item->id) }}">{{ $item->nome_completo }}</a></td>
                                    <td>{{ $item->sobre_nome }}</td>
                                    <td>{{ $item->numero_bilhete }}</td>
                                    <td>{{ $item->genero }}</td>
                                    <td>{{ $item->profissao }}</td>
                                    <td>{{ $item->telefone }}</td>
                                    <td>{{ count($item->educandos) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('read: encarregado'))
                                                <a href="{{ route('encarregados.show', $item->id) }}"class="ver_dados dropdown-item"><i class="fa fa-eye"></i> Visualizar</a>
                                                @endif
                                                @if (Auth::user()->can('update: encarregado'))
                                                <a href="{{ route('encarregados.edit', $item->id) }}" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                @endif
                                                @if (Auth::user()->can('create: encarregado'))
                                                <a href="{{ route('encarregados.adicionar-estudantes-encarregado', $item->id) }}" class="dropdown-item"><i class="fa fa-user-plus"></i> Adicionar Educando</a>
                                                @endif
                                                @if (Auth::user()->can('delete: encarregado'))
                                                <a href="#" data-id="{{ $item->id }}" class="dropdown-item delete-record text-danger"><i class="fa fa-trash"></i> Excluir</a>
                                                @endif
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
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    $(function() {
        $(document).on('click', '.delete-record', function(e) {
            e.preventDefault();
            let recordId = $(this).data('id'); // Obtém o ID do registro
   
            Swal.fire({
                title: 'Você tem certeza?'
                , text: "Esta ação não poderá ser desfeita!"
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#d33'
                , cancelButtonColor: '#3085d6'
                , confirmButtonText: 'Sim, excluir!'
                , cancelButtonText: 'Cancelar'
            , }).then((result) => {
                if (result.isConfirmed) {
                    // Envia a solicitação AJAX para excluir o registro
                    $.ajax({
                        url: `{{ route('encarregados.destroy', ':id') }}`.replace(':id', recordId)
                        , method: 'DELETE'
                        , data: {
                            _token: '{{ csrf_token() }}', // Inclui o token CSRF
                        }
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
                    , });
                }
            });
        });
    });

    $(function() {
        $("#carregarTabelaEstudantes").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
