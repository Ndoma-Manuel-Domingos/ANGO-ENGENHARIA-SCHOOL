@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Lista de depositos do(a) {{ $estudante->nome }} {{ $estudante->sobre_nome }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($estudante->id)) }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Estudantes</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="container-fluid">
    <div class="row">
        
        <div class="col-12 col-md-12">
            <form action="">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12 col-md-4">
                            <label for="">Anos Lectivos</label>
                            <select name="ano_lectivos_id" id="" class="form-control select2" style="width: 100%">
                                <option value="">Selecionar Ano Lectivo</option>
                                @foreach ($anos_lectivos as $item)
                                <option value="{{ Crypt::encrypt($item->id) }}">{{ $item->ano }}</option>
                                @endforeach
                            </select>
                            
                            <input type="hidden" name="estudante_id" value="{{ $estudante->id }}">
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
                <div class="card-body">
                    <table  style="width: 100%" class="table table-bordered  ">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Valor Actual</th>
                                <th>Valor Anterior</th>
                                <th>Operador</th>
                                <th>Estudante</th>
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
                                <td>{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                                <td>{{ $item->date_at }}</td>
                           
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
