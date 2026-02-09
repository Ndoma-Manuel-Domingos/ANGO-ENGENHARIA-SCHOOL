@extends('layouts.escolas')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Entradas de Notificações</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Noticação</a></li>
                <li class="breadcrumb-item active">Entradas</li>
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
            <a href="{{ route('web.enviar-boletin-encarregado') }}" class="btn btn-primary btn-block mb-3">Enviar Notifações</a>

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
                        <li class="nav-item active">
                            <a href="{{ route('web.entradas-notificao') }}" class="nav-link">
                                <i class="fas fa-inbox"></i> Entradas
                                <span class="badge bg-primary float-right">0</span>
                            </a>
                        </li>
        
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
                <h3 class="card-title">Entradas</h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <div class="table-responsive mailbox-messages">
                <table  style="width: 100%" class="table table-hover table-striped">
                    <tbody>

                        <tr>
                            <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                            <td class="mailbox-subject"><b>AdminLTE 3.0 Issue</b> - Trying to find a solution to this problem...
                            </td>
                            <td class="mailbox-attachment"></td>
                            <td class="mailbox-date">5 mins ago</td>
                        </tr>

                    </tbody>
                </table>
                <!-- /.table -->
                </div>
                <!-- /.mail-box-messages -->
            </div>
            
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
  <!-- /.content -->
      
@endsection
