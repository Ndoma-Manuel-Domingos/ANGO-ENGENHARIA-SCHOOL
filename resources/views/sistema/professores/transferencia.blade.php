@extends('layouts.provinciais')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Distribuição de Professores</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                {{-- <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Escola</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol> --}}
            </div><!-- /.col -->
        </div>

        <div class="row">
            <div class="col-12 col-md-12">
                @if(session()->has('danger'))
                    <div class="alert alert-warning">
                        {{ session()->get('danger') }}
                    </div>
                @endif

                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
            </div>

            <div class="col-12 col-md-12">
                <form action="{{ route('app.Dispanho-professores-store') }}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-body row">
                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Escolas</label>
                                <select name="escola_id" class="form-control select2">
                                    <option value="">Todos</option>
                                    @foreach ($escolas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                                @error('escola_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="" class="form-label">Professores</label>
                                <select name="professor_id" class="form-control select2">
                                    @if ($professor)
                                        <option value="{{ $professor->id }}">{{ $professor->nome }}  {{ $professor->sobre_nome }}</option>
                                    @else
                                        <option value="">Todos</option>
                                        @foreach ($professores as $item)
                                            <option value="{{ $item->id }}">{{ $item->nome }}  {{ $item->sobre_nome }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('professor_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Confirmar Distribuição</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<!-- /.content-header -->

@endsection
