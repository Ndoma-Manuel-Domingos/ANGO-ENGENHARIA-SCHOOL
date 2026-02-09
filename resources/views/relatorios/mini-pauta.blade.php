@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">PAUTAS</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('pedagogicos.lancamento-nas-turmas') }}">Voltas</a></li>
                    <li class="breadcrumb-item active">Pautas</li>
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
                <form action="{{ route('web.mini-pauta') }}" method="get">
                    <div class="card">
                        <div class="card-body">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-3 col-12">
                                    <label for="turmas_id" class="form-label">Turma</label>
                                    @if ($turmas)
                                    <select name="turmas_id" id="turmas_id" class="select2 form-control turmas_id">
                                        <option value="">Turma</option>
                                        @foreach ($turmas as $item)
                                        <option value="{{ $item->id }}">{{ $item->turma }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="list-disciplinas-mini-pauta" class="form-label">Disciplina</label>
                                    <select name="disciplinas_id" id="list-disciplinas-mini-pauta" class="select2 form-control disciplinas_id">
                                        <option value="">Disciplinas</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="condicao_pesquisar" class="form-label">Condição</label>
                                    <select name="condicao_pesquisar" id="condicao_pesquisar" class="select2 form-control condicao_pesquisar">
                                        <option value="">Condição</option>
                                        <option value="1">Disciplina Selecionada</option>
                                        <option value="2">Todas Disciplina</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="trimestre_id" class="form-label">Trimestre</label>
                                    @if ($trimestres)
                                    <select name="trimestre_id" id="trimestre_id" class="select2 form-control trimestre_id">
                                        <option value="">Trimestre</option>
                                        @foreach ($trimestres as $item)
                                        <option value="{{ $item->id }}">{{ $item->trimestre }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filtrar Dados</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            @if (isset($pesquisa_condicao))
                @if ($pesquisa_condicao == 2)
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row justify-center items-center">
                                <div class="col-12 col-md-6">
                                    <strong>Turma: </strong> {{ $turma->turma }} .
                                    <strong>Período: </strong> {{ $pesquisa_trimestre->trimestre }} .
                                </div>
                                <div class="col-12 col-md-6">
                                    <a href="{{ route('ficha-mini-pauta-todas', ['turma' => Crypt::encrypt($turma->id), 'trimestre' => Crypt::encrypt($pesquisa_trimestre->id)]) }}" class="btn btn-primary float-end mx-1" target="_blink"><i class="fas fa-print"></i> Imprimir</a>
                                    <a href="{{ route('ficha-mini-pauta-todas-excel', [ 'turma' => Crypt::encrypt($turma->id), 'trimestre' => Crypt::encrypt($pesquisa_trimestre->id), ]) }}" class="btn btn-success float-end mx-1" target="_blink"> <i class="fas fa-print"></i> Imprimir</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table style="width: 100%" class="table  table-bordered table-striped table-striped">
    
                                @if (isset($turma) and isset($pesquisa_trimestre) and isset($pesquisa_disciplina) and isset($pesquisa_condicao))
                                @php $contador = 0; @endphp
                                <thead>
                                    @if ($disciplinasTurma)
                                    <tr>
                                        <th colspan="4"></th>
                                        @foreach ($disciplinasTurma as $itemDisciplina)
                                            @if ($turma->curso->tipo === "Outros")
                                                <th colspan="3" style="text-align: center;background-color: rgba(0,0,0,.1)"> {{ $itemDisciplina->disciplina->abreviacao }}</th>
                                            @else
                                                <th colspan="4" style="text-align: center;background-color: rgba(0,0,0,.1)"> {{ $itemDisciplina->disciplina->abreviacao }}</th>
                                            @endif
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>Nº</th>
                                        <th>Proc Nº</th>
                                        <th>Estudante</th>
                                        <th style="writing-mode: vertical-rl; transform: rotate(180deg);">Sexo</th>
                                        @foreach ($disciplinasTurma as $itemDisciplina)
                                            
                                            @if ($turma->curso->tipo === "Técnico")
                                                <th style="text-align: center; writing-mode: vertical-rl; transform: rotate(180deg);">MAC</th>
                                                <th style="text-align: center; writing-mode: vertical-rl; transform: rotate(180deg);">NPP</th>
                                                <th style="text-align: center; writing-mode: vertical-rl; transform: rotate(180deg);">NPT</th>
                                            @endif
                                            
                                            @if ($turma->curso->tipo === "Punível")
                                                <th style="text-align: center; writing-mode: vertical-rl; transform: rotate(180deg);">P1</th>
                                                <th style="text-align: center; writing-mode: vertical-rl; transform: rotate(180deg);">P2</th>
                                                <th style="text-align: center; writing-mode: vertical-rl; transform: rotate(180deg);">PT</th>
                                            @endif
                                            
                                            @if ($turma->curso->tipo === "Outros")
                                                <th style="text-align: center; writing-mode: vertical-rl; transform: rotate(180deg);">MAC</th>
                                                <th style="text-align: center; writing-mode: vertical-rl; transform: rotate(180deg);">NPT</th>
                                            @endif
                                        
                                        <th style="text-align: center; writing-mode: vertical-rl; transform: rotate(180deg);background-color: rgba(0,0,0,.1)">MT</th>
                                        @endforeach
                                    </tr>
                                    @endif
                                </thead>
                                <body>
                                    @if ($estudantesTurma)
                                        @foreach ($estudantesTurma as $key => $itemEstudante)
                                            @php
                                                $contador++;
                                            @endphp
                                            @if ($itemEstudante->estudantes_id)
                                            <tr>
                                                <td>{{ $contador }}</td>
                                                <td>{{ $itemEstudante->estudante->numero_processo }}</td>
                                                <td>{{ $itemEstudante->estudante->nome }} {{ $itemEstudante->estudante->sobre_nome }}</td>
                                                <td>{{ $itemEstudante->estudante->sigla_genero($itemEstudante->estudante->genero) }}</td>
            
                                                @foreach ($disciplinasTurma as $itemDisciplina)
                                                @php
                                                $notas = (new App\Models\web\turmas\NotaPauta())::where("disciplinas_id", $itemDisciplina->disciplinas_id)
                                                    ->where("estudantes_id", $itemEstudante->estudante->id)
                                                    ->where("controlo_trimestres_id", $pesquisa_trimestre->id)
                                                    ->where("turmas_id", $turma->id)
                                                    ->where("ano_lectivos_id", $pesquisa_ano)
                                                ->get();
                                                @endphp
            
                                                @if ($notas)
                                                    @foreach ($notas as $itemNota)
                                                    
                                                        @if ($turma->curso->tipo === "Técnico")
                                                            <td style="text-align: center;">{{ $itemNota->mac }}</td>
                                                            <td style="text-align: center;">{{ $itemNota->npp }}</td>
                                                            <td style="text-align: center;">{{ $itemNota->npt }}</td>
                                                        @endif
                                                        
                                                        @if ($turma->curso->tipo === "Punível")
                                                            <td style="text-align: center;">{{ $itemNota->mac }}</td>
                                                            <td style="text-align: center;">{{ $itemNota->npp }}</td>
                                                            <td style="text-align: center;">{{ $itemNota->npt }}</td>
                                                        @endif
                                                        
                                                        @if ($turma->curso->tipo === "Outros")
                                                            <td style="text-align: center;">{{ $itemNota->mac }}</td>
                                                            <td style="text-align: center;">{{ $itemNota->npt }}</td>
                                                        @endif
                                                        
                    
                                                        @if ($itemNota->controlo_trimestres_id == $trimestre1->id)
                                                            @if ($itemNota->obs == "Apto")
                                                                <td style="color: #006699;">{{ $itemNota->mt }}</td>
                                                            @else
                                                                <td style="color: #ff0000;">{{ $itemNota->mt }}</td>
                                                            @endif
                                                        @endif
                                                        
                                                        @if ($itemNota->controlo_trimestres_id == $trimestre2->id)
                                                            @if ($itemNota->obs == "Apto")
                                                            <td style="color: #006699;">{{ $itemNota->mt }}</td>
                                                            @else
                                                            <td style="color: #ff0000;">{{ $itemNota->mt }}</td>
                                                            @endif
                                                        @endif
                                                            
                                                        @if ($itemNota->controlo_trimestres_id == $trimestre3->id)
                                                            @if ($itemNota->obs == "Apto")
                                                                <td style="color: #006699;">{{ $itemNota->mt }}</td>
                                                            @else
                                                                <td style="color: #ff0000;">{{ $itemNota->mt }}</td>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                                
                                                @endforeach
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </body>
                            </table>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                </div>
                @endif 
            @else
                    @php
                        if (isset($pesquisa_disciplina->id) and isset($pesquisa_trimestre->id) and isset($pesquisa_ano) and isset($turma->id)) {
                            $notas = (new App\Models\web\turmas\NotaPauta())::where('turmas_id', $turma->id)
                                ->where('ano_lectivos_id', $pesquisa_ano)
                                ->where('controlo_trimestres_id', $pesquisa_trimestre->id)
                                ->where('disciplinas_id', $pesquisa_disciplina->id)
                                ->with(['estudante'])
                            ->get();
                        }
                        $contador = 0;
                    @endphp
                    @if ($notas)
                        <div class="col-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row justify-center items-center">
                                        <div class="col-12 col-md-6">
                                            <strong>Turma: </strong> {{ $turma->turma }} .
                                            <strong>Disciplina: </strong> {{ $pesquisa_disciplina->disciplina }} .
                                            <strong>Período: </strong> {{ $pesquisa_trimestre->trimestre }} .
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <a href="{{ route('ficha-mini-pauta', [ 'turma' =>  Crypt::encrypt($turma->id), 'disciplina' =>   Crypt::encrypt($pesquisa_disciplina->id),  'trimestre' =>  Crypt::encrypt($pesquisa_trimestre->id) ]) }}" class="btn btn-primary float-end mx-2" target="_blink"> <i class="fas fa-print"></i> Imprimir</a>
                                            <a href="{{ route('ficha-mini-pauta-excel', [ 'turma' =>  Crypt::encrypt($turma->id), 'disciplina' =>   Crypt::encrypt($pesquisa_disciplina->id),  'trimestre' =>  Crypt::encrypt($pesquisa_trimestre->id) ]) }}" class="btn btn-success float-end" target="_blink"> <i class="fas fa-print"></i> Imprimir</a>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="card-body table-responsive">
                                    <table style="width: 100%" class="table  table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nº</th>
                                                <th>Nº Processo</th>
                                                <th width="400px">Nome Completo</th>
                                                <th style="text-align: center;">Genero</th>
                                                <th style="text-align: center;">Data Nascimento</th>
                                                <th style="text-align: center;">Idade</th>
                                                
                                                @if ($turma->curso->tipo === "Técnico")
                                                    <th style="text-align: center;">MAC</th>
                                                    <th style="text-align: center;">NPP</th>
                                                    <th style="text-align: center;">NPT</th>
                                                @endif
                                                
                                                @if ($turma->curso->tipo === "Punível")
                                                    <th style="text-align: center;">P1</th>
                                                    <th style="text-align: center;">P2</th>
                                                    <th style="text-align: center;">PT</th>
                                                @endif
                                                
                                                @if ($turma->curso->tipo === "Outros")
                                                    <th style="text-align: center;">MAC</th>
                                                    <th style="text-align: center;">NPT</th>
                                                @endif
                                                
                                                <th style="text-align: center;">MT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($notas as $item)
                                            @php $contador++; @endphp
                                            <tr>
                                                <td>{{ $contador }}</td>
                                                <td>{{ $item->estudante->numero_processo }}</td>
                                                <td width="400px">{{ $item->estudante->nome }} {{ $item->estudante->sobre_nome }}</td>
                                                <td style="text-align: center;">{{ $item->estudante->sigla_genero($item->estudante->genero) }}</td>
                                                <td style="text-align: center;">{{ $item->estudante->nascimento }}</td>
                                                <td style="text-align: center;">{{ $item->estudante->idade($item->estudante->nascimento) }}</td>
                                                
                                                @if ($turma->curso->tipo === "Técnico")
                                                    <td style="text-align: center;">{{ $item->mac }}</td>
                                                    <td style="text-align: center;">{{ $item->npp }}</td>
                                                    <td style="text-align: center;">{{ $item->npt }}</td>
                                                @endif
                                                
                                                @if ($turma->curso->tipo === "Punível")
                                                    <td style="text-align: center;">{{ $item->mac }}</td>
                                                    <td style="text-align: center;">{{ $item->npp }}</td>
                                                    <td style="text-align: center;">{{ $item->npt }}</td>
                                                @endif
                                                
                                                @if ($turma->curso->tipo === "Outros")
                                                    <td style="text-align: center;">{{ $item->mac }}</td>
                                                    <td style="text-align: center;">{{ $item->npt }}</td>
                                                @endif
            
                                                @if ($item->controlo_trimestres_id == $trimestre1->id)
                                                    @if ($item->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                                                        <td style="text-align: center;color: #006699;">{{ $item->mt }}</td>
                                                    @else
                                                        <td style="text-align: center;color: #ff0000;">{{ $item->mt }}</td>
                                                    @endif
                                           
                                                @else
            
                                                    @if ($item->controlo_trimestres_id == $trimestre2->id)
                                                        @if ($item->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                                                            <td style="text-align: center;color: #006699;">{{ $item->mt }}</td>
                                                        @else
                                                            <td style="text-align: center;color: #ff0000;">{{ $item->mt }}</td>
                                                        @endif
                                                       
                                                @else
                                                    @if ($item->controlo_trimestres_id == $trimestre3->id)
                                                        @if ($item->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                                                            <td style="text-align: center;color: #006699;">{{ $item->mt }}</td>
                                                        @else
                                                            <td style="text-align: center;color: #ff0000;">{{ $item->mt }}</td>
                                                        @endif
                                                    @endif
                                                @endif
                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="card-footer"></div>
                            </div>
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </div><!-- /.container-fluid -->
</section>
@endsection

@section('scripts')
<script>
    $(function() {
        // mudanca de estado do select
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
                        $('#list-disciplinas-mini-pauta').html("");
                        for (let index = 0; index < response.disciplinasTurma
                            .length; index++) {
                            $('#list-disciplinas-mini-pauta').append('<option value="' +
                                response.disciplinasTurma[index].id + '">' + response
                                .disciplinasTurma[index].disciplina + '</option>');
                        }
                    }
                }
                , error: function(xhr) {
                    Swal.close();
                    showMessage('Erro!', xhr.responseJSON.message, 'error');
                }
            });

        });
        // end mudanca de estado do select
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
