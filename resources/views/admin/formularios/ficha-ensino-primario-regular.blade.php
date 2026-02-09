@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Ficha AFEP Ensino Primário Regular</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Iniciação</a></li>
                    <li class="breadcrumb-item active">Formulário</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Painel Pedagógico</h5>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- table 01--}}
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table id="example1" style="width: 100%"
                            class="table table-bordered  ">
                            <thead>
                                <tr>
                                    <th colspan="18" class="text-center bg-info">Quadro 1. Aproveitamento dos Alunos da Ensino Primário Regular.</th>
                                </tr>
                                <tr>
                                    <th rowspan="2">Idades/Classes</th>
                                    <th colspan="2" rowspan="2" class="text-center">Matriculados</th>
                                    <th colspan="2" rowspan="2" class="text-center">Aprovados</th>
                                    <th colspan="2" rowspan="2" class="text-center">Reprovados</th>
                                    
                                    <th colspan="4" class="text-center">Transferidos</th>
                                    
                                    <th colspan="2" rowspan="2" class="text-center">Desistidos</th>
                                </tr>
                                
                                <tr>
                                    <th colspan="2" class="text-center">Entrada</th>
                                    <th colspan="2" class="text-center">Saída</th>
                                </tr>
                                
                                <tr>
                                    <th></th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                    
                                    <th>MF</th>
                                    <th>F</th>
                                </tr>
                                
                            </thead>
                            <tbody id="">
                                @for ($i = 0; $i < 6; $i++)
                                <tr>
                                    <td>{{ $i + 1 }}º Classe</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                </tr>
                                @endfor
                                <tr>
                                    <td>TOTAL</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                    <td>0</td>
                                    <td>0</td>
                                    
                                   
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
 
        </div>


    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<!-- /.content -->
@endsection