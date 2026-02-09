@extends('layouts.estudantes')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Transferências</h1>
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
                <form action="{{ route('est.solicitacoes-vagas') }}" method="get" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-12">
                                    <label for="password_2">Curso</label>
                                    <select name="cursos_id" placeholder="Informe a Nova Senha" class="form-control select2" style="width: 100%;">
                                        <option value="">Todos Cursos</option>
                                        @foreach ($cursos as $item)
                                            <option value="{{ $item->id }}" {{ $requests['cursos_id'] == $item->id ? 'selected' : '' }}>{{ $item->curso }}</option>
                                        @endforeach
                                    </select>
                                    @error('cursos_id')
                                    <span class="text-danger"> {{ $message }}</span>
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


        @if (count($cursos_vagas) > 0)
            <div class="row mt-3">
                <div class="col-md-4 offset-md-4">
                    <div class="list-group">
                        @foreach ($cursos_vagas as $item)
                            @if ($item->ano_lectivo->status == 'activo')
                                <div class="list-group-item pb-4 mb-1">
                                    <div class="row">
                                        <div class="col px-4">
                                            <div>
                                                <div class="float-right">{{ date("d/m/Y h:i:s", strtotime($item->created_at))  }}</div>
                                                <p class="mb-3 fs-5"><strong>{{ $item->escola->nome }}</strong> na Província de <strong>{{ $item->escola->provincia->nome }}</strong></p>
                                                <p class="mb-0 fs-6">Curso de {{ $item->curso->curso }}, total de <span class="text-success">Vagas disponível {{ $item->total_vagas }}</span>. Ano lectivo <span class="text-success">{{ $item->ano_lectivo->ano }}</span></p>
                                                @if ($item->total_vagas > 0)
                                                    <a href="{{ route('est.solicitacoes-transferencia', ['escola' => $item->escola->id, 'curso' => $item->curso->id, 'ano' => $item->ano_lectivo->id]) }}" class="btn btn-primary d-inline-block mt-3">Solicitar Transferência</a>    
                                                @endif                                         
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif



    </div>
</div>
<!-- /.content-header -->

@endsection