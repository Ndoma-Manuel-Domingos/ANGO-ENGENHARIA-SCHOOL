@extends('layouts.escolas')

@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Gestão de Cartões</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Cartão</li>
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
                    <form action="{{ route('web.index.create') }}" method="POST">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        <label for="estudante_id" class="form-label">Estudantes</label>
                                        <select name="estudante_id[]" id="estudante_id" class="form-control select2" multiple>
                                            @foreach ($matriculas as $item)
                                            <option value="{{ $item->id }}">{{ $item->numero_estudante }} - {{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <button class="btn btn-primary">Gerar Cartão</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
