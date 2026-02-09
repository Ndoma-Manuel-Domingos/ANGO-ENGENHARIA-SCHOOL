@extends('layouts.provinciais')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Distribuição de Professor</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                  <li class="breadcrumb-item active">Distribuição</li>
                </ol>
            </div><!-- /.col -->
        </div>

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

            <div class="col-12 col-md-12">
                <form action="{{ route('app.dispanho-professores-provincial-store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-body row">
                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Escolas</label>
                                <select name="escola_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($escolas as $item)
                                        <option value="{{ $item->id }}" {{ old('escola_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('escola_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Professores</label>
                                <select name="professor_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($professores as $item)
                                        <option value="{{ $item->id }}" {{ old('professor_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}  {{ $item->sobre_nome }}</option>
                                    @endforeach
                                </select>
                                @error('professor_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            
                            
                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Departamentos</label>
                                <select name="departamento_id" class="form-control select2 " id="departamento_id">
                                    <option value="">Todos</option>
                                    @foreach ($departamentos as $item)
                                        <option value="{{ $item->id }}" {{ old('departamento_id') == $item->id ? 'selected' : '' }}>{{ $item->departamento }}</option>
                                    @endforeach
                                </select>
                                @error('departamento_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Cargos</label>
                                <select name="cargo_id" class="form-control select2" id="cargo_id">
                                    <option value="">Todos</option>
                                    @foreach ($cargos as $item)
                                        <option value="{{ $item->id }}" {{ old('cargo_id') == $item->id ? 'selected' : '' }}>{{ $item->cargo }}</option>
                                    @endforeach
                                </select>
                                @error('cargo_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Confirmar Distribuição</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<!-- /.content-header -->

@endsection


@section('scripts')
<script>
    
    $("#departamento_id").change(()=>{
      let id = $("#departamento_id").val();
      $.get('../carregar-cargos-departamentos/'+id, function(data){
          $("#cargo_id").html("")
          $("#cargo_id").html(data)
      })
    })
    
    
</script>
@endsection