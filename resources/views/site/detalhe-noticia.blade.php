@extends('layouts.site')

@section('content')

<div class="container mt-5">
    <h2 class="text-center">DETALHES DA NOTÍCIAS</h2>
    <div class="row">
        <div class="col-md-12 mt-5">
            <img src="{{ asset('assets/anexos/'. $comunicado->documento ?? '') }}" class="img-fluid" alt="Imagem do Curso">
        </div>
        <div class="col-md-12 mt-5">
            <h4>{{ $comunicado->titulo }}</h4>
            <p>{!! $comunicado->descricao !!}</p>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
</script>
@endsection
