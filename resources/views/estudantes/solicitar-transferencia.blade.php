@extends('layouts.estudantes')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Solicitações Transferência</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route("est.home-estudante") }}">Voltar</a></li>
                  <li class="breadcrumb-item active">Solicitação</li>
                </ol>
            </div>
        </div>

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
            <div class="col-12 mb-3">
                <form action="{{ route('est.solicitacoes-transferencia-store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-4 col-12">
                                    <label for="password_2">Escolas</label>
                                    <select name="escola_id" placeholder="Informe a Nova Senha" class="form-control select2" style="width: 100%;">
                                        {{-- <option value="">Todos Cursos</option> --}}
                                        <option value="{{ $escola->id }}">{{ $escola->nome }}</option>
                                    </select>
                                    @error('escola_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="password_2">Cursos</label>
                                    <select name="cursos_id" placeholder="Informe a Nova Senha" class="form-control select2" style="width: 100%;">
                                        <option value="{{ $curso->id }}">{{ $curso->curso }}</option>
                                    </select>
                                    @error('cursos_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="password_2">Classes</label>
                                    <select name="classes_id" placeholder="Informe a Nova Senha" class="form-control select2" style="width: 100%;">
                                        <option value="">Selecione</option>
                                        @foreach ($classes as $item)
                                            <option value="{{ $item->classe->id }}">{{ $item->classe->classes }}</option>
                                        @endforeach
                                    </select>
                                    @error('classes_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="password_2">Turnos</label>
                                    <select name="turnos_id" placeholder="Informe a Nova Senha" class="form-control select2" style="width: 100%;">
                                        <option value="">Selecione</option>
                                        @foreach ($turnos as $item)
                                            <option value="{{ $item->turno->id }}">{{ $item->turno->turno }}</option>
                                        @endforeach
                                    </select>
                                    @error('turnos_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="password_2">Ano Lectivo</label>
                                    <select name="ano_lectivos_id" placeholder="Informe a Nova Senha" class="form-control select2" style="width: 100%;">
                                        <option value="{{ $anolectivo->id }}">{{ $anolectivo->ano }}</option>
                                    </select>
                                    @error('ano_lectivos_id')
                                    <span class="text-danger"> {{ $message }}</span>
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
    

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Pesquisar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<!-- /.content-header -->

@endsection