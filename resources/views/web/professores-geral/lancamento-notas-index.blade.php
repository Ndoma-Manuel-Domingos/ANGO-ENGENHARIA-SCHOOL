@extends('layouts.admin')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0 text-dark">Turmas na escola</h1>
                </div>
                <div class="col-sm-3">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Voltar</a></li>
                        <li class="breadcrumb-item active">Perfil</li>
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
                    <form action="{{ route('app.professores-lancamento-nota-estudante-store') }}" method="post">
                        @csrf
                        <div class="card">

                            <div class="card-header bg-light">
                                Lançamento de notas
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="nota_id" value="{{ $nota->id }}">
                                <input type="hidden" name="professor_id" value="{{ $professor->id }}">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="mac">MAC</label>
                                        <input type="number" name="mac" class="form-control mac" value="{{ $nota->mac }}">
                                        <span class="text-danger error-text mac_error"></span>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="npt">NPT</label>
                                        <input type="number" name="npt" class="form-control npt" value="{{ $nota->npt }}">
                                        <span class="text-danger error-text npt_error"></span>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="ne">NOTA EXAME</label>
                                        <input type="number" name="ne" class="form-control ne" value="{{ $nota->ne }}">
                                        <span class="text-danger error-text ne_error"></span>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="ne">NOTA RECURSO</label>
                                        <input type="number" name="nr" class="form-control nr" value="{{ $nota->nr }}">
                                        <span class="text-danger error-text nr_error"></span>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="ne">DESCRIÇÃO ESTUDANTES (Opcional)</label>
                                        <textarea name="descricao_estudante" class="form-control descricao_estudante" placeholder="Bom, Mau, Muito Bom, Muito Mal">{{ $nota->descricao }}</textarea>
                                        <span class="text-danger error-text descricao_estudante_error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Finalizar</button>
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
