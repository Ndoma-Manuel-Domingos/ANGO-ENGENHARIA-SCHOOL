@extends('layouts.escolas')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Adicionar Estudante na Turma</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="">Voltar</a></li>
                        <li class="breadcrumb-item active">Turmas</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12 col-md-12">
                    <form action="{{ route('web.adicionar-estudantes-turma-concluir') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-body row">

                                <div class="form-group col-12 col-md-4 mb-2">
                                    <label for="" class="form-label">Estudante</label>
                                    <select name="estudante_id" class="form-control select2" style="width: 100%" required>
                                        <option value="{{ $estudante->id }}">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</option>
                                    </select>
                                    @error('estudante_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-md-4 mb-2">
                                    <label for="ano_lectivos_id" class="form-label">Ano Lectivos</label>
                                    <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control select2" style="width: 100%" required>
                                        <option value="">Selecione Ano Lectivo</option>
                                        @foreach ($anos_lectivos as $item)
                                        <option value="{{ $item->id }}">{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivos_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-md-4 mb-2">
                                    <label for="turmas_id" class="form-label">Turmas</label>
                                    <select name="turmas_id" id="turmas_id" class="form-control select2" style="width: 100%" required>
                                        <option value="">Selecione a turmas</option>
                                        @foreach ($turmas as $item)
                                        <option value="{{ $item->id }}">{{ $item->turma }}</option>
                                        @endforeach
                                    </select>
                                    @error('turmas_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <input type="hidden" name="matricula_id" value="{{ $matricula->id }}">

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection

@section('scripts')
<script>
    $("#ano_lectivos_id").change(function() {
        let id = $(this).val(); // Pegando o valor selecionado no campo "ano_lectivos_id"
        $.ajax({
            url: `../../carregar-todas-turmas-anolectivos-escolas/${id}`, // URL para obter os dados
            type: 'GET', // Método HTTP
            beforeSend: function() {
                progressBeforeSend();
            }
            , success: function(data) {

                Swal.close();
                // Exibe uma mensagem de sucesso
                showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');

                // Limpa o campo #turmas_id e insere os dados recebidos
                $("#turmas_id").html("");
                $("#turmas_id").html(data);

            }
            , error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }
        , });
    });

</script>
@endsection
