@extends('layouts.site')

@section('content')

<div class="container content mt-5">
    <h2 class="text-center">Inscrição no Curso</h2>
    <form class="mt-4" method="POST" action="{{ route('site.formulario-candidatura-inscricoes-post') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="form-group col-12 col-md-6 mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="nome" value="{{ old('nome') }}" placeholder="Informe o Nome" class="form-control @error('nome') is-invalid @enderror" >
            </div>
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Sobre Nome</label>
                <input type="text" name="sobre_nome" value="{{ old('sobre_nome') }}" placeholder="Informe o seu sobre nome" class="form-control @error('sobre_nome') is-invalid @enderror" >
            </div>
            
            <input type="hidden" name="shcools_id" value="{{ $shcools->id }}">
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Nº do Documento (B.I, CEDULA)</label>
                <input type="text" placeholder="Nº do seu documento BI, CEDULA, PASSAPORTE" value="{{ old('bilheite') }}" name="bilheite" class="form-control @error('bilheite') is-invalid @enderror" >
            </div>
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Data de Nascimento</label>
                <input type="date" name="data_nascimento" class="form-control @error('data_nascimento') is-invalid @enderror" value="{{ old('data_nascimento') }}" >
            </div>
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Gênero</label>
                <select class="form-control @error('genero') is-invalid @enderror" name="genero" >
                    <option value="Masculino" {{ old('data_nascimento') == "Masculino" ? 'selected' : "" }}>Masculino</option>
                    <option value="Feminino" {{ old('data_nascimento') == "Feminino" ? 'selected' : "" }}>Feminino</option>
                </select>
            </div>
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Estado Cívil</label>
                <select class="form-control @error('estado_civil') is-invalid @enderror" name="estado_civil" >
                    <option value="Solteiro" {{ old('estado_civil') == "Solteiro" ? 'selected' : "" }}>Solteiro(a)</option>
                    <option value="Casado" {{ old('estado_civil') == "Casado" ? 'selected' : "" }}>Casado(a)</option>
                    <option value="Divorciado" {{ old('estado_civil') == "Divorciado" ? 'selected' : "" }}>Divorciado(a)</option>
                    <option value="Viúvo" {{ old('estado_civil') == "Viúvo" ? 'selected' : "" }}>Viúvo(a)</option>
                    <option value="União Estável" {{ old('estado_civil') == "União Estável" ? 'selected' : "" }}>União Estável</option>
                    <option value="Separado" {{ old('estado_civil') == "Separado" ? 'selected' : "" }}>Separado(a)</option>
                </select>
            </div>
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Nº de Telefone do Estudante</label>
                <input type="text" name="telefone" placeholder="Informe" value="{{ old('telefone') }}" class="form-control @error('telefone') is-invalid @enderror" >
            </div>
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">País</label>
                <select class="form-control @error('pais_id') is-invalid @enderror" name="pais_id" id="pais_id" >
                    @if (count($paises) != 0)
                        @foreach ($paises as $item)
                        <option value="{{ $item->id }}" {{ $item->id == 6 ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Província</label>
                <select class="form-control @error('provincia_id') is-invalid @enderror" name="provincia_id" id="provincia_id" >
                    @if (count($provincias) != 0)
                        @foreach ($provincias as $item)
                        <option value="{{ $item->id }}" {{ old('provincia_id') == $item->id ? 'selected' : "" }}>{{ $item->nome }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Município</label>
                <select class="form-control @error('municipio_id') is-invalid @enderror" name="municipio_id" id="municipio_id" >
                    @if (count($municipios) != 0)
                        @foreach ($municipios as $item)
                        <option value="{{ $item->id }}" {{ old('municipio_id') == $item->id ? 'selected' : "" }}>{{ $item->nome }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Distrito</label>
                <select class="form-control @error('distrito_id') is-invalid @enderror" name="distrito_id" id="distrito_id" >
                    @if (count($distritos) != 0)
                        @foreach ($distritos as $item)
                        <option value="{{ $item->id }}" {{ old('distrito_id') == $item->id ? 'selected' : "" }}>{{ $item->nome }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Classe Anterior</label>
                <select class="form-control @error('classe_anterior_id') is-invalid @enderror" name="classe_anterior_id" >
                    @if (count($classes) != 0)
                        @foreach ($classes as $item)
                        <option value="{{ $item->classe->id }}" {{ old('classe_anterior_id') == $item->classe->id ? 'selected' : "" }}>{{ $item->classe->classes }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Classe Actual</label>
                <select class="form-control @error('classe_actual_id') is-invalid @enderror" name="classe_actual_id" >
                    @if (count($classes) != 0)
                        @foreach ($classes as $item)
                        <option value="{{ $item->classe->id }}" {{ old('classe_actual_id') == $item->classe->id ? 'selected' : "" }}>{{ $item->classe->classes }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Curso 1º Opção</label>
                <select class="form-control @error('cursos_id') is-invalid @enderror" name="cursos_id" >
                    @if (count($cursos) != 0)
                        @foreach ($cursos as $item)
                        <option value="{{ $item->curso->id }}" {{ old('cursos_id') == $item->curso->id ? 'selected' : "" }}>{{ $item->curso->curso }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Curso 2º Opção</label>
                <select class="form-control @error('cursos_segunda_opcao_id') is-invalid @enderror" name="cursos_segunda_opcao_id">
                    @if (count($cursos) != 0)
                        @foreach ($cursos as $item)
                        <option value="{{ $item->curso->id }}" {{ old('cursos_segunda_opcao_id') == $item->curso->id ? 'selected' : "" }}>{{ $item->curso->curso }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Curso 3º Opção</label>
                <select class="form-control @error('cursos_terceira_opcao_id') is-invalid @enderror" name="cursos_terceira_opcao_id">
                    @if (count($cursos) != 0)
                        @foreach ($cursos as $item)
                        <option value="{{ $item->curso->id }}" {{ old('cursos_terceira_opcao_id') == $item->curso->id ? 'selected' : "" }}>{{ $item->curso->curso }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Turnos</label>
                <select class="form-control @error('turnos_id') is-invalid @enderror" name="turnos_id" >
                    @if (count($turnos) != 0)
                        @foreach ($turnos as $item)
                        <option value="{{ $item->turno->id }}" {{ old('turnos_id') == $item->turno->id ? 'selected' : "" }}>{{ $item->turno->turno }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
           
            <div class="form-group col-12 col-md-3 mb-3">
                <label class="form-label">Anexo B.I</label>
                <input type="file" name="anexo_bilhete" value="{{ old('anexo_bilhete') }}" class="form-control @error('anexo_bilhete') is-invalid @enderror" >
            </div>
            <div class="form-group col-12 col-md-3">
                <label class="form-label">Anexo certificado</label>
                <input type="file" name="anexo_certificado" value="{{ old('anexo_certificado') }}" class="form-control @error('anexo_certificado') is-invalid @enderror" >
            </div>
           
        </div>
        <button type="submit" class="btn btn-success mt-4">Enviar Inscrição</button>
    </form>
</div>

@endsection

@section('scripts')

<script>

    $("#pais_id").change(()=>{
      let id = $("#pais_id").val();
      
      if(id != 6) {
        $.get('../carregar-privincia-municipios-distrito-estrageiros/'+id, function(data){
            $("#provincia_id").html("")
            $("#provincia_id").html(data['provincias'])
            
            $("#municipio_id").html("")
            $("#municipio_id").html(data['municipios'])
            
            $("#distrito_id").html("")
            $("#distrito_id").html(data['distritos'])
        })
      }else {
        $.get('../carregar-privincia-municipios-distrito-estrageiros/'+id, function(data){
            $("#provincia_id").html("")
            $("#provincia_id").html(data['provinciass'])
        })
        
      }
    })
    // Eventos
    $("#provincia_id").change(function () {
      carregarDados({
        origem: "#provincia_id",
        destino: "#municipio_id",
        rota: rotas.carregarMunicipios,
        mensagemSucesso: "Municípios carregados"
      });
    });
    
    $("#municipio_id").change(function () {
      carregarDados({
        origem: "#municipio_id",
        destino: "#distrito_id",
        rota: rotas.carregarDistritos,
        mensagemSucesso: "Distritos carregados"
      });
    });
    
</script>

@endsection
