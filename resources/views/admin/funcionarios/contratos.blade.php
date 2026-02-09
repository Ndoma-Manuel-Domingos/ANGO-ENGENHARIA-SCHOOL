@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Lista Contratos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('recursos_humanos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Contratos</li>
                </ol>

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Cadastrar, listar, editar, eliminar e Mais informações dos contratos. Busca avançada para melhorar na navegação do software.</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{-- <h3 class="card-title">Listagem dos Funcionários </h3> --}}
                        <a href="{{ route('web.funcionarios-criar-contrato', Crypt::encrypt(null)) }}" class="btn btn-primary float-end">Novo Contrato</a>

                        <a href="{{ route('funcionarios-imprmir') }}" target="_blink" class="btn btn-primary float-end mx-2">Imprimir</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="carregarTabelaFuncionarios" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Nº Doc</th>
                                    <th>Nome Completo</th>
                                    <th>Inicio</th>
                                    <th>Final</th>
                                    <th>Status</th>
                                    <th width="100">Acções</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($contratos) != 0)
                                @foreach ($contratos as $item)
                                <tr>
                                    <td>000-{{ $item->id }}</td>
                                    <td>{{ $item->nome }} {{ $item->sobre_nome }}</td>
                                    <td>{{ $item->data_inicio_contrato }}</td>
                                    <td>{{ $item->data_final_contrato }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info">Opções</button>
                                            <button type="button" class="btn btn-info dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">

                                                <a title="Editar Funcionarios" id="{{ $item->id }}" class="activar_contrato dropdown-item"><i class="fa fa-edit"></i> Activar/Desativar </a>
                                                <a href="{{ route('web.funcionarios-editar-contrato', Crypt::encrypt($item->id)) }}" title="Editar Funcionarios" id="{{ $item->id }}" class="dropdown-item"><i class="fa fa-edit"></i> Editar </a>
                                                <a href="{{ route('ficha-funcionario-contrato', Crypt::encrypt($item->id)) }}" title="Imprimir Funcionarios" id="{{ $item->id }}" target="blink" class="dropdown-item"><i class="fa fa-print"></i> Imprimir </a>
                                                <a title="excluir Funcionarios" id="{{ $item->id }}" class="delete_funcionarios dropdown-item"><i class="fa fa-trash"></i> Excluir</a>
                                                <a href="{{ route('web.funcionarios-visualizar-contrato', Crypt::encrypt($item->id)) }}" title="Visualizar Funcionarios" class="ver_dados dropdown-item"><i class="fa fa-eye"></i> Visualizar</a>
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
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    $(function() {

        // editar update
        $(document).on('click', '.delete_funcionarios', function(e) {
            e.preventDefault();

            var novo_id = $(this).attr('id');

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
                        , url: "contratos-excluir/" + novo_id
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


        $(document).on('click', '.activar_contrato', function(e) {
            e.preventDefault();

            var novo_id = $(this).attr('id');

            Swal.fire({
                title: "Tens a certeza"
                , text: "Que desejas Activar este Funcionário"
                , icon: "warning"
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Sim, Desejo Activa-ló!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "GET"
                        , url: "contratos-activar/" + novo_id
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

    });

    $(function() {
        $("#carregarTabelaFuncionarios").DataTable({
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
