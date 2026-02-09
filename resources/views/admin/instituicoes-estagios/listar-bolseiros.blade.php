@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Listagem Bolseiros</h1>
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
                <div class="card">
                    <form action="{{ route('instituicoes.instituicao-listar-bolseiros') }}" method="get">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-3 col-12">
                                    <label for="user">Instituição <span class="text-danger">*</span></label>
                                    <select name="instituicao_id" id="" class="form-control select2" style="width: 100%">
                                        <option value="">Selecionar Instituição</option>
                                        @foreach ($instituicoes as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('instituicao_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-3 col-12">
                                    <label for="user">Bolsas <span class="text-danger">*</span></label>
                                    <select name="bolsa_id" id="" class="form-control select2" style="width: 100%">
                                        <option value="">Selecionar Bolsas</option>
                                        @foreach ($bolsas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('bolsa_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button class="btn btn-primary">Pesquisar</button>
                        </div>
                    </form>
                </div>
            
            </div>
        
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('web.estudante-atribuir-bolsa') }}" class="btn btn-primary">Atribuir Bolsairo</a>
                    </div>
                    <div class="card-body">
                        <table id="tabelasPermissions" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Id</th>
                                    <th>Nº Processo</th>
                                    <th>Estudante</th>
                                    <th>Idade</th>
                                    <th>Bolsa</th>
                                    <th>Instituição</th>
                                    <th>Tipo Instituição</th>
                                    <th>Desconto</th>
                                    <th>Período</th>
                                    <th>Estado</th>
                                    <th width="10%" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bolseiros as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->estudante->numero_processo }}</td>
                                        <td><a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}">{{ $item->estudante->nome ?? '' }} {{ $item->estudante->sobre_nome ?? '' }}</a></td>
                                        <td>{{ $item->estudante->idade($item->estudante->nascimento)  }} </td>
                                        <td>{{ $item->bolsa->nome ?? '' }}</td>
                                        <td>{{ $item->instituicao->nome ?? '' }}</td>
                                        <td>{{ $item->instituicao->tipo ?? '' }}</td>
                                        <td>{{ $item->instituicao_bolsa->desconto ?? '' }}%</td>
                                        <td>{{ $item->periodo->trimestre ?? '' }}</td>
                                        @if ($item->status == "activo")
                                        <td class="text-success text-uppercase">{{ $item->status ?? '' }}</td>
                                        @else
                                        <td class="text-danger text-uppercase">{{ $item->status ?? '' }}</td>
                                        @endif
                                        <td class="text-end">
                                            
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info">Opções</button>
                                                <button type="button"
                                                    class="btn btn-info dropdown-toggle dropdown-icon"
                                                    data-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                
                                                    {{-- @if (Auth::user()->can('update: estudante')) --}}
                                                    <a href="{{ route('web.estudante-editar-bolseiro-bolsa', Crypt::encrypt($item->id)) }}" title="Editar Bolsa" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                    <a href="{{ route('shcools.mais-informacao-estudante', Crypt::encrypt($item->estudante->id)) }}" title="Visualizar Bolsa" class="dropdown-item"><i class="fa fa-edit"></i> Visualizar</a>
                                                    <a href="{{ route('instituicoes.instituicao-remover-bolsa-bolseiros', Crypt::encrypt($item->id)) }}" title="Eliminar Bolsa" class="dropdown-item text-danger"><i class="fa fa-trash"></i> Remover Bolsa</a>
                                                    {{-- @endif --}}

                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#"><i class="fas fa-outdent"></i> Outros</a>
                                                </div>
                                            </div>
                                        
                                        </td>
                                    </tr>   
                                @endforeach                          
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    
    </div>
</section>

<!-- /.content-header -->

@endsection
