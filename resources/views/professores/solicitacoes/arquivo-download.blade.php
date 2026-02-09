<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="pt-pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestão Escolar | Lançamento de Notas</title>
    <style>
        
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: calibri;
        }ul,ol{
            list-style: none;
        }
        a{
            text-decoration: none;
        }
        body{
            padding: 20px;
            /* border: 20px solid rgb(162, 170, 6); */
            font-family: Arial, Helvetica, sans-serif;
        }
        h1{
            font-size: 10pt;
            font-family: arial;
            margin-bottom: 4px;
        }
        h2{
            font-size: 9pt;
        }

        .titulo{
            font-size: 10pt;
            text-align: center;
        }

        p{
            margin-bottom: 20px;
            line-height: 20px;
            font-size: 10pt;
            text-align: center;
        }

        table{
            width: 100%;
            text-align: left;
            border-spacing: 0;  
            margin-bottom: 10px;
            border: 1px solid #fff;
        }
        thead{
            background-color: #eaeaea;
            border-bottom: 1px solid #006699;

        }
        th, td{
            padding: 10px;
            border: 1px solid #000;
            font-size: 9pt;
        }

        .border{
            border: 1px solid #eaeaea;
        }

        .flex{
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-content: center;
            align-items: center;
        }

        .col{
            width: 25%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .cols{
            width: 50%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .col-2{
            width: 10%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .col-8{
            width: 80%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .logo{
            height: 80px;
            width: 80px;
            /*border-radius: 300px;*/
            /*padding: 30px; */
        }
        .ml{
            margin-left: 80px;
        }
        .text-center{
            text-align: center;
        }
        ul,ol{
            list-style: none;
        }
        a{
            text-decoration: none;
        }

        #header{
            width: 100%;
            float: left;
            text-align: center;
            margin-bottom: 10px;
        }

        .logo, 
        .texto-header{
            width: 100%;
            height: 70px;
        }

    </style>
</head>
<body class="hold-transition sidebar-mini">

    <header id="header">
        <div class="logo">
            <img src="{{ public_path('assets/images/insigna.png') }}" alt="" style="text-align: center;height: 60px;width: 60px;margin-bottom: 40px;">
        </div>

        <div class="texto-header">
            <h1 class="fs-5 text-center"><strong>República de Angola</strong></h1>
            <h1 class="fs-5 text-center"><strong>Governo Provincial de Luanda</strong></h1>
            <h1 class="fs-5 text-center"><strong>Gabinete Provincial da Educação</strong></h1>
            {{-- <h1 class="fs-5 text-center"><strong>Gabinete Provincial da Educação</strong></h1> --}}
            <h1 class="fs-5 text-center"><strong>{{ $item->instituicao_resposta->nome }}</strong></h1> 
        </div>
    </header>

    <h3 class="fs-5 text-center" style="font-size: 20pt;margin-top: 10px;margin-bottom: 30px;text-transform: uppercase"><strong style="border-bottom: 1px solid #000;color: red">SOLICITAÇÃO DE {{ $item->solicitacao }}</strong></h3>          
    <p style="font-size: 12pt;line-height: 30px;text-align: justify;">
        Eu <strong style="border-bottom: 1px solid rgba(0,0,0,.2);color: red;">{{ $item->instituicao_resposta->director }}</strong>, Director Do {{ $item->instituicao_resposta->nome }}, confirmo que <strong style="color: red">{{ $item->professor->nome}} {{ $item->professor->sobre_nome }}</strong>,
         filho de {{ $item->professor->pai}} e de {{ $item->professor->mae}}, natural de {{ $item->professor->naturalidade }}, municipio da(o) {{ $item->professor->municipio->nome }}, provincia de {{ $item->professor->provincia->nome }}, 
         nascimento aos {{ $item->professor->nascimento}}, portador no Bilheite Nº {{ $item->professor->bilheite }}.
    </p>
    
    <p style="font-size: 12pt;line-height: 30px;text-align: justify;">O Srº <span class="text-decoration-underline">{{ $item->professor->nome }}</span> <span class="text-decoration-underline">{{ $item->professor->sobre_nome }}</span>   fez uma solicitação de <span class="text-decoration-underline">{{ $item->solicitacao }}</span>  no curso de <span class="text-decoration-underline">{{ $item->curso->curso }}</span> , 
        na disciplina de <span class="text-decoration-underline">{{ $item->disciplina->disciplina }}</span>  e na classe de <span class="text-decoration-underline">{{ $item->classe->classes }}</span> na instituição <span class="text-decoration-underline">{{ $item->instituicao1->nome }}</span>.</p>
    <p style="font-size: 12pt;line-height: 30px;text-align: justify;">{{ $item->descricao }}</p>

    <div style="width: 100%;text-align: center;">
        <div style="width: 50%;float: right;">
            <h5>O DIRECTOR GERAL</h5>
            <p>_________________________________________________</p>
            <p>__________/____________/______________</p>
        </div>
    </div>


</body>
</html>





