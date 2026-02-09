@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Pesquisar Estudante</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('tesourarias.index') }}">Voltar</a></li>
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
                <form class="mb-3" method="get" action="{{ route('web.estudantes-efectuar-pagamento-especias') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <p> <strong>NOTA: </strong> Pesquise o estudante usando a referência da matrícula ou do comprovante de confirmação.</p>
                        </div>
                        <div class="card-body">
                            <div class="col-12 col-md-12 pb-4">
                                <div class="input-group">
                                    <input type="search" name="search" id="search" value="{{ $requests['search'] ?? "" }}" class="form-control form-control-lg" 
                                        placeholder="Referência da matrícula">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-lg btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        @if ($matricula)
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">

                    <div class="card-header">
                        <h5>
                            <i class="fas fa-graduation-cap"></i> {{ $matricula->estudante->nome }} {{ $matricula->estudante->sobre_nome }} <span class="float-right fs-3 text-danger">Troco <strong id="valor_troco_apresenta">0</strong></span>
                        </h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('web.estudantes-efectuar-pagamento-especias-store') }}" id="form_pagamento" method="post">
                            @csrf
                            <div class="row">

                                <div class="col-12 col-md-2 mb-3">
                                    <label for="" class="form-label">Genero:</label>
                                    <input type="text" value="{{ $matricula->estudante->genero }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label for="" class="form-label">Nascimento:</label>
                                    <input type="text" value="{{ $matricula->estudante->nascimento }}" disabled class="form-control">
                                </div>


                                <div class="col-12 col-md-2 mb-3">
                                    <label for="" class="form-label">Nacionalidade:</label>
                                    <input type="text" value="{{ $matricula->estudante->nacionalidade }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label for="" class="form-label">B.I:</label>
                                    <input type="text" value="{{ $matricula->estudante->bilheite }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label for="" class="form-label">Tel Estudante:</label>
                                    <input type="text" value="{{ $matricula->estudante->telefone_estudante }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label for="" class="form-label">Turma:</label>
                                    <input type="text" value="{{ $matricula->turma($matricula->estudantes_id) }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label for="" class="form-label">Sala:</label>
                                    <input type="text" value="{{ $matricula->sala($matricula->estudantes_id) }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label for="" class="form-label">Curso:</label>
                                    <input type="text" value="{{ $matricula->curso->curso }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label for="" class="form-label">Classe:</label>
                                    <input type="text" value="{{ $matricula->classe->classes }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label for="" class="form-label">Turno:</label>
                                    <input type="text" value="{{ $matricula->turno->turno }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-4 mb-3">
                                    <label for="" class="form-label">Processo Nº:</label>
                                    <input type="text" value="{{ $matricula->numero_estudante }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-4 mb-3">
                                    <label for="" class="form-label">Pai:</label>
                                    <input type="text" value="{{ $matricula->estudante->pai }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-4 mb-3">
                                    <label for="" class="form-label">Mãe:</label>
                                    <input type="text" value="{{ $matricula->estudante->mae }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-4 mb-3">
                                    <label for="" class="form-label">Telefone Pai e Mãe</label>
                                    <input type="text" value="{{ $matricula->estudante->telefone_pai }} {{ $matricula->estudante->telefone_mae }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label for="" class="form-label">Valor a Pagar <span class="text-danger">*</span></label>
                                    <input type="text" name="valor_a_pagar" value="{{ $matricula->tipo == "confirmacao" ? $turma->valor_confirmacao : $turma->valor_matricula }}" class="form-control" placeholder="Valor a Pagar" disabled>
                                    @error('valor_a_pagar')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <input type="hidden" name="tipo_matricula" value="{{ $matricula->tipo }}">
                                <input type="hidden" name="servicos_id" value="{{ $servico->id }}">
                                <input type="hidden" name="matricula_id" value="{{ $matricula->id }}">
                                <input type="hidden" name="valor_a_pagar" class="valor valor_total_a_pagar" value="{{ $matricula->tipo == "confirmacao" ? $turma->valor_confirmacao : $turma->valor_matricula }}">

                                <div class="col-12 col-md-2 mb-3">
                                    <label for="" class="form-label">Valor Entregue <span class="text-danger">*</span></label>
                                    <input type="text" name="valor_entregue" value="{{ $matricula->tipo == "confirmacao" ? $turma->valor_confirmacao : $turma->valor_matricula }}" id="valor_entregue" class="form-control valor_entregue" placeholder="Valor entregue">
                                    @error('valor_entregue')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-2 mb-3">
                                    <label for="tipo_pagamento">Forma de Pagamento <span class="text-danger">*</span></label>
                                    <select name="tipo_pagamento" id="tipo_pagamento" class="form-control tipo_pagamento select2 @error('tipo_pagamento') is-invalid @enderror">
                                        @foreach ($formas_pagamento as $item)
                                        <option value="{{ $item->sigla_tipo_pagamento }}" {{ old('tipo_pagamento') == $item->sigla_tipo_pagamento ? 'selected' : '' }}>{{ $item->descricao }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo_pagamento')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-md-2" id="form_campo_caixas">
                                    <label for="caixa_id">Caixas</label>
                                    <select name="caixa_id" id="caixa_id" class="form-control caixa_id">
                                        @foreach ($caixas as $item)
                                        <option value="{{ $item->id }}">{{ $item->conta }} - {{ $item->caixa }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text caixa_id_error"></span>
                                </div>

                                <div class="form-group col-12 col-md-2" id="form_campo_bancos" style="display: none;">
                                    <label for="banco_id">Bancos</label>
                                    <select name="banco_id" id="banco_id" class="form-control banco_id">
                                        @foreach ($bancos as $item)
                                        <option value="{{ $item->id }}">{{ $item->conta }} - {{ $item->banco }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text banco_id_error"></span>
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="sobre_nome">Número de Transição (Opcional)</label>
                                    <input type="text" value="{{ old('numero_transicao') }}" name="numero_transicao" class="form-control numero_transicao @error('numero_transicao') is-invalid @enderror" placeholder="Número da seríe ou ordem Bancaria">
                                    @error('numero_transicao')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" form="form_pagamento" id="">Efectuar</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')

<script>
    $('.valor_entregue').on('input', function(e) {
        e.preventDefault();

        if ($(this).val() > 0) {
            // valor total a pagar
            var valor_total = $('.valor_total_a_pagar').val();
            var valor_entregue = $(this).val();

            var troco = valor_entregue - valor_total;

            // var f = troco.toLocaleString('pt-br',{style: 'currency', currency: 'AOA'});
            var f2 = troco.toLocaleString('pt-br', {
                minimumFractionDigits: 2
            });

            $("#valor_troco_apresenta").html("")
            $("#valor_troco_apresenta").append(f2)

        } else {
            console.log("false")
        }
    })

    $('.tipo_pagamento').on('change', function(e) {
        e.preventDefault();
        var id = $(this).val();

        if (id == "NU") {
            $('#form_campo_caixas').css({
                display: "inline-block"
            });

            $('#form_campo_bancos').css({
                display: "none"
            });
        }

        if (id == "OU") {
            $('#form_campo_caixas').css({
                display: "inline-block"
            });

            $('#form_campo_bancos').css({
                display: "inline-block"
            });
        }

        if (id == "TT") {
            $('#form_campo_caixas').css({
                display: "none"
            });

            $('#form_campo_bancos').css({
                display: "inline-block"
            });
        }

        if (id == "MB") {
            $('#form_campo_caixas').css({
                display: "none"
            });

            $('#form_campo_bancos').css({
                display: "inline-block"
            });
        }

        if (id == "DD") {
            $('#form_campo_caixas').css({
                display: "none"
            });

            $('#form_campo_bancos').css({
                display: "inline-block"
            });
        }
    })

</script>

@endsection
