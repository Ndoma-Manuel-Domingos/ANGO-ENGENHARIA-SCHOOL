@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Atribuir Nota ao Estudante <span class="text-secondary"> {{ $estudante->nome }} {{ $estudante->sobre_nome }}. </span></h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route("prof.turmas") }}">Voltar</a></li>
              <li class="breadcrumb-item active">Turmas</li>
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
            <form action="{{ route('prof.professores-lancamento-nota-estudante-store') }}" method="post">
                @csrf
                <div class="card">
                
                    <div class="card-header bg-light">
                        Lançamento de notas
                    </div>
                    <input type="hidden" name="nota_id" value="{{ $nota->id }}">
                    <input type="hidden" name="professor_id" value="{{ $professor->id }}">
                    
                    @if ($escola->ensino && $escola->ensino->nome == "Ensino Superior")
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-3 col-12">
                                        <label for="p1">P1</label>
                                        <input type="number" name="p1" class="form-control p1" value="{{ $nota->p1 }}">
                                        <span class="text-danger error-text p1_error"></span>
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label for="p2">P2</label>
                                        <input type="number" name="p2" class="form-control p2" value="{{ $nota->p2 }}">
                                        <span class="text-danger error-text p2_error"></span>
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label for="p3">P3</label>
                                        <input type="number" name="p3" class="form-control p3" value="{{ $nota->p3 }}">
                                        <span class="text-danger error-text p3_error"></span>
                                    </div>   
                                    <div class="form-group col-md-3 col-12">
                                        <label for="p4">P4</label>
                                        <input type="number" name="p4" class="form-control p4" value="{{ $nota->p4 }}">
                                        <span class="text-danger error-text p4_error"></span>
                                    </div> 
                                    <div class="form-group col-md-3 col-12">
                                        <label for="exame_1_especial">Exame</label>
                                        <input type="number" name="exame_1_especial" class="form-control exame_1_especial" value="{{ $nota->exame_1_especial }}">
                                        <span class="text-danger error-text exame_1_especial_error"></span>
                                    </div>   
                                    <div class="form-group col-md-3 col-12">
                                        <label for="recurso">Recurso</label>
                                        <input type="number" name="recurso" class="form-control recurso" value="{{ $nota->recurso }}">
                                        <span class="text-danger error-text recurso_error"></span>
                                    </div>  
                                    
                                    <div class="form-group col-md-3 col-12">
                                        <label for="exame_especial">Exame Especial</label>
                                        <input type="number" name="exame_especial" class="form-control exame_especial" value="{{ $nota->exame_especial }}">
                                        <span class="text-danger error-text exame_especial_error"></span>
                                    </div>  
                                    
                                    <div class="form-group col-md-3 col-12">
                                        <label for="descricao">DESCRIÇÃO ESTUDANTES (Opcional)</label>
                                        <input name="descricao" id="" cols="30" rows="5" value="{{ $nota->descricao }}" class="form-control descricao"/>
                                        <span class="text-danger error-text descricao_error"></span>
                                    </div> 
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    @else
                        <div class="card-body">
                            
                            <div class="row">
                                <div class="col-4">
                                    <h6 class="text-center">1ª Mês</h6>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-4 col-12">
                                            <label for="av1">AV1</label>2
                                            <input type="number" name="av1" class="form-control av1" value="{{ $nota->av1 }}">
                                            <span class="text-danger error-text av1_error"></span>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label for="av2">AV2</label>
                                            <input type="number" name="av2" class="form-control av2" value="{{ $nota->av2 }}">
                                            <span class="text-danger error-text av2_error"></span>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label for="av3">AV3</label>
                                            <input type="number" name="av3" class="form-control av3" value="{{ $nota->av3 }}">
                                            <span class="text-danger error-text av3_error"></span>
                                        </div>                                 
                                    </div>
                                </div>
                            
                                <div class="col-4">
                                    <h6 class="text-center">2ª Mês</h6>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-4 col-12">
                                            <label for="av4">AV1</label>
                                            <input type="number"  {{ $usuario->numero_avaliacoes == 1 ? 'disabled': '' }} value="{{ $nota->av4 }}" name="av4" class="form-control av4">
                                            <span class="text-danger error-text av4_error"></span>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label for="av5">AV2</label>
                                            <input type="number"  {{ $usuario->numero_avaliacoes == 1 ? 'disabled': ''  }}  value="{{ $nota->av5 }}" name="av5" class="form-control av5">
                                            <span class="text-danger error-text av5_error"></span>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label for="av6">AV3</label>
                                            <input type="number"  {{ $usuario->numero_avaliacoes == 1 ? 'disabled': ''  }} value="{{ $nota->av6 }}" name="av6" class="form-control av6">
                                            <span class="text-danger error-text av6_error"></span>
                                        </div>                                  
                                    </div>
                                </div>
            
                                <div class="col-4">
                                    <h6 class="text-center">3ª Mês</h6>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-4 col-12">
                                            <label for="av7">AV1</label>
                                            <input type="number"  value="{{ $nota->av7 }}" {{ $usuario->numero_avaliacoes == 1 || $usuario->numero_avaliacoes == 2 ? 'disabled': ''  }}  name="av7" class="form-control av7">
                                            <span class="text-danger error-text av7_error"></span>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label for="av8">AV2</label>
                                            <input type="number"  value="{{ $nota->av8 }}" {{ $usuario->numero_avaliacoes == 1 || $usuario->numero_avaliacoes == 2  ? 'disabled': ''  }}  name="av8" class="form-control av8">
                                            <span class="text-danger error-text av8_error"></span>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label for="av9">AV3</label>
                                            <input type="number"  value="{{ $nota->av9 }}" {{ $usuario->numero_avaliacoes == 1 || $usuario->numero_avaliacoes == 2  ? 'disabled': ''  }}  name="av9" class="form-control av9">
                                            <span class="text-danger error-text av9_error"></span>
                                        </div>                                 
                                    </div>    
                                </div>
                            </div>
            
                            <div class="row">
                                <div class="col-12 col-md-4 col-12">
                                    <div class="row">
                                        <div class="form-group col-md-4 col-12">
                                        <label for="mac">MAC</label>
                                        <input type="number" name="mac" class="form-control mac" value="{{ $nota->mac }}">
                                        <span class="text-danger error-text mac_error"></span>
                                        </div>
                            
                                        <div class="form-group col-md-4 col-12">
                                        <label for="npp">NPP</label>
                                        <input type="number" name="npp" class="form-control  npp" value="{{ $nota->npp }}">
                                        <span class="text-danger error-text npp_error"></span>
                                        </div>
                            
                                        <div class="form-group col-md-4 col-12">
                                        <label for="npt">NPT</label>
                                        <input type="number" name="npt" class="form-control npt" value="{{ $nota->npt }}">
                                        <span class="text-danger error-text npt_error"></span>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="col-12 col-md-8">
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label for="ne">N.E</label>
                                            <input type="number" name="ne" class="form-control ne" value="{{ $nota->ne }}">
                                            <span class="text-danger error-text ne_error"></span>
                                        </div>
                                        
                                        <div class="form-group col-md-2">
                                            <label for="mf">MF</label>
                                            <input type="number" name="mf" class="form-control mf" value="{{ $nota->mf }}">
                                            <span class="text-danger error-text mf_error"></span>
                                        </div>
                                        
                                        <div class="form-group col-md-2">
                                            <label for="np">NP</label>
                                            <input type="number" name="np" class="form-control np" value="{{ $nota->np }}">
                                            <span class="text-danger error-text np_error"></span>
                                        </div>
                                        
                                        <div class="form-group col-md-2">
                                            <label for="pt">PT</label>
                                            <input type="number" name="pt" class="form-control pt" value="{{ $nota->pt }}">
                                            <span class="text-danger error-text pt_error"></span>
                                        </div>
                                        
                                        <div class="form-group col-md-2">
                                            <label for="nec">NEC</label>
                                            <input type="number" name="nec" class="form-control nec" value="{{ $nota->nec }}">
                                            <span class="text-danger error-text nec_error"></span>
                                        </div>
                                        
                                        <div class="form-group col-md-2">
                                            <label for="pap">PAP</label>
                                            <input type="number" name="pap" class="form-control pap" value="{{ $nota->pap }}">
                                            <span class="text-danger error-text pap_error"></span>
                                        </div>
                    
                                        <div class="form-group col-md-2">
                                            <label for="ne">NOTA RECURSO</label>
                                            <input type="number" name="nr" class="form-control nr" value="{{ $nota->nr }}">
                                            <span class="text-danger error-text nr_error"></span>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
            
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="ne">DESCRIÇÃO ESTUDANTES (Opcional)</label>
                                    <textarea  name="descricao_estudante" class="form-control descricao_estudante" placeholder="Bom, Mau, Muito Bom, Muito Mal">{{ $nota->descricao }}</textarea>
                                    <span class="text-danger error-text descricao_estudante_error"></span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="card-footer">
                        @if ($lancamento)
                        <button type="submit" class="btn btn-primary">Finalizar</button>
                        @endif
                    </div>
                </div>
            </form>
      </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection