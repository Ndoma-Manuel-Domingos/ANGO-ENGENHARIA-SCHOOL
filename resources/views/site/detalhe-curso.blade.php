@extends('layouts.site')

@section('content')
<div class="container mt-5">
    <h2 class="text-center">Detalhes do Curso</h2>
    <div class="row">
        <div class="col-md-8">
            <h4>{{ $curso->curso ? $curso->curso->curso : "" }}</h4>
            <p>{{ $curso ? $curso->descricao : "" }}</p>
            <h5>Disciplinas</h5>
            <ul>
                @foreach ($turmas as $turma)
                <li>{{ $turma->classe->classes }}
                    <ul>
                        @foreach ($turma->disciplinas as $item)
                        <li>{{ $item->disciplina ? $item->disciplina->disciplina : "" }}</li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>
            <h5>Vantagens</h5>
            {!! $curso->formatListFromText($curso->vantagens) !!}
            {{-- <ul>
                <li>Certificado reconhecido</li>
                <li>Professores experientes</li>
                <li>Ambiente moderno</li>
            </ul> --}}
            <h5>Áreas de Saída</h5>
            {!! $curso->formatListFromText($curso->area_saidas) !!}
            {{-- <ul>
                <li>Empresas privadas</li>
                <li>Setor público</li>
                <li>Empreendedorismo</li>
            </ul> --}}
            <a href="{{ route('site.formulario-candidatura-inscricoes') }}" class="btn btn-success">Inscreva-se</a>
        </div>
        <div class="col-md-4">
            <img src="{{ asset('site/img/01.png') }}" class="img-fluid" alt="Imagem do Curso">
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
</script>
@endsection
