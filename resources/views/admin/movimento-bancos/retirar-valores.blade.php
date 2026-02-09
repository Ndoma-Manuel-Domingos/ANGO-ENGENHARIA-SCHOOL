@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Retira Valores do Banco</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('web.movimentos-bancos') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Banco</li>
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
                <h5><i class="fas fa-info"></i> Sagriamento de valores no Banco</h5>
            </div>
        </div>
    </div>

      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card">
            <!-- form start -->
            <form action="{{ route('web.retirar-valores-banco-post') }}" method="post">
              @csrf
              <div class="card-body">

                <div class="form-group">
                  <label>Selecionar o Banco</label>
                  <select class="form-control banco_id @error('banco_id') is-invalid @enderror" style="width: 100%;" name="banco_id">
                    @if ($banco)
                      <option value="{{ $banco->id }}">{{ $banco->conta }} - {{ $banco->banco }}</option>
                    @endif
                  </select>
                  <input type="hidden" value="{{ $movimento_id }}" class="movimento_id" name="movimento_id">
                </div>

                <div class="row">
                  <div class="col-12 col-md-6">
                      <div class="form-group">
                        <label for="valor_retirado1">1º Registrar saída de caixa</label>
                        <input type="number" class="form-control valor_retirado1 @error('valor_retirado1') is-invalid @enderror" id="valor_retirado1" name="valor_retirado1" value="{{ $movimento->valor_retirado1 ?? old('valor_retirado1') }}">
                      </div>
                  </div>

                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for="motivo_retirar1">Motivo</label>
                      <input type="text" class="form-control motivo_retirar1 @error('motivo_retirar1') is-invalid @enderror" id="motivo_retirar1" name="motivo_retirar1" value="{{ $movimento->motivo_retirar1 ?? old('motivo_retirar1') }}">
                    </div>
                  </div>


                  <div class="col-12 col-md-6">
                      <div class="form-group">
                        <label for="valor_retirado2">2º Registrar saída de caixa</label>
                        <input type="number" class="form-control valor_retirado2 @error('valor_retirado2') is-invalid @enderror" id="valor_retirado2" name="valor_retirado2" value="{{ $movimento->valor_retirado2 ?? old('valor_retirado2') }}">
                      </div>
                  </div>

                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for="motivo_retirar2">Motivo</label>
                      <input type="text" class="form-control motivo_retirar2 @error('motivo_retirar2') is-invalid @enderror" id="motivo_retirar2" name="motivo_retirar2" value="{{ $movimento->motivo_retirar2 ?? old('motivo_retirar2') }}">
                    </div>
                  </div>


                  <div class="col-12 col-md-6">
                      <div class="form-group">
                        <label for="valor_retirado3">3º Registrar saída de caixa</label>
                        <input type="number" class="form-control valor_retirado3 @error('valor_retirado3') is-invalid @enderror" id="valor_retirado3" name="valor_retirado3" value="{{ $movimento->valor_retirado3 ?? old('valor_retirado3') }}">
                      </div>
                  </div>

                  <div class="col-12 col-md-6">
                    <div class="form-group">
                      <label for="motivo_retirar1">Motivo</label>
                      <input type="text" class="form-control motivo_retirar3 @error('motivo_retirar3') is-invalid @enderror" id="motivo_retirar3" name="motivo_retirar3" value="{{ $movimento->motivo_retirar3 ?? old('motivo_retirar3') }}">
                    </div>
                  </div>

                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                @if (Auth::user()->can('retirar valores caixa'))
                <button type="submit" class="btn btn-primary">Confirmar a retirada</button>
                @endif
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection
