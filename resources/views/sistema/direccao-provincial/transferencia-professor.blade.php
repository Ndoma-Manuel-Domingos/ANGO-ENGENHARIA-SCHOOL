@extends('layouts.provinciais')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Transferência de Professor</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
            <li class="breadcrumb-item active">Transferências</li>
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
                @if(session()->has('danger'))
                    <div class="alert alert-warning">
                        {{ session()->get('danger') }}
                    </div>
                @endif

                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
            </div>

            <div class="col-12 col-md-12">
                <form action="{{ route('web.transferencia-escola-provincial-professores-store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body row">
                            <div class="form-group col-12 col-md-6 mb-2">
                                <label for="professor_id">Professores</label>
                                <select name="professor_id" class="form-control select2" style="width: 100%" required>
                                    <option value="">Selecione o Professor </option>
                                    @foreach ($professoroes as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $id ? 'selected' : '' }}>{{ $item->nome }} {{ $item->sobre_nome }}</option>
                                    @endforeach
                                </select>
                                @error('professor_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-12 col-md-6 mb-2">
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

                            <div class="form-group col-12 mb-2">
                                <label for="" class="form-label">Motivo</label>
                                <textarea name="motivo" class="form-control" required rows="2" cols="12" placeholder="Informe os motivos para transferência do professor"></textarea>
                                @error('motivo')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                             <div class="form-group col-12 mb-2">
                                <label for="" class="form-label">Documento comprovativo (PDF)</label>
                                <input type="file" name="documento" accept=".pdf" class="form-control" required/>
                                @error('documento')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>
                            
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