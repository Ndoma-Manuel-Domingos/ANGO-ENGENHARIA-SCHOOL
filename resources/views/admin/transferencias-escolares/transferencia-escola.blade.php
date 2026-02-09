@extends('layouts.escolas')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Transferência de Estudante para Escolas</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="">Voltar</a></li>
            <li class="breadcrumb-item active">Escolas</li>
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
                <form action="{{ route('web.transferencia-escola-estudante-store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body row">

                            <div class="form-group col-12 col-md-4 mb-2">
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
                            
                            
                            <div class="form-group col-12 col-md-4 mb-2">
                                <label for="" class="form-label">Escolas</label>
                                <select name="escola_id" class="form-control select2" style="width: 100%" required>
                                    <option value="">Selecione a Escola</option>
                                    @foreach ($escolas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('escola_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-12 col-md-2 mb-2">
                                <label for="" class="form-label">Condição da Transferência</label>
                                <select name="condicao" class="form-control select2" style="width: 100%" required>
                                    <option value="concluir_ano_lectivo">Continuar Ano Lectivo</option>
                                    <option value="activa_outro_ano">Estudar outro Ano LEctivo</option>
                                </select>
                                @error('condicao')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            
                            <div class="form-group col-md-2 col-12">
                                <label for="password_2">Cursos</label>
                                <select name="cursos_id" placeholder="Informe a Nova Senha" class="form-control select2" style="width: 100%;">
                                    @foreach ($cursos as $item)
                                        <option value="{{ $item->id }}">{{ $item->curso }}</option>
                                    @endforeach
                                </select>
                                @error('cursos_id')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4 col-12">
                                <label for="password_2">Classes</label>
                                <select name="classes_id" placeholder="Informe a Nova Senha" class="form-control select2" style="width: 100%;">
                                    <option value="">Selecione</option>
                                    @foreach ($classes as $item)
                                        <option value="{{ $item->id }}">{{ $item->classes }}</option>
                                    @endforeach
                                </select>
                                @error('classes_id')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4 col-12">
                                <label for="password_2">Turnos</label>
                                <select name="turnos_id" placeholder="Informe a Nova Senha" class="form-control select2" style="width: 100%;">
                                    <option value="">Selecione</option>
                                    @foreach ($turnos as $item)
                                        <option value="{{ $item->id }}">{{ $item->turno }}</option>
                                    @endforeach
                                </select>
                                @error('turnos_id')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>

                             <div class="form-group col-4 mb-2">
                                <label for="" class="form-label">Documento comprovativo (PDF)</label>
                                <input type="file" name="documento" accept=".pdf" class="form-control" required/>
                                @error('documento')
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