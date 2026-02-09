@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Lançamento de Notas na turma: <span class="text-secondary">{{ $turma->turma }}</span> </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route("prof.turmas") }}">Voltar</a></li>
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
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('prof.informacao-professores-lancamento-nota', ['professor_id' =>  Crypt::encrypt($professor->id), 'turma_id' =>  Crypt::encrypt($turma->id)]) }}" method="get" class="row" id="formulario">
                                @csrf
                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Disciplinas</label>
                                    <select name="disciplina_id" id="" class="form-control select2">
                                        <option value="">Selecione</option>
                                        @foreach ($disciplinas as $item)
                                        <option value="{{ Crypt::encrypt($item->disciplina->id) }}">{{ $item->disciplina->disciplina }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Trimestre</label>
                                    <select name="trimestre_id" id="" class="form-control select2">
                                        <option value="">Selecione</option>
                                        @foreach ($lista_trimestres as $item)
                                        <option value="{{ Crypt::encrypt($item->id) }}">{{ $item->trimestre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" class="turma_id" name="turma_id" value="{{ Crypt::encrypt($turma->id ?? '') }}">
                                <input type="hidden" class="professor_id" name="professor_id" value="{{ Crypt::encrypt($professor_id->id ?? '') }}">
                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="submit" form="formulario" class="btn btn-primary">Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>

            @if ($notas != null)
            <div class="row mt-3">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            Turma: <span class="text-danger">{{ $turma->turma }}</span>.
                            Classe: <span class="text-danger">{{ $classe->classes }}</span>.
                            Turno: <span class="text-danger">{{ $turno->turno }}</span>.
                            Sala Nº: <span class="text-danger">{{ $sala->salas }}</span>.
                            Disciplina: <span class="text-danger">{{ $disciplina->disciplina }}</span>.
                            Período: <span class="text-danger">{{ $trimestre->trimestre }}</span>.
                            Ano Lectivo: <span class="text-danger">{{ $ano->ano }}</span>.

                            <a href="{{ route('prof.imprimir-professores-lancamento-nota', ['turma_id' => Crypt::encrypt($turma->id), 'disciplina_id' => Crypt::encrypt($disciplina->id), 'trimestre_id' => Crypt::encrypt($trimestre->id)]) }}" class="btn btn-primary text-white float-right" target="blink"> Imprimir Notas</a>
                        </div>

                        @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                        <div class="card-body">
                            <table style="width: 100%" class="table table-bordered">
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
                                    @foreach ($notas as $key => $item1)
                                    <tr>
                                        <td> {{ $key + 1 }}</td>
                                        <td> {{ $item1->estudante->nome }} {{ $item1->estudante->sobre_nome }}</td>
                                        <td> {{ $item1->estudante->genero }}</td>

                                        <td><input name="p1" disabled style="width: 50px" class="form-control" value="{{ $item1->p1 }}" data-id="{{ $item2->id }}" /></td>
                                        <td><input name="p2" disabled style="width: 50px" class="form-control" value="{{ $item1->p2 }}" data-id="{{ $item2->id }}" /></td>
                                        <td><input name="p3" disabled style="width: 50px" class="form-control" value="{{ $item1->p3 }}" data-id="{{ $item2->id }}" /></td>
                                        <td><input name="p4" disabled style="width: 50px" class="form-control" value="{{ $item1->p4 }}" data-id="{{ $item2->id }}" /></td>
                                        <td><input name="med" disabled style="width: 50px" class="form-control" value="{{ $item1->med }}" data-id="{{ $item2->id }}" /></td>
                                        <td><input name="obs1" disabled style="width: 50px" class="form-control" value="{{ $item1->obs1 }}" data-id="{{ $item2->id }}" /></td>
                                        <td><input name="exame_1_especial" disabled style="width: 50px" class="form-control" value="{{ $item1->exame_1_especial }}" data-id="{{ $item2->id }}" /></td>
                                        <td><input name="obs2" disabled style="width: 50px" class="form-control" value="{{ $item1->obs2 }}" data-id="{{ $item2->id }}" /></td>
                                        <td><input name="nr" disabled style="width: 50px" class="form-control" value="{{ $item1->nr }}" data-id="{{ $item2->id }}" /></td>
                                        <td><input name="exame_especial" disabled style="width: 50px" class="form-control" value="{{ $item1->exame_especial }}" data-id="{{ $item2->id }}" /></td>
                                        <td><input name="resultado_final" disabled style="width: 50px" class="form-control" value="{{ $item1->resultado_final }}" data-id="{{ $item2->id }}" /></td>
                                        <td><input name="obs3" style="width: 50px" disabled class="form-control" value="{{ $item1->obs3 }}" data-id="{{ $item2->id }}" /></td>

                                        <td><button type="button" title="Lançar notas" data-id="{{ $item1->id }}" class="atribuir_notas_id_estudantes btn-success btn">Salvar <i class="fas fa-save"></i></button></td>
                                        <td><button type="button" title="Activar Lançamento de notas" data-id="{{ $item1->id }}" class="activar_atribuicao_notas_id btn-secondary btn">Activar <i class="fas fa-check"></i></button></td>

                                        @if ($item1->obs3 == 'Apto')
                                        <td class="text-success text-uppercase">{{ $item1->obs3 }}</td>
                                        @else
                                        <td class="text-danger text-uppercase">{{ $item1->obs3 }}</td>
                                        @endif

                                        {{-- <td>
                                        @if (Auth::user()->can('create: nota'))
                                        <a href="{{ route('prof.professores-lancamento-nota-estudante', ['professor_id' => Crypt::encrypt($professor->id), 'nota_id' => Crypt::encrypt($item1->id)]) }}" title="Lançar notas" class="btn btn-primary">Editar</a>
                                        @endif
                                        </td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="card-body">
                            <table style="width: 100%" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Nome</th>
                                        <th>Sexo</th>

                                        @if($trimestre->trimestre == "IIIª Trimestre")
                                        <th>EN</th>
                                        <th>PT</th>
                                        <th>PAP</th>
                                        <th>NR</th>
                                        @endif
                                        
                                        
                                        @if ($turma->curso->tipo === "Técnico")
                                            <th style="text-align: center;">MAC</th>
                                            <th style="text-align: center;">NPP</th>
                                            <th style="text-align: center;">NPT</th>
                                        @endif 
                                        
                                        @if ($turma->curso->tipo === "Punível")
                                            <th style="text-align: center;">P1</th>
                                            <th style="text-align: center;">P2</th>
                                            <th style="text-align: center;">PT</th>
                                        @endif 
                                        
                                        @if ($turma->curso->tipo === "Outros")
                                            <th style="text-align: center;">MAC</th>
                                            <th style="text-align: center;">NPT</th>
                                        @endif

                                        <th>MT</th>
                                        <th>Resultado</th>
                                        <th>Acções</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($notas as $key => $nota)
                                    <tr>
                                        <td> {{ $key + 1 }}</td>
                                        <td><a href="{{ route('prof.estudantes-informacoes', Crypt::encrypt($nota->estudante->id)) }}">{{ $nota->estudante->nome }} {{ $nota->estudante->sobre_nome }}</a></td>
                                        <td> {{ $nota->estudante->genero }}</td>
                                        
                                        @if($trimestre->trimestre == "IIIª Trimestre")
                                            <td><input name="ne" disabled style="width: 50px" class="form-control" value="{{ $nota->ne }}" data-id="{{ $nota->id }}" /></td>
                                            <td><input name="pt" disabled style="width: 50px" class="form-control" value="{{ $nota->pt }}" data-id="{{ $nota->id }}" /></td>
                                            <td><input name="pap" disabled style="width: 50px" class="form-control" value="{{ $nota->pap }}" data-id="{{ $nota->id }}" /></td>
                                            <td><input name="nr" disabled style="width: 50px" class="form-control" value="{{ $nota->nr }}" data-id="{{ $nota->id }}" /></td>
                                        @endif  

                                        @if ($turma->curso->tipo === "Técnico")
                                            <td><input name="mac" disabled style="width: 50px" class="form-control" value="{{ $nota->mac }}" data-id="{{ $nota->id }}" /></td>
                                            <td><input name="npp" disabled style="width: 50px" class="form-control" value="{{ $nota->npp }}" data-id="{{ $nota->id }}" /></td>
                                            <td><input name="npt" disabled style="width: 50px" class="form-control" value="{{ $nota->npt }}" data-id="{{ $nota->id }}" /></td>
                                        @endif 
                                        
                                        @if ($turma->curso->tipo === "Punível")
                                            <td><input name="mac" disabled style="width: 50px" class="form-control" value="{{ $nota->mac }}" data-id="{{ $nota->id }}" /></td>
                                            <td><input name="npp" disabled style="width: 50px" class="form-control" value="{{ $nota->npp }}" data-id="{{ $nota->id }}" /></td>
                                            <td><input name="npt" disabled style="width: 50px" class="form-control" value="{{ $nota->npt }}" data-id="{{ $nota->id }}" /></td>
                                        @endif 
                                        
                                        @if ($turma->curso->tipo === "Outros")
                                            <td><input name="mac" disabled style="width: 50px" class="form-control" value="{{ $nota->mac }}" data-id="{{ $nota->id }}" /></td>
                                            <td><input name="npt" disabled style="width: 50px" class="form-control" value="{{ $nota->npt }}" data-id="{{ $nota->id }}" /></td>
                                        @endif

                                        <td>{{ $nota->mt }}</td>
                                        <td>{{ $nota->obs }}</td>

                                        <td>
                                            <button type="button" title="Lançar notas" disabled data-id="{{ $nota->id }}" class="atribuir_notas_id_estudantes btn-success btn">Salvar <i class="fas fa-save"></i>
                                            </button>
                                            <button type="button" title="Activar Lançamento de notas" data-id="{{ $nota->id }}" class="activar_atribuicao_notas_id btn-secondary btn">Activar <i class="fas fa-check"></i>
                                            </button>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif

                        <div class="card-footer"></div>
                    </div>

                </div>
            </div>
            @endif
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection

@section('scripts')
<script>
    $(function() {


        $(document).on('click', '.activar_atribuicao_notas_id', function() {
            const id = $(this).data('id');

            // Procura o botão correspondente pelo mesmo data-id e ativa-o
            $(`.atribuir_notas_id_estudantes[data-id="${id}"]`).prop('disabled', false);
            $(`.activar_atribuicao_notas_id[data-id="${id}"]`).prop('disabled', true);
        });


        $(document).on('click', '.atribuir_notas_id_estudantes', function(e) {
            e.preventDefault();
            // var id = $(this).val();

            var notas_id = $(this).data('id');
            var professor_id = $('.professor_id').val();

            var data = {
                nota_id: notas_id
                , professor_id: professor_id

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
                , url: "{{ route('prof.professores-lancamento-nota-estudante-store') }}"
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

        // mudanca de estado do select
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
            $('input[name="nr"][data-id="' + notas_id + '"]').prop('disabled', false);
        });
        // end pesquisar mini pautas
    });
</script>
@endsection
