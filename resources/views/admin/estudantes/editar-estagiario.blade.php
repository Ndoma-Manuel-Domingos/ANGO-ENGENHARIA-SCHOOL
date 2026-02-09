@extends('layouts.escolas')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Editar Estagiario</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('instituicoes_estagios.instituicao-listar-estagiarios') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Estagios</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row"> 
        <div class="col-12 col-md-12 mb-3">
            <form action="{{ route('web.estudante-atribuir-estagiario-update', $estagiario->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="row">
                        
                            <div class="form-group col-md-3 col-12">
                                <label for="estudante_id">Estudante <span class="text-danger">*</span></label>
                                <select name="estudante_id" id="estudante_id" class="form-control select2" style="width: 100%">
                                    <option value="">Selecionar estado</option>
                                    @foreach ($estudantes as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($estagiario->estudante_id ?? "") ? 'selected' : '' }}>{{ $item->nome }} {{ $item->sobre_nome }}</option>
                                    @endforeach
                                </select>
                                @error('estudante_id')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-3 col-12">
                                <label for="instituicao_id">Instituições <span class="text-danger">*</span></label>
                                <select name="instituicao_id" id="instituicao_id" class="form-control select2" style="width: 100%">
                                    @foreach ($instituicoes as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($estagiario->instituicao_id ?? "") ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('instituicao_id')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-3 col-12">
                                <label for="estagio_id">Estagios <span class="text-danger">*</span></label>
                                <select name="estagio_id" id="estagio_id" class="form-control select2" style="width: 100%">
                                    @foreach ($estagios as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($estagiario->estagio_id ?? "") ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('estagio_id')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div> 
                            
                            <div class="form-group col-md-3 col-12">
                                <label for="ano_lectivos_id">Ano Lectivo <span class="text-danger">*</span></label>
                                <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control select2" style="width: 100%">
                                    @foreach ($anos_lectivos as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($estagiario->ano_lectivos_id ?? "") ? 'selected' : '' }}>{{ $item->ano }}</option>
                                    @endforeach
                                </select>
                                @error('ano_lectivos_id')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            
                          
                            <div class="form-group col-md-3 col-12">
                                <label for="data_inicio">Data Inicio <span class="text-danger">*</span></label>
                                <input type="date" name="data_inicio" value="{{ $estagiario->data_inicio }}" id="data_inicio" class="form-control">
                                @error('data_inicio')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                          
                            <div class="form-group col-md-3 col-12">
                                <label for="data_final">Data Final <span class="text-danger">*</span></label>
                                <input type="date" name="data_final" value="{{ $estagiario->data_final }}" id="data_final" class="form-control">
                                @error('data_final')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-3 col-12">
                                <label for="status">Estado <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%">
                                    <option value="activo" {{ $estagiario->status == "activo" ? 'selected' : '' }}>Activo</option>
                                    <option value="desactivo" {{ $estagiario->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
                                </select>
                                @error('status')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                                                      
                            <div class="form-group col-md-3 col-12">
                                <label for="pago_at">Pago <span class="text-danger">*</span></label>
                                <select name="pago_at" id="pago_at" class="form-control select2" style="width: 100%">
                                    <option value="pago" {{ $estagiario->pago_at == "pago" ? 'selected' : '' }}>Pago</option>
                                    <option value="nao_pago" {{ $estagiario->pago_at == "nao_pago" ? 'selected' : '' }}>Não Pago</option>
                                </select>
                                @error('pago_at')
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
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection


@section('scripts')

<script>

    $("#instituicao_id").change(()=>{
      let id = $("#instituicao_id").val();
      $.get('../../carregar-bolsas-instituicao/'+id, function(data){
          $("#bolsa_id").html("")
          $("#bolsa_id").html(data)
      })
    })
    
</script>


@endsection