@extends('layouts.escolas')

@section('content')

<style>
    .video-container {}

    #background-video {}

</style>

<!-- Content Wrapper. Contains page content -->
<div class="content">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 col-12">
                    <h1 class="m-0 text-dark">Carregar Foto do Estudante</h1>
                </div>
                <div class="col-sm-6 col-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('web.estudantes') }}">Voltar</a></li>
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
                    <form action="{{ route('carregar-foto-estudante-store') }}" method="post" class="">
                        <div class="card">
                            @csrf
                            <div class="card-header"></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <!-- Opção Upload -->
                                        <h5>Carregar Foto</h5>
                                        <input type="file" id="inputFoto" accept="image/*" class="form-control mb-2">
                                    
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <!-- Opção Webcam -->
                                        <h5>Ou Capturar da Câmera</h5>
                                        <video id="camera" width="320" height="240" autoplay style="border:1px solid #ccc;"></video>
                                        <button type="button" id="btnCapturar" class="btn btn-sm btn-primary mt-2">Tirar Foto</button>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <!-- Área de Crop -->
                                        <div class="mt-3">
                                            <h5>Ajustar Foto:</h5>
                                            <img id="previewFoto" style="max-width:100%; display:none;">
                                        </div>
                                        <button type="button" id="btnRecortar" class="btn btn-success mt-2" style="display:none;">Recortar Foto</button>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <!-- Foto Final -->
                                        <div class="mt-3">
                                            <h5>Foto Final:</h5>
                                            <img id="fotoFinal" style="max-width:200px; display:none; border:2px solid #000;">
                                        </div>
                                
                                        <!-- Input oculto -->
                                        <input type="hidden" name="foto_base64" id="foto_base64">
                                    </div>

                                </div>
                                
                                <input type="hidden" name="estudante_id" value="{{ $estudante->id }}">

                            </div>
                            
                            <div class="card-footer">
                                @if (Auth::user()->can('create: matricula') || Auth::user()->can('create: estudante'))
                                <button type="submit" class="btn btn-outline-primary">Salvar</button>
                                @endif
                                <button type="reset" class="btn btn-outline-danger">Cancelar</button>
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

@section('scripts')
    <!-- Cropper.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    
    <script>
        let cropper;
        const previewFoto = document.getElementById('previewFoto');
        const fotoFinal = document.getElementById('fotoFinal');
        const inputHidden = document.getElementById('foto_base64');
        const btnRecortar = document.getElementById('btnRecortar');
    
        // ============================
        // 1. UPLOAD DE FOTO
        // ============================
        document.getElementById('inputFoto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    iniciarCrop(ev.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    
        // ============================
        // 2. WEBCAM
        // ============================
        const video = document.getElementById('camera');
        const btnCapturar = document.getElementById('btnCapturar');
    
        // Acessa a câmera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => { video.srcObject = stream; });
    
        // Captura da webcam
        btnCapturar.addEventListener('click', function() {
            let canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            let ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);
            let dataURL = canvas.toDataURL("image/png");
            iniciarCrop(dataURL);
        });
    
        // ============================
        // Função Iniciar Crop
        // ============================
        function iniciarCrop(src) {
            previewFoto.src = src;
            previewFoto.style.display = "block";
    
            if (cropper) cropper.destroy();
    
            cropper = new Cropper(previewFoto, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1,
            });
    
            btnRecortar.style.display = "inline-block";
        }
    
        // ============================
        // Recortar
        // ============================
        btnRecortar.addEventListener('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
                const dataURL = canvas.toDataURL("image/png");
                fotoFinal.src = dataURL;
                fotoFinal.style.display = "block";
                inputHidden.value = dataURL;
            }
        });
        
        // 
        $(document).ready(function() {
            $('form').on('submit', function(e) {
                e.preventDefault(); // Impede o envio tradicional do formulário
    
                let form = $(this);
                let formData = form.serialize(); // Serializa os dados do formulário
    
                $.ajax({
                    url: form.attr('action'), // URL do endpoint no backend
                    method: form.attr('method'), // Método HTTP definido no formulário
                    data: formData, // Dados do formulário
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    }
                    , beforeSend: function() {
                        // Você pode adicionar um loader aqui, se necessário
                        progressBeforeSend();
                    }
                    , success: function(response) {
                        // Feche o alerta de carregamento
                        Swal.close();
                        // Exibe uma mensagem de sucesso
                        showMessage('Sucesso!', 'Operação realizada com sucesso!', 'success');
                        window.location.reload();
                    }
                    , error: function(xhr) {
                        // Feche o alerta de carregamento
                        Swal.close();
                        // Trata erros e exibe mensagens para o usuário
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let messages = '';
                            $.each(errors, function(key, value) {
                                messages += `${value}\n *`; // Exibe os erros
                            });
                            showMessage('Erro de Validação!', messages, 'error');
                        } else {
                            showMessage('Erro!', xhr.responseJSON.message, 'error');
                        }
                    }, 
                });
            });
        });
        
    </script>
@endsection
