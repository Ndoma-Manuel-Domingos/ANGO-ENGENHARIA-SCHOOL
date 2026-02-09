@extends('layouts.admin')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Editar Stock de Mercadorias</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('web.stock-mercadorias') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Mercadoria</li>
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
                <div class="card">
                    <form action="{{ route('web.stock-mercadorias-post') }}" method="post">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                @method('put')
                                <div class="form-group col-md-4 col-12">
                                    <label for="mercadoria_id">Mercadoria</label>
                                    <select name="mercadoria_id" class="form-control mercadoria_id select2" id="mercadoria_id">
                                        <option value="">Selecionar</option>
                                        @foreach ($mercadorias as $item)
                                        <option value="{{ $item->id }}" {{ $stock->mercadoria_id == $item->id ? 'selected': '' }}>{{ $item->designacao }}</option>
                                        @endforeach
                                    </select>
                                    @error('mercadoria_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="quantidade">Quantidade</label>
                                    <input type="number" name="quantidade" value="{{ $stock->quantidade ?? '' }}" class="form-control" id="quantidade" placeholder="Quantidade de Mercadorias">
                                    @error('quantidade')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="unidade">Unidades</label>
                                    <select name="unidade" class="form-control unidade" id="unidade">
                                        <option value="unidade" {{ $stock->unidade == "unidade" ? 'selected': '' }}>Unidades</option>
                                        <option value="grosso" {{ $stock->unidade == "grosso" ? 'selected': '' }}>A Grosso</option>
                                    </select>
                                    @error('unidade')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4 col-12">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control status" id="status">
                                        <option value="entrada" {{ $stock->status == "entrada" ? 'selected': '' }}>Entrada</option>
                                        <option value="saida" {{ $stock->status == "saida" ? 'selected': '' }}>Saída</option>
                                    </select>
                                    @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="fornecedor_id">Fornecedores</label>
                                    <select name="fornecedor_id" class="form-control fornecedor_id select2" id="fornecedor_id">
                                        <option value="">Selecionar</option>
                                        @foreach ($fornecedores as $item)
                                        <option value="{{ $item->id }}" {{ $stock->fornecedor_id == $item->id ? 'selected': '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('fornecedor_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-12 col-12">
                                    <label for="descricao">Descrição</label>
                                    <textarea name="descricao" id="" cols="30" rows="4" class="form-control" placeholder="Descrição sobre a entrada de mercadorias">{{ $stock->descricao ?? '' }}</textarea>
                                    @error('descricao')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                            </div>
                        </div>
                        <div class="card-footer justify-content-between">
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

