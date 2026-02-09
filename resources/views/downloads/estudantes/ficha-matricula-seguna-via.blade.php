<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Ficha Técnica do Estudante</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 2cm 2cm 2.5cm 2cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .container {
            width: 100%;
            max-width: 18cm;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        .header img {
            height: 60px;
            margin-bottom: 5px;
        }

        .titulo {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .info-table td {
            padding: 6px;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        .info-table .label {
            font-weight: bold;
            width: 30%;
            background-color: #f9f9f9;
        }

        .foto {
            width: 4cm;
            height: 5cm;
            border: 1px solid #000;
            object-fit: cover;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 11px;
            color: #777;
        }

    </style>
</head>
<body>

    <div class="container">

        <div class="header">
            <img src="{{ $logotipo }}" alt="Logotipo"><br>
            <div><strong>{{ $escola->nome }}</strong></div>
            <div>{{ $escola->endereco }} | NIF: {{ $escola->documento }}</div>
            <div class="titulo">Ficha Técnica do Estudante</div>
        </div>

        <!-- Foto + Dados principais -->
        <table style="width: 100%; margin-top: 20px;">
            <tr>
                <td style="width: 80%;">
                    <table class="info-table">
                        <tr>
                            <td colspan="2" class="label">Dados Pessoais</td>
                        </tr>
                        <tr>
                            <td class="label">Nome Completo:</td>
                            <td>{{ $matricula->estudante->nome }} {{ $matricula->estudante->sobre_nome }}</td>
                        </tr>
                        <tr>
                            <td class="label">Número de Processo:</td>
                            <td>{{ $matricula->estudante->numero_processo }}</td>
                        </tr>
                        <tr>
                            <td class="label">Género | E. Cívil</td>
                            <td>{{ $matricula->estudante->genero }} | {{ $matricula->estudante->estado_civil }}</td>
                        </tr>
                        <tr>
                            <td class="label">D. Nascimento:</td>
                            <td>{{ \Carbon\Carbon::parse($matricula->estudante->nascimento)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Nacionalidade:</td>
                            <td>{{ $matricula->estudante->nacionalidade }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 20%;" align="center">
                    <img src="{{ public_path('assets/images/user.png') }}" class="foto" alt="Foto do estudante" style="text-align: center;height: 160px;width: 150px;">
                </td>
            </tr>
        </table>

        <!-- dados academicos -->
        <table class="info-table" style="margin-top: 20px;">
            <tr>
                <td colspan="2" class="label">Dados Academicos</td>
            </tr>
            <tr>
                <td class="label">Ano Lectivo:</td>
                <td>{{ $matricula->ano_lectivo->ano ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Curso:</td>
                <td>{{ $matricula->curso->curso ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Turno:</td>
                <td>{{ $matricula->turno->turno ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Classe:</td>
                <td>{{ $matricula->classe->classes ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Área de Formação:</td>
                <td>{{ $matricula->curso->area_formacao ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Referência:</td>
                <td>{{ $matricula->documento ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Data de Matrícula:</td>
                <td>{{ \Carbon\Carbon::parse($matricula->created_at)->format('d/m/Y') }}</td>
            </tr>
        </table>

        <!-- Contato e endereço -->
        <table class="info-table" style="margin-top: 20px;">
            <tr>
                <td colspan="2" class="label">Contacto e Endereço</td>
            </tr>
            <tr>
                <td class="label">Telefone:</td>
                <td>{{ $matricula->estudante->telefone_estudante ?? '---' }}</td>
            </tr>
            <tr>
                <td class="label">Email:</td>
                <td>{{ $matricula->estudante->email ?? '---' }}</td>
            </tr>
            <tr>
                <td class="label">Endereço:</td>
                <td>{{ $matricula->estudante->endereco ?? '---' }}</td>
            </tr>
        </table>

        <!-- Rodapé -->
        <div class="footer">
            Documento gerado automaticamente em {{ now()->format('d/m/Y H:i') }}<br>
            Angoengenharia e Sistemas Informáticos © {{ now()->year }}
        </div>
    </div>

</body>
</html>
