@extends('layouts.escolas')

@section('content')

<style>
    td {
        font-size: 8pt;
    }

    th {
        font-size: 9pt;
    }

    .text-vertical {
        display: inline-block;
        transform: rotate(-90deg);
        /* Define o ângulo de rotação */
    }
</style>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">PAUTA FINAL</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">PAUTA FINAL</a></li>
                    <li class="breadcrumb-item active">Estatísticas</li>
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
                    <div class="card-body">
                        <form action="{{ route('pedagogicos.estatistica-turmas-unica') }}" id="formulario_busca" class="row" method="GET">
                            @csrf
                            <div class="form-group col-md-3">
                                <label for="ano_lectivos_id" class="form-label">Ano Lectivos</label>
                                @if (isset($anolectivos))
                                <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control select2 ano_lectivos_id">
                                    <option value="">Ano Lectivos</option>
                                    @foreach ($anolectivos as $item)
                                    <option value="{{ $item->id }}" {{ $requests['ano_lectivos_id'] == $item->id ? 'selected' : '' }}>{{ $item->ano }}</option>
                                    @endforeach
                                </select>
                                @error('ano_lectivos_id')
                                <span class="text-danger error-text ano_lectivos_id_error">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                            <div class="form-group col-md-3">
                                <label for="turmas_id" class="form-label">Turmas</label>
                                @if (isset($turmas))
                                <select name="turmas_id" id="turmas_id" class="form-control select2 turmas_id">
                                    <option value="">Turma</option>
                                    @foreach ($turmas as $item)
                                    <option value="{{ $item->id }}" {{ $requests['turmas_id'] == $item->id ? 'selected' : '' }}>{{ $item->turma }}</option>
                                    @endforeach
                                </select>
                                @error('turmas_id')
                                <span class="text-danger error-text turmas_id_error">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="submit" form="formulario_busca" class="btn btn-primary"><i class="fas fa-search"></i> Filtrar</button>
                    </div>
                </div>
            </div>
        </div>
        
        @include('admin.require.estatistica-pautas')

    </div>
    <!-- /.container-fluid -->
</section>
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
            }, 
            success: function(data) {
                Swal.close();
                // Exibe uma mensagem de sucesso
                showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                // Limpa o campo #turmas_id e insere os dados recebidos
                $("#turmas_id").html("");
                $("#turmas_id").html(data);
            }, 
            error: function(xhr) {
                Swal.close();
                showMessage('Erro!', xhr.responseJSON.message, 'error');
            }, 
        });
    });
    
</script>
@endsection
