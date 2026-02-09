@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Detalhe Tipo de Estagio</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('instituicoes_estagios.tipo-estagio') }}">Listagem</a></li>
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
                                <i class="fas fa-globe"></i> {{ $bolsa->nome }}.
                                {{-- <small class="float-right">Date: 2/10/2014</small> --}}
                            </h4>
                        </div>

                    </div>

                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <br>
                            <address>
                                <strong>Codigo:</strong> {{ $bolsa->codigo }}<br>
                                <strong>Estado:</strong> {{ $bolsa->status }}<br>
                            </address>
                        </div>

                        <div class="col-sm-4 invoice-col">
                            <br>
                            <address>
                                <strong>Descrição:</strong><br>
                                {{ $bolsa->endereco }}
                            </address>
                        </div>

                    </div>

                    {{-- <div class="row">
                        <div class="col-12 col-md-12">
                            <h4>Instituições Associadas</h4>
                        </div>
                        <div class="col-12 col-md-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Bolsa</th>
                                        <th>Codigo</th>
                                        <th>Descrição</th>
                                        <th>Desconto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Call of Duty</td>
                                        <td>455-981-221</td>
                                        <td>El snort testosterone trophy driving gloves handsome</td>
                                        <td>$64.50</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Need for Speed IV</td>
                                        <td>247-925-726</td>
                                        <td>Wes Anderson umami biodiesel</td>
                                        <td>$50.00</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Monsters DVD</td>
                                        <td>735-845-642</td>
                                        <td>Terry Richardson helvetica tousled street art master</td>
                                        <td>$10.70</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>Grown Ups Blue Ray</td>
                                        <td>422-568-642</td>
                                        <td>Tousled lomo letterpress</td>
                                        <td>$25.99</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    </div> --}}

                </div>

            </div>
        </div>
    </div>
</section>

<!-- /.content-header -->

@endsection
