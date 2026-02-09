<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titulo }} | Gestão Escolar</title>
    <style type="text/css">
        *{
            margin: 0;
            padding: 0;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            text-align: left;
        }
        body{
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1{
            font-size: 15pt;
            margin-bottom: 10px;
        }
        h2{
            font-size: 12pt;
        }
        p{
            /* margin-bottom: 20px; */
            line-height: 25px;
            font-size: 12pt;
            text-align: justify;
        }
        strong{
            font-size: 12pt;
        }

        table{
            width: 100%;
            text-align: left;
            border-spacing: 0;	
            margin-bottom: 10px;
            /* border: 1px solid rgb(0, 0, 0); */
            font-size: 12pt;
        }
        thead{
            background-color: #fdfdfd;
            font-size: 10px;
        }
        th, td{
            padding: 6px;
            font-size: 9px;
            margin: 0;
            padding: 0;
        }
        strong{
            font-size: 9px;
        }
    </style>

</head>

<header style="position: absolute;top: 30px;right: 30px;left: 30px;">
    <table>
        <tr>
            <td rowspan="">
                <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 70px;width: 70px;">
            </td>
            <td style="text-align: right">
                <span>Pág: 1/1</span> <br> <br>
                {{ $movimento->created_at?? '' }} <br>
                ORGINAL
            </td>
        </tr>
        <tr>
            <td style="padding: 5px 0;">
                <strong>{{ $escola->cabecalho1??'' }}</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Endereço:</strong> {{ $escola->endereco??'' }}
            </td>
            <td>DADOS OPERADOR</td>
        </tr>
        <tr>
            <td>
                <strong>NIF:</strong> {{ $escola->documento??'' }}
            </td>
            <td  style="border-top: #eaeaea 1px solid;border-left: #eaeaea 1px solid; padding: 2px;">
                <strong style="font-size: 9px">{{ $movimento->user_abrir->nome??'' }}</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Telefone:</strong> {{ $escola->telefone1??'' }}
            </td>
            <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                <strong>E-mail:</strong> {{ $movimento->user_abrir->email??'' }}
            </td>
        </tr>
        <tr>
            <td>
                
            </td>
            <td  style="border-left: #eaeaea 1px solid; padding: 2px">
            </td>
        </tr>

        <tr>
            <td>
                
            </td>
            <td  style="border-left: #eaeaea 1px solid; padding: 2px">
                <strong>TEL: </strong> {{ $movimento->user_abrir->telefone??'' }}
            </td>
        </tr>
        <tr>
            <td>
                <strong>E-mail:</strong> {{ $escola->site??'' }}
            </td>
            
        </tr>
        
    </table>
</header>


<main style="position: absolute;top: 230px;right: 30px;left: 30px;">

    <table>
        <tr>
            <td style="font-size: 14px;padding: 1px 0;display: block;text-align: center"><h6>RELATÓRIO DO CAIXA - <strong>{{ $movimento->caixa->conta ?? "" }} - {{ $movimento->caixa->caixa ?? "" }}</strong></h6></td>
        </tr>
    </table>
    <hr>

    <table  style="width: 100%" class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
        <tbody>
                
            <tr>
                <th style="padding: 5px 0px;text-align: left;text-transform: uppercase;border-bottom: 1px dashed #000;">Valor Abertura</th>
                <td style="padding: 5px 0px;text-align: left;border-bottom: 1px dashed #000;">{{ number_format($movimento->valor_abrir ?? 0, 2, ',', '.') }} Kz</td>
            </tr>
                
            <tr>
                <th style="padding: 5px 0px;text-align: left;text-transform: uppercase;border-bottom: 1px dashed #000;">Valor Fecho</th>
                <td style="padding: 5px 0px;text-align: left;border-bottom: 1px dashed #000;">{{ number_format($movimento->valor_fecha ?? 0, 2, ',', '.') }} Kz</td>
            </tr>
                
            <tr>
                <th style="padding: 5px 0px;text-align: left;text-transform: uppercase;border-bottom: 1px dashed #000;">Valor TPA</th>
                <td style="padding: 5px 0px;text-align: left;border-bottom: 1px dashed #000;width: 500px">{{ number_format($movimento->valor_tpa ?? 0, 2, ',', '.') }} Kz</td>
            </tr>
            
            <tr>
                <th style="padding: 5px 0px;text-align: left;text-transform: uppercase;border-bottom: 1px dashed #000;">Valor Cash</th>
                <td style="padding: 5px 0px;text-align: left;border-bottom: 1px dashed #000;">{{ number_format($movimento->valor_cache ?? 0, 2, ',', '.') }} Kz</td>
            </tr>
            
            <tr>
                <th style="padding: 5px 0px;text-align: left;text-transform: uppercase;border-bottom: 1px dashed #000;">Valor Transferência</th>
                <td style="padding: 5px 0px;text-align: left;border-bottom: 1px dashed #000;">{{ number_format($movimento->valor_transferencia ?? 0, 2, ',', '.') }} Kz</td>
            </tr>
            
            <tr>
                <th style="padding: 5px 0px;text-align: left;text-transform: uppercase;border-bottom: 1px dashed #000;">Valor Depositado</th>
                <td style="padding: 5px 0px;text-align: left;border-bottom: 1px dashed #000;">{{ number_format($movimento->valor_depositado ?? 0, 2, ',', '.') }} Kz</td>
            </tr>
            
        </tbody>
    </table> 
    
    
    <table  style="width: 100%" class="table table-stripeds" style="border-top: 1px dashed #000;border-bottom: 1px dashed #000;">
        <tbody>
            <tr>
                <th style="padding: 5px 0px;text-align: left;text-transform: uppercase;border-bottom: 1px dashed #000;">STATUS BANCO</th>
                <td style="padding: 5px 0px;text-align: left;border-bottom: 1px dashed #000;text-transform: uppercase;">{{ $movimento->status ?? '' }}</td>
            </tr>
            <tr>
                <th style="padding: 5px 0px;text-align: left;text-transform: uppercase;border-bottom: 1px dashed #000;">1º Registrar saída de caixa:</th>
                <td style="padding: 5px 0px;text-align: left;border-bottom: 1px dashed #000;width: 500px">{{ number_format($movimento->valor_retirado1 ?? 0, 2, ',', '.') }} Kz</td>
            </tr>    
            <tr>
                <th style="padding: 5px 0px;text-align: left;text-transform: uppercase;border-bottom: 1px dashed #000;">2º Registrar saída de caixa:</th>
                <td style="padding: 5px 0px;text-align: left;border-bottom: 1px dashed #000;">{{ number_format($movimento->valor_retirado1 ?? 0, 2, ',', '.') }} Kz</td>
            </tr>
            <tr>
                <th style="padding: 5px 0px;text-align: left;text-transform: uppercase;border-bottom: 1px dashed #000;">3º Registrar saída de caixa:</th>
                <td style="padding: 5px 0px;text-align: left;border-bottom: 1px dashed #000;">{{ number_format($movimento->valor_retirado3 ?? 0, 2, ',', '.') }} Kz</td>
            </tr>
        </tbody>
    </table> 
    
    <table style="margin-top: 50px ">
        <thead>
            <tr>
                <th style="padding: 4px 0">Assinatura Operador</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>_______________________________________</td>
            </tr>
            <tr>
                <td><br></td>
            </tr>
            <tr>
                <td>{{ $movimento->user_abrir->nome??'' }}</td>
            </tr>    
        </tbody>
        
    </table>  
    
</main>

</html>