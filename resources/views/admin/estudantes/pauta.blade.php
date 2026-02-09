@extends('layouts.escolas')

@section('content')

<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Notas do Estudante</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($estudantes_id)) }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Perfil</li>
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
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('web.mapa-aproveitamento-estudante') }}" method="GET" class="row" id="pesquisarMiniPaut">
                                @csrf
                                <div class="form-group col-md-5">
                                    @if ($ano_lectivos)
                                    <label for="" class="form-label">Ano Lectivo</label>
                                    <select name="ano_lectivos_id" id="ano_lectivos_id" class="select2 form-control ano_lectivos_id" style="width: 100%">
                                        @foreach ($ano_lectivos as $item)
                                        <option value="{{ $item->id }}">{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                    @error('ano_lectivos_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                    @endif
                                </div>

                                <input type="hidden" name="estudantes_id" value="{{ $estudantes_id }}" id="estudantes_id" class="estudantes_id">

                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" form="pesquisarMiniPaut"><i class="fas fa-search"></i> Pesquisar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    @if (Auth::user()->can('read: nota'))
                    @include('admin.require.pauta-admin-pedagogico')
                    @endif
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <!-- /.content -->
</div>
@endsection
