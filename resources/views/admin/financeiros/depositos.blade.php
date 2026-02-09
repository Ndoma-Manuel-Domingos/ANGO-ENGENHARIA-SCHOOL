@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Depositos</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Depositos</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">

            <div class="col-12 col-md-12">
                <form action="{{ route('web.financeiro-depositos') }}" method="get">
                    @csrf
                    <div class="card">
                        <div class="card-body row">
                            
                            <div class="col-12 col-md-3">
                                <label for="">Operadores</label>
                                <select name="ano_lectivos_id" id="" class="form-control select2" style="width: 100%">
                                    <option value="">Seleciona Operador</option>
                                    @foreach ($anos_lectivos as $item)
                                    <option value="{{ Crypt::encrypt($item->id) }}">{{ $item->ano }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                            <div class="col-12 col-md-3">
                                <label for="">Anos Lectivos</label>
                                <select name="ano_lectivos_id" id="" class="form-control select2" style="width: 100%">
                                    <option value="">Seleciona Ano Lectivo</option>
                                    @foreach ($anos_lectivos as $item)
                                    <option value="{{ Crypt::encrypt($item->id) }}">{{ $item->ano }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-12 col-md-3 mb-4">
                                <label for="data_inicio" class="form-label">Data Inicio: </label>
                                <input type="date" id="data_inicio" name="data_inicio" class="form-control">
                                @error('data_inicio')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="col-12 col-md-3 mb-4">
                                <label for="data_final" class="form-label">Data Final: </label>
                                <input type="date" id="data_final" name="data_final" class="form-control" >
                                @error('data_final')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Pesquisar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-12 col-md-12">

                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('shcools.pesquisar-estudante-index') }}" class="btn btn-primary">Novos Depositos</a>
                        <a href="" class="btn btn-primary">Imprimir</a>
                    </div>
                    <div class="card-body">
                        <table style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>Valor Actual</th>
                                    <th>Valor Anterior</th>
                                    <th>Operador</th>
                                    <th>Estudante</th>
                                    <th>Bilhete</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($depositos as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ number_format($item->valor, '2', ',', '.')  }} kz</td>
                                    <td>{{ number_format($item->valor_anterior, '2', ',', '.')  }} kz</td>
                                    <td>{{ $item->operador->nome }} {{ $item->operador->sobre_nome }}</td>
                                    <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</a></td>
                                    <td>{{ $item->estudante->bilheite }}</td>
                                    <td>{{ $item->date_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer">
                        <h6>Total de Registro: {{ count($depositos) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>

@endsection
