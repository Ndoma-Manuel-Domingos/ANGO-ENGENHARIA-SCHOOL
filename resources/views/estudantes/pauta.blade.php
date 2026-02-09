@extends('layouts.estudantes')

@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Minhas Notas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="{{ route("est.home-estudante") }}">Voltar</a></li>
                      <li class="breadcrumb-item active">Notas</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('est.mapa-aproveitamento') }}" method="GET" class="row">
                                @csrf
                                <div class="form-group col-md-5">
                                    <label for="" class="form-label">Selecione o Ano Lectivo</label>
                                    @if ($ano_lectivos)
                                        <select name="ano_lectivos_id" id="ano_lectivos_id" class="custom-select form-control-sm ano_lectivos_id">
                                            @foreach ($ano_lectivos as $item)
                                                <option value="{{ Crypt::encrypt($item->id) }}">{{ $item->ano }}</option>
                                            @endforeach
                                        </select>  
                                        @error('ano_lectivos_id')
                                            <span class="text-danger error-text">{{ $message }}</span>
                                        @enderror  
                                        
                                    @endif
                                </div>
                                <input type="hidden" name="estudantes_id" value="{{ Crypt::encrypt($estudante->id) }}" id="estudantes_id" class="estudantes_id">
                    
                                <div class="form-group col-md-2" style="margin-top: 35px">
                                    <button type="submit" class="btn btn-primary" id="pesquisarMiniPaut"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-12">
                    @if (isset($trimestre1) AND isset($trimestre2) AND isset($trimestre3) AND isset($trimestre4) )
                        <div class="card">
                            <div class="card-header">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="p-2">
                                                <strong>Turma: </strong> <span class="span_turma">
                                                @if (isset($turma))
                                                    @php
                                                        echo $turma->turma;
                                                    @endphp 
                                                @else
                                                    desc
                                                @endif </span>. &nbsp; 
                                            </th>
                                            
                                            <th class="p-2">
                                                <strong>Classe: </strong> <span class="span_classe">
                                                    @if (isset($classe))
                                                        @php
                                                            echo $classe->classes;
                                                        @endphp 
                                                    @else
                                                        desc
                                                @endif </span>. &nbsp; 
                                            </th>
                                            
                                            <th class="p-2">
                                                <strong>Curso: </strong> <span class="span_curso">
                                                    @if (isset($curso))
                                                        @php
                                                            echo $curso->curso;
                                                        @endphp 
                                                    @else
                                                        desc
                                                    @endif </span>. &nbsp; 
                                            </th>
                                            
                                            <th class="p-2">
                                                <strong>Turno: </strong> <span class="span_turno">
                                                    @if (isset($turno))
                                                        @php
                                                            echo $turno->turno;
                                                        @endphp 
                                                    @else
                                                        desc
                                                    @endif </span>. &nbsp; 
                                            </th>
                                            
                                            <th class="p-2">
                                                <strong>Ano Lectivo </strong> <span class="span_ano_lectivo"> 
                                                    @if (isset($anoLectivo))
                                                        @php
                                                            echo $anoLectivo->ano;
                                                        @endphp 
                                                    @else
                                                        desc
                                                    @endif </span>. 
                                            </th>
                                            
                                            <th class="p-2">
                                                <strong>Estudante: </strong><span class="span_estudante"> 
                                                    @if (isset($estudantes))
                                                        @php
                                                            echo "{$estudantes->nome} {$estudantes->sobre_nome}";
                                                        @endphp 
                                                    @else
                                                        desc
                                                    @endif </span>. &nbsp; 
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                                
                                <div class="card-body">
                                    @include('admin.require.estudantes.notas')
                                </div>
                                
                                @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                              
                                    <div class="card-footer">
                                        <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("simestre1")]) }}" target="_blink" class="btn btn-primary my-2">Iª Simestre</a>
                                        <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("simestre2")]) }}" target="_blink" class="btn btn-primary my-2">IIª Simestre</a>
                                        <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre4")]) }}" target="_blink" class="btn btn-primary my-2">Anual</a>
                                        <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("declaracao-nota") ]) }}" target="_blink" class="btn btn-primary my-2">Declaração Com notas</a>
                                        <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("declarcao-sem-nota")]) }}" target="_blink" class="btn btn-primary my-2">Declaração Sem notas</a>
                                    </div>
                                    
                                @else    
                               
                                <div class="card-footer">
                                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre1")]) }}" target="_blink" class="btn btn-primary my-2">Iª Trimestre</a>
                                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre2")]) }}" target="_blink" class="btn btn-primary my-2">IIª Trimestre</a>
                                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre3") ]) }}" target="_blink" class="btn btn-primary my-2">IIIª Trimestre</a>
                                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("trimestre4")]) }}" target="_blink" class="btn btn-primary my-2">Geral</a>
                                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("declaracao-nota") ]) }}" target="_blink" class="btn btn-primary my-2">Declaração Com notas</a>
                                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("declarcao-sem-nota")]) }}" target="_blink" class="btn btn-primary my-2">Declaração Sem notas</a>
                                    <a href="{{ route('ficha-pauta-estudante', ['id'=> Crypt::encrypt($estudantes_id), 'ano' => Crypt::encrypt($anoLectivo->id), 'condicao' => Crypt::encrypt("classificacao-final")]) }}" target="_blink" class="btn btn-primary my-2">Classificação Final</a>
                                </div> 
                                
                                @endif
                                
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
      <!-- /.content -->
  <!-- /.content -->
</div>
@endsection







