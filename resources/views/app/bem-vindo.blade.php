@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header"> </div>
<!-- /.content-header -->
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-kiss-wink-heart"></i>Holla Srº(ª) {{ $usuario->nome }}, seja Bem-vindo ao software {{ env('APP_NAME') }}. <span class="float-right text-warning">Módulo {{ $escola->modulo }}</span></h5>
                </div>
            </div>
            
            @if (count($solicitacoes) > 0)
            <div class="col-12 col-md-12">
                <a href="{{ route('web.solicitacoes-dos-professores') }}">
                    <div class="callout callout-warning bg-warning">
                        <h5><i class="fas fa-kiss-wink-heart"></i> Tens {{ count($solicitacoes) }} solicitação(ões) do(s) professor(es) de outra(s) ou mesma escola(s). Clica sobre a notificação para poder visualizar.</h5>
                    </div>
                </a>
            </div>
            @endif
            
            @if (count($transferincias_professores) > 0)
            <div class="col-12 col-md-12">
                <a href="{{ route('web.transferincias-professores-pela-direccao') }}">
                    <div class="callout callout-warning bg-warning">
                        <h5><i class="fas fa-kiss-wink-heart"></i> Todas as transferências de professores enviadas pela direcção provincial ou direcção Municipal. Total: {{ count($transferincias_professores) }}</h5>
                    </div>
                </a>
            </div>
            @endif
            
            @if (Auth::user()->login == "Y")
                <div class="col-12 col-md-12">
                    <div class="callout callout-warning bg-warning">
                        <h5><i class="fas fa-kiss-wink-heart"></i> Como é pela primeira vez que usa o sistema, pedimos que actualiza as tuas credências de modo ajudar-te o voltar no sistema, caso não pode ignorar. <a href="{{ route('informacoes-escolares.privacidade') }}">Clicar aqui.</a></h5>
                    </div>
                </div>
            @endif
            
        </div>

        <div class="row">
            <div class="col-1"></div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header text-">
                        <h4><a href="{{ route('paineis.painel-informativo-administrativo') }}">Clica aqui ignorar tudo ir para tela inicial</a></h4>
                        <h1 class="text-lg">Apresentamos alguns vídeos para te auxiliar no uso correto do sistema. Para mais informações, entre em contato com o Suporte Técnico pelo número 942-39-35-08.</h1>
                    </div>

                    <div class="card-body">
                        <h4>1 - Como usar e Configurar o Sistema? </h4>
                        <div class="embed-responsive embed-responsive-16by9 mb-1" style="height: 400px;">
                            {{-- <iframe width="1280" height="718"
                                src="https://www.youtube.com/embed/bifUVlOtr-c?list=PL8cM8aoFSgjYjjjo4mSUmsHhudKqCZkgb"
                                title="[ACTUALIZADO] - CRIAR CONTA - GESTÃO ESCOLA {{ env('APP_NAME') }}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe> --}}
                                
                                <iframe width="1280" height="720" src="https://www.youtube.com/embed/fGZ426QrvAY" 
                                title="SISTEMA DE GESTÃO ESCOLAR COMPLETO PARTE 1 - ACTUALIZADO" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                    </div>


                    {{-- <div class="card-body">
                        <h4>2 - Como usar e Configurar o Sistema? </h4>
                        <div class="embed-responsive embed-responsive-16by9 mb-1" style="height: 400px;">
                            <iframe width="1280" height="718"
                                src="https://www.youtube.com/embed/pQ1Xonth76k?list=PL8cM8aoFSgjYjjjo4mSUmsHhudKqCZkgb"
                                title="[ACTUALIZADO] - LOGIN {{ env('APP_NAME') }}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                    </div> --}}

                </div>
            </div>

            <div class="col-12 col-md-5">
            
                <div class="card">
                    <div class="card-body">
                        <h4>03 - Como usar e Configurar o Sistema? </h4>
                        <div class="embed-responsive embed-responsive-16by9 mb-4" style="height: 400px;">
                            <iframe width="1280" height="718"
                                src="https://www.youtube.com/embed/t8TFueX4I58?list=PL8cM8aoFSgjYjjjo4mSUmsHhudKqCZkgb"
                                title="[ACTUALIZADO] -   PAGAMENTO MENSALIDADE - {{ env('APP_NAME') }}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
  
            </div>
        </div>

    </div><!-- /.container-fluid -->
</div>

@endsection