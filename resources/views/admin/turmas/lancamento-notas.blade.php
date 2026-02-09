@extends('layouts.escolas')

@section('content')
<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">LANÇAMENTO DE NOTAS</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pedagogicos.lancamento-nas-turmas') }}">Voltas</a></li>
                        <li class="breadcrumb-item active">Boletim</li>
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
                    <div class="card">
                        <div class="card-body">
                            <form action="">
                                <div class="row">
                                    <div class="form-group col-md-3 col-12">
                                        <label for="ano_lectivos_id" class="form-label">Ano Lectivo</label>
                                        @if ($ano_lectivos)
                                        <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control ano_lectivos_id select2" style="width: 100%">
                                            <option value="">Escolher Ano Lectivos</option>
                                            @foreach ($ano_lectivos as $item)
                                            <option value="{{ $item->id }}">{{ $item->ano }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text ano_lectivos_id_error"></span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label for="turmas_id" class="form-label">Turmas</label>
                                        @if ($turmas)
                                        <select name="turmas_id" id="turmas_id" class="form-control turmas_id select2" style="width: 100%">
                                            <option value="">Escolher Turma</option>
                                            @foreach ($turmas as $item)
                                            <option value="{{ $item->id }}">{{ $item->turma }}</option>
                                            @endforeach
                                        </select>

                                        <input type="hidden" value="{{ $idTurmaSelecionada }}" class="input_value_turma_selecionada">
                                        <span class="text-danger error-text turmas_id_error"></span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label for="list-disciplinas-mini-pauta-2" class="form-label">Disciplinas</label>
                                        <select name="disciplinas_id" id="list-disciplinas-mini-pauta-2" class="form-control disciplinas_id select2" style="width: 100%">
                                            <option value="">Escolher Disciplinas</option>
                                        </select>
                                        <span class="text-danger error-text disciplinas_id_error"></span>
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label for="trimestre_id" class="form-label">Trimestre</label>
                                        @if ($trimestres)
                                        <select name="trimestre_id" id="trimestre_id" class="form-control trimestre_id select2" style="width: 100%">
                                            <option value="">Escolher Trimestre</option>
                                            @foreach ($trimestres as $item)
                                            <option value="{{ $item->id }}">{{ $item->trimestre }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text trimestre_id_error"></span>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="pesquisarMiniPauta"><i class="fas fa-search"></i> Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-sm-12 col-md-12">
                                <ul class="fs-5 d-flex p-0">
                                    <li><strong>Turma: </strong> <span class="span_turma">desc</span>. &nbsp; </li>
                                    <li><strong>Classe: </strong> <span class="span_classe">desc</span>. &nbsp; </li>
                                    <li><strong>Turno: </strong> <span class="span_turno">desc</span>. &nbsp; </li>
                                    <li><strong>Sala Nº: </strong> <span class="span_sala">desc</span>. &nbsp; </li>
                                    <li><strong>Disciplina: </strong><span class="span_disciplina">desc</span>. &nbsp; </li>
                                    <li><strong>Período: </strong> <span class="span_trimestre">desc</span>. &nbsp; </li>
                                    <li><strong>Ano Lectivo </strong> <span class="span_ano_lectivo">desc</span>. </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                            <table style="width: 100%" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Nome Completo</th>
                                        <th>Sexo</th>
                                        <th>P1</th>
                                        <th>P2</th>
                                        <th>P3</th>
                                        <th>P4</th>
                                        <th>Média</th>
                                        <th>Obs</th>
                                        <th>Exame</th>
                                        <th>Resultado</th>
                                        <th>Recurso</th>
                                        <th>Exame Especial</th>
                                        <th>NF</th>
                                        <th>Estado</th>
                                        <th>Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- --}}
                                </tbody>
                            </table>
                            @else
                            <table style="width: 100%" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Nome</th>
                                        <th>Sexo</th>

                                        <th>EN</th>
                                        <th>PT</th>
                                        <th>PAP</th>
                                        <th>NR</th>

                                        <th>MAC</th>
                                        <th>NPT</th>
                                        <th>MT</th>
                                        <th>OBS</th>

                                        <th>Acções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- --}}
                                </tbody>
                            </table>
                            @endif
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

</div>
@endsection

@section('scripts')
<script>
    function renderInput(name, value, id) {
        return `<td><input name="${name}" disabled style="width: 50px" class="form-control" value="${value}" data-id="${id}" /></td>`;
    }

    function renderButton(id, texto, icone, cor) {
        return `
            <td>
                <button type="button" disabled title="Lançar notas" data-id="${id}" class="atribuir_notas_id_estudantes mx-1 btn ${cor}">
                    ${texto} <i class="fa ${icone}"></i>
                </button>

                <button type="button" title="Activar Lançamento de notas" data-id="${id}" class="activar_atribuicao_notas_id mx-1 btn btn-secondary">
                    Activar <i class="fas fa-check"></i>
                </button>
            </td>
        `;
    }

    $(document).on('click', '.activar_atribuicao_notas_id', function() {
        const id = $(this).data('id');

        // Procura o botão correspondente pelo mesmo data-id e ativa-o
        $(`.atribuir_notas_id_estudantes[data-id="${id}"]`).prop('disabled', false);
        $(`.activar_atribuicao_notas_id[data-id="${id}"]`).prop('disabled', true);
    });

    function gerarLinha(estudante, tipo, response, soma, resultado, texto, icone, cor) {
        
        // console.log(response.trimestre.trimestre)
        // console.log(response.turma.turma)
        // return;    
        var genero;

        if (estudante.genero == "Masculino") {
            genero = 'M';
        } else {
            genero = 'F';
        }

        const id = estudante.id;
        let linha = `
            <tr>
                <td>${id}</td>
                <td>${estudante.estudante.nome} ${estudante.estudante.sobre_nome}</td>
                <td>${genero}</td>
        `;
        
        let camposComuns = [];
  
        if(response.turma.curso.tipo === "Outros") {
            if(response.trimestre.trimestre === "IIIª Trimestre") {
                camposComuns = ['ne', 'pt', 'pap', 'nr', 'mac', 'npt'];
            }else {
                camposComuns = ['mac', 'npt'];
            }
        }else if(response.turma.curso.tipo === "Técnico" || response.turma.curso.tipo === "Punível") {
            if(response.trimestre.trimestre === "IIIª Trimestre") {
                camposComuns = ['ne', 'pt', 'pap', 'nr', 'mac', 'npt', 'npp'];
            }else {
                camposComuns = ['mac', 'npt', 'npp'];
            }
        } 
        
        camposComuns.forEach(campo => linha += renderInput(campo, estudante[campo], id));
        
        linha += `<td>${soma}</td>`;
        linha += `<td>${resultado}</td>`;
        linha += renderButton(id, texto, icone, cor);
        linha += `</tr>`;

        return linha;
    }

    $(function() {

        $(document).on('click', '.atribuir_notas_id_estudantes', function(e) {
            e.preventDefault();

            var notas_id = $(this).data('id');

            var data = {
                notas_id: notas_id
                , ne: $('input[name="ne"][data-id="' + notas_id + '"]').val()
                , pt: $('input[name="pt"][data-id="' + notas_id + '"]').val()
                , pap: $('input[name="pap"][data-id="' + notas_id + '"]').val()
                , mac: $('input[name="mac"][data-id="' + notas_id + '"]').val()
                , npt: $('input[name="npt"][data-id="' + notas_id + '"]').val()
                , npp: $('input[name="npp"][data-id="' + notas_id + '"]').val()
                , mt: $('input[name="mt"][data-id="' + notas_id + '"]').val()

                , p1: $('input[name="p1"][data-id="' + notas_id + '"]').val()
                , p2: $('input[name="p2"][data-id="' + notas_id + '"]').val()
                , p3: $('input[name="p3"][data-id="' + notas_id + '"]').val()
                , p4: $('input[name="p4"][data-id="' + notas_id + '"]').val()
                , exame_1_especial: $('input[name="exame_1_especial"][data-id="' + notas_id + '"]').val()
                , exame_especial: $('input[name="exame_especial"][data-id="' + notas_id + '"]').val()
                , nr: $('input[name="nr"][data-id="' + notas_id + '"]').val()
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.finanlizar-lancamento-notas') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    carregamentoAutomatica();
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            , });

        });

        $(document).on('click', '.activar_atribuicao_notas_id', function(e) {

            var notas_id = $(this).data('id');

            $('input[name="pt"][data-id="' + notas_id + '"]').prop('disabled', false);
            $('input[name="ne"][data-id="' + notas_id + '"]').prop('disabled', false);
            $('input[name="nr"][data-id="' + notas_id + '"]').prop('disabled', false);
            $('input[name="pap"][data-id="' + notas_id + '"]').prop('disabled', false);
            $('input[name="mac"][data-id="' + notas_id + '"]').prop('disabled', false);
            $('input[name="npt"][data-id="' + notas_id + '"]').prop('disabled', false);
            $('input[name="npp"][data-id="' + notas_id + '"]').prop('disabled', false);
            $('input[name="mt"][data-id="' + notas_id + '"]').prop('disabled', false);

            $('input[name="p1"][data-id="' + notas_id + '"]').prop('disabled', false);
            $('input[name="p2"][data-id="' + notas_id + '"]').prop('disabled', false);
            $('input[name="p3"][data-id="' + notas_id + '"]').prop('disabled', false);
            $('input[name="p4"][data-id="' + notas_id + '"]').prop('disabled', false);
            $('input[name="exame_1_especial"][data-id="' + notas_id + '"]').prop('disabled', false);
            $('input[name="exame_especial"][data-id="' + notas_id + '"]').prop('disabled', false);

        });

        // function extraido para verificar a pesquisa
        function carregamentoAutomatica() {

            var data = {
                'turmas_id': $('.turmas_id').val()
                , 'disciplinas_id': $('.disciplinas_id').val()
                , 'trimestre_id': $('.trimestre_id').val()
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST"
                , url: "{{ route('web.pesquisa-turmas-mini-pauta2') }}"
                , data: data
                , dataType: "json"
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    $('.span_turma').html(response.turma.turma);
                    $('.span_classe').html(response.classe.classes);
                    $('.span_turno').html(response.turno.turno);
                    $('.span_sala').html(response.sala.salas);
                    $('.span_disciplina').html(response.disciplina.disciplina);
                    $('.span_trimestre').html(response.trimestre.trimestre);
                    $('.span_ano_lectivo').html(response.anoLectivo.ano);

                    if (response.escola.ensino.nome == "Ensino Superior") {
                        $('tbody').html("");
                        for (let index = 0; index < response.resultados.length; index++) {

                            var genero;

                            if (response.resultados[index].estudante.genero == 'Masculino') {
                                genero = 'M';
                            } else {
                                genero = 'F';
                            }

                            if (response.resultados[index].conf_pro == 'sim') {
                                texto = "bloqueado";
                                icone = "fa-lock";
                                cor = "btn-danger";
                            } else {
                                texto = "Salvar";
                                icone = "fa-edit";
                                cor = "btn-success";
                            }

                            $('tbody').append(`<tr>
                                <td>${response.resultados[index].id}</td>
                                <td>${response.resultados[index].estudante.nome} ${response.resultados[index].estudante.sobre_nome}</td>
                                <td>${genero}</td>
                                <td><input name="p1" disabled style="width: 50px" class="form-control" value="${response.resultados[index].p1}" data-id="${response.resultados[index].id}" /></td>
                                <td><input name="p2" disabled style="width: 50px" class="form-control" value="${response.resultados[index].p2}" data-id="${response.resultados[index].id}" /></td>
                                <td><input name="p3" disabled style="width: 50px" class="form-control" value="${response.resultados[index].p3}" data-id="${response.resultados[index].id}" /></td>
                                <td><input name="p4" disabled style="width: 50px" class="form-control" value="${response.resultados[index].p4}" data-id="${response.resultados[index].id}" /></td>
                                <td><input name="med" disabled style="width: 50px" class="form-control" value="${response.resultados[index].med}" data-id="${response.resultados[index].id}" /></td>
                                <td><input name="obs1" disabled style="width: 50px" class="form-control" value="${response.resultados[index].obs1}" data-id="${response.resultados[index].id}" /></td>
                                <td><input name="exame_1_especial" disabled style="width: 50px" class="form-control" value="${response.resultados[index].exame_1_especial}" data-id="${response.resultados[index].id}" /></td>
                                <td><input name="obs2" disabled style="width: 50px" class="form-control" value="${response.resultados[index].obs2}" data-id="${response.resultados[index].id}" /></td>
                                <td><input name="nr" disabled style="width: 50px" class="form-control" value="${response.resultados[index].nr}" data-id="${response.resultados[index].id}" /></td>
                                <td><input name="exame_especial" disabled style="width: 50px" class="form-control" value="${response.resultados[index].exame_especial}" data-id="${response.resultados[index].id}" /></td>
                                <td><input name="resultado_final" disabled style="width: 50px" class="form-control" value="${response.resultados[index].resultado_final}" data-id="${response.resultados[index].id}" /></td>
                                <td><input name="obs3" style="width: 50px" disabled class="form-control" value="${response.resultados[index].obs3}" data-id="${response.resultados[index].id}" /></td>
                                <td><button type="button" title="Lançar notas" data-id="${response.resultados[index].id}" value="${response.resultados[index].id}" class="atribuir_notas_id_estudantes btn ${cor} ">${texto} <i class="fa ${icone}"></i></button></td>
                                <td><button type="button" title="Activar Lançamento de notas" data-id="${response.resultados[index].id}" value="${response.resultados[index].id}" class="activar_atribuicao_notas_id btn btn-secondary">Activar <i class="fas fa-check"></i></button></td>
                            </tr>`);
                        }
                    } else {
                        $('thead').html("");
                        let headerHTML = `
                            <tr>
                                <th>Nº</th>
                                <th>Nome</th>
                                <th>Sexo</th>`;
                                
                        if("IIIª Trimestre" == response.trimestre.trimestre) {
                            headerHTML += `
                            <th>EN</th>
                            <th>PT</th>
                            <th>PAP</th>
                            <th>NR</th>`;
                        }
                        
                        if (response.turma.curso.tipo === "Técnico") {
                            headerHTML += `
                                <th style="text-align: center;">MAC</th>
                                <th style="text-align: center;">NPP</th>
                                <th style="text-align: center;">NPT</th>`;
                        }else if (response.turma.curso.tipo === "Punível") {
                            headerHTML += `
                                <th style="text-align: center;">P1</th>
                                <th style="text-align: center;">P2</th>
                                <th style="text-align: center;">PT</th>`;
                        }else if (response.turma.curso.tipo === "Outros") {
                            headerHTML += `
                                <th style="text-align: center;">MAC</th>
                                <th style="text-align: center;">NPT</th>`;
                        }
                        
                        headerHTML += `
                            <th>MT</th>
                            <th>OBS</th>
                            <th>Acções</th>
                        </tr>
                        `;
                        
                        $('thead').append(headerHTML);
                        $('tbody').html("");

                        for (let index = 0; index < response.resultados.length; index++) {

                            var resultado;
                            var genero;
                            var soma;

                            if (response.resultados[index].estudante) {

                                if (response.resultados[index].estudante.genero == 'Masculino') {
                                    genero = 'M';
                                } else {
                                    genero = 'F';
                                }

                                soma = response.resultados[index].mt;
                                resultado = response.resultados[index].obs;

                                if (response.resultados[index].conf_pro == 'sim') {
                                    texto = "bloqueado";
                                    icone = "fa-lock";
                                    cor = "btn-danger";
                                } else {
                                    texto = "Salvar";
                                    icone = "fa-edit";
                                    cor = "btn-success";
                                }
                                $('tbody').append(
                                    gerarLinha(response.resultados[index], response.escola.tipo_avaliacao, response, soma, resultado, texto, icone, cor)
                                );
                            }
                        }
                    }
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            , });
        }

        // pesquisar mini pautas
        $(document).on('click', '#pesquisarMiniPauta', function(e) {
            e.preventDefault();
            carregamentoAutomatica();
        });
        // end pesquisar mini pautas

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

        // mudanca de estado do select
        $(".turmas_id").change(function(e) {
            e.preventDefault();
            var turma = $('.turmas_id').val();

            var selecionadaTurma = $('.input_value_turma_selecionada').val();

            if (selecionadaTurma != "") {
                $.ajax({
                    type: "GET"
                    , url: `../carregar-disciplinas-turma/${turma}`
                    , dataType: "json"
                    , beforeSend: function() {
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        $('#list-disciplinas-mini-pauta-2').html("");
                        for (let index = 0; index < response.disciplinasTurma.length; index++) {
                            $('#list-disciplinas-mini-pauta-2').append(`<option value="${response.disciplinasTurma[index].id}">${response.disciplinasTurma[index].disciplina}</option>`);
                        }
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            } else {
                $.ajax({
                    type: "GET"
                    , url: `carregar-disciplinas-turma/${turma}`
                    , dataType: "json"
                    , beforeSend: function() {
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        Swal.close();
                        $('#list-disciplinas-mini-pauta-2').html("");
                        for (let index = 0; index < response.disciplinasTurma.length; index++) {
                            $('#list-disciplinas-mini-pauta-2').append(`<option value="${response.disciplinasTurma[index].id}">${response.disciplinasTurma[index].disciplina}</option>`);
                        }
                    }
                    , error: function(xhr) {
                        Swal.close();
                        showMessage('Erro!', xhr.responseJSON.message, 'error');
                    }
                , });
            }
        });
        // end mudanca de estado do select
    });

</script>
@endsection
