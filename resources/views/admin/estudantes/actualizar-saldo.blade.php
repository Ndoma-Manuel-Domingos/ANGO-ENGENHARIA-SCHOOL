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
                    <form action="{{ route('shcools.actualizar-saldo-store') }}" method="post">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="saldo" class="form-label">Saldo: </label>
                                        <input type="text" id="saldo" name="saldo" value="{{ old('saldo') ?? 0 }}" class="form-control" placeholder="Informe o Saldo:">
                                        @error('saldo')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 col-md-6 mb-4">
                                        <label for="saldo" class="form-label">Forma de Pagamento: </label>
                                        <select name="forma_de_pagamento" id="" class="form-control select2" style="width: 100%">
                                            <option value="">Forma Pagamento</option>
                                            <option value="MB">Multicaixa</option>
                                            <option value="NU">Númerario</option>
                                        </select>
                                        @error('forma_de_pagamento')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12 col-md-12 mb-4">
                                        <label for="descricao" class="form-label">Descrição(opcional) </label>
                                        <textarea type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição:"></textarea>
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
