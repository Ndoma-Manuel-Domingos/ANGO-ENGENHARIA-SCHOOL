@extends('layouts.escolas')


@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 col-12">
                    <h1 class="m-0 text-dark">Cartão do Estudante</h1>
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
                    <div class="card">
                        <div class="card-body cartoes-container" style="display: flex;gap: 40px;" id="horizontal-duplo">
                            <!-- Frente Horizontal -->
                            <div id="frente-horizontal" 
                                style="
                                    width: {{ $template->width }}px;
                                    height: {{ $template->height }}px;
                                    position: relative;
                                    overflow: hidden;
                                    border-top: {{ $template->border_top_space }}px solid {{ $template->border_top_color }};
                                    border-bottom: {{ $template->border_bottom_space }}px solid {{ $template->border_bottom_color }};
                                    padding: 15px;border-radius: {{ $template->border_radius }}px;align-items: center;box-shadow: 0 0 8px rgba(0,0,0,0.1);"
                                >
                                <div style="
                                    position: absolute; 
                                    inset: 0; 
                                    background: linear-gradient({{ $template->rotacao_fundo }}deg, {{ $template->background_color }}, {{ $template->background_color_segunda }}, {{ $template->background_color_terceira }}); 
                                    background-image: url('{{ $template->background_image ? asset("/assets/images/$template->background_image") : "" }}');
                                    background-size: 100% 100%;
                                    background-position: 43% 10%;
                                    background-repeat: no-repeat;
                                    background-attachment: fixed;
                                    z-index: -1; 
                                    opacity: {{ $template->opacity }}; 
                                    filter: blur({{ $template->filter }}px);
                                "></div>
                                <div style="position: relative; z-index: 99;color: {{ $template->text_color }};font-family: {{ $template->font_family }};">
                                    <div style="display: flex;align-items: center;width: 100%;margin-bottom: 0;
                                    {{ 
                                        $template->logo_position == 'left' ? 'flex-direction: row' : 
                                        ($template->logo_position == 'right' ? 'flex-direction: row-reverse' : 
                                        ($template->logo_position == 'top' ? 'flex-direction: column' : 'flex-direction: column-reverse')); 
                                    }}">
                                        <img src="{{ asset('uploads/logos/'.$escola->logotipo) }}" alt="Logo" style="height: {{ $template->height_logo }}px; width: auto;margin: 2px;">
                                        <h3 style="font-size: {{ $template->font_size_title }};margin-top: 0;">{{ $escola->nome }}</h3>
                                        </div>
                              
                                    <div style="
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            {{ $template->photo_position == 'left' ? 'flex-direction: row;' : '' }}
                                            {{ $template->photo_position == 'right' ? 'flex-direction: row-reverse;' : '' }}
                                            {{ $template->photo_position == 'top' ? 'flex-direction: column;' : '' }}
                                            {{ $template->photo_position == 'bottom' ? 'flex-direction: column-reverse;' : '' }}
                                            {{ $template->orientation == 'horizontal' ? 'flex-direction: row;' : 'flex-direction: column;' }}
                                        ">
                                        <div style="{{ $template->orientation == "horizontal" ? 'width: 80px;height: 100px;margin-right: 15px;flex-shrink: 0;' : 'width: 100px;height: 120px;margin: 0 auto 10px auto;'  }}">
                                            <img src="{{ asset('/assets/images/estudantes/'.$estudante->image) }}" style="width: 100%; height: 100%; object-fit: cover;border: {{ $template->border_logo }}px solid {{ $template->border_logo_color }};border-radius: {{ $template->border_logo_radius }}px;">
                                            <p style="font-size: {{ $template->font_size }};{{ $template->orientation == "horizontal" ? 'text-align: left;' : 'text-align: center;' }}"><b>Validade:</b> {{ \Carbon\Carbon::parse($template->data_validade)->format('d/m/Y') }}</p>
                                        </div>
                                        <div style="line-height: {{ $template->line_height }};padding: 10px">
                                            <h2 style="font-size: {{ $template->font_size_subtitle }};{{ $template->orientation == "horizontal" ? 'text-align: left;' : 'text-align: center;' }}">Nº {{ $estudante->numero_processo ?? "" }} - {{ $estudante->nome ?? "" }} {{ $estudante->sobre_nome ?? "" }} </h2>
                                            <p style="font-size: {{ $template->font_size }};{{ $template->orientation == "horizontal" ? 'text-align: left;' : 'text-align: center;' }}"><b>Curso:</b> {{ $matricula->curso->curso ?? "" }}</p>
                                            <p style="font-size: {{ $template->font_size }};{{ $template->orientation == "horizontal" ? 'text-align: left;' : 'text-align: center;' }}"><b>Classe:</b> {{ $matricula->classe->classes ?? "" }}</p>
                                           
                                        </div>
                                    </div>
                                    
                                    <div style="display: flex;align-items: center;width: 100%;justify-content: left;flex-direction: column;line-height: 10px;">
                                        <p style="font-size: {{ $template->font_size }};margin-top: 0px;border-bottom: 1px solid;padding-bottom: 15px;line-height: 2px;">Director Geral</p>
                                        <p style="font-size: {{ $template->font_size }};margin: 0;line-height: 2px;">{{ $director->nome ?? "" }}</p>
                                    </div>
                                    
                                </div>
                                
                            </div>
                            <!-- Verso Horizontal -->
                            <div id="verso-horizontal" 
                                style="width: {{ $template->width }}px; 
                                height: {{ $template->height }}px;
                                position: relative;align-items: center;
                                padding: 15px;display: flex;
                                justify-content: center;
                                overflow: hidden;
                                position: relative;
                                border-top: {{ $template->border_top_space }}px solid {{ $template->border_top_color }};
                                border-bottom: {{ $template->border_bottom_space }}px solid {{ $template->border_bottom_color }};
                                border-radius: {{ $template->border_radius }}px;
                                box-shadow: 0 0 8px rgba(0,0,0,0.3);">
                                {!! QrCode::size(180)->generate($estudante->id) !!}
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <button class="btn btn-primary" onclick="imprimirCartoes()">🖨️ Imprimir Tudo</button>
                            <button class="btn btn-primary" onclick="downloadPNG('frente-horizontal')">⬇️ Download Frente Horizontal</button>
                            <button class="btn btn-primary" onclick="downloadPNG('verso-horizontal')">⬇️ Download Verso Horizontal</button>
                            <br><br>
                            <button class="btn btn-primary" onclick="imprimirContainer('horizontal-duplo')">🖨️ Imprimir Cartão Horizontal Duplo</button>
                            <button class="btn btn-primary" onclick="downloadPNG('horizontal-duplo')">⬇️ Download Horizontal Duplo</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<!-- /.content-wrapper -->
<!-- /.content -->
@endsection

@section('scripts')
    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function imprimirCartoes() {
            window.print();
        }

        function imprimirContainer(id) {
            const container = document.getElementById(id).outerHTML;
            const win = window.open('', '_blank');
            win.document.write('<html><head><title>Impressão</title></head><body>' + container + '</body></html>');
            win.document.close();
            win.print();
        }

        function downloadPNG(id) {
            const card = document.getElementById(id);
            html2canvas(card).then(canvas => {
                const link = document.createElement("a");
                link.download = id + ".png";
                link.href = canvas.toDataURL("image/png");
                link.click();
            });
        }
    </script>
@endsection
