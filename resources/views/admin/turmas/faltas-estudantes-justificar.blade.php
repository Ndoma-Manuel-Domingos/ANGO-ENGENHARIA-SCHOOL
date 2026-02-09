@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Faltas Estudantes</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Faltas</a></li>
                    <li class="breadcrumb-item active">Faltas</li>
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
                    <h5><i class="fas fa-info"></i> Visualizar lista de presença dos estudantes</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card p-2">
                    <form action="{{ route('web.faltas-turmas-estudantes-post') }}" method="post" class="row">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label for="turma">Turma</label>
                                    <select name="turma" id="turma" class="form-control turma">
                                        @if (count($turmas) != 0)
                                        @foreach ($turmas as $item)
                                        <option value="{{ $item->id }}">{{ $item->turma }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span class="text-danger error-text turma_error"></span>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="list_professores">Funcionários</label>
                                    <select name="funcionario" id="list_professores" class="form-control funcionario">
                                    </select>
                                    <span class="text-danger error-text funcionario_error"></span>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="list_disciplinas">Disciplinas</label>
                                    <select name="disciplina" id="list_disciplinas" class="form-control disciplina">
                                    </select>
                                    <span class="text-danger error-text disciplina_error"></span>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="meses">Mês</label>
                                    <select name="meses" id="meses" class="form-select meses">
                                        <option value="Presente">Meses</option>
                                        <option value="January">Janeiro</option>
                                        <option value="February">Fevereiro</option>
                                        <option value="March">Março</option>
                                        <option value="April">Abril</option>
                                        <option value="May">Maio</option>
                                        <option value="June">Junho</option>
                                        <option value="July">Julho</option>
                                        <option value="August">Agosto</option>
                                        <option value="September">Setembro</option>
                                        <option value="October">Outumbro</option>
                                        <option value="November">Novembro</option>
                                        <option value="December">Dezembro</option>
                                    </select>
                                    <span class="text-danger error-text meses_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-start gerar_lista_presenca">Gerar Lista</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="carregarTabelaTurmas" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Estudante</th>
                                    <th>Status</th>
                                    <th>acções</th>
                                </tr>
                            </thead>
                            <tbody id="carregar_table">
                            </tbody>
                        </table>
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

        $(document).on('click', '.gerar_lista_presenca', function(e) {
            e.preventDefault();

            var data = {
                'funcionario': $('.funcionario').val()
                , 'turma': $('.turma').val()
                , 'disciplina': $('.disciplina').val()
                , 'meses': $('.meses').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "GET"
                , url: "{{ route('web.faltas-turmas-estudantes-justifcar-post') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();

                    $('#carregar_table').html("");
                    for (let index = 0; index < response.resultado.length; index++) {
                        $('#carregar_table').append('<tr>\
                    <td>' + response.resultado[index].id + '</td>\
                    <td>' + response.resultado[index].nome + ' ' + response.resultado[index].sobre_nome + '</td>\
                    <td>' + response.resultado[index].staus + '</td>\
                    <td>\
                        <a href="#" id="" title="Actualizar o novo status" class="activar_novo_Status btn-success btn">Actualizar</a>\
                    </td>\
                  </tr>');
                    }
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });

        });

        $(document).on('change', '.turma', function(e) {
            e.preventDefault();
            var id = $(this).val();
            $('.id_turmas_add').val(id);
            $.ajax({
                type: "GET"
                , url: "carregar-disciplinas-turma/" + id
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('#list_disciplinas').html("");
                    $('#list_professores').html("");
                    for (let index = 0; index < response.disciplinasTurma.length; index++) {
                        $('#list_disciplinas').append('<option value="' + response.disciplinasTurma[index].id + '">' + response.disciplinasTurma[index].disciplina + '</option>');
                    }

                    for (let index = 0; index < response.resultado.length; index++) {
                        $('#list_professores').append('<option value="' + response.resultado[index].idFuncionario + '"> ' + response.resultado[index].nome + ' ' + response.resultado[index].sobre_nome + '</option>');
                    }
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });

        });

    });

</script>

@endsection
