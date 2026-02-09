@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Pesquisar estudantes</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('tesourarias.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Estudantes</li>
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
                <form class="mb-3" method="get" action="{{ route('financeiros.financeiro-pagamentos-propina') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h5> <strong>NOTA: </strong> Pesquisa o Estudante pelo número de Matricula, número do bilheite/Cedula, número de referência, nome e Sobrenome!</h5>
                        </div>
                        <div class="card-body">
                            <div class="col-12 col-md-12">
                                <div class="input-group">
                                    <input type="search" name="search" id="search" value="{{ $requests['search'] ?? "" }}" class="form-control form-control-lg" placeholder="Informe o nome, número do Bilhete de Identidade/Cédula, número de referência e sobrenome.">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-lg btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </form>
            </div>
        </div>
        @if ($matriculas)
        <div class="row">
            <div class="col-12 col-md-12">
                @foreach ($matriculas as $item)
                <div class="card">
                    <div class="card-header">
                        <h5>
                            <i class="fas fa-graduation-cap"></i> {{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}
                        </h5>
                    </div>

                    <div class="card-body">
                        <form action="">
                            <div class="row">

                                <div class="col-12 col-md-6 col-lg-2 mb-3">
                                    <label for="" class="form-label">Genero:</label>
                                    <input type="text" value="{{ $item->estudante->genero }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-6 col-lg-2 mb-3">
                                    <label for="" class="form-label">Nascimento:</label>
                                    <input type="text" value="{{ $item->estudante->nascimento }}" disabled class="form-control">
                                </div>


                                <div class="col-12 col-md-6 col-lg-2 mb-3">
                                    <label for="" class="form-label">Nacionalidade:</label>
                                    <input type="text" value="{{ $item->estudante->nacionalidade }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-6 col-lg-2 mb-3">
                                    <label for="" class="form-label">B.I:</label>
                                    <input type="text" value="{{ $item->estudante->bilheite }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-6 col-lg-2 mb-3">
                                    <label for="" class="form-label">Tel Estudante:</label>
                                    <input type="text" value="{{ $item->estudante->telefone_estudante }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-6 col-lg-2 mb-3">
                                    <label for="" class="form-label">Turma:</label>
                                    <input type="text" value="{{ $item->turma($item->estudantes_id) }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-6 col-lg-2 mb-3">
                                    <label for="" class="form-label">Sala:</label>
                                    <input type="text" value="{{ $item->sala($item->estudantes_id) }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-6 col-lg-2 mb-3">
                                    <label for="" class="form-label">Curso:</label>
                                    <input type="text" value="{{ $item->curso->curso }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-6 col-lg-2 mb-3">
                                    <label for="" class="form-label">Classe:</label>
                                    <input type="text" value="{{ $item->classe->classes }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-6 col-lg-2 mb-3">
                                    <label for="" class="form-label">Turno:</label>
                                    <input type="text" value="{{ $item->turno->turno }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-4 mb-3">
                                    <label for="" class="form-label">Processo Nº:</label>
                                    <input type="text" value="{{ $item->numero_estudante }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-4 mb-3">
                                    <label for="" class="form-label">Pai:</label>
                                    <input type="text" value="{{ $item->estudante->pai }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-4 mb-3">
                                    <label for="" class="form-label">Mãe:</label>
                                    <input type="text" value="{{ $item->estudante->mae }}" disabled class="form-control">
                                </div>

                                <div class="col-12 col-md-4 mb-3">
                                    <label for="" class="form-label">Telefone Pai e Mãe:</label>
                                    <input type="text" value="{{ $item->estudante->telefone_pai }} {{ $item->estudante->telefone_mae }}" disabled class="form-control">
                                </div>

                            </div>
                        </form>
                    </div>

                    <div class="card-footer">

                        {{-- @if (Auth::user()->can('read: pagamento'))
                      <a href="{{ route('web.sistuacao-financeiro', Crypt::encrypt($item->estudante->id)) }}" class="btn btn-primary">Ver Extrato Financeiro</a>
                        @endif --}}

                        @if (Auth::user()->can('create: pagamento'))
                        <a href="{{ route('web.estudantes-pagamento-propina', Crypt::encrypt($item->estudante->id)) }}" class="btn btn-app bg-primary">
                            <i class="fas fa-credit-card"></i>
                            Fazer Pagamentos
                        </a>
                        @endif

                        @if (Auth::user()->can('create: factura'))
                        <a href="{{ route('web.facturar-pagamento-servico', $item->estudante->id) }}" class="btn btn-app bg-primary">
                            <i class="fas fa-file-alt"></i>
                            Facturar
                        </a>
                        @endif
                        @if (Auth::user()->can('read: factura'))
                        <a href="{{ route('web.liquidar-factura') }}" class="btn btn-app bg-primary">
                            <i class="fas fa-file-invoice-dollar"></i>
                            Liquidar Facturas
                        </a>
                        @endif
                        {{-- @if (Auth::user()->can('read: pautas'))
                        <a href="{{ route('web.pauta-estudante', Crypt::encrypt($item->estudante->id)) }}" class="btn btn-primary">Pauta</a>
                        @endif --}}

                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')

@endsection
