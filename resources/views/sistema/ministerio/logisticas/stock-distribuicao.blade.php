@extends('layouts.admin')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Distribuição de Mercadorias</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('web.stock-mercadorias') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Distritos</li>
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
                    <form action="{{ route('web.stock-mercadorias-distribuica-post') }}" method="post">
                        <div class="card-body">
                            <div class="row">
                                @csrf
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="mercadoria_id">Mercadoria</label>
                                    <select name="mercadoria_id" class="form-control mercadoria_id select2" id="mercadoria_id">
                                        <option value="">Selecionar</option>
                                        @foreach ($mercadorias as $item)
                                        <option value="{{ $item->id }}">{{ $item->designacao }}</option>
                                        @endforeach
                                    </select>
                                    @error('mercadoria_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="quantidade">Quantidade</label>
                                    <input type="number" name="quantidade" class="form-control" id="quantidade" placeholder="Quantidade de Mercadorias">
                                    @error('quantidade')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group col-md-4 col-12">
                                    <label for="unidade">Unidades</label>
                                    <select name="unidade" class="form-control unidade" id="unidade">
                                        <option value="unidade">Unidades</option>
                                        <option value="grosso">A Grosso</option>
                                    </select>
                                    @error('unidade')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4 col-12">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control status" id="status">
                                        <option value="entrada">Entrada</option>
                                        <option value="saida" selected>Saída</option>
                                    </select>
                                    @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-3 col-md-4 col-12">
                                    <label for="instituicao_id" class="form-label">Tipo de Instituições <span class="text-danger">*</span></label>
                                    <select name="instituicao_id" id="instituicao_id" class="form-control instituicao_id select2">
                                        <option value="">Selecione</option>
                                        @foreach ($tipo_instituicoes as $item)
                                        <option value="{{ $item->id }}" {{ old('instituicao_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('instituicao_id')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                     
                                <div class="form-group mb-3 col-md-4 col-12">
                                    <label for="instituicoes_destino" class="form-label">Instituição Destino <span class="text-danger">*</span></label>
                                    <select name="instituicoes_destino" id="instituicoes_destino" class="form-control instituicoes_destino select2">
                                    </select>
                                    @error('instituicoes_destino')
                                      <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                
                                <div class="form-group col-md-12 col-12">
                                    <label for="descricao">Descrição</label>
                                    <textarea name="descricao" id="" cols="30" rows="4" class="form-control" placeholder="Descrição sobre a entrada de mercadorias"></textarea>
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


@section('scripts')
  <script>
    
    $("#instituicao_id").change(()=>{
      let id = $("#instituicao_id").val();
      $.get('../carregar-destino-funcionarios/'+id, function(data){
          $("#instituicoes_destino").html("")
          $("#instituicoes_destino").html(data)
      })
    })
    
  </script>
@endsection