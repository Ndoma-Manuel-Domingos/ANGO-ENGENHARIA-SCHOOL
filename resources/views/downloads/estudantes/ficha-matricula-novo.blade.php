<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if ($matricula->tipo == "inscricao")
    <title style="display: block;margin-top: 30px">FICHA DE INSCRIÇÃO</title>
    @endif

    @if ($matricula->tipo == "matricula")
    <title style="display: block;margin-top: 30px">FICHA DE MATRICULA</title>
    @endif

    @if ($matricula->tipo == "candidatura")
    <title style="display: block;margin-top: 30px">FICHA DE CANDIDATURA</title>
    @endif

    @if ($matricula->tipo == "confirmacao")
    <title style="display: block;margin-top: 30px">FICHA DE CONFIRMAÇÃO</title>
    @endif
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

        .border {
            border: 1px solid #eaeaea;
        }

        .flex {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-content: center;
            align-items: center;
        }

        .col {
            width: 25%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .cols {
            width: 50%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .col-2 {
            width: 10%;
            padding-left: 2px;
            padding-right: 50px;
            margin-right: 50px;
            float: right;
        }

        .col-8 {
            width: 80%;
            padding-left: 2px;
            padding-right: 2px;
        }

        .logo {
            height: 70px;
            width: 70px;
            /*border-radius: 300px;*/
            /* padding: 30px;  */
            border: 1px solid #000;
        }

        .ml {
            margin-left: 80px;
        }

        .text-center {
            text-align: center;
        }

    </style>
</head>
<body>
    @if (Auth::user()->impressora == "Ticket")

    <header style="max-width: 250px;position: absolute;left: 10px;right: 10px;">
        <table style="border: 1px solid #000">
            <tr>
                <td>
                    <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 50px;width: 50px;">
                </td>

                <td style="text-align: center;font-size: 9px">
                    <strong>{{ $escola->cabecalho1 }}</strong> <br>
                    @if ($matricula->tipo == "inscricao")
                    <span style="display: block;margin-top: 30px;font-size: 9px">FICHA DE INSCRIÇÃO</span>
                    @endif

                    @if ($matricula->tipo == "matricula")
                    <span style="display: block;margin-top: 30px;font-size: 9px">FICHA DE MATRICULA</span>
                    @endif

                    @if ($matricula->tipo == "candidatura")
                    <span style="display: block;margin-top: 30px;font-size: 9px">FICHA DE CANDIDATURA</span>
                    @endif

                    @if ($matricula->tipo == "confirmacao")
                    <span style="display: block;margin-top: 30px;font-size: 9px">FICHA DE CONFIRMAÇÃO</span>
                    @endif
                </td>

                <td style="text-align: right">
                    <span style="display: block;font-size: 9px">Data: {{ date("Y-m-d") }} </span><br>
                    <span style="display: block;font-size: 9px">Hora: {{ date("H:i:s") }} </span><br>
                    <span style="display: block;font-size: 9px">Pág: 1/1 </span>
                </td>
            </tr>
        </table>
    </header>

    <main style="max-width: 250px;position: absolute;top: 150px; left: 10px;right: 10px;">
        <table style="border: 1px solid #000">
            <tr>
                <td style="font-size: 10px;" colspan="4">DADOS PESSOAIS</td>
            </tr>
            <tr>
                <td style="font-size: 9px;display: block">
                    <strong style="display: block">MATRICULA: {{ $matricula->estudante->numero_processo }}</strong> <br>
                    <strong style="display: block">ALUNO(a): {{ $matricula->estudante->nome }} {{ $matricula->estudante->sobre_nome }}</strong> <br>
                    <strong style="display: block">FILHAÇÃO: {{ $matricula->estudante->pai }} e {{ $matricula->estudante->mae }}</strong> <br>
                </td>
                <td style="text-align: right" colspan="3">
                    <img src="{{ public_path("assets/images/user.png") }}" alt="" style="text-align: right;height: 70px;width: 70px;">
                </td>
            </tr>

            <tr>
                <td colspan="4" style="font-size: 9px;">GENERO: <strong>{{ $matricula->estudante->genero }}</strong>
                    <br><br>B.I/CÉDULA: <strong>{{ $matricula->estudante->bilheite }}</strong>
                    <br><br>ESTADO CIVIL: <strong>{{ $matricula->estudante->estado_civil }}</strong>
                    <br><br>NASCIMENTO: <strong>{{ $matricula->estudante->nascimento }}</strong>
                    <br><br>TELEFONE: <strong>{{ $matricula->estudante->telefone_estudante }}</strong>
                    <br><br>TELEFONE PAI/MÃE: <strong>{{ $matricula->estudante->telefone_mae?? '000 000 000' }} / {{ $matricula->estudante->telefone_pai ?? '000 000 000' }}</strong>
                </td>
            </tr>


            <tr>
                <td style="font-size: 10px;" colspan="4">DADOS ACADEMICOS</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: left;font-size: 9px;">
                    CLASSE: <strong>{{ $matricula->classe->classes }}</strong>
                    <br><br>CLASSE ANTEIRIOR: <strong>{{ $matricula->classe_at->classes }}</strong>
                    <br><br>TURNO: <strong>{{ $matricula->turno->turno }}</strong>
                    <br><br>CURSO: <strong>{{ $matricula->curso->curso }}</strong>
                    <br><br>ÁREA FORMAÇÃO: <strong>{{ $matricula->curso->area_formacao }}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong style="text-align: left;font-size: 9px;"><span style="color: red">REF MAT:</span> {{ $matricula->ficha }}</strong>
                </td>
                <td colspan="2">
                    <strong style="text-align: left;font-size: 9px;">ANO LECTIVO: {{ $matricula->ano_lectivo->ano }}</strong>
                </td>
            </tr>

        </table>

        @if ($escola->categoria == 'Privado')
        <table style="border: 1px solid #000">
            <tr>
                <td style="font-size: 10px;" colspan="4">DADOS PAGAMENTO</td>
            </tr>

            <tr>
                <td style="text-align: left;font-size: 9px;" colspan="4">
                    <br> FACTURA RECIBO: <strong>{{ $pagamento->next_factura ?? "" }}</strong>
                    <br> REF: <strong>{{ $pagamento->ficha ?? "" }}</strong>
                </td>
            </tr>

            <tr>
                <td style="text-align: left;font-size: 9px;" colspan="4">
                    <br> FORMA PAGAMENTO: <strong>{{ $pagamento->forma_pagamento->descricao ?? "" }}</strong>
                    <br> VALOR ENTREGUE: <strong>{{ number_format($pagamento->valor_entregue ?? 0 , 2, ',', '.') }}
                        <br> TROCO: <strong>{{ number_format($pagamento->troco ?? 0 , 2, ',', '.') }}</strong>
                        <br> TOTAL: <strong>{{ number_format($pagamento->valor ?? 0, 2, ',', '.')  }}
                </td>
            </tr>

        </table>
        @endif

        <table>
            <tr>
                <td>Recibo Comprovativo</td>
            </tr>
        </table>

        <table style="border: 1px dashed #000">
            <tr>
                <td style="font-size: 10px;" colspan="4">DADOS PESSOAIS</td>
            </tr>
            <tr>
                <td style="font-size: 10px;" colspan="4">Nome: <strong>{{ $matricula->estudante->nome }} {{ $matricula->estudante->sobre_nome }}</strong></td>
            </tr>
            <tr>
                <td style="font-size: 9px;" colspan="4">
                    <br>GENERO: <strong>{{ $matricula->estudante->genero }}</strong>
                    <br>B.I/CÉDULA: <strong>{{ $matricula->estudante->bilheite }}</strong>
                    <br>ESTADO CIVIL: <strong>{{ $matricula->estudante->estado_civil }}</strong>
                    <br>NASCIMENTO: <strong>{{ $matricula->estudante->nascimento }}</strong>
                </td>
            </tr>

            <tr>
                <td style="font-size: 10px;" colspan="4">DADOS ACADEMICOS</td>
            </tr>
            <tr>
                <td style="text-align: left;font-size: 9px;" colspan="4">
                    CLASSE: <strong>{{ $matricula->classe->classes }}</strong> <br>
                    CLASSE ANTEIRIOR: <strong>{{ $matricula->classe_at->classes }}</strong> <br>
                    TURNO: <strong>{{ $matricula->turno->turno }}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: left;font-size: 9px;">
                    CURSO: <strong>{{ $matricula->curso->curso }}</strong> <br>
                    ÁREA FORMAÇÃO: <strong>{{ $matricula->curso->area_formacao }}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong style="text-align: left;font-size: 9px;"><span style="color: red">REF MAT:</span> {{ $matricula->ficha }}</strong>
                </td>
                <td colspan="2">
                    <strong style="text-align: left;font-size: 9px;">ANO LECTIVO: {{ $matricula->ano_lectivo->ano }}</strong>
                </td>
            </tr>
        </table>

    </main>

    @endif

    @if (Auth::user()->impressora == "Normal")

    <header>
        <table style="border: 1px solid #000">
            <tr>
                <td>
                    <img src="{{ $logotipo }}" alt="" style="text-align: center;height: 70px;width: 70px;">
                </td>

                <td style="text-align: center">
                    <strong>{{ $escola->cabecalho1 }}</strong> <br>
                    @if ($matricula->tipo == "inscricao")
                    <span style="display: block;margin-top: 30px">FICHA DE INSCRIÇÃO</span>
                    @endif

                    @if ($matricula->tipo == "matricula")
                    <span style="display: block;margin-top: 30px">FICHA DE MATRICULA</span>
                    @endif

                    @if ($matricula->tipo == "candidatura")
                    <span style="display: block;margin-top: 30px">FICHA DE CANDIDATURA</span>
                    @endif

                    @if ($matricula->tipo == "confirmacao")
                    <span style="display: block;margin-top: 30px">FICHA DE CONFIRMAÇÃO</span>
                    @endif
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
                    <strong style="display: block">MATRICULA: {{ $matricula->estudante->numero_processo }}</strong> <br>
                    <strong style="display: block">ALUNO(a): {{ $matricula->estudante->nome }} {{ $matricula->estudante->sobre_nome }}</strong> <br>
                    <strong style="display: block">FILHAÇÃO: {{ $matricula->estudante->pai }} e {{ $matricula->estudante->mae }}</strong> <br>
                </td>
                <td></td>
                <td></td>
                <td style="text-align: right">
                    <img src="{{ public_path("assets/images/user.png") }}" alt="" style="text-align: right;height: 70px;width: 70px;">
                </td>
            </tr>

            <tr>
                <td style="font-size: 9px;">GENERO: <strong>{{ $matricula->estudante->genero }}</strong></td>
                <td style="font-size: 9px;">B.I/CÉDULA: <strong>{{ $matricula->estudante->bilheite }}</strong></td>
                <td style="font-size: 9px;">ESTADO CIVIL: <strong>{{ $matricula->estudante->estado_civil }}</strong></td>
                <td style="font-size: 9px;">NASCIMENTO: <strong>{{ $matricula->estudante->nascimento }}</strong></td>
            </tr>

            <tr>
                <td style="font-size: 9px;">TELEFONE: <strong>{{ $matricula->estudante->telefone_estudante ?? '--- --- ---' }}</strong></td>
                <td style="font-size: 9px;">TELEFONE PAI: <strong>{{ $matricula->estudante->telefone_pai ?? '--- --- ---' }}</strong></td>
                <td style="font-size: 9px;">TELEFONE MÃE: <strong>{{ $matricula->estudante->telefone_mae ?? '--- --- ---' }}</strong></td>
                <td style="font-size: 9px;"></td>
            </tr>


            <tr>
                <td style="font-size: 10px;">DADOS ACADEMICOS</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong style="display: block;text-align: left;font-size: 9px;">CLASSE: {{ $matricula->classe->classes }}</strong></td>
                <td><strong style="display: block;text-align: left;font-size: 9px;">CLASSE ANTEIRIOR: {{ $matricula->classe_at->classes }}</strong></td>
                <td colspan="2"><strong style="display: block;text-align: left;font-size: 9px;">TURNO: {{ $matricula->turno->turno }}</strong></td>
            </tr>
            <tr>
                <td colspan="2"><strong style="display: block;text-align: left;font-size: 9px;">CURSO: {{ $matricula->curso->curso }}</strong></td>
                <td colspan="2"><strong style="display: block;text-align: left;font-size: 9px;">ÁREA FORMAÇÃO: {{ $matricula->curso->area_formacao }}</strong> </td>
            </tr>
            <tr>
                <td colspan="2"><strong style="display: block;text-align: left;font-size: 9px;">ANO LECTIVO: {{ $matricula->ano_lectivo->ano }}</strong> </td>
                <td colspan="2"><strong style="display: block;text-align: left;font-size: 9px;"><span style="color: red">REF MAT:</span> {{ $matricula->ficha }}</strong> </td>
            </tr>

        </table>

        @if ($escola->categoria == 'Privado')

        <table style="border: 1px solid #000">
            <tr>
                <td style="font-size: 10px;">DADOS PAGAMENTO</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td style="display: block;text-align: left;font-size: 9px;">FACTURA RECIBO: <strong>{{ $pagamento->next_factura ?? "" }}</strong></td>
                <td style="text-align: left;font-size: 9px;">REF: <strong>{{ $pagamento->ficha ?? "" }}</strong></td>
                <td style="text-align: left;font-size: 9px;"></td>
                <td style="text-align: left;font-size: 9px;"></td>
            </tr>

            <tr>
                <td><strong style="display: block;text-align: left;font-size: 9px;">FORMA PAGAMENTO: {{ $pagamento->forma_pagamento->descricao ?? "" }}</strong></td>
                <td><strong style="display: block;text-align: left;font-size: 9px;">VALOR ENTREGUE: {{ number_format($pagamento->valor_entregue ?? 0 , 2, ',', '.') }}</td>
                <td><strong style="display: block;text-align: left;font-size: 9px;">TROCO: {{ number_format($pagamento->troco ?? 0 , 2, ',', '.') }}</strong></td>
                <td><strong style="display: block;text-align: right;font-size: 9px;">TOTAL: {{ number_format($pagamento->valor ?? 0, 2, ',', '.')  }} </td>
            </tr>


        </table>
        @endif

        <table>
            <tr>
                <td>Recibo Comprovativo</td>
            </tr>
        </table>

        <table style="border: 1px dashed #000">
            <tr>
                <td style="font-size: 10px;">DADOS PESSOAIS</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="font-size: 10px;">Nome: <strong>{{ $matricula->estudante->nome }} {{ $matricula->estudante->sobre_nome }}</strong></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="font-size: 9px;">GENERO: <strong>{{ $matricula->estudante->genero }}</strong></td>
                <td style="font-size: 9px;">B.I/CÉDULA: <strong>{{ $matricula->estudante->bilheite }}</strong></td>
                <td style="font-size: 9px;">ESTADO CIVIL: <strong>{{ $matricula->estudante->estado_civil }}</strong></td>
                <td style="font-size: 9px;">NASCIMENTO: <strong>{{ $matricula->estudante->nascimento }}</strong></td>
            </tr>

            <tr>
                <td style="font-size: 10px;">DADOS ACADEMICOS</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong style="display: block;text-align: left;font-size: 9px;">CLASSE: {{ $matricula->classe->classes }}</strong></td>
                <td><strong style="display: block;text-align: left;font-size: 9px;">CLASSE ANTEIRIOR: {{ $matricula->classe_at->classes }}</strong></td>
                <td colspan="2"><strong style="display: block;text-align: left;font-size: 9px;">TURNO: {{ $matricula->turno->turno }}</strong></td>
            </tr>
            <tr>
                <td colspan="2"><strong style="display: block;text-align: left;font-size: 9px;">CURSO: {{ $matricula->curso->curso }}</strong></td>
                <td colspan="2"><strong style="display: block;text-align: left;font-size: 9px;">ÁREA FORMAÇÃO: {{ $matricula->curso->area_formacao }}</strong> </td>
            </tr>
            <tr>
                <td colspan="2"><strong style="display: block;text-align: left;font-size: 9px;">ANO LECTIVO: {{ $matricula->ano_lectivo->ano }}</strong> </td>
                <td colspan="2"><strong style="display: block;text-align: left;font-size: 9px;"><span style="color: red">REF:</span> {{ $matricula->ficha }}</strong> </td>
            </tr>
        </table>

    </main>
    @endif
</body>
</html>
