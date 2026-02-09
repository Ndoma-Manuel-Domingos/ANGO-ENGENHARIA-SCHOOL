@extends('layouts.escolas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Editar Perfil</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('roles-escola.index') }}">Perfil</a></li>
                    <li class="breadcrumb-item active">Editar</li>
                </ol>
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

            <div class="col-12 mb-3">
                <form action="{{ route('roles-escola.update', $role->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-header">

                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="form-group col-md-12 col-12">
                                    <label for="role">Perfil <span class="text-danger">*</span></label>
                                    <input type="text" name="role" value="{{ $role->name }}" placeholder="Novo Perfil" class="form-control">
                                    @error('role')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-12">
                                    <h6 class="bg-light p-2 mb-4"><strong>Conceder Permissões</strong></h6>
                                </div>

                                <div class="col-12 col-md-12">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="select_all" />
                                            <label for="select_all">
                                                Selecionar Todos
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                @foreach ($permissions as $permission)
                                <div class="col-12 col-md-2">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="permission_id{{ $permission->id }}" value="{{ $permission->id }}" name="permission_id[]" @if(in_array($permission->id, $role_permissions)) checked @endif>
                                            <label for="permission_id{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="card-footer">
                            @if (Auth::user()->can('update: role'))
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- /.content-header -->

@endsection


@section('scripts')

<script>
    document.getElementById('select_all').addEventListener('click', function(event) {
        const checkboxes = document.querySelectorAll('input[name="permission_id[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = event.target.checked;
        });
    });

</script>

<script>
    $(function() {
        $("#tabelasRole").DataTable({
            language: {
                url: "{{ asset('plugins/datatables/pt_br.json') }}"
            }
            , "responsive": true
            , "lengthChange": false
            , "autoWidth": false
            , "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });

</script>

@endsection
