@extends('layouts.site')

@section('content')
<div id="carouselExample" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active"><img src="site/img/004.jpg" class="d-block w-100" alt="Imagem 1"></div>
        <div class="carousel-item"><img src="site/img/026.jpg" class="d-block w-100" alt="Imagem 2"></div>
        <div class="carousel-item"><img src="site/img/028.jpg" class="d-block w-100" alt="Imagem 3"></div>
        <div class="carousel-item"><img src="site/img/025.jpg" class="d-block w-100" alt="Imagem 4"></div>
        <div class="carousel-item"><img src="site/img/005.jpg" class="d-block w-100" alt="Imagem 5"></div>
    </div>
    <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </a>
    <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </a>
</div>

<div class="container mt-5">
    <section data-aos="zoom-in-up">
        <h2>Quem somos?</h2>
        <p style="text-align: justify;font-size: 15pt">
            Somos a Escola Internacional Francófona de Luanda, uma Instituição angolana que actua no campo da Educação. Estamos 
            situados na Província de Luanda, município da Camama, na Estrada principal do Camama. Ministramos as aulas na Língua 
            francesa e existimos há mais de 4 anos, com uma Direcção diligente e profissionais docentes competentes, com vasta 
            experiência no campo da docência. Temos servido a comunidade académica com brio e esmero.
        </p>
        
        <h2>Objecto e função Social</h2>
        <p style="text-align: justify;font-size: 15pt">
            O objeto social da nossa instituição consiste em promover o desenvolvimento intelectual, social e 
            cultural dos estudantes e formar cidadãos. Temos como função social o desenvolvimento das potencialidades físicas, 
            cognitivas e afetivas do indivíduo, capacitando-o a tornar um cidadão, participativo na sociedade; garantir a aprendizagem 
            de conhecimento, habilidades e valores necessários à socialização do indivíduo, propiciando o domínio dos conteúdos 
            culturais básicos da leitura, da escrita, da ciência das artes e das letras.
        </p>
    </section>

    <section class="mt-4">
        <h2>Galeria de Fotos</h2>
        <div class="row gallery" id="gallery">
            <div class="col-md-4 col-sm-6 mb-3"><img src="site/img/020.jpg" class="img-fluid" alt="Foto 1" data-toggle="modal" data-target="#imagemModal"></div>
            <div class="col-md-4 col-sm-6 mb-3"><img src="site/img/024.jpg" class="img-fluid" alt="Foto 2" data-toggle="modal" data-target="#imagemModal"></div>
            <div class="col-md-4 col-sm-6 mb-3"><img src="site/img/026.jpg" class="img-fluid" alt="Foto 3" data-toggle="modal" data-target="#imagemModal"></div>
            <div class="col-md-4 col-sm-6 mb-3"><img src="site/img/039.jpg" class="img-fluid" alt="Foto 4" data-toggle="modal" data-target="#imagemModal"></div>
            <div class="col-md-4 col-sm-6 mb-3"><img src="site/img/006.jpg" class="img-fluid" alt="Foto 1" data-toggle="modal" data-target="#imagemModal"></div>
            <div class="col-md-4 col-sm-6 mb-3"><img src="site/img/011.jpg" class="img-fluid" alt="Foto 2" data-toggle="modal" data-target="#imagemModal"></div>
            <div class="col-md-4 col-sm-6 mb-3"><img src="site/img/010.jpg" class="img-fluid" alt="Foto 3" data-toggle="modal" data-target="#imagemModal"></div>
            <div class="col-md-4 col-sm-6 mb-3"><img src="site/img/004.jpg" class="img-fluid" alt="Foto 4" data-toggle="modal" data-target="#imagemModal"></div>
            <div class="col-md-4 col-sm-6 mb-3"><img src="site/img/013.jpg" class="img-fluid" alt="Foto 4" data-toggle="modal" data-target="#imagemModal"></div>
        </div>
        {{-- <button id="loadMore" class="btn btn-primary mt-3">Carregar Mais</button> --}}
    </section>
    
    <section class="mt-5">
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Objetivos:</h4>
                    </div>
                    <div class="card-body" style="padding-bottom: 347px">
                        <ul>
                            <li>Promover o domínio e o conhecimento da língua francesa;</li>
                            <li>Facilitar a interação dos alunos com outras pessoas de geografias diferentes;</li>
                            <li>Fazer dos nossos alunos um ser comunicativo e interativo na língua francesa;</li>
                            <li>Oferecer educação formal e sistemática;</li>
                            <li>Desenvolver o educando;</li>
                            <li>Assegurar a formação comum indispensável para o exercício da cidadania;</li>
                            <li>Fornecer meios para progredir no trabalho e em estudos posteriores;</li>
                            <li>Socializar o saber sistematizado;</li>
                            <li>Fazer com que o saber seja criticamente apropriado pelos alunos;</li>
                            <li>Aliar o saber científico ao saber prévio dos alunos (saber popular), contribuindo para a sociedade;</li>
                            <li>Contribuir para a formação de indivíduos mais preparados para enfrentar os desafios da vida em sociedade;</li>
                            <li>Preparar profissionais competentes e éticos para o mercado de trabalho;</li> 
                            <li>Promover a extensão, aberta à participação da população, para difundir as conquistas e benefícios resultantes dos estudos sistematizados;</li> 
                            <li>Estimular a criação cultural, por meio da promoção de eventos diversificados;</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Missão:</h4>
                    </div>
                    <div class="card-body" style="padding-bottom: 213px">
                        <p class="card-text">
                            A missão de uma instituição de ensino é o objetivo principal da escola, que define a sua razão de existir. 
                            A missão orienta as ações da escola, contribuindo para a formação de cidadãos e para o desenvolvimento da sociedade.
                        </p>
                        <ul>
                            <li>Ajudar os alunos a familiarizar-se com a língua francesa a fim de facilitar a sua integração no mundo global;</li>
                            <li>Facilitar o acesso ao mercado de trabalho em organizações nacionais e internacionais;</li>
                            <li>Promover uma educação de qualidade;</li>
                            <li>Contribuir para uma sociedade mais justa, fraterna e feliz;</li>
                            <li>Formar cidadãos críticos e conscientes dos seus direitos e deveres;</li>
                            <li>Desenvolver valores de solidariedade, cooperação, tolerância e respeito pelo outro;</li>
                            <li>Apoiar o aluno a tornar-se membro moralmente responsável e socialmente interventivo;</li>
                            <li>Estimular a emergência de uma consciência ecológica;</li>
                            <li>Capacitar os alunos para gerir um percurso pessoal e profissional ao longo da vida;</li>
                            <li>Proporcionar acesso às melhores Universidades;</li>
                            <li>Contribuir na formação de pessoas capazes de viver plenamente;</li>
                            <li>Promover uma cultura de liberdade;</li>
                            <li>Estar atenta à diversidade de todos os membros da comunidade educativa;</li>
                            <li>Contribuir para a autonomização intelectual dos jovens e adultos;</li>
                            <li>Ser uma Escola inclusiva;</li>
                            <li>Fortalecer a autoestima dos alunos;</li>
                            <li>Educar para a cidadania;</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Valores:</h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            São os princípios que orientam as decisões e o comportamento da equipe; Representam a importância atribuída a determinadas crenças; São ideais a serem atingidos com base no que a empresa acredita.
                        </p>
                        <p class="card-text">
                            Sendo nós uma instituições de ensino defendemos os seguintes valores:  respeito, justiça, solidariedade, tolerância, responsabilidade, não violência, diálogo, e afeto. 
                        </p>
                        
                        <h6 class="card-text"><strong>Defendemos Valores éticos como:</strong></h6>
                        <ul>
                            <li>Autonomia</li>
                            <li>Responsabilidade</li>
                            <li>Solidariedade</li>
                            <li>Respeito ao bem comum</li>
                            <li>Integridade</li>
                            <li>Honestidade</li>
                            <li>Compromisso com a ética</li>
                        </ul>
                        <h6 class="card-text"><strong>Defendemos Valores morais e culturais como:</strong></h6>
                        <ul>
                            <li>Trabalho bem feito;</li>
                            <li>Desejo de melhorar e superar;</li>
                            <li>Visão positiva das pessoas e das situações;</li>
                            <li>Companheirismo;</li>
                            <li>Sinceridade;</li>
                            <li>Lealdade;</li>
                            <li>Generosidade;</li>
                            <li>Busca de justiça e concórdia;</li>
                        </ul>
                        
                        <h6 class="card-text"><strong>Defendemos Valores de cidadania como:</strong></h6>
                        <ul>
                            <li>Desconstrução de estereótipos;</li>
                            <li>Defesa da não discriminação;</li>
                            <li>Defesa da igualdade e da dignidade de todos;</li>
                            <li>Valorização da cultura de cidadania responsável;</li>
                            <li>Valorização da cultura de trabalho, do esforço e da exigência;</li>
                        </ul>
                        <h6 class="card-text"><strong>Defendemos outros valores como:</strong></h6>
                        <ul>
                            <li>Empatia;</li> 
                            <li>Igualdade de oportunidades;</li>
                            <li>Respeito pelo meio ambiente;</li>
                            <li>Cuidado da saúde;</li>
                            <li>Pensamento crítico.</li>
                        </ul>
                        
                    </div>
                </div>
            </div>
            
        </div>
    </section>

    <section class="mt-4" data-aos="zoom-in-up">
        <h2>Comunicados Internos</h2>
        <ul class="list-group" id="comunicados">
            @foreach ($comunicados as $item)
            <li class="list-group-item">{{ $item->titulo }}: {{ $item->descricao }}</li>
            @endforeach
        </ul>
    </section>

    <div class="container mt-5">
        <h2>Noticias</h2>
        <div class="row">
            @if (count($noticias) != 0)
                @foreach ($noticias as $item)
                <div class="col-md-4" data-aos="zoom-in-up">
                    <div class="card">
                        <img src="{{ asset('assets/anexos/'. $item->documento ?? '') }}" class="card-img-top" alt="Curso 1">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->titulo }}</h5>
                            <p class="card-text">{{ $item->descricao }}</p>
                            <a href="{{ route('site.noticia-disponiveis-detalhe', ['id_detalhe' => $item->id]) }}" class="btn btn-primary">Saiba Mais</a>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="imagemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-xl modal-dialog-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Visualização da Imagem</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" class="img-fluid" id="imagemModalSrc">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
</script>
@endsection
