@extends('layouts.provinciais')

@section('content')

<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Gestão de Pautas</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                        <li class="breadcrumb-item active">Pautas gerais</li>
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
                            <form action="{{ route('app.planificacao-provincial-pesquisas-mini-pauta-gerais') }}" id="formulario_pesquisar_mini_pautas_gerais" class="row" method="GET">
                                @csrf
                                <div class="form-group col-md-3">
                                    <label for="escola_id" class="form-label">Escolas</label>
                                    @if ($escolas)
                                    <select name="escola_id" id="escola_id" class="form-control escola_id select2" style="width: 100%">
                                        <option value="">Escolas</option>
                                        @foreach ($escolas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('escola_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                    @endif
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="ano_lectivos_id" class="form-label">Ano Lectivo</label>
                                    <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control ano_lectivos_id select2" style="width: 100%">
                                        <option value="">Ano Lectivo</option>
                                    </select>
                                    @error('escola_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-2">
                                    <label for="turmas_id" class="form-label">Turma</label>
                                    <select name="turmas_id" id="turmas_id" class="form-control turmas_id select2" style="width: 100%">
                                        <option value="">Turma</option>
                                    </select>
                                    @error('turmas_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2">
                                    <label for="disciplinas_id" class="form-label">Disciplinas</label>
                                    <select name="disciplinas_id" id="disciplinas_id" class="form-control disciplinas_id select2" style="width: 100%">
                                        <option value="">Disciplinas</option>
                                    </select>
                                    @error('disciplinas_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                            </form>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" form="formulario_pesquisar_mini_pautas_gerais" id="pesquisarMiniPautaGeral---"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <!-- Main content -->
                    <div class="card">
                        <div class="card-header">
                            <ul class="fs-6 d-flex py-2 px-0">
                                <li>
                                    <strong>Turma: </strong> <span class="span_turma">
                                        @if ( isset($turma) )
                                        {{ $turma->turma }}
                                        @else
                                        desc
                                        @endif</span>. &nbsp;
                                </li>
                                <li><strong>Classe: </strong> <span class="span_classe">
                                        @if ( isset($classe) )
                                        {{ $classe->classes }}
                                        @else
                                        desc
                                        @endif</span>. &nbsp;
                                </li>
                                <li><strong>Turno: </strong> <span class="span_turno">
                                        @if ( isset($turno) )
                                        {{ $turno->turno }}
                                        @else
                                        desc
                                        @endif</span>. &nbsp;
                                </li>
                                <li><strong>Sala Nº: </strong> <span class="span_sala">
                                        @if ( isset($sala) )
                                        {{ $sala->salas }}
                                        @else
                                        desc
                                        @endif</span>. &nbsp;
                                </li>
                                <li><strong>Curso: </strong> <span class="curso_sala">
                                        @if ( isset($curso) )
                                        {{ $curso->curso }}
                                        @else
                                        desc
                                        @endif</span>. &nbsp;
                                </li>
                                <li><strong>Disciplina: </strong><span class="span_disciplina">
                                        @if ( isset($disciplina) )
                                        {{ $disciplina->disciplina }}
                                        @else
                                        desc
                                        @endif</span>. &nbsp;
                                </li>
                                <li><strong>Ano Lectivo </strong> <span class="span_ano_lectivo">
                                        @if ( isset($anoLectivo) )
                                        {{ $anoLectivo->ano }}
                                        @else
                                        desc
                                        @endif</span>. &nbsp;
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            @if ( isset($classe) && $classe->tipo == 'Transição')
                            @include('admin.require.classe-transicao')
                            @else
                            @if (isset($classe) && $classe->tipo == 'Exame')
                            @include('admin.require.classe-exames')
                            @endif
                            @endif
                        </div>

                        <div class="card-footer">
                            @if( isset($turma) AND isset($disciplina) )
                            <a href="{{ route('ficha-mini-pauta-geral', [ 'turma' => Crypt::encrypt($turma->id), 'disciplina' => Crypt::encrypt($disciplina->id), ]) }}" target="_blank" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Imprimir</a>
                            @endif
                        </div>

                    </div>

                </div><!-- /.col -->

            </div><!-- /.row -->

        </div>
    </section>
</div>

@endsection


@section('scripts')
<script>
    $("#escola_id").change(() => {
        let id = $("#escola_id").val();
        $.get('../carregar-todos-anolectivos-escolas/' + id, function(data) {
            $("#ano_lectivos_id").html("")
            $("#ano_lectivos_id").html(data)
        })
    })

    $("#ano_lectivos_id").change(() => {
        let id = $("#ano_lectivos_id").val();
        $.get('../carregar-todas-turmas-anolectivos-escolas/' + id, function(data) {
            $("#turmas_id").html("")
            $("#turmas_id").html(data)
        })
    })

    $("#turmas_id").change(() => {
        let id = $("#turmas_id").val();
        $.get('../carregar-disciplinas-turma/' + id, function(data) {
            $("#disciplinas_id").html("")
            $("#disciplinas_id").html(data)
        })
    })

</script>
@endsection


@section('scripts')
<script>
    $(function() {
        // mudança de stado do select
        $(document).on('change', '.turmas_id', function(e) {
            e.preventDefault();
            var turma = $('.turmas_id').val();

            $.ajax({
                type: "GET"
                , url: "carregar-turmas-pautas/" + turma
                , dataType: "json"
                , success: function(response) {
                    if (response.status == 200) {
                        $('#list-disciplinas-mini-pauta-geral').html("");
                        for (let index = 0; index < response.disciplinasTurma.length; index++) {
                            $('#list-disciplinas-mini-pauta-geral').append('<option value="' + response.disciplinasTurma[index].id + '">' + response.disciplinasTurma[index].disciplina + '</option>');
                        }
                    }

                }
            });

        });
        // end mudanca do estado
    });

</script>
@endsection


@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

</script>
@endsection
