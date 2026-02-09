@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Associar Estudantes à Encarregados</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('encarregados.index') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Encarregados</li>
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
            <div class="col-2 col-md-12">
                <form action="{{ route('encarregados.adicionar-estudantes-encarregado-store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <div class="row">
                            
                                <input type="hidden" name="encarregados_id" id="encarregados_id" value="{{ $encarregado->id }}">
                            
                                <div class="form-group col-md-6">
                                    <label for="grau_parentesco">Grau de Parentesco</label>
                                    <input type="text" name="grau_parentesco" class="form-control" id="grau_parentesco" value="{{ old('grau_parentesco') }}" placeholder="informe o grau parentesco">
                                    @error('grau_parentesco')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
    
                                <div class="form-group col-md-6">
                                    <label for="estudantes_id">Estudantes</label>
                                    <select name="estudantes_id[]" id="estudantes_id" multiple class="form-control select2">
                                        <option value="">Selecione</option>
                                            @foreach ($matriculas as $item)
                                            <option value="{{ $item->estudante->id }}">{{ $item->estudante->nome_completo }}</option>
                                            @endforeach
                                    </select>
                                    @error('estudantes_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                             
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
</script>
@endsection
