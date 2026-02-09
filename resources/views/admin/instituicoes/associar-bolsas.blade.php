@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Associar Bolsas a Instituição</h1>
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
                @if(session()->has('danger'))
                <div class="alert alert-warning">
                    {{ session()->get('danger') }}
                </div>
                @endif

                @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
                @endif
            </div>
            <div class="col-12 col-md-12 mb-3">
                <form action="{{ route('instituicoes.associar-bolsas-store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-4 col-12">
                                    <label for="user">Instituição <span class="text-danger">*</span></label>
                                    <select name="instituicao_id" id="" class="form-control select2" style="width: 100%">
                                        <option value="">Selecionar Instituição</option>
                                        @foreach ($instituicoes as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == ($institicao->id ?? "")  ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('instituicao_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="user">Bolsas <span class="text-danger">*</span></label>
                                    <select name="bolsa_id[]" id="" class="form-control select2" multiple style="width: 100%">
                                        <option value="">Selecionar Bolsas</option>
                                        @foreach ($bolsas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('bolsa_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="user">Desconto <span class="text-danger">*</span></label>
                                    <input type="number" name="desconto" value="" min="5" max="100" placeholder="Informe o desconto. Ex: 100" class="form-control">
                                    @error('desconto')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- /.content-header -->

@endsection
