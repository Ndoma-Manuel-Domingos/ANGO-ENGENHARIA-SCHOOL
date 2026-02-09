@extends('layouts.escolas')

@php
$escolaId = Auth::user()->shcools_id;
@endphp

@section('content')

<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">MINI PAUTAS</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('pedagogicos.lancamento-nas-turmas') }}">Voltas</a></li>
                        <li class="breadcrumb-item active">Mini Pautas</li>
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
                    <form action="{{ route('web.mini-pauta-geral') }}" method="GET">
                        <div class="card">
                            <div class="card-body">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-2 col-md-4">
                                        <label for="turmas_id" class="form-label">Turma</label>
                                        <select name="turmas_id" id="turmas_id" class="select2 form-control turmas_id" style="width: 100%">
                                            <option value="">Escolher Turma</option>
                                            @foreach ($turmas as $item)
                                            <option value="{{ $item->id }}">{{ $item->turma }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2 col-md-4">
                                        <label for="list-disciplinas-mini-pauta-geral" class="form-label">Disciplinas</label>
                                        <select name="disciplinas_id" id="list-disciplinas-mini-pauta-geral" class="select2 form-control disciplinas_id" style="width: 100%">
                                            <option value="">Escolher Disciplinas</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filtra Dados</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    <!-- Main content -->
                    <div class="card">
                        <div class="card-header">
                            @if( isset($turma) AND isset($disciplina) )
                            <a href="{{ route('ficha-mini-pauta-geral', [ 'turma' => Crypt::encrypt($turma->id), 'disciplina' => Crypt::encrypt($disciplina->id), ]) }}" target="_blank" class="btn btn-danger float-end mx-1"><i class="fas fa-file-pdf"></i> Imprimir</a>
                            <a href="{{ route('ficha-mini-pauta-geral-excel', [ 'turma' => Crypt::encrypt($turma->id), 'disciplina' => Crypt::encrypt($disciplina->id), ]) }}" target="_blank" class="btn btn-success float-end mx-1"><i class="fas fa-file-excel"></i> Imprimir</a>
                            @endif
                        </div>
                        
                        <!-- title row -->
                        <div class="card-body">
                            @if ( isset($classe) && $classe->tipo == 'Transição')
                                @include('admin.require.classe-transicao')
                            @endif
                          
                            @if (isset($classe) && $classe->tipo == 'Exame')
                                @include('admin.require.classe-exames')
                            @endif
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
</div>

@endsection

@section('scripts')
<script>
    $(function() {
        // mudança de stado do select
        $(document).on('change', '.turmas_id', function(e) {
            e.preventDefault();
            var turma = $('.turmas_id').val();

            $.ajax({
                type: "GET"
                , url: `carregar-turmas-pautas/${turma}`
                , dataType: "json"
                , beforeSend: function() {
                    progressBeforeSend();
                }
                , success: function(response) {
                    Swal.close();
                    if (response.status == 200) {
                        $('#list-disciplinas-mini-pauta-geral').html("");
                        for (let index = 0; index < response.disciplinasTurma.length; index++) {
                            $('#list-disciplinas-mini-pauta-geral').append(`<option value="${response.disciplinasTurma[index].id}">${response.disciplinasTurma[index].disciplina}</option>`);
                        }
                    }
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });

        });
        // end mudanca do estado
    });

</script>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.stepper = new Stepper(document.querySelector('.bs-stepper'))
    })

</script>
@endsection
