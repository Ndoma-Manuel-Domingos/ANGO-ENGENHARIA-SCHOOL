@extends('layouts.provinciais')

@section('content')
<div class="container-fluid">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Controle</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home-provincial') }}">Voltar ao painel</a></li>
                        <li class="breadcrumb-item active">Controle</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-3 col-12 col-md-12">
                    <!-- small box -->
                    <div class="small-box bg-light">
                        <div class="inner">
                            <h4>Controlo Notas</h4>
                            <p>Para activar novos periodo de lançamento de notas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <a href="{{ route('web.controlo-lancamento-notas.index') }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                @if ($controlos)
                    <div class="col-lg-3 col-12 col-md-12">
                        <!-- small box -->
                        <div class="small-box bg-light">
                            <div class="inner">
                                
                                <h4>{{ count($controlos) }}</h4>
    
                                <p>Total escolas</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chalkboard"></i>
                            </div>
                            <a href="{{ route('web.controlo-lancamento-notas.escolas', ['lancamento_id' => $lancamento->id]) }}" class="small-box-footer">Mais Informação <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endif

                
            </div>

            
            @if ($controlos)
                
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="callout callout-info bg-info">
                            <h6><i class="fas fa-info"></i> controle geral do lançamento de notas de todas as escolas. Apresentando total de estudantes com as notas já lançadas e as não lançadas.</h6>
                        </div>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-12 col-md-12 mb-4">
                        <div id="poll_div"></div>
                        {!! $lava->render('ColumnChart', 'Grafico', 'poll_div') !!}
                    </div>
                </div>   
            @else
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="callout callout-info bg-warning">
                            <h6><i class="fas fa-info"></i> Neste momento não temos nenhum controle de lançamento de notas, porque encontrar-se desactivo o praso ou periodo de lançamento de notas!</h6>
                        </div>
                    </div>
                </div>
            @endif
            
        </div>
    </section>

</div>

@endsection