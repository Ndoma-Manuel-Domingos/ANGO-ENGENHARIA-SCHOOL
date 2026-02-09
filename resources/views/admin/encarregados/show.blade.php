@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Mais Detalhe Encarregado: <span class="text-dark">{{ $encarregado->nome_completo }}</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('encarregados.index') }}">Voltar</a></li>
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
                        <h4>Dados do Encarregado</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                    <th>Nº Bilhete</th>
                                    <th>Estado Cívil</th>
                                    <th>Gênero</th>
                                    <th>Data de Nascimento</th>
                                    <th>Profissão</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $encarregado->nome_completo }}</td>
                                    <td>{{ $encarregado->telefone }}</td>
                                    <td>{{ $encarregado->numero_bilhete }}</td>
                                    <td>{{ $encarregado->estado_civil }}</td>
                                    <td>{{ $encarregado->genero }}</td>
                                    <td>{{ $encarregado->data_nascimento }}</td>
                                    <td>{{ $encarregado->profissao }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('encarregados.adicionar-estudantes-encarregado', $encarregado->id) }}" class="btn btn-primary">Associar Mais estudantes</a>    
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Dados dos Educandos</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped ">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Nome</th>
                                    <th>Gênero</th>
                                    <th>Turma</th>
                                    <th>Classe</th>
                                    <th>Curso</th>
                                    <th>Turno</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($encarregado->educandos as $item)
                                    <tr>
                                        <td>{{ $item->estudante->numero_processo }}</td>
                                        <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome_completo }}</a></td>
                                        <td>{{ $item->estudante->genero }}</td>
                                        @if ($item->estudante->turma->turma)
                                        <td>{{ $item->estudante->turma->turma->turma ?? "" }}</td>
                                        <td>{{ $item->estudante->turma->turma->classe->classes ?? "" }}</td>
                                        <td>{{ $item->estudante->turma->turma->curso->curso ?? "" }}</td>
                                        <td>{{ $item->estudante->turma->turma->turno->turno ?? "" }}</td>
                                        @else
                                        <td colspan="4" class="bg-warning">Estudante sem turma no momento</td>
                                        @endif
                                        <td>
                                            @if (Auth::user()->can('delete: encarregado'))
                                            <a href="#" data-id="{{ $item->id }}" class="btn btn-danger delete-record"><i class="fa fa-trash"></i> Excluir</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            </div>
        </div>
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
                        url: `{{ route('encarregados.remover-estudantes-encarregado', ':id') }}`.replace(':id', recordId)
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

</script>
@endsection
