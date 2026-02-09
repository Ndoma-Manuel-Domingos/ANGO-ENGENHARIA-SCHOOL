@extends('layouts.escolas')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Transferência de Estudante entre Turma</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="">Voltar</a></li>
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
            <div class="callout callout-info">
                <h5><i class="fas fa-info"></i> Mais informações sobre do estudante</h5>
            </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('web.transferencia-turma-estudante-store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body row">

                            <div class="form-group col-12 col-md-6 mb-2">
                                <label for="" class="form-label">Estudante</label>
                                <select name="estudante_id" class="form-control select2" style="width: 100%" required>
                                    @if ($estudante)
                                        <option value="{{ $estudante->id }}">{{ $estudante->nome }} {{ $estudante->sobre_nome }}</option>
                                    @else
                                        <option value="">Selecione Estudante</option>
                                        @foreach ($estudantes as $item)
                                            <option value="{{ $item->estudante->id }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('estudante_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-12 col-md-6 mb-2">
                                <label for="" class="form-label">Turmas</label>
                                <select name="turmas_id" class="form-control select2" style="width: 100%" required>
                                    <option value="">Selecione a turmas</option>
                                    @foreach ($turmas as $item)
                                        <option value="{{ $item->id }}">{{ $item->turma }}</option>
                                    @endforeach
                                </select>
                                @error('turmas_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-12 mb-2">
                                <label for="" class="form-label">Motivo</label>
                                <textarea name="motivo" class="form-control" required rows="2" cols="12" placeholder="Informe os motivos para transferência do estudante"></textarea>
                                @error('motivo')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                             <div class="form-group col-12 mb-2">
                                <label for="" class="form-label">Documento comprovativo (PDF)</label>
                                <input type="file" name="documento" accept=".pdf" class="form-control"/>
                                @error('documento')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- <input type="hidden" name="estudante_id" value="{{ $estudante->id }}" /> --}}
                            
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Confirmar Transferência</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection