@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Editar Serviço</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
             <li class="breadcrumb-item"><a href="{{ route('web.calendarios') }}">Serviços</a></li>
            <li class="breadcrumb-item active">Editar</li>
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
                <h5><i class="fas fa-info"></i> Editar serviço.</h5>
            </div>
        </div>
      </div>

        <div class="row">
            <div class="col-12 card p-3">

                <form class="row" method="post" action="{{ route('web.update-servico', $servico->id) }}">
                    @csrf
                    @method('put')
                    <div class="form-group col-md-6">
                        <label for="servico">Serviço <span class="text-danger">*</span></label>
                        <input type="text" name="servico" value="{{ $servico->servico ?? old('servico') }}" class="form-control servico" id="servico" placeholder="Informe o serviço">
                        <span class="text-danger error-text servico_error"></span>
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="contas">Contas <span class="text-danger">*</span></label>
                        <select name="contas" id="contas" class="form-control contas select2">
                            <option value="">Selecione Contas</option>
                            <option value="despesa" {{ $servico->contas == "despesa" ? 'selected' : old('contas') }}>Contas a  Pagar ou Dispesa</option>
                            <option value="receita" {{ $servico->contas == "receita" ? 'selected' : old('contas') }}>Contas a Receber ou Receitas</option>
                        </select>
                        <span class="text-danger error-text contas_error"></span>
                    </div>
                    
                    <div class="form-group col-md-3">
                      <label for="tipo">Tipo <span class="text-danger">*</span></label>
                      <select name="tipo" id="tipo" class="form-control tipo select2">
                          <option value="">Selecione tipo</option>
                          <option value="S" {{ $servico->tipo == "S" ? 'selected' : "" }}>Serviço</option>
                          <option value="P" {{ $servico->tipo == "P" ? 'selected' : "" }}>Produto</option>
                      </select>
                      <span class="text-danger error-text tipo_error"></span>
                    </div>
                    
                    <div class="form-group col-md-3">
                      <label for="taxa_id">Taxas <span class="text-danger">*</span></label>
                      <select name="taxa_id" id="taxa_id" class="form-control taxa_id select2">
                          <option value="">Selecione</option>
                          @foreach ($taxas as $taxa)
                          <option value="{{ $taxa->id }}" {{ $servico->taxa_id == $taxa->id ? 'selected' : old('taxa_id') }}>{{ $taxa->taxa }} %</option>
                          @endforeach
                      </select>
                      <span class="text-danger error-text taxa_id_error"></span>
                    </div>
                    
                    <div class="form-group col-md-3">
                      <label for="unidade">Unidade <span class="text-danger">*</span></label>
                      <input type="text" name="unidade" value="{{ $servico->unidade ?? old('unidade') }}" class="form-control unidade" id="unidade" placeholder="Informe a unidade">
                      <span class="text-danger error-text unidade_error"></span>
                    </div>
                    
                    <div class="form-group col-md-3">
                      <label for="motivo_id">Motivo <span class="text-danger">*</span></label>
                      <select name="motivo_id" id="motivo_id" class="form-control motivo_id select2">
                          <option value="">Selecione</option>
                          @foreach ($motivos as $motivo)
                          <option value="{{ $motivo->id }}" {{ $motivo->id == $motivo->id ? 'selected' : '' }}>{{ $motivo->descricao }}</option>
                          @endforeach
                      </select>
                      <span class="text-danger error-text motivo_id_error"></span>
                    </div>
                    

                    <div class="form-group col-md-3">
                        <label for="status">Status do Serviço <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control status select2">
                            <option value="">Selecione status</option>
                            <option value="activo" {{ $servico->status == 'activo' ? 'selected' : old('status') }}>Activo</option>
                            <option value="desactivo" {{ $servico->status == 'desactivo' ? 'selected' : old('status') }}>Desactivo</option>
                        </select>
                        <span class="text-danger error-text status_error"></span>
                    </div>

                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-success">Actualizar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection
