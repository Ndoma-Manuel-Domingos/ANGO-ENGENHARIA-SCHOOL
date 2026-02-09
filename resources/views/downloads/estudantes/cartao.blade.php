<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Cartão do Estudante</title>
	
    @if ($escola->formato_cartao == 'Horizontal')
        <style>
            /* Estilo do cartão Horizontal */
            .cartao {
                width: 85.6mm;
                height: 54mm;
            }
        </style>
    @endif
    @if ($escola->formato_cartao == 'Vertical')
        <style>
            /* Estilo do cartão Vertical */
            .cartao {
                width: 54mm;
                height: 85.6mm;
            }
        </style>
    @endif
	
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }
        /* Estilo do contêiner */
        .container {
            width: 100%;
            text-align: center;
        }

        /* Estilo do cartão */
        .cartao {
            border: 1px solid #000;
            padding: 10px;
            margin: 10px;
            display: inline-block; /* Para manter os cartões lado a lado */
            vertical-align: top; /* Alinha os cartões na parte superior */
            border-radius: 10px;
        }

        /* Estilo da foto */
        .foto img {
            width: 80px;
            height: 80px;
            border: 1px solid #000;
            margin: 10px;
        }

        /* Estilo da logo */
        .logo img {
            width: 50px;
            height: 50px;
            border: 0px solid #000;
        }

        /* QR Code posicionado no canto inferior direito */
        .qr-code {
            position: relative;
            top: -105px;
            left: 120px;
        }
        
        .qr-code-2 {
            position: relative;
            top: -2px;
            left: 60px;
        }

        .qr-code img,
        .qr-code-2 img{
            width: 100px;
            height: 100px;
        }
        
        .dados h3 {
            text-align: left;
            font-size: 15px;
        }
        .dados p {
            text-align: left;
            font-size: 10px;
        }
                
    </style>
</head>
<body>

    <div class="container">
        @foreach ($matriculas as $item)
            @php
                $estudante = App\Models\web\calendarios\Matricula::with(['classe_at', 'classe', 'turno', 'curso', 'estudante'])->findOrFail($item);
                $cartao = App\Models\web\estudantes\CartaoEstudante::where('servicos_id', $servico->id)->where('estudantes_id', $estudante->estudantes_id)->where('ano_lectivos_id', $estudante->ano_lectivos_id)->get();
                
                $url = Crypt::encrypt($estudante->estudantes_id);
                $qrCode = QrCode::size(200)->generate($url);
            @endphp
            <div class="cartao" style="border-left: 10px solid {{ $escola->cor_cartao ?? "#336699" }}">
                <div style="display: inline-block;width: 100%">
                    <div style="width: 50%;float: left;">
                        <div class="logo">
                            <img src="assets/images/insigna.png" alt="Logo da Escola"  style="float: left;">
                        </div>
                    </div>
                    <div style="width: 50%;float: right;">
                        <div class="foto">
                            <img src="assets/images/user.png" alt="Foto do Estudante"  style="float: right;">
                        </div>
                    </div>
                </div>
                <div class="dados" style="width: 100%;margin-top: 50px">
                    <h3 style="font-size: 7pt;margin-top: -10px;text-transform: uppercase;margin-bottom: 5px;color: {{ $escola->cor_letra_cartao ?? "#000" }}">{{ $escola->nome }}</h3>
                    <h5 style="text-align: left;padding-bottom: 2px">{{ $estudante->estudante->nome }}  {{ $estudante->estudante->sobre_nome }}</h5>
                    <p><strong>CARTÃO DE ESTUDANTE Nº:</strong> _______/ </p>
                    <p><strong>Classe:</strong> {{ $estudante->classe->classes }}</p>
                    <p><strong>Turma:</strong> {{ $estudante->turma($estudante->estudantes_id) }}</p>
                    <p><strong>Turno:</strong> {{ $estudante->turno->turno }}</p>
                    <p><strong>Curso:</strong> {{ $estudante->curso->curso }}</p>
                    
                    <div style="width: 100%;text-align: center;margin-top: 5px;">
                        <p style=""><strong>Director:</strong> ___________________</p>
                        <p style="">{{ $director->nome }}</p>
                        <p><strong>VALIDO ATÉ:</strong> {{ $ano->final }}</p>
                    </div>
                </div>
                @if ($escola->formato_cartao == 'Horizontal')
                    <div class="qr-code" style="margin-right: 10px;">
                        <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code">
                    </div>
                @endif
                
                @if ($escola->formato_cartao == 'Vertical')
                    <div class="qr-code-2" style="margin-right: 10px;">
                        <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="QR Code">
                    </div>
                @endif
            </div>
            
            @if ($escola->tipo_cartao == 'Duplo')
                <div class="cartao" style="border-left: 10px solid {{ $escola->cor_cartao ?? "#336699" }}">
                    <div class="dados" style="width: 100%;padding-top: 10px">
                        @foreach($cartao as $cart)
                            @if ($cart->status == "Pago")
                                <div style="background-color: #ffffff; display: inline-block;height: 34px;width: 31.333333%;border: 1px solid {{ $escola->cor_letra_cartao }};text-align: center;margin-bottom: 5px;padding-top: 5px ">
                                    @if ($estudante->classe->classes == "6ª Classe" || $estudante->classe->classes == "9ª Classe" || $estudante->classe->classes == "13ª Classe")
                                    <h6 style="color: {{ $escola->cor_letra_cartao ?? "#000" }}">{{ $cart->month_name == "Aug" ? "Taxa de Exame" : $cart->mes($cart->month_name)  }}</h6>
                                    @else 
                                    <h6 style="color: {{ $escola->cor_letra_cartao ?? "#000" }}">{{ $cart->mes($cart->month_name)  }}</h6>
                                    @endif
                                    <p style="padding-left: 4px">
                                    {{ $cart->status }} __/__/___
                                    </p>
                                </div>
                            @else
                                @if ($cart->status == "Nao Pago")
                                    <div style="background-color: #ffffff; display: inline-block;height: 34px;width: 31.333333%;border: 1px solid {{ $escola->cor_letra_cartao }};text-align: center;margin-bottom: 5px;padding-top: 5px ">
                                        @if ($estudante->classe->classes == "6ª Classe" || $estudante->classe->classes == "9ª Classe" || $estudante->classe->classes == "13ª Classe")
                                        <h6 style="color: {{ $escola->cor_letra_cartao ?? "#000" }}">{{ $cart->month_name == "Aug" ? "Taxa de Exame" : $cart->mes($cart->month_name)  }}</h6>
                                        @else 
                                        <h6 style="color: {{ $escola->cor_letra_cartao ?? "#000" }}">{{ $cart->mes($cart->month_name)  }}</h6>
                                        @endif
                                        <p style="padding-left: 4px">
                                        {{ $cart->status }} __/__/___
                                        </p>
                                    </div>
                                @else
                                    <div style="background-color: #ffffff; display: inline-block;height: 34px;width: 31.333333%;border: 1px solid {{ $escola->cor_letra_cartao }};text-align: center;margin-bottom: 5px;padding-top: 5px ">
                                        @if ($estudante->classe->classes == "6ª Classe" || $estudante->classe->classes == "9ª Classe" || $estudante->classe->classes == "13ª Classe")
                                        <h6 style="color: {{ $escola->cor_letra_cartao ?? "#000" }}">{{ $cart->month_name == "Aug" ? "Taxa de Exame" : $cart->mes($cart->month_name)  }}</h6>
                                        @else 
                                        <h6 style="color: {{ $escola->cor_letra_cartao ?? "#000" }}">{{ $cart->mes($cart->month_name)  }}</h6>
                                        @endif
                                        <p style="padding-left: 4px">
                                        {{ $cart->status }} __/__/___
                                        </p>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                        <p style="font-size: 5pt;color: {{ $escola->cor_letra_cartao ?? "#000" }}">Contactos da instituição: {{ $escola->telefone1 }} / {{ $escola->telefone2 }} / {{ $escola->telefone3 }}</p>
                        <p style="font-size: 5pt;margin-bottom:10px;color: {{ $escola->cor_letra_cartao ?? "#000" }}">BANCO: {{ $escola->banco }} | Nº CONTA: {{ $escola->conta }} | Nº IBAN: {{ $escola->iban }}</p>
                    </div>
                </div>
            @endif
            
        @endforeach
    </div>

</body>
</html>

