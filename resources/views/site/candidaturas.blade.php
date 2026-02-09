@extends('layouts.site')

@section('content')
<div class="container mt-5 pt-5">
    <section class="row">
        <article class="col-12 col-sm-4 col-md-4 col-lg-4 mb-5" data-aos="fade-left">
            <div class="tex-center">
                <div class="text-center p-4">
                    <!-- Ícone: Formulário de candidatura -->
                    <svg style="width: 100px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75H7.5A2.25 2.25 0 005.25 6v12a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0016.5 3.75zM9 8.25h6M9 12h6M9 15.75h3"></path>
                    </svg>
                </div>
                <h4 class="card-title text-center pt-2"><a href="{{ route('site.formulario-candidatura-inscricoes') }}" class="text-primary text-decoration-none">Candidaturar-se</a></h4>
                <div class="card-body">
                    <p class="card-text" style="font-size: 16pt;line-height: 35pt">Inicie sua candidatura de forma simples e segura. Preencha o formulário com suas informações e anexe os documentos exigidos para concorrer às vagas disponíveis.</p>
                </div>
            </div>
        </article>

        <article class="col-12 col-sm-4 col-md-4 col-lg-4 mb-5" data-aos="fade-right">
            <div class="">
                <div class="text-center p-4">
                    <!-- Ícone: Consulta ou busca -->
                    <svg style="width: 100px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103 10.5a7.5 7.5 0 0013.65 6.15z"></path>
                    </svg>
                </div>
                <h4 class="card-title text-center pt-2"><a href="{{ route('site.consultar-candidatura-inscricoes') }}" class="text-primary text-decoration-none">Consultar Candidatura</a></h4>
                <div class="card-body">
                    <p class="card-text" style="font-size: 16pt;line-height: 35pt">Acompanhe o andamento da sua candidatura em tempo real. Veja se foi aprovada, recusada ou está em análise, e fique por dentro de todas as atualizações.</p>
                </div>
            </div>
        </article>

        <article class="col-12 col-sm-4 col-md-4 col-lg-4 mb-5" data-aos="fade-right">
            <div class="">
                <div class="text-center p-4">
                    <!-- Ícone: Upload de comprovativo -->
                    <svg style="width: 100px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-8m0 0l-3.5 3.5M12 8l3.5 3.5M6 18h12a2 2 0 002-2v-1a6 6 0 00-6-6h-4a6 6 0 00-6 6v1a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h4 class="card-title text-center pt-2"><a href="{{ route('site.formulario-submiter-comprovativo') }}" class="text-primary text-decoration-none">Submeter Comprovativo</a></h4>
                <div class="card-body">
                    <p class="card-text" style="font-size: 16pt;line-height: 35pt">Já fez o pagamento? Envie agora o comprovativo para confirmar sua inscrição. Certifique-se de preencher corretamente seus dados antes de submeter.</p>
                </div>
            </div>
        </article>
    </section>
</div>
@endsection

@section('scripts')
<script>
</script>
@endsection
