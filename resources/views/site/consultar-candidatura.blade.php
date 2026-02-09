@extends('layouts.site')

@section('content')

<div class="container content mt-5">
    <h2 class="text-center">Consultar Candidatura</h2>
    <div class="row">
        <div class="form-group col-12 col-md-6 offset-md-3">
            <form class="mt-4" method="GET" action="{{ route('site.consultar-candidatura-inscricoes') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="bilheite" value="{{ $numero ?? old('bilheite') }}" placeholder="Informe o Número do Bilhete de Identidade" class="form-control form-control-lg @error('bilheite') is-invalid @enderror" >
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <button type="submit" class="btn btn-success mt-4 d-block" style="width: 100%">Pesquisar</button>
                    </div>
                    <div class="col-12 col-md-6">
                        <a type="reset" href="{{ route('site.consultar-candidatura-inscricoes') }}" class="btn btn-success mt-4 d-block">Limpar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    @if ($estudante != null)
        <div class="row mt-5 mb-5">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="list-group">
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-auto">
                                <svg class="w-6 h-6" style="width: 150px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                            </div>
                            <div class="col px-4">
                                <div>
                                    <div class="float-right"><strong>Data:</strong> {{ date('Y-m-d', strtotime($estudante->created_at))  }} - <strong>Horas:</strong> {{ date('H:i:s', strtotime($estudante->created_at))  }}</div>
                                    <h3>{{ $estudante->nome }} {{ $estudante->sobre_nome }}</h3>
                                    <h5>Candidatura Nº: {{ $estudante->numeracao }}</h5>
                                    <p class="mb-0"><strong>Classe:</strong> {{ $estudante->classes }}</p>
                                    <p class="mb-0"><strong>Curso:</strong> {{ $estudante->curso }}</p>
                                    <p class="mb-0"><strong>Turno:</strong> {{ $estudante->turno }}</p>
                                    <p class="mb-0"><strong>Escola:</strong> {{ $estudante->escola }}</p>
                                    @if ($estudante->status_matricula == 'confirmado')
                                        <h6 class="mt-2 text-success">Estado: Aceite <i class="fas fa-ckeck"></i></h6>    
                                    @endif
                                    @if ($estudante->status_matricula == 'nao_confirmado')
                                        <h6 class="mt-2 text-warning">Estado: Ainda não aceite <i class="fas fa-close"></i></h6>    
                                    @endif
                                    {{-- <a href="{{ route('site.ficha-candidatura', $estudante->ficha) }}" target="_blink" class="btn btn-primary"><i class="fas fa-print"></i> Reimprimir Ficha</a> --}}
                                    <a href="{{ route('site.ficha-factura-candidatura', [$estudante->ficha, $estudante->idMatricula]) }}" target="_blank" class="btn btn-primary"><i class="fas fa-print"></i> Reimprimir Factura</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
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
