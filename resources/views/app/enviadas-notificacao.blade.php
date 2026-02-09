@extends('layouts.escolas')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Notificações Enviadas</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Notificação</a></li>
                <li class="breadcrumb-item active">Enviadas</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
  
<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-md-3">

        @if (auth()->user()->hasRole(['encarregado']))
            <a href="{{ route('web.enviar-boletin-encarregado') }}" class="btn btn-primary btn-block mb-3">Entradas</a>
        @else
            <a href="{{ route('web.enviar-boletin-encarregado') }}" class="btn btn-primary btn-block mb-3">Enviar Notifações</a>
        @endif 
        
        <div class="card">
            <div class="card-header">
            <h3 class="card-title">Arquivos</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">

                    @if (auth()->user()->hasRole(['encarregado']))
                        <li class="nav-item">
                            <a href="{{ route('web.enviadas-notificao') }}" class="nav-link">
                                <i class="far fa-envelope"></i> Entradas
                                <span class="badge bg-primary float-right">{{ $notificacaoEnviadas }}</span>
                            </a>
                        </li>
        
                        <li class="nav-item">
                            <a href="{{ route('web.reciclagem-notificao') }}" class="nav-link">
                                <i class="far fa-envelope"></i> Reciclagem
                                <span class="badge bg-primary float-right">{{ $notificacaoReciclagem }}</span>
                            </a>
                        </li> 
                    @else
                        <li class="nav-item">
                            <a href="{{ route('web.enviadas-notificao') }}" class="nav-link">
                                <i class="far fa-envelope"></i> Enviadas
                                <span class="badge bg-primary float-right">{{ $notificacaoEnviadas }}</span>
                            </a>
                        </li>
        
                        <li class="nav-item">
                            <a href="{{ route('web.reciclagem-notificao') }}" class="nav-link">
                                <i class="far fa-envelope"></i> Reciclagem
                                <span class="badge bg-primary float-right">{{ $notificacaoReciclagem }}</span>
                            </a>
                        </li>  
                    @endif 
                

                </ul>
            </div>  
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="card card-primary card-outline">
        <div class="card-header">
            @if (auth()->user()->hasRole(['encarregado']))
                <h3 class="card-title">Entradas</h3>
            @else
                <h3 class="card-title">Enviadas</h3>
            @endif 
        </div>
        <!-- /.card-header -->
        @if ($notificacao)
            <div class="card-body p-0">
                <div class="table-responsive mailbox-messages">
                <table  style="width: 100%" class="table table-hover table-striped">
                    <tbody>
                        @foreach ($notificacao as $item)
                            <tr>
                                <td class="mailbox-name"><a href="{{ route('web.ler-notificacao', $item->id) }}">{{ $item->titulo }}</a></td>
                                <td class="mailbox-subject">{{ $item->descricao }}</td>
                                <td class="mailbox-attachment"></td>
                                <td class="mailbox-date">{{ $item->created_at }}</td>
                            </tr>    
                        @endforeach
                    </tbody>
                </table>
                <!-- /.table -->
                </div>
                <!-- /.mail-box-messages -->
            </div>           
        @endif

        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
      
@endsection
