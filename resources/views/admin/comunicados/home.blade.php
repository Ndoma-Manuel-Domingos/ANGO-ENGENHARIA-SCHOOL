@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Escolhe Tipo Comunicado</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Comunicados</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-header text-center py-4">
                        <h3><i class="fas fa-file"></i></h3>
                        <h3>Comunicado Interno</h3>
                        <p>
                            Escolha esta opção para enviar comunicados destinados a estudantes, professores ou outros funcionários da instituição.  
                            Ideal para avisos internos como reuniões, mudanças de horários, eventos acadêmicos ou administrativos.
                        </p>
                    </div>

                    <div class="card-body text-center">
                        <a href="{{ route('comunicados.index') }}" class="btn btn-outline-primary d-block my-4">Comunicar</a>
                        <p>Estudantes, Professores & Funcionários</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="card">
                    <div class="card-header text-center py-4">
                        <h3><i class="fas fa-file"></i></h3>
                        <h3>Comunicado para Encarregados</h3>
                        <p>
                            Selecione esta opção para enviar comunicados diretamente aos encarregados de educação.  
                            Ideal para informar sobre desempenho dos alunos, eventos escolares, reuniões de pais e outros assuntos pertinentes.
                        </p>
                    </div>

                    <div class="card-body text-center">
                        <a href="{{ route('comunicados.comunicadar-encarregados') }}" class="btn btn-outline-primary d-block my-4">Comunicar</a>
                        <p>Pais & Encarregados</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')
<script>
    $(function() {
        $("#table_load").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#carregarTabelaEstudantes_wrapper .col-md-6:eq(0)');
    });

</script>
@endsection
