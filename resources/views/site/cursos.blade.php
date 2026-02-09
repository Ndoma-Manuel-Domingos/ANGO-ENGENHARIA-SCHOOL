@extends('layouts.site')

@section('content')
<div class="container mt-5">
    <h2>Cursos Disponíveis</h2>
    <div class="row mt-3">
        @if (count($cursos) != 0)
        @foreach ($cursos as $curso)
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-icon p-4">
                    <i class="fas fa-laptop-code fa-4x text-dark"></i> <!-- Ícone centralizado -->
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $curso->curso ? $curso->curso->curso : "" }}</h5>
                    <p class="card-text">{{ $curso ? $curso->descricao : "" }}</p>
                    <a href="{{ route('site.cursos-disponiveis-detalhe', ['req_id' => $shcools->req_id, 'curso_id' => $curso->id]) }}" class="btn btn-info">Saiba Mais</a>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        {{-- <div class="col-md-4">
            <div class="card">
                <img src="site/img/01.png" class="card-img-top" alt="Curso 2">
                <div class="card-body">
                    <h5 class="card-title">Curso de Administração</h5>
                    <p class="card-text">Gerencie empresas e negócios com eficiência.</p>
                    <a href="{{ route('site.cursos-disponiveis-detalhe', ['id' => 1]) }}" class="btn btn-info">Saiba Mais</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <img src="site/img/02.png" class="card-img-top" alt="Curso 3">
                <div class="card-body">
                    <h5 class="card-title">Curso de Contabilidade</h5>
                    <p class="card-text">Aprenda a gerenciar finanças e contabilidade.</p>
                    <a href="{{ route('site.cursos-disponiveis-detalhe', ['id' => 1]) }}" class="btn btn-info">Saiba Mais</a>
                </div>
            </div>
        </div> --}}
    </div>
</div>

{{-- <div class="parallax mt-5">Educação, Sucesso, Futuro</div> --}}

@endsection

@section('scripts')
<script>
</script>
@endsection
