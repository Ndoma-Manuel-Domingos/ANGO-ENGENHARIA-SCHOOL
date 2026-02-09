@extends('layouts.escolas')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Editar Bolseiro</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('creditos-educacionais.instituicao-listar-bolseiros') }}">Voltar</a></li>
            <li class="breadcrumb-item active">Bolsa</li>
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
            <form action="{{ route('web.estudante-atribuir-bolsa-update', $bolseiro->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="row">
                        
                            <div class="form-group col-md-3 col-12">
                                <label for="user">Estudante <span class="text-danger">*</span></label>
                                <select name="estudante_id" id="" class="form-control select2" style="width: 100%">
                                    <option value="">Selecionar estado</option>
                                    @foreach ($estudantes as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($bolseiro->estudante_id ?? "") ? 'selected' : '' }}>{{ $item->nome }} {{ $item->sobre_nome }}</option>
                                    @endforeach
                                </select>
                                @error('estudante_id')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-3 col-12">
                                <label for="user">Instituições <span class="text-danger">*</span></label>
                                <select name="instituicao_id" id="instituicao_id" class="form-control select2" style="width: 100%">
                                    <option value="">Selecionar estado</option>
                                    @foreach ($instituicoes as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($bolseiro->instituicao_id ?? "") ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('instituicao_id')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-3 col-12">
                                <label for="bolsa_id">Bolsas <span class="text-danger">*</span></label>
                                <select name="bolsa_id" id="bolsa_id" class="form-control select2" style="width: 100%">
                                    <option value="">Selecionar estado</option>
                                    @foreach ($bolsas as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($bolseiro->bolsa_id ?? "") ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('bolsa_id')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div> 
                            
                            <div class="form-group col-md-3 col-12">
                                <label for="user">Ano Lectivo <span class="text-danger">*</span></label>
                                <select name="ano_lectivos_id" id="" class="form-control select2" style="width: 100%">
                                    <option value="">Selecionar Ano Lectivo</option>
                                    @foreach ($anos_lectivos as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($bolseiro->ano_lectivos_id ?? "") ? 'selected' : '' }}>{{ $item->ano }}</option>
                                    @endforeach
                                </select>
                                @error('ano_lectivos_id')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-3 col-12">
                                <label for="user">Período da Bolsa <span class="text-danger">*</span></label>
                                <select name="periodo_bolsa" id="" class="form-control select2" style="width: 100%">
                                    <option value="">Selecionar período</option>
                                    @foreach ($trimestres as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($bolseiro->periodo_id ?? "") ? 'selected' : '' }}>{{ $item->trimestre }}</option>
                                    @endforeach
                                </select>
                                @error('periodo_bolsa')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div> 
                            
                            
                            <div class="form-group col-md-3 col-12">
                                <label for="user">Afectação <span class="text-danger">*</span></label>
                                <select name="afectacao" id="" class="form-control select2" style="width: 100%">
                                    <option value="">Selecionar Afectação</option>
                                    <option value="mensalidade" {{ $bolseiro->afectacao == "mensalidade" ? 'selected' : '' }}>Mensalidades</option>
                                    <option value="global" {{ $bolseiro->afectacao == "global" ? 'selected' : '' }}>Globais</option>
                                </select>
                                @error('afectacao')
                                <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-6 col-12">
                                <label for="status">Estado <span class="text-danger">*</span></label>
                                <select name="status" id="" class="form-control select2" style="width: 100%">
                                    <option value="">Selecionar Afectação</option>
                                    <option value="activo" {{ $bolseiro->status == "activo" ? 'selected' : '' }}>Activo</option>
                                    <option value="desactivo" {{ $bolseiro->status == "desactivo" ? 'selected' : '' }}>Desactivo</option>
                                </select>
                                @error('status')
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