@extends('layouts.escolas')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Nova inscrição</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('paineis.painel-informativo-administrativo') }}">Voltar</a></li>
                    <li class="breadcrumb-item active">Inscrições</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Preencha todos os campos para inscrever estudante.</h5>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">
                <form action="{{ route('web.estudantes-inscricao-store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Dados Pessoais</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3 col-12">
                                    <label for="nome_turmas">Nome</label>
                                    <input type="text" name="nome" class="form-control nome" placeholder="Nome" value="{{ old('nome') }}">
                                    @error('nome')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="sobre_nome">Sobrenome</label>
                                    <input type="text" name="sobre_nome" class="form-control sobre_nome"
                                        placeholder="Sobrenome" value="{{ old('sobre_nome') }}">
                                    @error('sobre_nome')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="pai">Pai</label>
                                    <input type="text" name="pai" class="form-control pai" placeholder="Nome Completo do Pai"
                                        value="{{ old('pai') }}">
                                    @error('pai')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="mae">Mãe</label>
                                    <input type="text" name="mae" class="form-control mae" placeholder="Nome Completo da Mãe"
                                        value="{{ old('mae') }}">
                                    @error('mae')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="nascimento">Data Nascimento</label>
                                    <input type="date" name="nascimento" value="{{ old('nascimento') }}" class="form-control nascimento">
                                    @error('nascimento')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="genero">Genero</label>
                                    <select name="genero" id="genero" class="form-control genero">
                                        <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>MASCULINO</option>
                                        <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>FEMENINO</option>
                                    </select>
                                    <span class="text-danger error-text genero_error"></span>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="estado_civil">Estado Cívil</label>
                                    <select name="estado_civil" id="estado_civil" class="form-control estado_civil">
                                        <option value="Solteiro" {{ old('estado_civil') == 'Solteiro' ? 'selected' : '' }}>SOLTEIRO</option>
                                        <option value="Casado" {{ old('estado_civil') == 'Casado' ? 'selected' : '' }}>CASADO</option>
                                    </select>
                                    <span class="text-danger error-text estado_civil_error"></span>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="" class="form-label">País</label>
                                    <select name="pais_id" id="pais_id" class="form-control select2 pais_id"
                                        style="width: 100%">
                                        <option value="">Selecione o País</option>
                                        @foreach ($paises as $item)
                                        <option value="{{ $item->id }}" {{ old('pais_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('pais_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="" class="form-label">Provincia</label>
                                    <select name="provincia_id" id="provincia_id"
                                        class="form-control select2 provincia_id" style="width: 100%">
                                        <option value="">Selecione o País</option>
                                        @foreach ($provincias as $item)
                                        <option value="{{ $item->id }}" {{ old('provincia_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('provincia_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="" class="form-label">Município</label>
                                    <select name="municipio_id" id="municipio_id"
                                        class="form-control select2 municipio_id" style="width: 100%">
                                        <option value="">Selecione o País</option>
                                        @foreach ($municipios as $item)
                                        <option value="{{ $item->id }}" {{ old('municipio_id') == $item->id ? 'selected' : '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('municipio_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-2 col-md-3">
                                    <label for="" class="form-label">Distrito</label>
                                    <select name="distrito_id" id="distrito_id" class="form-control select2 distrito_id"
                                        style="width: 100%">
                                        <option value="">Selecione o Distrito</option>
                                        @foreach ($distritos as $item)
                                        <option value="{{ $item->id }}" {{ old('distrito_id') == $item->id ? 'selected' :
                                            '' }}>{{ $item->nome }}</option>
                                        @endforeach
                                    </select>
                                    @error('distrito_id')
                                    <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                    <span class="text-danger error_distrito_id"></span>
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="bilheite">B.I/CÉDULA</label>
                                    <input type="text" name="bilheite" class="form-control bilheite" value="{{ old('bilheite') }}" placeholder="Nº B.I">
                                    @error('bilheite')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="dificiencia">Deficiência</label>
                                    <select name="dificiencia" id="dificiencia" class="form-control dificiencia">
                                        <option value="Nenhuma" {{ old('dificiencia') == "Nenhuma" ? 'selected' : '' }}>Nenhuma</option>
                                        <option value="Auditiva"  {{ old('dificiencia') == "Auditiva" ? 'selected' : '' }}>Auditiva</option>
                                        <option value="Visual"  {{ old('dificiencia') == "Visual" ? 'selected' : '' }}>Visual</option>
                                        <option value="Motora"  {{ old('dificiencia') == "Motora" ? 'selected' : '' }}>Motora</option>
                                        <option value="Outras"  {{ old('dificiencia') == "Outras" ? 'selected' : '' }}>Outras</option>
                                    </select>
                                    <span class="text-danger error-text dificiencia_error"></span>

                                </div>


                                <div class="form-group col-md-3 col-12">
                                    <label for="telefone">Telefone estudante</label>
                                    <input type="text" name="telefone" class="form-control telefone"
                                        placeholder="Terminal do Estudante" value="{{ old('telefone') }}">
                                    @error('telefone')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3 col-12">
                                    <label for="telefone_pai">Telefone do Pai</label>
                                    <input type="text" name="telefone_pai" class="form-control telefone_pai"
                                        placeholder="Terminal do Pai" value="{{ old('telefone_pai') }}">
                                    @error('telefone_pai')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3 col-12">
                                    <label for="telefone_mae">Telefone da Mãe</label>
                                    <input type="text" name="telefone_mae" class="form-control telefone_mae"
                                        placeholder="Terminal do Mãe" value="{{ old('telefone_mae') }}">
                                    @error('telefone_mae')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label for="endereco" class="form-label">Endereço da Morada</label>
                                    <textarea class="form-control endereco" placeholder="descrever endereço"
                                        id="endereco" rows="3">{{ old('endereco') }}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Dados Academicos</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3 col-12">
                                    <label for="at_classes_id">Classe Anterior</label>
                                    <select name="at_classes_id" id="at_classes_id"
                                        class="form-control at_classes_id select2">
                                        {{-- <option value="">Selecione Genero</option> --}}
                                        @if ($classes)
                                        @foreach ($classes as $classe)
                                        <option value="{{ $classe->classe->id }}" {{ old('at_classes_id') == $classe->classe->id ? 'selected' : '' }}>{{ $classe->classe->classes }}
                                        </option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>
                                    @error('at_classes_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="classes_id">Classe</label>
                                    <select name="classes_id" id="classes_id" class="form-control classes_id select2">
                                        <option value="">Selecione a Classe</option>
                                        @if ($classes)
                                        @foreach ($classes as $classe)
                                        <option value="{{ $classe->classe->id }}" {{ old('classes_id') == $classe->classe->id ? 'selected' : '' }}>{{ $classe->classe->classes }}
                                        </option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>
                                    @error('classes_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="cursos_id">Curso</label>
                                    <select name="cursos_id" id="cursos_id" class="form-control cursos_id select2">
                                        <option value="">Selecione o Curso</option>
                                        @if ($cursos)
                                        @foreach ($cursos as $curso)
                                        <option value="{{ $curso->curso->id }}" {{ old('cursos_id') == $curso->curso->id ? 'selected' : '' }}>{{ $curso->curso->curso }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>
                                    @error('cursos_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 col-12">
                                    <label for="turnos_id">Turno</label>
                                    <select name="turnos_id" id="turnos_id" class="form-control turnos_id select2">
                                        <option value="">Selecione o Turno</option>
                                        @if ($turnos)
                                        @foreach ($turnos as $turno)
                                        <option value="{{ $turno->turno->id }}" {{ old('turnos_id') == $turno->turno->id ? 'selected' : '' }}>{{ $turno->turno->turno }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Turno cadastrado</option>
                                        @endif
                                    </select>
                                    @error('turnos_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                
                                
                                <div class="form-group col-md-6 col-12">
                                    <label for="cursos_primeira_opcao_id">Curso 2º Opção</label>
                                    <select name="cursos_primeira_opcao_id" id="cursos_primeira_opcao_id" class="form-control cursos_primeira_opcao_id select2">
                                        @if ($cursos)
                                        @foreach ($cursos as $curso)
                                        <option value="{{ $curso->curso->id }}" {{ old('cursos_primeira_opcao_id') == $curso->curso->id ? 'selected' : '' }}>{{ $curso->curso->curso }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>
                                    @error('cursos_primeira_opcao_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                
                                <div class="form-group col-md-6 col-12">
                                    <label for="cursos_segunda_opcao_id">Curso 3º Opção</label>
                                    <select name="cursos_segunda_opcao_id" id="cursos_segunda_opcao_id" class="form-control cursos_segunda_opcao_id select2">
                                        @if ($cursos)
                                        @foreach ($cursos as $curso)
                                        <option value="{{ $curso->curso->id }}" {{ old('cursos_segunda_opcao_id') == $curso->curso->id ? 'selected' : '' }}>{{ $curso->curso->curso }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Curso cadastrado</option>
                                        @endif
                                    </select>
                                    @error('cursos_segunda_opcao_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group {{ $escola->processo_admissao_estudante == 'Prova' ? 'col-md-6': 'col-md-3' }} col-12" >
                                    <label for="tipo_matricula">Situação</label>
                                    <select name="tipo_matricula" id="tipo_matricula"
                                        class="form-control tipo_matricula select2">
                                        {{-- <option value="">Selecione</option> --}}
                                        <option value="inscricao" {{ old('tipo_matricula') == "inscricao" ? 'selected' : '' }}>Inscrição</option>
                                    </select>
                                    <span class="text-danger error-text tipo_error"></span>
                                </div>
                                
                                @if ($escola->processo_admissao_estudante == 'Normal')
                                <div class="form-group col-md-3 col-12">
                                    <label for="media">Média</label>
                                    <input type="number" name="media" value="{{ old('media') ?? 10 }}" max="20" min="0" maxlength="20" minlength="0" class="form-control bilheite" placeholder="Introduz a média do estudante">
                                    @error('media')
                                    <span class="text-danger error-text media_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                @endif

                                
                                <div class="form-group col-md-6 col-12">
                                    <label for="ano_lectivos_id">Ano Lectivo</label>
                                    <select name="ano_lectivos_id" id="ano_lectivos_id"
                                        class="form-control ano_lectivos_id select2">
                                        @if ($anolectivos)
                                        @foreach ($anolectivos as $anolectivo)
                                        <option value="{{ $anolectivo->id }}" {{ old('ano_lectivos_id') == $anolectivo->id ? 'selected' : '' }}>{{ $anolectivo->ano }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Sem Nenhum Ano Lectivo cadastrado</option>
                                        @endif
                                    </select>
                                    @error('ano_lectivos_id')
                                    <span class="text-danger error-text nome_error">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                            </div>
                        </div>

                    </div>

                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Rede Sociais</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group mb-2 col-md-3 col-12">
                                    <label for="whatsapp" class="form-label">Whatsapp</label>
                                    <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}"
                                        class="form-control whatsapp" placeholder="Whatsapp do Estudante">
                                    @error('whatsapp')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-2 col-md-3 col-12">
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="text" name="instagram" id="instagram" value="{{ old('instagram') }}"
                                        class="form-control instagram" placeholder="Instagram do Estudante">
                                    @error('instagram')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-2 col-md-3 col-12">
                                    <label for="facebook" class="form-label">facebook</label>
                                    <input type="text" name="facebook" id="facebook" value="{{ old('facebook') }}"
                                        class="form-control facebook" placeholder="facebook do Estudante">
                                    @error('facebook')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-2 col-md-3 col-12">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="text" name="email" id="email" value="{{ old('email') }}"
                                        class="form-control email" placeholder="E-mail do Estudante">
                                    @error('email')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light">
                            <p>Documentos</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3 mb-3 col-12">
                                    <label for="doc_bilheite">Bilhete de Identidade</label>
                                    <input type="file" name="doc_bilheite" accept=".pdf" value="{{ old('doc_bilheite') }}" id="doc_bilheite" class="form-control doc_bilheite">
                                    @error('doc_bilheite')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 mb-3 col-12">
                                    <label for="doc_certificado">Certificado</label>
                                    <input type="file" name="doc_certificado" value="{{ old('doc_certificado') }}" accept=".pdf" id="doc_certificado"
                                        class="form-control doc_certificado">
                                    @error('doc_certificado')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 mb-3 col-12">
                                    <label for="doc_atestedao_medico">Atestado Médico</label>
                                    <input type="file" name="doc_atestedao_medico" value="{{ old('doc_atestedao_medico') }}" accept=".pdf"
                                        id="doc_atestedao_medico" class="form-control doc_atestedao_medico">
                                    @error('doc_atestedao_medico')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3 mb-3 col-12">
                                    <label for="doc_outros">Outros Documentos</label>
                                    <input type="file" name="doc_outros" value="{{ old('doc_outros') }}" accept=".pdf" class="form-control doc_outros"
                                        id="doc_outros">
                                    @error('doc_outros')
                                    <span class="text-danger error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit"
                                class="btn btn-primary concluir_cadastro_estudante">Concluir</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection


@section('scripts')

<script>

    var turmasServico;

    $("#classes_id").change(()=>{
        let id = $("#classes_id").val();
        let id_cursos = $("#cursos_id").val();
        $.get('carregar-servicos-turmas?classes_id='+id+ '&cursos_id='+id_cursos, function(data){
            $('#servicos_id').html("");
            $('#servicos_id').append('<option value="">Selecione um Serviço</option>');
            for (let index = 0; index < data.servicos.length; index++) {
              $('#servicos_id').append('<option value="'+ data.servicos[index].id +'">'+ data.servicos[index].servico +'</option>');
            }
            turmasServico = data.turma.id;
        })
    })
    
    $("#cursos_id").change(()=>{
        let id = $("#cursos_id").val();
        let id_classes = $("#classes_id").val();
        $.get('carregar-servicos-turmas?classes_id='+id_classes+ '&cursos_id='+id, function(data){
            $('#servicos_id').html("");
            $('#servicos_id').append('<option value="">Selecione um Serviço</option>');
            for (let index = 0; index < data.servicos.length; index++) {
              $('#servicos_id').append('<option value="'+ data.servicos[index].id +'">'+ data.servicos[index].servico +'</option>');
            }
            turmasServico = data.turma.id;
        })
    })

    // Eventos
    $("#provincia_id").change(function () {
      carregarDados({
        origem: "#provincia_id",
        destino: "#municipio_id",
        rota: rotas.carregarMunicipios,
        mensagemSucesso: "Municípios carregados"
      });
    });
    
    $("#municipio_id").change(function () {
      carregarDados({
        origem: "#municipio_id",
        destino: "#distrito_id",
        rota: rotas.carregarDistritos,
        mensagemSucesso: "Distritos carregados"
      });
    });

</script>

@endsection