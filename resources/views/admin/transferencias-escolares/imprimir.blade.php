<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transferências Escolares</title>
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            font-size: 10pt;
            margin-bottom: 4px;
        }

        p {
            /* margin-bottom: 20px; */
            line-height: 20px;
            font-size: 12pt;
        }

        table {
            width: 100%;
            text-align: left;
            border-spacing: 0;
            margin-bottom: 10px;
            border: 1px solid #fff;
            font-size: 12pt;
        }

        thead {
            background-color: #eaeaea;
            border-bottom: 1px solid #006699;
            font-size: 10pt;

        }

        th,
        td {
            padding: 12px;
            border-top: 1px solid #000;
            font-size: 10pt;

        }

    </style>
</head>
<body>

    @php
    function cabecalho($classe, $header1, $header2){
    switch ($classe) {
    case '10º Classe':
    case '10ª Classe':
    return $header2;
    break;

    case '11º Classe':
    case '11ª Classe':
    return $header2;
    break;

    case '12º Classe':
    case '12ª Classe':
    return $header2;
    break;

    case '13º Classe':
    case '13ª Classe':
    return $header2;
    break;

    default:
    return $header1;
    break;
    }
    }
    @endphp

    <header>
        <table style="border: 1px solid #000">
            <tr>
                <td>
                    <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 70px;width: 70px;">
                </td>
                <td style="text-align: center">
                    <strong>@php echo cabecalho($transferencia->classe->classes, $escola->cabecalho1, $escola->cabecalho2); @endphp</strong> <br>
                    <span style="display: block;margin-top: 30px">FICHA DE TRANSFERÊNCIA ESCOLAR DO ESTUDANTE</span>
                </td>

                <td style="text-align: right">
                    <span style="display: block">Data: {{ date("Y-m-d") }} </span><br>
                    <span style="display: block">Hora: {{ date("H:i:s") }} </span><br>
                    <span style="display: block">Pág: 1/1 </span>
                </td>
            </tr>

        </table>
    </header>

    <main>
        <table style="border: 1px solid #000">
            <tr>
                <td style="font-size: 10px;">DADOS PESSOAIS</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="font-size: 9px;display: block">
                    <strong style="display: block">MATRICULA: {{ $transferencia->estudante->matricula->numero_estudante ?? '----' }} </strong> <br>
                    <strong style="display: block">ALUNO(a): {{ $transferencia->estudante->nome }} {{ $transferencia->estudante->sobre_nome }}</strong> <br>
                    <strong style="display: block">FILHAÇÃO: {{ $transferencia->estudante->pai }} e {{ $transferencia->estudante->mae }}</strong> <br>
                </td>
                <td></td>
                <td></td>
                <td style="text-align: right">
                    <img src="{{ public_path("assets/images/user.png") }}" alt="" style="text-align: right;height: 70px;width: 70px;">
                </td>
            </tr>

            <tr>
                <td style="font-size: 9px;">GENERO: <strong>{{ $transferencia->estudante->genero }}</strong></td>
                <td style="font-size: 9px;">B.I/CÉDULA: <strong>{{ $transferencia->estudante->bilheite }}</strong></td>
                <td style="font-size: 9px;">ESTADO CIVIL: <strong>{{ $transferencia->estudante->estado_civil }}</strong></td>
                <td style="font-size: 9px;">NASCIMENTO: <strong>{{ $transferencia->estudante->nascimento }}</strong></td>
            </tr>

            <tr>
                <td style="font-size: 9px;">TELEFONE: <strong>{{ $transferencia->estudante->telefone_estudante ?? '--- --- ---' }}</strong></td>
                <td style="font-size: 9px;">TELEFONE PAI: <strong>{{ $transferencia->estudante->telefone_pai ?? '--- --- ---' }}</strong></td>
                <td style="font-size: 9px;">TELEFONE MÃE: <strong>{{ $transferencia->estudante->telefone_mae ?? '--- --- ---' }}</strong></td>
                <td style="font-size: 9px;"></td>
            </tr>


            <tr>
                <td style="font-size: 10px;">DADOS ACADEMICOS</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong style="display: block;text-align: left;font-size: 9px;">CLASSE: {{ $transferencia->classe->classes }}</strong></td>
                <td><strong style="display: block;text-align: left;font-size: 9px;">TURNO: {{ $transferencia->turno->turno }}</strong></td>
                <td><strong style="display: block;text-align: left;font-size: 9px;">CURSO: {{ $transferencia->curso->curso }}</strong></td>
                <td><strong style="display: block;text-align: left;font-size: 9px;">ÁREA FORMAÇÃO: {{ $transferencia->curso->area_formacao }}</strong> </td>
            </tr>


            <tr>
                <td style="font-size: 10px;">DADOS DA TRANSFERÊNCIA</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" style="font-size: 9px;padding: 5px;">ESCOLA ORIGEM: <br><br><strong>{{ $transferencia->origem->nome }}</strong></td>
                <td colspan="2" style="font-size: 9px;padding: 5px;">ESCOLA DESTINO: <br><br><strong>{{ $transferencia->destino->nome }}</strong></td>
            </tr>

            <tr>
                <td style="font-size: 9px;">DATA: <strong>{{ date("d-m-Y", strtotime($transferencia->created_at)) }}</strong></td>
                <td style="font-size: 9px;">HORA: <strong>{{ date("H:i:s", strtotime($transferencia->created_at)) }}</strong></td>
                <td style="font-size: 9px;">ESTADO: <strong>{{ $transferencia->status }}</strong></td>
                <td style="font-size: 9px;">RESPONSÁVEL DA TRANSFERÊNCIA: <strong>{{ $transferencia->user->nome }}</strong></td>
            </tr>


        </table>

    </main>

</body>
</html>
