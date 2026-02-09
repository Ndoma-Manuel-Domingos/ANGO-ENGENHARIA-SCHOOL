@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Instituições & Bolsas</h1>
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
                    <form action="{{ route('instituicoes.instituicao-bolsas') }}" method="get">
                        @csrf
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-6 col-12">
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
                                
                                <div class="form-group col-md-6 col-12">
                                    <label for="user">Bolsas <span class="text-danger">*</span></label>
                                    <select name="bolsa_id" id="" class="form-control select2" style="width: 100%">
                                        <option value="">Selecionar Bolsas</option>
                                        @foreach ($bolsas_listagem as $item)
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
                        <a href="{{ route('instituicoes.associar-bolsas') }}" class="btn btn-primary">Associar Bolsas a instituição</a>
                    </div>
                    <div class="card-body">
                        <table id="tabelasPermissions" style="width: 100%" class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th style="width: 5%">Id</th>
                                    <th>Bolsa</th>
                                    <th>Codigo</th>
                                    <th>Instituição</th>
                                    <th>Tipo Instituição</th>
                                    <th>Desconto</th>
                                    <th width="10%" class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bolsas as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->bolsa->nome ?? '' }}</td>
                                        <td>{{ $item->bolsa->codigo ?? '' }}</td>
                                        <td>{{ $item->instituicao->nome ?? '' }}</td>
                                        <td class="text-uppercase">{{ $item->instituicao->tipo ?? '' }}</td>
                                        <td>{{ $item->desconto ?? '' }}%</td>
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
                                                    <a href="{{ route('instituicoes.associar-bolsas-editar', Crypt::encrypt($item->id)) }}" title="Editar Bolsa" class="dropdown-item"><i class="fa fa-edit"></i> Editar</a>
                                                    <a href="{{ route('instituicoes.associar-bolsas-delete', Crypt::encrypt($item->id)) }}" title="Eliminar Bolsa" class="dropdown-item text-danger"><i class="fa fa-trash"></i> Eliminar</a>
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
