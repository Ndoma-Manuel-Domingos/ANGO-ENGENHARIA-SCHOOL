@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">BOLETINS</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('pedagogicos.lancamento-nas-turmas') }}">Voltas</a></li>
                    <li class="breadcrumb-item active">Boletim</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('web.post-turmas-boletins-estudantes') }}" method="POST">
                    <div class="card">
                        <div class="card-body">
                            @csrf
                            <div class="row">

                                <div class="form-group col-md-3 col-12">
                                    <label for="ano_lectivos_id" class="form-label">Anos Lectivos</label>
                                    @if ($anos_lectivos)
                                    <select name="ano_lectivos_id" id="ano_lectivos_id" class="custom-select form-control ano_lectivos_id">
                                        <option value="">Anos Lectivos</option>
                                        @foreach ($anos_lectivos as $item)
                                        <option value="{{ $item->id }}">{{ $item->ano }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text ano_lectivos_id_error"></span>
                                    @endif
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="turmas_id" class="form-label">Turma</label>
                                    @if ($turmas)
                                    <select name="turmas_id" id="turmas_id" class="custom-select form-control turmas_id">
                                        <option value="">Turma</option>
                                        @foreach ($turmas as $item)
                                        <option value="{{ $item->id }}">{{ $item->turma }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text turmas_id_error"></span>
                                    @endif

                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="trimestre_id" class="form-label">Trimestre</label>
                                    @if ($trimestres)
                                    <select name="trimestre_id" id="trimestre_id" class="custom-select form-control trimestre_id">
                                        <option value="">Trimestre</option>
                                        @foreach ($trimestres as $item)
                                        <option value="{{ $item->id }}">{{ $item->trimestre }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text trimestre_id_error"></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Gerar Boletins</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>


@endsection

@section('scripts')
<script>
    $(function() {
        // mudanca de estado do select
        $("#ano_lectivos_id").change(() => {
            let id = $("#ano_lectivos_id").val();
            $.get('/carregar-todas-turmas-anolectivos-escolas/' + id, function(data) {
                $("#turmas_id").html("")
                $("#turmas_id").html(data)
            })
        })

    });

    document.addEventListener('DOMContentLoaded', function() {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

</script>
@endsection
