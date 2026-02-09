@extends('layouts.professores')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0 text-dark">Detalhe do Comunicado</h1>
                </div>
                <div class="col-sm-3">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('prof.meus-comunicados') }}">Voltar</a></li>
                        <li class="breadcrumb-item active">Comunicados</li>
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
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">Ler Comunicado</h3>
                        </div>
    
                        <div class="card-body p-0">
                            <div class="mailbox-read-info">
                                <h5>{{ $comunicado->titulo }}</h5>
                                <h6>From: {{ $comunicado->escola->nome }}</a>
                                    <span class="mailbox-read-time float-right"> {{ date("d M. Y H:i", strtotime($comunicado->created_at)) }}</span>
                                </h6>
                            </div>
                            
                            <div class="mailbox-read-message">
                                <p>@php echo $comunicado->descricao @endphp</p>
                            </div>
    
                        </div>
    
                        <div class="card-footer bg-white">
                            <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                                @if ($comunicado->documento)
                                    <li>
                                        <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>
                                        <div class="mailbox-attachment-info">
                                            <a href="../assets/anexos/{{ $comunicado->documento }}" class="mailbox-attachment-name" target="_blink"><i class="fas fa-paperclip"></i>
                                                Sep2014-report.pdf</a>
                                            <span class="mailbox-attachment-size clearfix mt-1">
                                                <span>1,245 KB</span>
                                                <a href="../assets/anexos/{{ $comunicado->documento }}" class="btn btn-default float-right"><i
                                                        class="fas fa-cloud-download-alt"></i></a>
                                            </span>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
    
                        <div class="card-footer">
                        </div>
    
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@endsection
