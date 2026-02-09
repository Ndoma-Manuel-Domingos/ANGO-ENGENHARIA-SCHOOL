@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Detalhe Instituições Educacionais</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('instituicoes.instituicao') }}">Listagem</a></li>
                    <li class="breadcrumb-item active">Detalhe</li>
                </ol>
            </div><!-- /.col -->
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">

                <div class="invoice p-3 mb-3">

                    <div class="row">
                        <div class="col-12 col-md-12">
                            <h4>
                                <i class="fas fa-globe"></i> {{ $institicao->nome }}.
                                {{-- <small class="float-right">Date: 2/10/2014</small> --}}
                            </h4>
                        </div>

                    </div>

                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <br>
                            <address>
                                <strong>Email:</strong> {{ $institicao->email }}<br>
                                <strong>NIF:</strong> {{ $institicao->nif }}<br>
                                <strong>Tipo:</strong> {{ $institicao->tipo }}<br>
                                <strong>Estado:</strong> {{ $institicao->status }}<br>
                            </address>
                        </div>

                        <div class="col-sm-4 invoice-col">
                            <br>
                            <address>
                                <strong>Endereço:</strong><br>
                                {{ $institicao->endereco }}
                                
                                <strong><br>Director:</strong>
                                {{ $institicao->director }}
                            </address>
                        </div>
                        
                        <div class="col-sm-4 invoice-col">
                            <a href="{{ route('instituicoes.associar-bolsas', Crypt::encrypt($institicao->id)) }}" class="btn btn-primary btn float-end">Associar Bolsas a esta instituição</a>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-12 col-md-12">
                            <h4>Bolsas Associadas</h4>
                        </div>
                        <div class="col-12 col-md-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Bolsa</th>
                                        <th>Codigo</th>
                                        <th>Instituição</th>
                                        <th>Desconto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bolsas as $item)
                                        <tr>
                                            <td>1</td>
                                            <td>{{ $item->bolsa->nome ?? '' }}</td>
                                            <td>{{ $item->bolsa->codigo ?? '' }}</td>
                                            <td>{{ $item->instituicao->nome ?? '' }}</td>
                                            <td>{{ $item->desconto ?? '' }}%</td>
                                        </tr>
                                        
                                    @endforeach
                                  
                                </tbody>
                            </table>
                        </div>
                        
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>

<!-- /.content-header -->

@endsection
