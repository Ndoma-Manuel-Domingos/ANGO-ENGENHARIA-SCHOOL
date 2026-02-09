@extends('layouts.escolas')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Abertura do TPA</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('paineis.administrativo') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Abertura do TPA</li>
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
                <h5><i class="fas fa-info"></i> Abertura do TPA para poder registrar pagamentos.</h5>
            </div>
        </div>
    </div>

      <div class="row">
        <div class="col-12 col-md-12">
          <div class="card">
            <!-- form start -->
            <form action="{{ route('web.abertura-bancos') }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="valor_inicial">Valor Inicial</label>
                  <input type="number" class="form-control valor_inicial @error('valor_inicial') is-invalid @enderror" value="0" id="valor_inicial" name="valor_inicial" placeholder="Introduz um Valor Inicial">
                </div>
                <div class="form-group">
                  <label>Selecionar o TPA</label>
                  <select class="form-control banco_id @error('valor_inicial') is-invalid @enderror" name="banco_id">
                  @if ($bancos)
                    @foreach ($bancos as $item)
                      <option value="{{ $item->id }}">{{ $item->conta }} - {{ $item->banco }}</option>
                    @endforeach
                  @endif
                  </select>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                @if (Auth::user()->can('abertura caixa'))
                <button type="submit" class="btn btn-primary">Abrir o Caixa</button>
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
