@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Serviço da Turma: {{ $servico_turma->turma->turma ?? "" }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('financeiros.listagem-servicos') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Serviços</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <form id="UpdateServico" action="{{ route('web.editar-servico-turma', $servico_turma->id) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3 col-12">
                                    <label for="servicos_id">Serviço <span class="text-danger">*</span></label>
                                    <select name="servicos_id" id="servicos_id" class="form-control servicos_id servicos_all select2">
                                        <option value="">Selecione serviço</option>
                                        @if ($servicos)
                                            @foreach ($servicos as $servico)
                                            <option value="{{ $servico->id }}" {{ $servico->id == $servico_turma->servicos_id ? "selected" : "" }}>{{ $servico->servico }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="preco">Preço sem o IVA <span class="text-danger">*</span></label>
                                    <input type="number" name="preco" class="form-control preco" value="{{ $servico_turma->preco }}" id="preco" placeholder="Informe o proço do serviço">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="multa">Multa</label>
                                    <input type="number" name="multa" value="{{ $servico_turma->multa }}" class="form-control multa" placeholder="Informe a multa para este serviço">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="desconto">Desconto</label>
                                    <input type="number" value="{{ $servico_turma->desconto }}" name="desconto" class="form-control desconto" placeholder="Informe o desconto para esse serviços">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="status">Status do Serviço <span class="text-danger">*</span></label>
                                    <select name="status" id="status" id="status" class="form-control status">
                                        <option value="activo" {{ $servico_turma->status == "activo" ? 'selected' : '' }}>Activo</option>
                                        <option value="desactivo" {{ $servico_turma->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="pagamento">Pagamento <span class="text-danger">*</span></label>
                                    <select name="pagamento" id="pagamento" id="pagamento" class="form-control pagamento">
                                        <option value="">Selecione status</option>
                                        <option value="unico" {{ $servico_turma->pagamento == "unico" ? 'selected' : '' }}>Pagamento Unico</option>
                                        <option value="mensal" {{ $servico_turma->pagamento == "mensal" ? 'selected' : '' }}>Pagamento Mensal</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="taxa_multa1_dia" class="form-label">Dia de Atraso para aplica 1º Taxa <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $servico_turma->taxa_multa1_dia }}" name="taxa_multa1_dia" placeholder="Dias de Atraso para primeira taxa" id="taxa_multa1_dia" class="form-control taxa_multa1_dia">
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="taxa_multa1" class="form-label">Valor 1º Taxa (%) <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $servico_turma->taxa_multa1 }}" name="taxa_multa1" placeholder="Dia final Ex: 5%" id="taxa_multa1" class="form-control taxa_multa1">
                                 
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="taxa_multa2_dia" class="form-label">Dia de Atraso para aplica 2º Taxa <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $servico_turma->taxa_multa2_dia }}" name="taxa_multa2_dia" placeholder="Dias de Atraso para segunda taxa" id="taxa_multa2_dia" class="form-control taxa_multa2_dia">
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="taxa_multa2" class="form-label">Valor 2º Taxa (%) <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $servico_turma->taxa_multa2 }}" name="taxa_multa2" placeholder="Dia final Ex: 10%" id="taxa_multa2" class="form-control taxa_multa2">
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="taxa_multa3_dia" class="form-label">Dia de Atraso para aplica 3º Taxa <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $servico_turma->taxa_multa3_dia }}" name="taxa_multa3_dia" placeholder="Dias de Atraso para terceira taxa" id="taxa_multa3_dia" class="form-control taxa_multa3_dia">
                                </div>

                                <div class="form-group mb-3 col-md-3 col-12">
                                    <label for="taxa_multa3" class="form-label">Valor 3º Taxa (%) <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $servico_turma->taxa_multa3 }}" name="taxa_multa3" placeholder="Dia final Ex: 15%" id="taxa_multa3" class="form-control taxa_multa3">
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="total_vezes">Total de Parcelamento <span class="text-danger">*</span></label>
                                    <input type="number" value="{{ $servico_turma->total_vezes }}" name="total_vezes" class="form-control total_vezes" id="total_vezes" placeholder="informe Informe o total de parcelamento Ex: 12">
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="data_inicio">Data Inicio do pagamento <span class="text-danger">*</span></label>
                                    <input type="date" value="{{ $servico_turma->data_inicio }}" name="data_inicio" class="form-control data_inicio" id="data_inicio" placeholder="informe a data final do pagamento">
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="data_final">Data Final do pagamento <span class="text-danger">*</span></label>
                                    <input type="date" value="{{ $servico_turma->data_final }}" name="data_final" class="form-control data_final" placeholder="informe o dia do final da cobrança">
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success editar_servico" id="btnEditar">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    ajaxFormSubmit('#UpdateServico');
</script>
@endsection
