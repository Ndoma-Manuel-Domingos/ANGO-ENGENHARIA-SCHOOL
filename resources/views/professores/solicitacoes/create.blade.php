@extends('layouts.professores')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Fazer solicitações</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="{{ route('prof.home-profs') }}">Voltar</a></li>
                  <li class="breadcrumb-item active">Solicitações</li>
                </ol>
            </div>
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
            <div class="col-12 mb-3">
                <form action="{{ route('prof.solicitacoes-processo-store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-2 col-12">
                                    <label for="tipo_documento">Tipo Documento</label>
                                    <select name="tipo_documento" class="form-control select2" style="width: 100%;">
                                        <option value="">Selecione</option>
                                        <option value="vagas" {{ old('tipo_documento') == 'vagas' ? 'selected' : '' }}>Solicitação de Vagas</option>
                                        <option value="transferencia" {{ old('tipo_documento') == 'transferencia' ? 'selected' : '' }}>Solicitação de transferência</option>
                                        <option value="outros" {{ old('tipo_documento') == 'outros' ? 'selected' : '' }}>Outros</option>
                                    </select>
                                    @error('tipo_documento')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="instituicao_id">Tipo Instituições</label>
                                    <select name="instituicao_id" id="instituicao_id" class="form-control select2" style="width: 100%;">
                                        <option value="">Selecione</option>
                                        @foreach ($instituicoes as $item)
                                        <option value="{{ $item->id }}" {{ old('instituicao_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('instituicao_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group mb-3 col-md-4 col-12">
                                    <label for="instituicoes_destino" class="form-label">Instituição Destino</label>
                                    <select name="instituicoes_destino" id="instituicoes_destino" class="form-control instituicoes_destino select2">
                                    </select>
                                    @error('instituicoes_destino')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3 col-md-4 col-12">
                                    <label for="escola_transferencia_id" class="form-label">Escola para transferência/Vaga</label>
                                    <select name="escola_transferencia_id" id="escola_transferencia_id" class="form-control escola_transferencia_id select2">
                                        <option value="">Selecione</option>
                                        @foreach ($escolas as $item)
                                        <option value="{{ $item->id }}" {{ old('escola_transferencia_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('escola_transferencia_id')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4 col-12">
                                    <label for="disciplinas_id">Disciplinas</label>
                                    <select name="disciplinas_id" class="form-control select2" style="width: 100%;">
                                        <option value="">Selecione</option>
                                        @foreach ($disciplinas as $item)
                                        <option value="{{ $item->id }}" {{ old('disciplinas_id') == $item->id ? 'selected' : '' }}>{{ $item->disciplina }}</option>
                                        @endforeach
                                    </select>
                                    @error('disciplinas_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4 col-12">
                                    <label for="cursos_id">Cursos</label>
                                    <select name="cursos_id" class="form-control select2" style="width: 100%;">
                                        <option value="">Selecione</option>
                                        @foreach ($cursos as $item)
                                        <option value="{{ $item->id }}" {{ old('cursos_id') == $item->id ? 'selected' : '' }}>{{ $item->curso }}</option>
                                        @endforeach
                                    </select>
                                    @error('cursos_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="classes_id">Classes</label>
                                    <select name="classes_id" class="form-control select2" style="width: 100%;">
                                        <option value="">Selecione</option>
                                        @foreach ($classes as $item)
                                        <option value="{{ $item->id }}" {{ old('classes_id') == $item->id ? 'selected' : '' }}>{{ $item->classes }}</option>
                                        @endforeach
                                    </select>
                                    @error('classes_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-2 col-12">
                                    <label for="documento">Documento PDF</label>
                                    <input type="file" name="documento" value="{{ old('documento') }}" class="form-control" placeholder="Escreve uma descrição da sua solicitação">
                                    @error('documento')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-12 col-12">
                                    <label for="password_2">Descrição</label>
                                    <textarea name="descricao" cols="30" rows="2" class="form-control" placeholder="Escreve uma descrição da sua solicitação">{{ old('descricao') }}</textarea>
                                    @error('descricao')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Solicitar Documento</button>
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
    $("#provincia_id").change(() => {
        let id = $("#provincia_id").val();
        $.get('carregar-municipios/' + id, function(data) {
            $("#municipio_id").html("")
            $("#municipio_id").html(data)
        })
    })

    $("#municipio_id").change(() => {
        let id = $("#municipio_id").val();
        $.get('carregar-distritos/' + id, function(data) {
            $("#distrito_id").html("")
            $("#distrito_id").html(data)
        })
    })

    $("#instituicao_id").change(() => {

        let id = $("#instituicao_id").val();
        $.get('../carregar-destino-funcionarios/' + id, function(data) {
            $("#instituicoes_destino").html("")
            $("#instituicoes_destino").html(data)
        })
    })

    $("#departamento_id").change(() => {
        let id = $("#departamento_id").val();
        $.get('carregar-cargos-departamentos/' + id, function(data) {
            $("#cargo_id").html("")
            $("#cargo_id").html(data)
        })
    })

</script>
@endsection
