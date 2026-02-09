@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Processos pedagógicos de Estudante</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('web.processos-estudantes') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Estudantes</li>
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
                    <form action="">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-2">
                                    <label for="" class="form-label">Estudante</label>
                                    <select name="numero_processo" id="numero_processo" class="select2 form-control numero_processo" data-placeholder="pesquisar o número do processo" style="width: 100%;">
                                        @if ($matriculas_passadas)
                                        @foreach ($matriculas_passadas as $matricula)
                                        <option value="{{ Crypt::encrypt($matricula->estudantes_id) }}">{{ $matricula->numero_estudante }} - {{ $matricula->estudante->nome }} {{ $matricula->estudante->sobre_nome }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Tipo Documento</label>
                                    <select name="tipo_processo" id="tipo_processo" class="select2 form-control tipo_processo" data-placeholder="Tipo documento ou Processo" style="width: 100%;">
                                        <option value="{{ Crypt::encrypt("Ficha-Tecnica") }}">Ficha Técnica</option>
                                        <option value="{{ Crypt::encrypt("Ficha-Matricula") }}">Ficha de Matricula</option>
                                        <option value="{{ Crypt::encrypt("Ficha-inscricao") }}">Ficha de Inscrição</option>
                                        <option value="{{ Crypt::encrypt("declaracao-nota") }}">Declarações com Notas</option>
                                        <option value="{{ Crypt::encrypt("declarcao-sem-nota") }}">Declarações Sem Notas</option>
                                        <option value="{{ Crypt::encrypt("classificacao-final") }}">Declaração</option>
                                    </select>
                                </div>

                                <div class="col-2">
                                    <label for="" class="form-label">Efeito</label>
                                    <select name="efeito" id="efeito" class="select2 form-control efeito" data-placeholder="Para que efeito" style="width: 100%;">
                                        <option value="s">Para que Efeito</option>
                                        @foreach ($efeitos as $item)
                                        <option value="{{ Crypt::encrypt($item->id) }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="" class="form-label">Ano Lectivo</label>
                                    <select name="ano_lectivos_ids" id="ano_lectivos_ids" class="select2 form-control ano_lectivos_ids" data-placeholder="Selecione o Ano Lectivo" style="width: 100%;">
                                        @if ($ano_lectivos)
                                        @foreach ($ano_lectivos as $ano)
                                        <option value="{{ Crypt::encrypt($ano->id) }}">{{ $ano->ano }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary pesquisarEstudante"><i class="fas fa-print"></i> Imprimir</button>
                        </div>
                    </form>
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
        // ver dados
        var processo;
        var codigo;

        $(document).on('click', '.pesquisarEstudante', function(e) {
          e.preventDefault();
          var numero_processo = $('.numero_processo').val();
          var tipo_processo = $('.tipo_processo').val();
          var efeito = $('.efeito').val();
          var ano_lectivos_ids = $('.ano_lectivos_ids').val();
          window.open(`../turmas/distribuicao-rotas?id=${numero_processo}&ano=${ano_lectivos_ids}&condicao=${tipo_processo}&condicao2=${efeito}`, "_blank");
        });
    });

</script>

@endsection
