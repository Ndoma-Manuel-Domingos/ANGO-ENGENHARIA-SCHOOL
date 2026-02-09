<div id="header">
    <div class="logo">
        <img src="{{ public_path('assets/images/'. $escola->logotipo ?? '') }}" alt="" style="text-align: center;height: 60px;width: 60px;margin-bottom: 40px;">
    </div>   
    
    <div class="texto-header">
        <div>
          @if ($escola->categoria == 'Privado')
          <h1 class="fs-5"><strong style="text-transform: uppercase">{{ $escola->nome }}</strong></h1>
          <h1 class="fs-5"><strong style="text-transform: uppercase">{{ $escola->sigla }}</strong></h1>
          <h1 class="fs-5"><strong class="text-uppercase" style="text-transform: uppercase">{{ $titulo }}</strong></h1>
          <br>
          @else
          <h1 class="fs-5"><strong style="text-transform: uppercase">República de Angola</strong></h1>
          <h1 class="fs-5"><strong style="text-transform: uppercase">Ministério da Educação</strong></h1>
          <h1 class="fs-5"><strong style="text-transform: uppercase">{{ $escola->nome }}</strong></h1>
          <h1 class="fs-5"><strong style="text-transform: uppercase">{{ $escola->sigla }}</strong></h1>
          <h1 class="fs-5"><strong class="text-uppercase" style="text-transform: uppercase">{{ $titulo }}</strong></h1>
          <br>
          @endif
        </div>
    </div>
      
</div>  