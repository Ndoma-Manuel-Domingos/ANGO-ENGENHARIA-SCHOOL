@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Configuracão de Horários</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('pedagogicos.lancamento-nas-turmas') }}">Painel Pedagógico</a></li>
                    <li class="breadcrumb-item active">Horários</li>
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
                        <h6>Cadastrar novos Horários</h6>
                    </div>
                    <div class="card-body">
                        <form class="row">
                            <div class="col-md-3 col-12 mb-3">
                                <label for="turmas_id" class="form-label">Turmas</label>
                                <select name="turmas_id" id="turmas_id" class="custom-select editar_turmas_id turmas_id select2" style="width: 100%">
                                    <option value="">Selecione Turma</option>
                                    @if ($turmas)
                                    @foreach ($turmas as $item)
                                    <option value="{{ $item->id }}">{{ $item->turma }}</option>
                                    @endforeach
                                    @else
                                    <option value="">Sem Nenhum Turma cadastrado</option>
                                    @endif
                                </select>
                                <span class="text-danger error-text turmas_id_error"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="disciplinas_horario" class="form-label">Disciplinas</label>
                                <select name="disciplinas_id" id="disciplinas_horario" class="custom-select custom-select disciplinas_horario">
                                    <option value="">Disciplinas</option>
                                </select>
                            </div>


                            <div class="col-md-6 mb-3 col-12">
                                <label for="professores_id" class="form-label">Professores</label>
                                <select name="professores_id" id="professores_id" class="custom-select editar_professores_id professores_id select2" style="width: 100%">
                                    <option value="">Selecione Prefossores</option>
                                    @if ($professores)
                                    @foreach ($professores as $professor)
                                    <option value="{{ $professor->id }}">{{ $professor->nome }} {{ $professor->sobre_nome }}</option>
                                    @endforeach
                                    @else
                                    <option value="">Sem Nenhum Professor cadastrado</option>
                                    @endif
                                </select>
                                <span class="text-danger error-text professores_id_error"></span>
                            </div>


                            <div class="col-md-3 mb-3 col-12">
                                <label for="hora_inicio" class="form-label">Hora Inicio</label>
                                <input type="time" name="hora_inicio" id="hora_inicio" class="hora_inicio editar_hora_inicio form-control" placeholder="Informe a Hora do Incicio">
                            </div>

                            <div class="col-md-3 mb-3 col-12">
                                <label for="hora_final" class="form-label">Hora Final</label>
                                <input type="time" name="hora_final" id="hora_final" class="hora_final editar_hora_final form-control" placeholder="Informe a Hora do Final">
                            </div>

                            <div class="col-md-3 mb-3 col-12">
                                <label for="tempo_disciplina" class="form-label">Tempo</label>
                                <select name="tempo_disciplina" id="tempo_disciplina" class="custom-select tempo_disciplina editar_tempo_disciplina select2" aria-label="Default select example" style="width: 100%">
                                    <option>Selecione</option>
                                    @foreach ($tempos as $item)
                                    <option value="{{ $item->id }}">{{ $item->nome }}º Tempo</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 col-12">
                                <label for="dias_semanas_horario" class="form-label">Dias de Semanas</label>
                                <select name="dias_semanas_horario" id="dias_semanas_horario" class="custom-select editar_dias_semanas_horario dias_semanas_horario select2" style="width: 100%">
                                    @foreach ($semanas as $item)
                                    <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text dias_semanas_horario_error"></span>
                            </div>

                            <input type="hidden" class="editar_horario_id" value="">

                        </form>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary cadastrar_horario_turmas">Salvar</button>
                        <button type="submit" id="btn_actualizar_horario" style="display: none" class="btn btn-success actualizar_horario_turmas">Actualizar</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body table-responsive">
                        <table style="width: 100%" class="table table-bordered table-striped" id="carregarTabelaTurmas">
                            <thead>
                                <th>Turma</th>
                                <th>Professor</th>
                                <th>Tempo</th>
                                <th>Hora inicio</th>
                                <th>Hora Final</th>
                                <th>Dia Semana</th>
                                <th>Disciplinas</th>
                                <th>Acções</th>
                            </thead>
                            <tbody>
                                @foreach ($horarios as $item)
                                <tr>
                                    <td><a href="{{ route('web.apresentar-turma-informacoes', Crypt::encrypt($item->turma->id)) }}">{{ $item->turma->turma }}</a></td>
                                    <td>{{ $item->professor->nome ?? "" }} {{ $item->professor->sobre_nome ?? "" }}</td>
                                    <td>{{ $item->tempo->nome }} º Tempo</td>
                                    <td>{{ $item->hora_inicio }}</td>
                                    <td>{{ $item->hora_final }}</td>
                                    <td>{{ $item->semana->nome }}</td>
                                    <td>{{ $item->disciplina->disciplina }}</td>
                                    <td>
                                        <button type="button" title="Excluir O horário do professor" value="{{ $item->id }}" class="remover_disciplina_horario_id btn btn-danger"><i class="fa fa-times"></i></button>
                                        <button type="button" title="Editar O horário do professor" value="{{ $item->id }}" class="editar_disciplina_horario_id btn btn-success"><i class="fa fa-edit"></i></button>
                                        <a href="{{ route('web.turmas-configuracao', Crypt::encrypt($item->turmas_id)) }}" title="Configurar Turma" class="btn btn-info"><i class="fa fa-cog"></i></a>
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
<!-- /.content -->
@endsection

@section('scripts')

<script>
    $(function() {

        // remover disciplina do horario da turma
        $(document).on('click', '.remover_disciplina_horario_id', function(e) {
            e.preventDefault();

            var novo_id = $(this).val();

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
                        , url: "remover-horario-turma/" + novo_id
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

        // editar
        $(document).on('click', '.editar_disciplina_horario_id', function(e) {
            e.preventDefault();
            var novo_id = $(this).val();
            $("#btn_actualizar_horario").css({
                "display": "inline-block"
            })

            $.ajax({
                type: "GET"
                , url: "editar-horario-turma/" + novo_id
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');

                    $('.editar_turmas_id').val(response.horario.turmas_id).trigger('change');
                    $('.editar_professores_id').val(response.horario.professor_id).trigger('change');
                    $('.editar_disciplinas_horario').val(response.horario.disciplinas_id).trigger('change');
                    $('.editar_hora_inicio').val(response.horario.hora_inicio);
                    $('.editar_hora_final').val(response.horario.hora_final);
                    $('.editar_tempo_disciplina').val(response.horario.tempos_id).trigger('change');
                    $('.editar_dias_semanas_horario').val(response.horario.semanas_id).trigger('change');
                    $('.editar_horario_id').val(response.horario.id);
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });

        // cadastrar horario
        $(document).on('click', '.actualizar_horario_turmas', function(e) {
            e.preventDefault();
            var data = {
                'professores_id': $('.editar_professores_id').val(), 
                'hora_inicio': $('.editar_hora_inicio').val(), 
                'turmas_id': $('.editar_turmas_id').val(), 
                'hora_final': $('.editar_hora_final').val(), 
                'tempo_disciplina': $('.editar_tempo_disciplina').val(), 
                'dias_semanas': $('.editar_dias_semanas_horario').val(), 
                'disciplinas_horario': $('.editar_disciplinas_horario').val(), 
                'editar_horario_id': $('.editar_horario_id').val(), 
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.editar-horario-turmas-update') }}"
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

        // cadastrar horario
        $(document).on('click', '.cadastrar_horario_turmas', function(e) {
            e.preventDefault();

            var data = {
                'turma_select_id': $('.turmas_id').val()
                , 'professores_id': $('.professores_id').val()
                , 'hora_inicio': $('.hora_inicio').val()
                , 'hora_final': $('.hora_final').val()
                , 'tempo_disciplina': $('.tempo_disciplina').val()
                , 'dias_semanas_horario': $('.dias_semanas_horario').val()
                , 'disciplinas_horario': $('.disciplinas_horario').val()
            , }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.cadastrar-horario-turmas') }}"
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

        // mudança de stado do select
        $(document).on('change', '.turmas_id', function(e) {

            e.preventDefault();
            var turma = $('.turmas_id').val();

            $.ajax({
                type: "GET"
                , url: "../relatorios/carregar-turmas-pautas/" + turma
                , dataType: "json"
                , beforeSend: function() {
                    // Você pode adicionar um loader aqui, se necessário
                    progressBeforeSend();
                }
                , success: function(response) {

                    Swal.close();
                    // Exibe uma mensagem de sucesso
                    showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');

                    $('#disciplinas_horario').html("");
                    for (let index = 0; index < response.disciplinasTurma.length; index++) {
                        $('#disciplinas_horario').append('<option value="' + response.disciplinasTurma[index].id + '">' + response.disciplinasTurma[index].disciplina + '</option>');
                    }
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });
        });
        // end mudanca do estado
    });

</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Definir uma hora padrão fixa
        const horaInicioInput = document.getElementById('hora_inicio');
        const horaFinalInput = document.getElementById('hora_final');
        horaInicioInput.value = '08:00'; // Exemplo de hora padrão
        horaFinalInput.value = '08:45'; // Exemplo de hora padrão
    });

</script>

<script>
    $(function() {
        $("#carregarTabelaTurmas").DataTable({
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
