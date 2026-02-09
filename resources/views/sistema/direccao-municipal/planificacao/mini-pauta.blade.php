@extends('layouts.municipal')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('app.planificacao-municipal-pesquisa-mini-pauta') }}" method="get" class="row" id="formulario_pesquisa_notas">
                            @csrf
                            <div class="form-group col-md-3">
                                <label for="escola_id" class="form-label">Escolas</label>
                                @if ($escolas)
                                <select name="escola_id" id="escola_id" class="form-control escola_id select2" style="width: 100%">
                                    <option value="">Escolas</option>
                                    @foreach ($escolas as $item)
                                    <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('escola_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>

                            <div class="form-group col-md-2">
                                <label for="ano_lectivos_id" class="form-label">Ano Lectivo</label>
                                <select name="ano_lectivos_id" id="ano_lectivos_id" class="form-control ano_lectivos_id select2" style="width: 100%">
                                    <option value="">Ano Lectivo</option>
                                </select>
                                @error('escola_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-2">
                                <label for="turmas_id" class="form-label">Turma</label>
                                <select name="turmas_id" id="turmas_id" class="form-control turmas_id select2" style="width: 100%">
                                    <option value="">Turma</option>
                                </select>
                                @error('turmas_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-2">
                                <label for="disciplinas_id" class="form-label">Disciplinas</label>
                                <select name="disciplinas_id" id="disciplinas_id" class="form-control disciplinas_id select2" style="width: 100%">
                                    <option value="">Disciplinas</option>
                                </select>
                                @error('disciplinas_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-2">
                                <label for="condicao_pesquisar" class="form-label">Condição de Listagem</label>
                                <select name="condicao_pesquisar" class="form-control condicao_pesquisar select2" style="width: 100%">
                                    <option value="">Condição</option>
                                    <option value="1">Disciplina Selecionada</option>
                                    <option value="2">Todas Disciplina</option>
                                </select>
                                @error('condicao_pesquisar')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-1">
                                <label for="trimestre_id" class="form-label">Trimestres</label>
                                @if ($trimestres)
                                <select name="trimestre_id" id="trimestre_id" class="form-control trimestre_id select2" style="width: 100%">
                                    <option value="">Trimestre</option>
                                    @foreach ($trimestres as $item)
                                    <option value="{{ $item->id }}">{{ $item->trimestre }}</option>
                                    @endforeach
                                </select>
                                @error('trimestre_id')
                                <span class="text-danger error-text">{{ $message }}</span>
                                @enderror
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="submit" form="formulario_pesquisa_notas" class="btn btn-primary" id="pesquisarMiniPautaEXMPLOOOOOO "><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    
        @if (isset($pesquisa_condicao))
            @if ($pesquisa_condicao == 2)
            <div class="row">
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
                            <table style="width: 100%" class="table  table-bordered table-striped  ">
        
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
                                                            @if ($itemNota->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                                                                <td style="color: #006699;">{{ $itemNota->mt }}</td>
                                                            @else
                                                                <td style="color: #ff0000;">{{ $itemNota->mt }}</td>
                                                            @endif
                                                        @endif
                                                        
                                                        @if ($itemNota->controlo_trimestres_id == $trimestre2->id)
                                                            @if ($itemNota->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
                                                            <td style="color: #006699;">{{ $itemNota->mt }}</td>
                                                            @else
                                                            <td style="color: #ff0000;">{{ $itemNota->mt }}</td>
                                                            @endif
                                                        @endif
                                                            
                                                        @if ($itemNota->controlo_trimestres_id == $trimestre3->id)
                                                            @if ($itemNota->mt >= ($turma->classe->tipo_avaliacao_nota / 2))
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
                    </div>
                </div>
                @endif
            </div>
            @else
            <div class="row">
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
                            <table style="width: 100%" class="table  table-bordered table-striped  ">
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
                                    @php
                                         $contador = 0;
                                    @endphp
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
        
                    </div>
                </div>
                @endif
            </div>
            @endif
        @endif

    </div><!-- /.container-fluid -->
</section>

@endsection

@section('scripts')
<script>
    $("#escola_id").change(() => {
        let id = $("#escola_id").val();
        $.get('../carregar-todos-anolectivos-escolas/' + id, function(data) {
            $("#ano_lectivos_id").html("")
            $("#ano_lectivos_id").html(data)
        })
    })

    $("#ano_lectivos_id").change(() => {
        let id = $("#ano_lectivos_id").val();
        $.get('../carregar-todas-turmas-anolectivos-escolas/' + id, function(data) {
            $("#turmas_id").html("")
            $("#turmas_id").html(data)
        })
    })

    $("#turmas_id").change(() => {
        let id = $("#turmas_id").val();
        $.get('../carregar-disciplinas-turma/' + id, function(data) {
            $("#disciplinas_id").html("")
            $("#disciplinas_id").html(data)
        })
    })

</script>
@endsection
