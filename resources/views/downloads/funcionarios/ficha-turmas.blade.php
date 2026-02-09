<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Horários | </title>

    <style type="text/css">
		*{
			margin: 0;
			padding: 0;
			box-sizing: border-box;
            text-align: left;
		}

        body{
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
        }

		h1{
			font-size: 10pt;
			margin-bottom: 10px;
		}
        h2{
            font-size: 9pt;
        }

		.titulo{
			font-size: 9pt;
			text-align: center;
            margin-top: 0;
		}

		p{
			/* margin-bottom: 20px; */
			line-height: 30px;
            font-size: 9pt;
            text-align: justify;
		}
        strong{
            font-size: 9pt;
        }

		table{
			width: 100%;
			text-align: left;
			border-spacing: 0;	
			margin-bottom: 10px;
			border: 1px solid rgb(0, 0, 0);
            font-size: 9pt;
		}
		thead{
			background-color: #fdfdfd;
			border-bottom: 1px solid #006699;
            font-size: 10pt;

		}
		th, td{
			padding: 12px;
			border: 1px solid rgb(0, 0, 0);
            font-size: 9pt;
		}

		.border{
			border: 1px solid #fdfdfd;
		}



		.logo{
			height: 70px;
			width: 70px;
			/*border-radius: 300px;*/
			/* padding: 30px;  */
            border: 1px solid #000;
		}
		.ml{
			margin-left: 80px;
		}
		.text-center{
			text-align: left;
		}

        /* ----------------------------------------- */
        .header{
            /* position: fixed;
            top: 0;
            padding: 40px 20px; */
            border-bottom: 1px solid rgba(0, 0, 0, .1);
            width: 100%;
            float: left;
        }

        .div01{
            width: 50%;
            float: left;
        }

        .section{
            /* margin-top: 90px;
            padding: 20px;  */
        }
	</style>
</head>
<body>

	<div style="background-color: rgb(255, 255, 255);color: #000000;padding: 5px;font-family: Arial, Helvetica, sans-serif;">
		<img src="{{ $logotipo }}" alt="" style="text-align: center;height: 100px;width: 100px;float: right;">
        <h1>{{ $escola->nome }}</h1>
		<p>NIF: {{ $escola->documento }}</p> 
		<p>Angola - {{ $escola->provincia->nome ?? ""}}</p> 
        <h1>Disciplinas e Horários dos Professores</h1>
	</div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th colspan="5" 
                    style="background-color: rgb(223, 223, 223);
                    color: #575757;font-size: 10pt;"
                    >Dados Pessoais do Funcionário</th>
                </tr>
                <tr>
                    <th>Nome</th>
                    <th>Nascimento</th>
                    <th>Genero</th>
                    <th>Bilhete</th>
                    <th>Telefone</th>
                </tr>   
                <tr>
                    <th>{{ $funcionario->nome }}  {{ $funcionario->sobre_nome }}</th>
                    <th>{{ $funcionario->nascimento }}</th>
                    <th>{{ $funcionario->genero }}</th>
                    <th>{{ $funcionario->bilheite }}</th>
                    <th>{{ $funcionario->telefone }}</th>
                </tr> 
            </thead>
        </table>
    
        @if ($turmas)
            <table>
                @foreach ($turmas as $item)
                    <thead>
                        <tr>
                            <th colspan="6" style="background-color: rgb(220, 220, 220); color: #545454;font-size: 10pt;">Horário na Turma {{ $item->turma->turma }}</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Disciplina</th>
                            <th>Dia de Semana</th>
                            <th>Tempos</th>
                            <th>Hora Entrada</th>
                            <th>Hora Saída</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $horarios = (new App\Models\web\turmas\Horario)::where('professor_id', $item->professor->id)
                              ->where('turmas_id', $item->turma->id)
                              ->with(['disciplina'])
                              ->get();
                        @endphp

                        @if ($horarios)
                            @foreach ($horarios as $key => $items)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $items->disciplina->disciplina }}</td>
                                    <td>{{ $items->semana->nome }}</td>
                                    <td>{{ $items->tempo->nome }} º</td>
                                    <td>{{ $items->hora_inicio }}</td>
                                    <td>{{ $items->hora_final }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>     
                    <tfoot>
                        <tr>
                            <th colspan="6">TOTAL DE TEMPOS: {{ count($horarios) }}</th>
                        </tr>
                    </tfoot>
                @endforeach
            </table>                    
        @endif

    </div>


</body>
</html>