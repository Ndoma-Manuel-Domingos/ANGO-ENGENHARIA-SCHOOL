@extends('layouts.escolas')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Retirar Saldo do(a) estudante/Levantamento</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($estudante->id)) }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Levantamento</li>
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
                    <form action="{{ route('shcools.remover-saldo-store') }}" method="post">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-4 mb-4">
                                        <label for="saldo_actual" class="form-label">Saldo Actual: </label>
                                        <input type="text" id="saldo_actual" disabled name="saldo_actual" value="{{ $estudante->saldo ?? old('saldo_actual') }}" class="form-control" placeholder="Informe o Saldo:">
                                        @error('saldo_actual')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <input type="hidden" name="credito_estudante" value="{{ $estudante->saldo }}">
                                    
                                    <div class="col-12 col-md-4 mb-4">
                                        <label for="saldo" class="form-label">Saldo a retirar: </label>
                                        <input type="text" id="saldo" name="saldo" value="{{ old('saldo') ?? 0 }}" class="form-control" placeholder="Informe o Saldo:">
                                        @error('saldo')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 col-md-4 mb-4">
                                        <label for="saida_valor_id" class="form-label">Qual serviço o estudante pagou? </label>
                                        <select name="saida_valor_id" id="" class="form-control select2" style="width: 100%">
                                            @foreach ($servicos as $item)
                                            <option value="{{  $item->servico->id }}">{{  $item->servico->servico ?? "" }}</option>
                                            @endforeach
                                        </select>
                                        @error('saida_valor_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 col-md-12 mb-4">
                                        <label for="descricao" class="form-label">Motivo da Retirada do saldo</label>
                                        <textarea type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição (opcional)"></textarea>
                                        @error('descricao')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <input type="hidden" name="estudante_id" value="{{ $estudante->id }}">
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <button class="btn btn btn-primary">Salvar</button>
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
