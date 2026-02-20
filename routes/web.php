<?php

use App\Http\Controllers\{
    ActivadorController,
    AuthController,
    NotificacaoController,
    TurmaController,
    AdminController,
    AnoLectivoClasseController,
    AnoLectivoTurnoController,
    AnoLectivoCursoController,
    EstatisticaTurmaController,
    WebController,
    WebDownloadController,
    WebGraficoController,
    AnoLectivoController,
    AnoLectivoConfiguracaoController,
    AnoLectivoGlobalController,
    AprovarCandidaturaController,
    BancoController,
    BibliotecaController,
    BiometricoController,
    BolsaController,
    DescontoController,
    TurnoController,
    ClasseController,
    SalaController,
    CursoController,
    DisciplinaController,
    DistritoController,
    ServicoController,
    HomeController,
    CaixaController,
    CargoController,
    CargoMinisterioController,
    CargoProvincialController,
    DepartamentoMunicipalController,
    CargoMunicipalController,
    FuncionarioMunicipalController,
    ContratoFuncioarioController,
    DepartamentoController,
    DepartamentoMinisterioController,
    DepartamentoProvincialController,
    DireccaoMunicipalController,
    DireccaoProvinciaController,
    MovimentoCaixaController,
    FacturaController,
    EstudanteController,
    EncarregadoController,
    EscolaController,
    EstudanteConfirmacaoController,
    EstudanteMatriculaController,
    EstudantePautasController,
    ExtensaoController,
    FinanceiroController,
    FuncionarioController,
    GraficoController,
    PrinterController,
    PrinterExcelController,
    SegurancaController,
    DocumentoController,
    EnsinoController,
    UniversidadeController,
    EspecialidadeController,
    CategoriaController,
    ComunicadoController,
    ControloLancamentoNotasController,
    ControloLancamentoNotasMunicipalController,
    EnsinoClasseController,
    EscolaridadeController,
    FaculdadeController,
    CandidaturaAcademicaController,
    CartaoEstudanteController,
    DistritoPortalProvinciaController,
    EscolaFilharController,
    ExameAcessoController,
    FormacaoAcademicoController,
    FormularioController,
    FormularioProvincialController,
    FornecedorController,
    FuncionarioMinisterioController,
    FuncionarioProvincialController,
    GeneroLivroController,
    TipoMaterialController,
    EditoraController,
    AutoresController,
    BackupController,
    CartaoTemplateController,
    DevolucaoEmprestimoLivroController,
    LivrosController,
    EmprestimoLivroController,
    EstudanteDescontoController,
    ProfessorProvincialController,
    GerarSaftController,
    HelperController,
    InstituicaoController,
    BolseiroController,
    ContaPagarController,
    ContaReceberController,
    GestaoDividaController,
    InstituicaoEstagioController,
    IsencaoServicoController,
    LaboratorioController,
    LogisticaController,
    MercadoriaController,
    MovimentoBancoController,
    MunicipioController,
    MunicipioPortalProvinciaController,
    NotificacaoAdminController,
    outraRotasController,
    PainelController,
    PermissionController,
    PermissionEscolaController,
    PortalEstudanteController,
    PortalMunicipioController,
    PortalProvinciaController,
    ProfessorController,
    ProfessorMateriasController,
    ProvinciaController,
    QRCodeController,
    RelatorioController,
    RoleController,
    RoleEscolaController,
    ValidacaoRupeController,
    SincronizarConfiguracao,
    SiteController,
    SolicitacaoDocumentoController,
    TempoLecionadoController,
    TesourariaController,
    TestController,
    TipoEstagioController,
    TipoMercadoriasController,
    TransferenciaEscolaProfessorController,
    TransferenciaEscolarController,
    UtilizadoresController,
};
use App\Models\web\calendarios\Matricula;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;


// dd(Hash::make("123456789"));

// AUTH CONTROLLER
#######################################################################################################################
#######################################################################################################################
Route::get('/entrar', [AuthController::class, 'login'])->name('login');
Route::post('/entrar_sistema', [AuthController::class, 'login_sistem'])->name('login_sistem');
Route::get('/sair', [AuthController::class, 'logout'])->name('logout');

Route::get('/portal-ministerio', [AuthController::class, 'loginAdmin'])->name('login-admin');
Route::post('/portal-ministerio', [AuthController::class, 'loginSistemSuperAdmin'])->name('login-sistem-super-admin');
Route::get('/sair-administrator', [AuthController::class, 'logoutAdministrator'])->name('web.logout-administrator');

Route::get('/portal-provincial', [AuthController::class, 'loginProvincial'])->name('login-provincial');
Route::post('/portal-provincial', [AuthController::class, 'loginProvincialPost'])->name('login-provincial-post');
Route::get('/sair-provincial', [AuthController::class, 'logoutProvincial'])->name('web.logout-provincial');

Route::get('/portal-municipal', [AuthController::class, 'loginMunicipal'])->name('login-municipal');
Route::post('/portal-municipal', [AuthController::class, 'loginMunicipalPost'])->name('login-municipal-post');
Route::get('/sair-municipal', [AuthController::class, 'logoutMunicipal'])->name('web.logout-municipal');

Route::get('/portal-estudante', [AuthController::class, 'portaEstudante'])->name('app.login-estudante');
Route::post('/portal-estudante', [AuthController::class, 'loginPortaEstudante'])->name('app.login-portal-estudante');
Route::get('/sair-estudante', [AuthController::class, 'logoutEstudante'])->name('web.logout-estudante');

Route::get('/portal-professor', [AuthController::class, 'portaProfessor'])->name('portal-professor');
Route::post('/portal-professor', [AuthController::class, 'loginportaProfessor'])->name('login-portal-professor');
Route::get('/sair-professor', [AuthController::class, 'logoutProfessor'])->name('web.logout-professor');

Route::get('/sair-admin', [AuthController::class, 'logoutAdmin'])->name('logout-admin');
Route::get('/criar-conta/{modulo?}', [AuthController::class, 'criarConta'])->name('criar-conta');
Route::post('/criar-conta', [AuthController::class, 'criarContaStore'])->name('criar-conta-store');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
Route::get("/aguardando-confirmacao-da-conta", [AuthController::class, "aguardando_confirmacao_email"])->name("aguardando_confirmacao_email");

#######################################################################################################################
#######################################################################################################################


Route::group(["middleware" => "auth"], function () {

    // portal universal estudantes logados
    Route::get('/portal-estudantes/perfil', [PortalEstudanteController::class, 'home'])->name('est.home-estudante');
    Route::get('/portal-estudantes/privacidade', [PortalEstudanteController::class, 'privacidade'])->name('est.privacidade');
    Route::put('/portal-estudantes/privacidade-update/{id}', [PortalEstudanteController::class, 'privacidadeUpdate'])->name('est.privacidade-update');
    Route::get('/portal-estudantes/pautas', [PortalEstudanteController::class, 'pautaEstudantes'])->name('est.pauta-estudante');
    Route::get('/portal-estudantes/mapa-aproveitamento-estudante', [PortalEstudanteController::class, 'mapaAproveitamentoGeralEstudante'])->name('est.mapa-aproveitamento');
    Route::get('/portal-estudantes/pagamentos', [PortalEstudanteController::class, 'pagamentos'])->name('est.estudante-pagamentos');
    Route::get('/portal-estudantes/historicos', [PortalEstudanteController::class, 'historicos'])->name('est.historicos');
    Route::get('/portal-estudantes/horarios', [PortalEstudanteController::class, 'horarios'])->name('est.horarios');
    Route::get('/portal-estudantes/solicitar-declaracao', [PortalEstudanteController::class, 'solicitacaoDeclaracao'])->name('est.solicitacoes-declaracao');
    Route::post('/portal-estudantes/solicitar-declaracao', [PortalEstudanteController::class, 'solicitacaoDeclaracaoStore'])->name('est.solicitacoes-declaracao-store');
    Route::get('/portal-estudantes/solicitar-vegas', [PortalEstudanteController::class, 'solicitacaoVagas'])->name('est.solicitacoes-vagas');
    Route::get('/portal-estudantes/solicitar-transferencia', [PortalEstudanteController::class, 'solicitacaoTransferencia'])->name('est.solicitacoes-transferencia');
    Route::post('/portal-estudantes/solicitar-transferencia', [PortalEstudanteController::class, 'storeTransferencia'])->name('est.solicitacoes-transferencia-store');
    Route::get('/portal-estudantes/eliminar-transferencia/{id}', [PortalEstudanteController::class, 'eliminar'])->name('est.eliminar-transferencia');

    Route::get('/portal-estudantes/meus-depositos', [PortalEstudanteController::class, 'meusDepositos'])->name('est.meus-depositos-estudante');
    Route::get('/portal-estudantes/meus-pagamentos', [PortalEstudanteController::class, 'meusPagamento'])->name('est.meus-pagamento-estudante');
    Route::get('/portal-estudantes/meus-pagamentos-detalhe/{id}', [PortalEstudanteController::class, 'meusPagamentoDetalhe'])->name('est.meus-pagamento-estudante-detalhe');
    Route::get('/portal-estudantes/efectuar-pagamentos', [PortalEstudanteController::class, 'efectuarPagamentos'])->name('est.efectuar-pagamento-estudante');
    Route::post('/portal-estudantes/efectuar-pagamentos-store', [PortalEstudanteController::class, 'efectuarPagamentosStore'])->name('est.efectuar-pagamento-estudante-store');
    Route::get('/portal-estudantes/minhas-materias', [PortalEstudanteController::class, 'minhaMateria'])->name('est.minhas-materias-estudante');
    Route::get('/portal-estudantes/minhas-materias/{id}/apresentar', [PortalEstudanteController::class, 'minhaMateriaApresentar'])->name('est.minhas-materias-estudante-apresentar');
    Route::get('/portal-estudantes/meus-comunicados', [PortalEstudanteController::class, 'meusComunicados'])->name('est.meus-comunicados');
    Route::get('/portal-estudantes/{id}/detalhe-comunicado', [PortalEstudanteController::class, 'detalheComunicado'])->name('est.detalhe-comunicado');
    Route::post('/portal-estudantes/editar-foto', [PortalEstudanteController::class, 'estudantesFotoPerfil'])->name('est.editar-foto-perfil');

    /**
     *  Professores logados universal
     */
    Route::get('/portal-professor/perfil', [ProfessorController::class, 'home'])->name('prof.home-profs');
    Route::get('/portal-professor/informacao-turmas/{id}/{escola?}', [ProfessorController::class, 'informacaoTurmaProfessores'])->name('prof.informacao-turma-professores');
    Route::get('/portal-professor/imprimir-lancamento-nota', [ProfessorController::class, 'imprimirProfessoresLancamentoNota'])->name('prof.imprimir-professores-lancamento-nota');
    Route::get('/portal-professor/informacao-lancamento-nota', [ProfessorController::class, 'informacaoProfessoresLancamentoNota'])->name('prof.informacao-professores-lancamento-nota');
    Route::get('/portal-professor/lancamento-nota-estudantes', [ProfessorController::class, 'professoresLancamentoNotaEstudante'])->name('prof.professores-lancamento-nota-estudante');
    Route::post('/portal-professor/lancamento-nota-estudantes', [ProfessorController::class, 'professoresLancamentoNotaEstudanteStore'])->name('prof.professores-lancamento-nota-estudante-store');
    Route::get('/portal-professor/privacidade', [ProfessorController::class, 'privacidade'])->name('prof.privacidade');
    Route::put('/portal-professor/privacidade-update/{id}', [ProfessorController::class, 'privacidadeUpdate'])->name('prof.privacidade-update');
    Route::get('/portal-professor/escolas', [ProfessorController::class, 'escolas'])->name('prof.escolas');
    Route::get('/portal-professor/turmas', [ProfessorController::class, 'turmas'])->name('prof.turmas');
    Route::get('/portal-professor/horarios', [ProfessorController::class, 'horarios'])->name('prof.horarios');
    Route::get('/portal-professor/turmas-informacoes/{id}', [ProfessorController::class, 'turmasInformacoes'])->name('prof.turmas-informacoes');
    Route::get('/portal-professor/estudantes', [ProfessorController::class, 'estudantes'])->name('prof.estudantes');
    Route::get('/portal-professor/estudantes-informacoes/{id}', [ProfessorController::class, 'estudantesInformacoes'])->name('prof.estudantes-informacoes');
    Route::post('/portal-professor/editar-foto', [ProfessorController::class, 'FotoPerfil'])->name('web.professor-foto-perfil');
    Route::get('/portal-professor/minhas-solicitacoes', [ProfessorController::class, 'minhaSolicitacoes'])->name('prof.minhas-solicitacoes');
    Route::get('/portal-professor/meus-comunicados', [ProfessorController::class, 'meusComunicados'])->name('prof.meus-comunicados');
    Route::get('/portal-professor/{id}/detalhe-comunicados', [ProfessorController::class, 'detalheComunicados'])->name('prof.detalhe-comunicados');
    Route::get('/portal-professor/detalhe-minhas-solicitacoes/{id}', [ProfessorController::class, 'detalheMinhaSolicitacoes'])->name('prof.detalhes-minhas-solicitacoes');
    Route::get('/portal-professor/baixar-solicitacoes/{id}', [ProfessorController::class, 'baixarSolicitacoes'])->name('prof.baixar-solicitacoes');

    Route::resource('portal-professor-minhas-materias', ProfessorMateriasController::class);
    Route::get('/portal-professor/solicitar-processo', [ProfessorController::class, 'solicitacaoProcesso'])->name('prof.solicitacoes-processo');
    Route::post('/portal-professor/solicitar-processo', [ProfessorController::class, 'solicitacaoProcessoStore'])->name('prof.solicitacoes-processo-store');

    /** CONTROLLERES PORTAL MUNICIPAL */

    Route::get('/home-municipal-admin', [PortalMunicipioController::class, 'home'])->name('home-municipal');
    Route::get('/municipal/privacidade', [PortalMunicipioController::class, 'privacidade'])->name('app.privacidade-municipal');
    Route::put('/municipal/privacidade-update/{id}', [PortalMunicipioController::class, 'privacidadeUpdate'])->name('app.privacidade-municipal-update');

    Route::get('/municipal/utilizadores', [PortalMunicipioController::class, 'utilizadoresIndex'])->name('app.municipal-utilizadores-index');
    Route::get('/municipal/utilizadores/criar', [PortalMunicipioController::class, 'utilizadoresCreate'])->name('app.municipal-utilizadores-create');
    Route::post('/municipal/utilizadore/criar', [PortalMunicipioController::class, 'utilizadoresStore'])->name('app.municipal-utilizadores-store');
    Route::get('/municipal/utilizadores/{id}/editar', [PortalMunicipioController::class, 'utilizadoresEdit'])->name('app.municipal-utilizadores-edit');
    Route::put('/municipal/utilizadore/{id}/editar', [PortalMunicipioController::class, 'utilizadoresUpdate'])->name('app.municipal-utilizadores-update');


    Route::get('/criar-escolas-municipal', [PortalMunicipioController::class, 'criarEscolas'])->name('criar-escola-municipal');
    Route::post('/criar-escolas-municipal', [PortalMunicipioController::class, 'criarEscolasStore'])->name('criar-escola-municipal-store');
    Route::get('/editar-escolas-municipal/{id}', [PortalMunicipioController::class, 'editarEscolas'])->name('web.editar-escola-municipal');
    Route::put('/criar-escolas-municipal/{id}', [PortalMunicipioController::class, 'editEscolasUpdate'])->name('web.edit-escola-municipal-update');

    Route::get('/mudar-status-escolas-municipal/{id}', [PortalMunicipioController::class, 'mundarStatusEscolas'])->name('web.mudar-status-escola-municipal');

    Route::get('/listagem-escolas-municipal/{id?}', [PortalMunicipioController::class, 'listagemEscolas'])->name('listagem-escola-municipal');
    Route::get('/informacao-escola-municipal/{id}', [PortalMunicipioController::class, 'informacaoEscolar'])->name('web.informacao-escola-municipal');
    Route::get('/activa-licenca-escola-municipal/{id}', [PortalMunicipioController::class, 'activarLicencaEscola'])->name('web.activar-licenca-escola-municipal');
    Route::post('/activa-licenca-escola-municipal-post', [PortalMunicipioController::class, 'activarLicencaEscolaPost'])->name('web.activar-licenca-escola-municipal-post');

    Route::get('/listagem-estudantes-municipal/{escola}', [PortalMunicipioController::class, 'listagemEstudantes'])->name('app.listagem-estudantes-municipal');
    Route::get('/informacao-estudante-municipal/{id}', [PortalMunicipioController::class, 'informacaoEstudante'])->name('app.informacao-estudante-municipal');
    Route::get('/listagem-professores-municipal/{escola}', [PortalMunicipioController::class, 'listagemProfessores'])->name('app.listagem-professores-municipal');
    Route::get('/informacao-professores-municipal/{id}', [PortalMunicipioController::class, 'informacaoProfessores'])->name('app.informacao-professores-municipal');
    Route::get('/informacao-turmas-professores-municipal/{id}/{escola?}', [PortalMunicipioController::class, 'informacaoTurmaProfessores'])->name('app.informacao-turma-professores-municipal');

    Route::get('/listagem-estudantes-municipal', [PortalMunicipioController::class, 'listagemEstudantesGeral'])->name('app.listagem-estudantes-municipal-geral');

    // WEB DEPARTAMENTOS PROVINCIAL
    Route::get('/departamento-municipal/home', [DepartamentoMunicipalController::class, 'index'])->name('web.departamento-municipal');
    Route::get('/departamento-municipal/cadastrar', [DepartamentoMunicipalController::class, 'create'])->name('web.create-departamento-municipal');
    Route::post('/departamento-municipal/store', [DepartamentoMunicipalController::class, 'store'])->name('web.store-departamento-municipal');
    Route::get('/departamento-municipal/editar/{id}', [DepartamentoMunicipalController::class, 'edit'])->name('web.edit-departamento-municipal');
    Route::put('/departamento-municipal/editar/{id}', [DepartamentoMunicipalController::class, 'update'])->name('web.update-departamento-municipal');
    Route::get('/departamento-municipal/delete/{id}', [DepartamentoMunicipalController::class, 'delete'])->name('web.delete-departamento-municipal');
    Route::get('/departamento-municipal/imprimir', [DepartamentoMunicipalController::class, 'Imprimir'])->name('web.departamento-municipal-pdf');
    Route::get('/departamento-municipal/excel', [DepartamentoMunicipalController::class, 'excel'])->name('web.departamento-municipal-excel');

    // WEB CARGOS municipal
    Route::get('/cargos-municipal/home', [CargoMunicipalController::class, 'index'])->name('web.cargos-municipal');
    Route::get('/cargos-municipal/cadastrar', [CargoMunicipalController::class, 'create'])->name('web.create-cargos-municipal');
    Route::post('/cargos-municipal/store', [CargoMunicipalController::class, 'store'])->name('web.store-cargos-municipal');
    Route::get('/cargos-municipal/editar/{id}', [CargoMunicipalController::class, 'edit'])->name('web.edit-cargos-municipal');
    Route::put('/cargos-municipal/editar/{id}', [CargoMunicipalController::class, 'update'])->name('web.update-cargos-municipal');
    Route::get('/cargos-municipal/delete/{id}', [CargoMunicipalController::class, 'delete'])->name('web.delete-cargos-municipal');
    Route::get('/cargos-municipal/imprimir', [CargoMunicipalController::class, 'Imprimir'])->name('web.cargos-municipal-pdf');
    Route::get('/cargos-municipal/excel', [CargoMunicipalController::class, 'excel'])->name('web.cargos-municipal-excel');

    // FUNCIONARIOS
    Route::get('/funcionarios-municipal/controlo', [FuncionarioMunicipalController::class, 'FuncionariosControlo'])->name('web.funcionarios-municipal-controlo');
    Route::get('/funcionarios-municipal/departamentos', [FuncionarioMunicipalController::class, 'FuncionariosDepartamento'])->name('web.funcionarios-municipal-departamentos');
    Route::get('/funcionarios-municipal/cargos', [FuncionarioMunicipalController::class, 'FuncionariosCargo'])->name('web.funcionarios-municipal-cargos');

    Route::get('/funcionarios-municipal/home', [FuncionarioMunicipalController::class, 'Funcionarios'])->name('web.funcionarios-municipal');
    Route::get('/funcionarios-municipal/create', [FuncionarioMunicipalController::class, 'FuncionariosCreate'])->name('web.funcionarios-municipal-create');
    Route::post('/funcionarios-municipal/store', [FuncionarioMunicipalController::class, 'FuncionariosStore'])->name('web.funcionarios-municipal-store');
    Route::get('/funcionarios-municipal/show/{id}', [FuncionarioMunicipalController::class, 'FuncionariosShow'])->name('web.funcionarios-municipal-show');
    Route::get('/funcionarios-municipal/edit/{id}', [FuncionarioMunicipalController::class, 'FuncionariosEdit'])->name('web.funcionarios-municipal-edit');
    Route::get('/funcionarios-municipal/status/{id}', [FuncionarioMunicipalController::class, 'FuncionariosStatus'])->name('web.funcionarios-municipal-status');
    Route::put('/funcionarios-municipal/update/{id}', [FuncionarioMunicipalController::class, 'FuncionariosUpdate'])->name('web.funcionarios-municipal-update');
    Route::delete('/funcionarios-municipal/destroy/{id}', [FuncionarioMunicipalController::class, 'FuncionariosDestroy'])->name('web.funcionarios-municipal-destroy');

    Route::get('/funcionarios-municipal/imprimir-pdf', [PrinterController::class, 'funcionariosImprimirMunicipalPDF'])->name('funcionarios-imprmir-municipal-pdf');
    Route::get('/funcionarios-municipal/imprimir-excel', [PrinterController::class, 'funcionariosImprimirMunicipalEXCEL'])->name('funcionarios-imprmir-municipal-excel');

    Route::get('/funcionarios-municipal/imprimir-departamentos-pdf', [PrinterController::class, 'funcionariosImprimirDepartamentoMunicipalPDF'])->name('funcionarios-imprmir-departamentos-municipal-pdf');
    Route::get('/funcionarios-municipal/imprimir-departamentos-excel', [PrinterController::class, 'funcionariosImprimirDepartamentoMunicipalEXCEL'])->name('funcionarios-imprmir-departamentos-municipal-excel');

    Route::get('/funcionarios-municipal/imprimir-cargos-pdf', [PrinterController::class, 'funcionariosImprimirCargoMunicipalPDF'])->name('funcionarios-imprmir-cargos-municipal-pdf');
    Route::get('/funcionarios-municipal/imprimir-cargos-excel', [PrinterController::class, 'funcionariosImprimirCargoMunicipalEXCEL'])->name('funcionarios-imprmir-cargos-municipal-excel');
    /** CONTROLLERES PORTAL PROVINCIAL */

    Route::get('/home-provincial-admin', [PortalProvinciaController::class, 'home'])->name('home-provincial');
    Route::get('/listagem-escolas-provincial/{id?}', [PortalProvinciaController::class, 'listagemEscolas'])->name('listagem-escola-provincial');
    Route::get('/informacao-escola-provincial/{id}', [PortalProvinciaController::class, 'informacaoEscolar'])->name('web.informacao-escola-provincial');
    Route::get('/listagem-estudantes-provincial/{escola}', [PortalProvinciaController::class, 'listagemEstudantes'])->name('app.listagem-estudantes-provincial');
    Route::get('/informacao-estudante-provincial/{id}', [PortalProvinciaController::class, 'informacaoEstudante'])->name('app.informacao-estudante-provincial');
    Route::get('/listagem-professores-provincial/{escola}', [PortalProvinciaController::class, 'listagemProfessores'])->name('app.listagem-professores-provincial');
    Route::get('/informacao-professores-provincial/{id}', [PortalProvinciaController::class, 'informacaoProfessores'])->name('app.informacao-professores-provincial');
    Route::get('/estado-candidatura-professores-provincial/{status}', [PortalProvinciaController::class, 'estadoCandidaturaProfessores'])->name('app.estado-candidaturas-professores-provincial');


    Route::get('/provincial/gestao-professores', [PortalProvinciaController::class, 'professoresGestao'])->name('app.provincial-gestao-professores-index');
    Route::get('/dispanho-professores-provincial/{id?}', [PortalProvinciaController::class, 'dispanhoProfessoresIndex'])->name('app.dispanho-professores-provincial-index');
    Route::post('/dispanho-professores-provincial', [PortalProvinciaController::class, 'dispanhoProfessoresStore'])->name('app.dispanho-professores-provincial-store');

    Route::get('/transferencia-escolares-provincial-professores', [TransferenciaEscolaProfessorController::class, 'listProvincial'])->name('web.transferencia-escolares-provincial-professores');
    Route::get('/transferencia-escola-provincial-professores/{id?}', [TransferenciaEscolaProfessorController::class, 'indexProvincial'])->name('web.transferencia-escola-provincial-professores');
    Route::post('/transferencia-escola-provincial-professores', [TransferenciaEscolaProfessorController::class, 'storeProvincial'])->name('web.transferencia-escola-provincial-professores-store');


    Route::get('/informacao-turmas-professores-provincial/{id}/{escola?}', [PortalProvinciaController::class, 'informacaoTurmaProfessores'])->name('app.informacao-turma-professores-provincial');
    Route::get('/listagem-estudantes-provincial', [PortalProvinciaController::class, 'listagemEstudantesGeral'])->name('app.listagem-estudantes-provincial-geral');
    Route::get('/listagem-professores-provincial', [PortalProvinciaController::class, 'professoresIndex'])->name('app.professores-provincial');
    Route::get('/provincial/privacidade', [PortalProvinciaController::class, 'privacidade'])->name('app.privacidade-provincial');
    Route::put('/provincial/privacidade-update/{id}', [PortalProvinciaController::class, 'privacidadeUpdate'])->name('app.privacidade-provincial-update');

    Route::get('/provincial/utilizadores', [PortalProvinciaController::class, 'utilizadoresIndex'])->name('app.provincial-utilizadores-index');
    Route::get('/provincial/utilizadores/criar', [PortalProvinciaController::class, 'utilizadoresCreate'])->name('app.provincial-utilizadores-create');
    Route::post('/provincial/utilizadore/criar', [PortalProvinciaController::class, 'utilizadoresStore'])->name('app.provincial-utilizadores-store');
    Route::get('/provincial/utilizadores/{id}/editar', [PortalProvinciaController::class, 'utilizadoresEdit'])->name('app.provincial-utilizadores-edit');
    Route::put('/provincial/utilizadore/{id}/editar', [PortalProvinciaController::class, 'utilizadoresUpdate'])->name('app.provincial-utilizadores-update');

    Route::get('/provincial/visualizar-solicitacoes', [PortalProvinciaController::class, 'solicitacoes'])->name('app.provincial-solicitacoes-dos-professores');
    Route::put('/provincial/visualizar-solicitacoes/{id}', [PortalProvinciaController::class, 'solicitacoesResposta'])->name('app.provincial-solicitacoes-dos-professores-resposta');

    /** PROFESSAMENTOS DE NOTAS OU PLANIFICACAO */
    Route::get('/planificacao-provincial/controlo', [ControloLancamentoNotasController::class, 'controlo'])->name('app.planificacao-provincial-controlo');
    Route::get('/planificacao-provincial/mini-pauta', [ControloLancamentoNotasController::class, 'miniPauta'])->name('app.planificacao-provincial-mini-pauta');
    Route::get('/planificacao-provincial/pesquisa-mini-pauta', [ControloLancamentoNotasController::class, 'pesquisarMiniPauta'])->name('app.planificacao-provincial-pesquisa-mini-pauta');
    Route::get('/planificacao-provincial/mini-pauta-geral', [ControloLancamentoNotasController::class, 'miniPautaGeral'])->name('app.planificacao-provincial-mini-pauta-geral');
    Route::get('/planificacao-provincial/pesquisas-mini-pauta-geral', [ControloLancamentoNotasController::class, 'pesquisarMiniPautaGerais'])->name('app.planificacao-provincial-pesquisas-mini-pauta-gerais');
    Route::get('/planificacao-provincial/mapa-aproveitamento', [ControloLancamentoNotasController::class, 'mapaAproveitamentoGeral'])->name('app.planificacao-provincial-mapa-aproveitamento');
    Route::get('/planificacao-provincial/mapa-aproveitamento-create', [ControloLancamentoNotasController::class, 'mapaAproveitamentoGeralCreate'])->name('app.planificacao-provincial-mapa-aproveitamento-create');

    Route::get('/controlo-lancamento-notas/home', [ControloLancamentoNotasController::class, 'index'])->name('web.controlo-lancamento-notas.index');
    Route::post('/controlo-lancamento-notas/home', [ControloLancamentoNotasController::class, 'store'])->name('web.controlo-lancamento-notas.store');

    Route::get('/controlo-lancamento-notas/status-controlo-lancamento/{id}', [ControloLancamentoNotasController::class, 'status'])->name('web.controlo-lancamento-notas.status');
    Route::get('/controlo-lancamento-notas/escolas-controlo-lancamento', [ControloLancamentoNotasController::class, 'escolas'])->name('web.controlo-lancamento-notas.escolas');

    Route::get('/activadores/candidatura-professores', [ActivadorController::class, 'candidaturaProfessor'])->name('web.activadores-candidatura-professores');
    Route::post('/activadores/candidatura-professores', [ActivadorController::class, 'candidaturaProfessorPost'])->name('web.activadores-candidatura-professores-post');
    Route::get('/activadores/candidatura-estudantes', [ActivadorController::class, 'candidaturaEstudante'])->name('web.activadores-candidatura-estudantes');
    Route::post('/activadores/candidatura-estudantes', [ActivadorController::class, 'candidaturaEstudantePost'])->name('web.activadores-candidatura-estudantes-post');
    Route::get('/activadores/candidatura-professores/status/{id}', [ActivadorController::class, 'candidaturaProfessorStatus'])->name('web.activadores-candidatura-professores-status');
    Route::get('/activadores/candidatura-estudantes/status/{id}', [ActivadorController::class, 'candidaturaEstudanteStatus'])->name('web.activadores-candidatura-estudantes-status');

    // MUNICIPAL
    // PROFESSORES
    Route::get('/municipal-activadores/candidatura-professores', [ActivadorController::class, 'municipalCandidaturaProfessor'])->name('web.municipal-activadores-candidatura-professores');
    Route::post('/municipal-activadores/candidatura-professores', [ActivadorController::class, 'municipalCandidaturaProfessorPost'])->name('web.municipal-activadores-candidatura-professores-post');
    Route::get('/municipal-activadores/candidatura-professores/status/{id}', [ActivadorController::class, 'municipalCandidaturaProfessorStatus'])->name('web.municipal-activadores-candidatura-professores-status');
    // ESTUDANTES
    Route::get('/municipal-activadores/candidatura-estudantes', [ActivadorController::class, 'municipalCandidaturaEstudante'])->name('web.municipal-activadores-candidatura-estudantes');
    Route::post('/municipal-activadores/candidatura-estudantes', [ActivadorController::class, 'municipalCandidaturaEstudantePost'])->name('web.municipal-activadores-candidatura-estudantes-post');
    Route::get('/municipal-activadores/candidatura-estudantes/status/{id}', [ActivadorController::class, 'municipalCandidaturaEstudanteStatus'])->name('web.municipal-activadores-candidatura-estudantes-status');

    Route::get('/municipal-controlo-lancamento-notas/home', [ControloLancamentoNotasMunicipalController::class, 'index'])->name('web.municipal-controlo-lancamento-notas.index');
    Route::post('/municipal-controlo-lancamento-notas/home', [ControloLancamentoNotasMunicipalController::class, 'store'])->name('web.municipal-controlo-lancamento-notas.store');
    Route::get('/municipal-controlo-lancamento-notas/status-controlo-lancamento/{id}', [ControloLancamentoNotasMunicipalController::class, 'status'])->name('web.municipal-controlo-lancamento-notas.status');
    Route::get('/municipal-controlo-lancamento-notas/escolas-controlo-lancamento', [ControloLancamentoNotasMunicipalController::class, 'escolas'])->name('web.municipal-controlo-lancamento-notas.escolas');
    Route::get('/planificacao-municipal/controlo', [ControloLancamentoNotasMunicipalController::class, 'controlo'])->name('app.planificacao-municipal-controlo');
    Route::get('/planificacao-municipal/mini-pauta', [ControloLancamentoNotasMunicipalController::class, 'miniPauta'])->name('app.planificacao-municipal-mini-pauta');
    Route::get('/planificacao-municipal/pesquisa-mini-pauta', [ControloLancamentoNotasMunicipalController::class, 'pesquisarMiniPauta'])->name('app.planificacao-municipal-pesquisa-mini-pauta');
    Route::get('/planificacao-municipal/mini-pauta-geral', [ControloLancamentoNotasMunicipalController::class, 'miniPautaGeral'])->name('app.planificacao-municipal-mini-pauta-geral');
    Route::get('/planificacao-municipal/pesquisas-mini-pauta-geral', [ControloLancamentoNotasMunicipalController::class, 'pesquisarMiniPautaGerais'])->name('app.planificacao-municipal-pesquisas-mini-pauta-gerais');

    // WEB DEPARTAMENTOS PROVINCIAL
    Route::get('/departamento-provincial/home', [DepartamentoProvincialController::class, 'index'])->name('web.departamento-provincial');
    Route::get('/departamento-provincial/cadastrar', [DepartamentoProvincialController::class, 'create'])->name('web.create-departamento-provincial');
    Route::post('/departamento-provincial/store', [DepartamentoProvincialController::class, 'store'])->name('web.store-departamento-provincial');
    Route::get('/departamento-provincial/editar/{id}', [DepartamentoProvincialController::class, 'edit'])->name('web.edit-departamento-provincial');
    Route::put('/departamento-provincial/editar/{id}', [DepartamentoProvincialController::class, 'update'])->name('web.update-departamento-provincial');
    Route::get('/departamento-provincial/delete/{id}', [DepartamentoProvincialController::class, 'delete'])->name('web.delete-departamento-provincial');
    Route::get('/departamento-provincial/imprimir', [DepartamentoProvincialController::class, 'Imprimir'])->name('web.departamento-provincial-pdf');
    Route::get('/departamento-provincial/excel', [DepartamentoProvincialController::class, 'excel'])->name('web.departamento-provincial-excel');


    // WEB CARGOS PROVINCIAL
    Route::get('/cargos-provincial/home', [CargoProvincialController::class, 'index'])->name('web.cargos-provincial');
    Route::get('/cargos-provincial/cadastrar', [CargoProvincialController::class, 'create'])->name('web.create-cargos-provincial');
    Route::post('/cargos-provincial/store', [CargoProvincialController::class, 'store'])->name('web.store-cargos-provincial');
    Route::get('/cargos-provincial/editar/{id}', [CargoProvincialController::class, 'edit'])->name('web.edit-cargos-provincial');
    Route::put('/cargos-provincial/editar/{id}', [CargoProvincialController::class, 'update'])->name('web.update-cargos-provincial');
    Route::get('/cargos-provincial/delete/{id}', [CargoProvincialController::class, 'delete'])->name('web.delete-cargos-provincial');
    Route::get('/cargos-provincial/imprimir', [CargoProvincialController::class, 'Imprimir'])->name('web.cargos-provincial-pdf');
    Route::get('/cargos-provincial/excel', [CargoProvincialController::class, 'excel'])->name('web.cargos-provincial-excel');

    // FUNCIONARIOS
    Route::get('/funcionarios-provincial/controlo', [FuncionarioProvincialController::class, 'FuncionariosControlo'])->name('web.funcionarios-provincial-controlo');
    Route::get('/funcionarios-provincial/departamentos', [FuncionarioProvincialController::class, 'FuncionariosDepartamento'])->name('web.funcionarios-provincial-departamentos');
    Route::get('/funcionarios-provincial/cargos', [FuncionarioProvincialController::class, 'FuncionariosCargo'])->name('web.funcionarios-provincial-cargos');


    Route::get('/funcionarios-provincial/home', [FuncionarioProvincialController::class, 'Funcionarios'])->name('web.funcionarios-provincial');
    Route::get('/funcionarios-provincial/create', [FuncionarioProvincialController::class, 'FuncionariosCreate'])->name('web.funcionarios-provincial-create');
    Route::post('/funcionarios-provincial/store', [FuncionarioProvincialController::class, 'FuncionariosStore'])->name('web.funcionarios-provincial-store');
    Route::get('/funcionarios-provincial/edit/{id}', [FuncionarioProvincialController::class, 'FuncionariosEdit'])->name('web.funcionarios-provincial-edit');
    Route::get('/funcionarios-provincial/duplicar/{id}', [FuncionarioProvincialController::class, 'FuncionariosDuplicar'])->name('web.funcionarios-provincial-duplicar');
    Route::post('/funcionarios-provincial/duplicar', [FuncionarioProvincialController::class, 'FuncionariosDuplicarStore'])->name('web.funcionarios-provincial-duplicar-store');
    Route::put('/funcionarios-provincial/update/{id}', [FuncionarioProvincialController::class, 'FuncionariosUpdate'])->name('web.funcionarios-provincial-update');
    Route::delete('/funcionarios-provincial/destroy/{id}', [FuncionarioProvincialController::class, 'FuncionariosDestroy'])->name('web.funcionarios-provincial-destroy');
    Route::get('/funcionarios-provincial/show/{id}', [FuncionarioProvincialController::class, 'FuncionariosShow'])->name('web.funcionarios-provincial-show');
    Route::get('/funcionarios-provincial/status/{id}', [FuncionarioProvincialController::class, 'FuncionariosStatus'])->name('web.funcionarios-provincial-status');

    Route::get('/professores-provincial/create', [ProfessorProvincialController::class, 'professoresCreate'])->name('web.professores-provincial-create');
    Route::post('/professores-provincial/store', [ProfessorProvincialController::class, 'professoresStore'])->name('web.professores-provincial-store');

    Route::get('/professores-provincial/edit/{id}', [ProfessorProvincialController::class, 'professoresEdit'])->name('web.professores-provincial-edit');
    Route::put('/professores-provincial/update/{id}', [ProfessorProvincialController::class, 'professoresUpdate'])->name('web.professores-provincial-update');

    Route::get('/professores-provincial/duplicar/{id}', [ProfessorProvincialController::class, 'professoresDuplicar'])->name('web.professores-provincial-duplicar');
    Route::post('/professores-provincial/duplicar', [ProfessorProvincialController::class, 'professoresDuplicarStore'])->name('web.professores-provincial-duplicar-store');

    // Route::get('/funcionarios-provincial/imprimir-pdf', [PrinterController::class, 'funcionariosImprimirProvincialPDF'])->name('funcionarios-imprmir-provincial-pdf');
    // Route::get('/funcionarios-provincial/imprimir-excel', [PrinterController::class, 'funcionariosImprimirProvincialEXCEL'])->name('funcionarios-imprmir-provincial-excel');

    // Route::get('/funcionarios-provincial/imprimir-departamentos-pdf', [PrinterController::class, 'funcionariosImprimirDepartamentoProvincialPDF'])->name('funcionarios-imprmir-departamentos-provincial-pdf');
    // Route::get('/funcionarios-provincial/imprimir-departamentos-excel', [PrinterController::class, 'funcionariosImprimirDepartamentoprovincialEXCEL'])->name('funcionarios-imprmir-departamentos-provincial-excel');

    // Route::get('/funcionarios-provincial/imprimir-cargos-pdf', [PrinterController::class, 'funcionariosImprimirCargoProvincialPDF'])->name('funcionarios-imprmir-cargos-provincial-pdf');
    // Route::get('/funcionarios-provincial/imprimir-cargos-excel', [PrinterController::class, 'funcionariosImprimirCargoProvincialEXCEL'])->name('funcionarios-imprmir-cargos-provincial-excel');

    // ADMIN NDOMA AMANUEL
    ########################################### ADMIN CONTROLLER ##########################################################
    #######################################################################################################################
    Route::get('/admin/notificacoes', [AdminController::class, 'notificacoes'])->name('web.admin.notificacoes');
    Route::get('/home-super-admin', [AdminController::class, 'homeAdmin'])->name('home-admin');


    Route::get('/listagem-escolas/{id?}', [AdminController::class, 'listagemEscolas'])->name('listagem-escola');
    Route::get('/termos', [AdminController::class, 'termos'])->name('termos');
    Route::post('/termos', [AdminController::class, 'termosEditar'])->name('termos-editar');
    Route::get('/politicas', [AdminController::class, 'politicas'])->name('politicas');
    Route::post('/politicas', [AdminController::class, 'politicasEditar'])->name('politicas-editar');
    Route::get('/definicoes', [AdminController::class, 'definicoes'])->name('definicoes');
    Route::post('/definicoes', [AdminController::class, 'definicoesEditar'])->name('definicoes-editar');
    Route::post('/configuracao-escola', [AdminController::class, 'configuracaoEscola'])->name('configuracao-escola');
    Route::get('/ativar-escola/{id}', [AdminController::class, 'activarEscola'])->name('activar-escola');
    Route::get('/informacao-escola/{id}', [AdminController::class, 'informacaoEscolar'])->name('web.informacao-escola');
    Route::get('/confirguracao-escola/{id}', [AdminController::class, 'configurarEscola'])->name('web.configurar-escola');

    // software
    Route::delete('/eliminar-escola/{id}', [AdminController::class, 'eliminar_escola'])->name('app.eliminar_escola');

    Route::get('/superadmin/privacidade', [AdminController::class, 'privacidade'])->name('app.privacidade');
    Route::put('/superadmin/privacidade-update/{id}', [AdminController::class, 'privacidadeUpdate'])->name('app.privacidade-update');
    Route::get('/superadmin/professores', [AdminController::class, 'professoresIndex'])->name('app.professores-index');
    Route::get('/superadmin/dispanho-professores/{id?}', [AdminController::class, 'DispanhoProfessoresIndex'])->name('app.Dispanho-professores-index');
    Route::post('/superadmin/dispanho-professores', [AdminController::class, 'DispanhoProfessoresStore'])->name('app.Dispanho-professores-store');
    Route::get('/superadmin/utilizadores', [AdminController::class, 'utilizadoresIndex'])->name('app.utilizadores-index');
    Route::get('/superadmin/utilizadores/criar', [AdminController::class, 'utilizadoresCreate'])->name('app.utilizadores-create');
    Route::post('/superadmin/utilizadore/criar', [AdminController::class, 'utilizadoresStore'])->name('app.utilizadores-store');
    Route::get('/superadmin/utilizadores/{id}/editar', [AdminController::class, 'utilizadoresEdit'])->name('app.utilizadores-edit');
    Route::put('/superadmin/utilizadore/{id}/editar', [AdminController::class, 'utilizadoresUpdate'])->name('app.utilizadores-update');

    /**
     * lista de estudantes de escola do pais
     */

    Route::get('/estatisticas-estudantes', [AdminController::class, 'estatisticaEstudantesGeral'])->name('app.estatisticas-estudantes-geral');

    Route::get('/listagem-estudantes', [AdminController::class, 'listagemEstudantesGeral'])->name('app.listagem-estudantes-geral');
    Route::get('/listagem-estudantes/{escola}', [AdminController::class, 'listagemEstudantes'])->name('app.listagem-estudantes');
    Route::get('/informacao-estudante/{id}', [AdminController::class, 'informacaoEstudante'])->name('app.informacao-estudante');

    Route::get('/listagem-professores/{escola}', [AdminController::class, 'listagemProfessores'])->name('app.listagem-professores');
    Route::get('/informacao-professores/{id}', [AdminController::class, 'informacaoProfessores'])->name('app.informacao-professores');
    Route::get('/informacao-turmas-professores/{id}/{escola?}', [AdminController::class, 'informacaoTurmaProfessores'])->name('app.informacao-turma-professores');
    Route::get('/informacao-professores-lancamento-nota/{id}/{turma?}', [AdminController::class, 'informacaoProfessoresLancamentoNota'])->name('app.informacao-professores-lancamento-nota');
    Route::get('/professores-lancamento-nota-estudantes/{prof}/{notas?}', [AdminController::class, 'professoresLancamentoNotaEstudante'])->name('app.professores-lancamento-nota-estudante');
    Route::post('/professores-lancamento-nota-estudantes', [AdminController::class, 'professoresLancamentoNotaEstudanteStore'])->name('app.professores-lancamento-nota-estudante-store');

    Route::get('/estado-candidatura-professores/{status}', [AdminController::class, 'estadoCandidaturaProfessores'])->name('app.estado-candidaturas-professores');

    // WEB DEPARTAMENTOS MINISTERIO
    Route::get('/departamento-ministerio/home', [DepartamentoMinisterioController::class, 'index'])->name('web.departamento-ministerio');
    Route::get('/departamento-ministerio/cadastrar', [DepartamentoMinisterioController::class, 'create'])->name('web.create-departamento-ministerio');
    Route::post('/departamento-ministerio/store', [DepartamentoMinisterioController::class, 'store'])->name('web.store-departamento-ministerio');
    Route::get('/departamento-ministerio/editar/{id}', [DepartamentoMinisterioController::class, 'edit'])->name('web.edit-departamento-ministerio');
    Route::put('/departamento-ministerio/editar/{id}', [DepartamentoMinisterioController::class, 'update'])->name('web.update-departamento-ministerio');
    Route::get('/departamento-ministerio/delete/{id}', [DepartamentoMinisterioController::class, 'delete'])->name('web.delete-departamento-ministerio');
    Route::get('/departamento-ministerio/imprimir', [DepartamentoMinisterioController::class, 'Imprimir'])->name('web.departamento-ministerio-pdf');
    Route::get('/departamento-ministerio/excel', [DepartamentoMinisterioController::class, 'excel'])->name('web.departamento-ministerio-excel');

    // WEB CARGOS MINISTERIO
    Route::get('/cargos-ministerio/home', [CargoMinisterioController::class, 'index'])->name('web.cargos-ministerio');
    Route::get('/cargos-ministerio/cadastrar', [CargoMinisterioController::class, 'create'])->name('web.create-cargos-ministerio');
    Route::post('/cargos-ministerio/store', [CargoMinisterioController::class, 'store'])->name('web.store-cargos-ministerio');
    Route::get('/cargos-ministerio/editar/{id}', [CargoMinisterioController::class, 'edit'])->name('web.edit-cargos-ministerio');
    Route::put('/cargos-ministerio/editar/{id}', [CargoMinisterioController::class, 'update'])->name('web.update-cargos-ministerio');
    Route::get('/cargos-ministerio/delete/{id}', [CargoMinisterioController::class, 'delete'])->name('web.delete-cargos-ministerio');
    Route::get('/cargos-ministerio/imprimir', [CargoMinisterioController::class, 'Imprimir'])->name('web.cargos-ministerio-pdf');
    Route::get('/cargos-ministerio/excel', [CargoMinisterioController::class, 'excel'])->name('web.cargos-ministerio-excel');

    // FUNCIONARIOS
    Route::get('/funcionarios-ministerio/home', [FuncionarioMinisterioController::class, 'Funcionarios'])->name('web.funcionarios-ministerio');
    Route::get('/funcionarios-ministerio/create', [FuncionarioMinisterioController::class, 'FuncionariosCreate'])->name('web.funcionarios-ministerio-create');
    Route::post('/funcionarios-ministerio/store', [FuncionarioMinisterioController::class, 'FuncionariosStore'])->name('web.funcionarios-ministerio-store');
    Route::get('/funcionarios-ministerio/edit/{id}', [FuncionarioMinisterioController::class, 'FuncionariosEdit'])->name('web.funcionarios-ministerio-edit');
    Route::put('/funcionarios-ministerio/update/{id}', [FuncionarioMinisterioController::class, 'FuncionariosUpdate'])->name('web.funcionarios-ministerio-update');
    Route::delete('/funcionarios-ministerio/destroy/{id}', [FuncionarioMinisterioController::class, 'FuncionariosDestroy'])->name('web.funcionarios-ministerio-destroy');

    // APP Modulos  escola
    Route::resource('/permissions', PermissionController::class);
    Route::get('/permissions-escola/{id}/delete', [PermissionController::class, 'destroy'])->name('app.permissions.delete');
    Route::resource('/roles', RoleController::class);
    Route::get('/roles/{id}/delete', [RoleController::class, 'destroy'])->name('app.roles.delete');

    ########################################## PAINES ############################################################
    Route::prefix('paineis')->group(function () {
        Route::get('/home-principal-administrativo', [PainelController::class, 'home'])->name('paineis.administrativo');
        Route::get('/painel-informativo-administrativo', [PainelController::class, 'inicio'])->name('paineis.painel-informativo-administrativo');
        Route::get('/verificar/licenca-validade', [PainelController::class, 'validadeDaLicenca'])->name('paineis.verificar/licenca-validade');
    });
    Route::get('/verificar-actualizacoes-cartao', [HomeController::class, 'verificar_actualizacoes_cartao'])->name('web.verificar-actualizacoes-cartao');
    Route::get('/verificar-actualizacoes-cartao-primeira-taxa', [HomeController::class, 'verificar_actualizacoes_cartao_primeira_taxa'])->name('web.verificar-actualizacoes-cartao-primeira-taxa');
    Route::get('/verificar-actualizacoes-cartao-segunda-taxa', [HomeController::class, 'verificar_actualizacoes_cartao_segunda_taxa'])->name('web.verificar-actualizacoes-cartao-segunda-taxa');
    Route::get('/verificar-actualizacoes-cartao-terceira-taxa', [HomeController::class, 'verificar_actualizacoes_cartao_terceira_taxa'])->name('web.verificar-actualizacoes-cartao-terceira-taxa');

    Route::resource('tempos-lecionados', TempoLecionadoController::class);
    Route::get('/relatorio-tempos-lecionados', [TempoLecionadoController::class, 'relatorio'])->name('relatorio.tempos-lecionados');

    ########################################## PAINES ############################################################
    Route::prefix('recursos-humanos')->group(function () {
        Route::get('/recursos-humanos', [HomeController::class, 'recursos_humanos'])->name('recursos_humanos');

        Route::get('/funcionarios/contratos', [ContratoFuncioarioController::class, 'contratos'])->name('web.funcionarios-contrato');
        Route::get('/funcionarios/contratos-activar/{id?}', [ContratoFuncioarioController::class, 'contratosActivar'])->name('web.funcionarios-activar-contrato');
        Route::delete('/funcionarios/contratos-excluir/{id?}', [ContratoFuncioarioController::class, 'contratosExcluir'])->name('web.funcionarios-excluir-contrato');
        Route::get('/funcionarios/criar-contrato/{id?}', [ContratoFuncioarioController::class, 'criarContrato'])->name('web.funcionarios-criar-contrato');
        Route::post('/funcionarios/criar-contrato', [ContratoFuncioarioController::class, 'criarContratoStore'])->name('web.funcionarios-criar-contrato-store');
        Route::get('/funcionarios/editar-contrato/{id?}', [ContratoFuncioarioController::class, 'editarContrato'])->name('web.funcionarios-editar-contrato');
        Route::put('/funcionarios/editar-contrato/{id}', [ContratoFuncioarioController::class, 'editarContratoStore'])->name('web.funcionarios-editar-contrato-update');
        Route::get('/funcionarios/visualizar-contrato/{id?}', [ContratoFuncioarioController::class, 'visualizarContrato'])->name('web.funcionarios-visualizar-contrato');


        // WEB APP FUNCIONARIOS
        Route::get('/outro-funcionarios/home', [FuncionarioController::class, 'outroFuncionarios'])->name('web.outro-funcionarios');
        Route::get('/outro-funcionarios/create', [FuncionarioController::class, 'outroFuncionariosCreate'])->name('web.outro-funcionarios-create');
        Route::post('/outro-funcionarios/store', [FuncionarioController::class, 'outroFuncionariosStore'])->name('web.outro-funcionarios-store');
        Route::get('/outro-funcionarios/edit/{id}', [FuncionarioController::class, 'outroFuncionariosEdit'])->name('web.outro-funcionarios-edit');
        Route::put('/outro-funcionarios/update/{id}', [FuncionarioController::class, 'outroFuncionariosUpdate'])->name('web.outro-funcionarios-update');
        Route::delete('/outro-funcionarios/destroy/{id}', [FuncionarioController::class, 'outroFuncionariosDestroy'])->name('web.outro-funcionarios-destroy');
        Route::get('/outro-funcionarios/contrato/{id?}', [FuncionarioController::class, 'outrocriarContrato'])->name('web.outro-funcionarios-contrato');
        Route::post('/outro-funcionarios/contrato/store', [FuncionarioController::class, 'outrocriarContratoStore'])->name('web.outro-funcionarios-contrato-store');
        Route::get('/outro-funcionarios/mais-informacao-funcionarios/{id}', [FuncionarioController::class, 'outroMaisInformacoesFuncionario'])->name('web.outro-mais-informacao-funcionarios');

        Route::get('/funcionarios/home', [FuncionarioController::class, 'funcionarios'])->name('web.funcionarios');
        Route::get('/funcionarios/editar-funcionarios/{id}', [FuncionarioController::class, 'editarFuncionarios']);
        Route::post('/funcionarios/editar-funcionarios', [FuncionarioController::class, 'updateFuncionarios'])->name('web.editar-funcionario');
        Route::delete('/funcionarios/excluir-funcionarios/{id}', [FuncionarioController::class, 'deleteFuncionarios']);
        Route::get('/funcionarios/activar-funcionarios/{id}', [FuncionarioController::class, 'activarFuncionarios']);
        Route::get('/funcionarios/pesquisae-funcionarios/{code}', [FuncionarioController::class, 'pesquisarFuncionario']);

        Route::post('/funcionarios/home', [FuncionarioController::class, 'cadastrarFuncionariosNovo'])->name('web.cadastrar-funcionarios-novo');
        Route::post('/funcionarios/homes', [FuncionarioController::class, 'cadastrarFuncionariosAcademico'])->name('web.cadastrar-funcionarios-academicos');
        Route::post('/funcionarios/homesss', [FuncionarioController::class, 'concluirCadastroFuncionarios'])->name('web.concluir-cadastrar-funcionarios');

        Route::get('/funcionarios/edicao-prazo-notas/{id}', [FuncionarioController::class, 'actualizarPrazoNotas'])->name('web.funcionarios-actualizar-prazo-notas');
        Route::post('/funcionarios/edicao-prazo-notas', [FuncionarioController::class, 'actualizarPrazoNotasStore'])->name('web.funcionarios-actualizar-prazo-notas-store');

        Route::get('/funcionarios/docentes/{id?}', [FuncionarioController::class, 'funcionariosDocentes'])->name('web.funcionarios-docentes');


        Route::get('/professores/create', [FuncionarioController::class, 'professoresCreate'])->name('shcools.professores-create');
        Route::post('/professores/store', [FuncionarioController::class, 'professoresStore'])->name('shcools.professores-store');

        Route::get('/professores/edit/{id}', [FuncionarioController::class, 'professoresEdit'])->name('shcools.professores-edit');
        Route::put('/professores/update/{id}', [FuncionarioController::class, 'professoresUpdate'])->name('shcools.professores-update');

        Route::get('/funcionarios/activar-professores/{id}', [FuncionarioController::class, 'activarProfessores']);
        Route::get('/funcionarios/excluir-professores/{id}', [FuncionarioController::class, 'excluirProfessores']);

        Route::get('/funcionarios/mais-informacao-funcionarios/{id}', [FuncionarioController::class, 'maisInformacoesFuncionario'])->name('web.mais-informacao-funcionarios');

        Route::get('/funcionarios/pagamento-salario/{id}', [WebController::class, 'funcionariosPagamentoSalario'])->name('web.funcionarios-pagamento-salario');
        Route::post('/funcionarios/pagamento-salario', [WebController::class, 'funcionariosPagamentoSalarioCreate'])->name('web.funcionarios-pagamento-salario-create');
        Route::get('/funcionarios/detalhes-pagamento-salario/{id}/{func}', [WebController::class, 'funcionarioDetalhesPagamentoSalario'])->name('web.adicionar-meses-pagamento-salario');
        Route::get('/funcionarios/detalhes-pagamento-salario-remover-mes/{id}/{est}/{mes}', [WebController::class, 'funcionariosDetalhesPagamentoSalarioRemoverMes'])->name('web.remover-meses-pagamento-salario');

        // WEB DEPARTAMENTOS
        Route::get('/departamento/home', [DepartamentoController::class, 'index'])->name('web.departamento');
        Route::get('/departamento/cadastrar', [DepartamentoController::class, 'create'])->name('web.create-departamento');
        Route::post('/departamento/store', [DepartamentoController::class, 'store'])->name('web.store-departamento');
        Route::get('/departamento/editar/{id}', [DepartamentoController::class, 'edit'])->name('web.edit-departamento');
        Route::put('/departamento/editar/{id}', [DepartamentoController::class, 'update'])->name('web.update-departamento');
        Route::get('/departamento/delete/{id}', [DepartamentoController::class, 'delete'])->name('web.delete-departamento');
        Route::get('/departamento/imprimir', [DepartamentoController::class, 'Imprimir'])->name('web.departamento-pdf');
        Route::get('/departamento/excel', [DepartamentoController::class, 'excel'])->name('web.departamento-excel');

        // WEB DEPARTAMENTOS
        Route::get('/cargos/home', [CargoController::class, 'index'])->name('web.cargos');
        Route::get('/cargos/cadastrar', [CargoController::class, 'create'])->name('web.create-cargos');
        Route::post('/cargos/store', [CargoController::class, 'store'])->name('web.store-cargos');
        Route::get('/cargos/editar/{id}', [CargoController::class, 'edit'])->name('web.edit-cargos');
        Route::put('/cargos/editar/{id}', [CargoController::class, 'update'])->name('web.update-cargos');
        Route::get('/cargos/delete/{id}', [CargoController::class, 'delete'])->name('web.delete-cargos');
        Route::get('/cargos/imprimir', [CargoController::class, 'Imprimir'])->name('web.cargos-pdf');
        Route::get('/cargos/excel', [CargoController::class, 'excel'])->name('web.cargos-excel');

        Route::get('/biometrico', [BiometricoController::class, 'index'])->name('web.biometrico-index');
        Route::post('/biometrico', [BiometricoController::class, 'store'])->name('web.biometrico-store');
    });

    ########################################## WEB CONTROLLER ############################################################
    #######################################################################################################################
    Route::get('/painel-bem-vindo-administrativo', [HomeController::class, 'bemVindo'])->name('shcools.painel-benvindo-administrativo');
    Route::get('/actualizar-cor-fundo/{color}', [HomeController::class, 'actualizar_cor_fundo'])->name('web.actualizar-cor-fundo');

    ########################################## TABELA DE APOIO ############################################################
    Route::prefix('informacoes-escolares')->group(function () {

        Route::get('/informacao-geral', [EscolaController::class, 'informacaoGeraisEscolar'])->name('informacoes-escolares.index');
        Route::get('/informacao-editar/{id}', [EscolaController::class, 'informacaoGeraisEscolarEditar'])->name('informacoes-escolares.editar');
        Route::put('/informacao-update/{id}', [EscolaController::class, 'informacaoGeraisEscolarUpdate'])->name('informacoes-escolares.update');
        Route::get('/privacidade', [EscolaController::class, 'privacidade'])->name('informacoes-escolares.privacidade');
        Route::put('/privacidade-update/{id}', [EscolaController::class, 'privacidadeUpdate'])->name('informacoes-escolares.privacidade-update');
        Route::resource('configuracao-carto-funcionario', CartaoTemplateController::class);

        Route::resource('utilizadores-escola', UtilizadoresController::class);
        Route::post('/confirmar-redefinir-senha', [UtilizadoresController::class, 'confirmarRedefinicaoSenha'])->name('confirmar-redefinir-senha');


        ##################################################################################################################
        ######################################## SINCRONIZACAO ##############################################################
        ##################################################################################################################
        Route::get('/sincronizacao/configuracao', [SincronizarConfiguracao::class, 'configuracao'])->name('web.sincronizacao-configuracao');
        Route::post('/sincronizacao/configuracao', [SincronizarConfiguracao::class, 'configuracaoPost'])->name('web.sincronizacao-configuracao-post');
        Route::get('/sincronizacao/banco-dados', [SincronizarConfiguracao::class, 'bancoDados'])->name('web.sincronizacao-banco-dado');
        //catrtão dos estudantes
        Route::get('/sincronizacao/actualizar-calendario', [SincronizarConfiguracao::class, 'actualizar_calendario'])->name('web.sincronizacao-actualizar-calendario');
        Route::post('/sincronizacao/actualizar-calendario', [SincronizarConfiguracao::class, 'actualizarCalendarioPost'])->name('web.sincronizacao-actualizar-calendario-post');
    });

    Route::get('/enviar-boletins-encarregados', [NotificacaoAdminController::class, 'enviarBoletinsEncarregado'])->name('web.enviar-boletin-encarregado');
    Route::post('/enviar-boletins-encarregados', [NotificacaoAdminController::class, 'enviarBoletinsEncarregadoPost'])->name('web.enviar-boletin-encarregado-post');
    Route::get('/enviar-notificacao', [NotificacaoAdminController::class, 'enviarNotificacao'])->name('web.enviar-notificacao');
    Route::post('/enviar-notificacao', [NotificacaoAdminController::class, 'enviarNotificacaoPost'])->name('web.enviar-notificacao-post');
    Route::post('/enviar-notificacao-sms', [NotificacaoAdminController::class, 'enviarNotificacaoSmsPost'])->name('web.enviar-sms-post');
    Route::get('/entradas-notificacao', [NotificacaoAdminController::class, 'entradasNofificacao'])->name('web.entradas-notificao');
    Route::get('/enviadas-notificacao', [NotificacaoAdminController::class, 'enviadasNofificacao'])->name('web.enviadas-notificao');
    Route::get('/reciclagem-notificacao', [NotificacaoAdminController::class, 'reciclagemNofificacao'])->name('web.reciclagem-notificao');
    Route::get('/ler-notificacao/{id}', [NotificacaoAdminController::class, 'lerNotifacacao'])->name('web.ler-notificacao');

    // ESCOLAS AFILHARES
    Route::get('/escolas-afilhares/home', [EscolaFilharController::class, 'index'])->name('web.escolas-afilhares.index');
    Route::get('/escolas-afilhares/cadastrar', [EscolaFilharController::class, 'create'])->name('web.escolas-afilhares.create');
    Route::post('/escolas-afilhares/store', [EscolaFilharController::class, 'store'])->name('web.escolas-afilhares.store');
    Route::get('/escolas-afilhares/editar/{id}', [EscolaFilharController::class, 'edit'])->name('web.escolas-afilhares.edit');
    Route::get('/escolas-afilhares/show/{id}', [EscolaFilharController::class, 'show'])->name('web.escolas-afilhares.show');
    Route::put('/escolas-afilhares/editar/{id}', [EscolaFilharController::class, 'update'])->name('web.escolas-afilhares.update');
    Route::delete('/escolas-afilhares/excluir/{id}', [EscolaFilharController::class, 'delete'])->name('web.escolas-afilhares.delete');

    Route::get('/escolas-afilhares/estudantes/{id}', [EscolaFilharController::class, 'estudantes'])->name('web.escolas-afilhares.estudantes');
    Route::get('/escolas-afilhares/novo-estudantes/{id}', [EscolaFilharController::class, 'create_estudantes'])->name('web.escolas-afilhares.create-estudantes');
    Route::post('/escolas-afilhares/novo-estudantes/{id}', [EscolaFilharController::class, 'create_estudantes_store'])->name('web.escolas-afilhares.create-estudantes-store');

    // RUPES
    Route::get('/rupes/home', [ValidacaoRupeController::class, 'index'])->name('web.rupes.index');
    Route::get('/rupes/cadastrar', [ValidacaoRupeController::class, 'create'])->name('web.rupes.create');
    Route::post('/rupes/store', [ValidacaoRupeController::class, 'store'])->name('web.rupes.store');
    Route::get('/rupes/show/{id}', [ValidacaoRupeController::class, 'show'])->name('web.rupes.show');
    Route::get('/verificar-rupe-validados', [ValidacaoRupeController::class, 'verificar_rupe'])->name('web.verificar-rupe-validados');



    // WEB APP ANO LECTIVO GLOBAL
    Route::get('/ano-lectivo-global/home', [AnoLectivoGlobalController::class, 'anoLectivo'])->name('ano-lectivo-global');
    Route::get('/ano-lectivo-global/cadastrar', [AnoLectivoGlobalController::class, 'create'])->name('web.create-ano-lectivo-global');
    Route::post('/ano-lectivo-global/store', [AnoLectivoGlobalController::class, 'store'])->name('web.store-ano-lectivo-global');
    Route::get('/ano-lectivo-global/editar-ano-lectivo/{id}', [AnoLectivoGlobalController::class, 'editarAnoLectivo'])->name('web.edit-ano-lectivo-global');
    Route::put('/ano-lectivo-global/editar-ano-lectivo/{id}', [AnoLectivoGlobalController::class, 'updateAnoLectivo'])->name('web.update-ano-lectivo-global');
    Route::get('/ano-lectivo-global/activar-ano-lectivo/{id}', [AnoLectivoGlobalController::class, 'activarAnoLectivo'])->name('web.route-desactivar-ano-lectivo-global');
    Route::get('/ano-lectivo-global/imprimir', [AnoLectivoGlobalController::class, 'anoLectivoImprimir'])->name('ano-lectivo-imprmir-global');
    Route::get('/ano-lectivo-global/excel', [AnoLectivoGlobalController::class, 'anoLectivoExcel'])->name('ano-lectivo-excel-global');


    // WEB APP CONFIRGURAÇÃO DO ANO LECTIVO
    // cursos, classes, turnos, salas, disciplinas
    
    Route::get('/ano-lectivo/candidaturas-ano-lectivo', [AnoLectivoConfiguracaoController::class, 'candidaturasIndex'])->name('web.candidaturas-index-ano-lectivo');
    Route::get('/ano-lectivo/salas-ano-lectivo', [AnoLectivoConfiguracaoController::class, 'SalasIndex'])->name('web.salas-index-ano-lectivo');
    Route::get('/ano-lectivo/disciplinas-ano-lectivo', [AnoLectivoConfiguracaoController::class, 'disciplinasIndex'])->name('web.disciplinas-index-ano-lectivo');
    Route::get('/ano-lectivo/faculdades-ano-lectivo', [AnoLectivoConfiguracaoController::class, 'faculdadesIndex'])->name('web.faculdades-index-ano-lectivo');
    Route::delete('/ano-lectivo/disciplina-eliminar-ano-lectivo/{id}', [AnoLectivoConfiguracaoController::class, 'deleteDisciplina'])->name('web.disciplina-eliminar-ano-lectivo');
    Route::delete('/ano-lectivo/faculdade-eliminar-ano-lectivo/{id}', [AnoLectivoConfiguracaoController::class, 'deletefaculdade'])->name('web.faculdade-eliminar-ano-lectivo');
    Route::get('/ano-lectivo/candidaturas-eliminar-ano-lectivo/{id}', [AnoLectivoConfiguracaoController::class, 'deletecandidaturas'])->name('web.candidaturas-eliminar-ano-lectivo');
    Route::get('/ano-lectivo/disciplina-ano-lectivo-pdf', [AnoLectivoConfiguracaoController::class, 'DisciplinaPDF'])->name('web.disciplina-pdf-ano-lectivo');
    Route::get('/ano-lectivo/salas-ano-lectivo-pdf', [AnoLectivoConfiguracaoController::class, 'SalasPDF'])->name('web.salas-pdf-ano-lectivo');
    Route::get('/ano-lectivo/disciplina-ano-lectivo-excel', [AnoLectivoConfiguracaoController::class, 'DisciplinaExcel'])->name('web.disciplina-excel-ano-lectivo');
    Route::get('/ano-lectivo/salas-ano-lectivo-excel', [AnoLectivoConfiguracaoController::class, 'SalasExcel'])->name('web.salas-excel-ano-lectivo');
    Route::get('/ano-lectivo/candidaturas-ano-lectivo/{id}/editar', [AnoLectivoConfiguracaoController::class, 'candidaturasEdit'])->name('web.candidaturas-index-ano-lectivo-edit');
    Route::get('/ano-lectivo/disciplinas-ano-lectivo/{id}/editar', [AnoLectivoConfiguracaoController::class, 'disciplinasEdit'])->name('web.disciplinas-index-ano-lectivo-edit');
    Route::post('/ano-lectivo/actualizar-candidatura-ano-lectivo', [AnoLectivoConfiguracaoController::class, 'Updatecandidatura'])->name('web.candidatura-update-ano-lectivo');
    Route::post('/ano-lectivo/actualizar-salas-ano-lectivo', [AnoLectivoConfiguracaoController::class, 'UpdateSalas'])->name('web.salas-update-ano-lectivo');
    Route::post('/ano-lectivo/actualizar-disciplinas-ano-lectivo', [AnoLectivoConfiguracaoController::class, 'Updatedisciplinas'])->name('web.disciplinas-update-ano-lectivo');

    Route::get('/ano-lectivo/{id}/carregamento-tabelas-configuracoes', [AnoLectivoConfiguracaoController::class, 'carregamentoTabelasConfiguracoes'])->name('web.carregamento-configuracao-ano-lectivo');

    Route::post('/ano-lectivo/salas-ano-lectivo', [AnoLectivoConfiguracaoController::class, 'createSalas'])->name('web.cadastrar-salas-ano-lectivo');
    Route::post('/ano-lectivo/candidatura-ano-lectivo', [AnoLectivoConfiguracaoController::class, 'createcandidatura'])->name('web.cadastrar-candidatura-ano-lectivo');
    Route::post('/ano-lectivo/disciplinas-ano-lectivo', [AnoLectivoConfiguracaoController::class, 'createDisciplinas'])->name('web.cadastrar-disciplinas-ano-lectivo');
    Route::post('/ano-lectivo/faculdade-ano-lectivo', [AnoLectivoConfiguracaoController::class, 'createfaculdade'])->name('web.cadastrar-faculdade-ano-lectivo');


    Route::delete('/ano-lectivo/excluir-classes-ano-lectivo/{id}', [AnoLectivoConfiguracaoController::class, 'deleteClassesAnoLectivo'])->name('ano-lectivo.excluir-classes-ano-lectivo');
    Route::delete('/ano-lectivo/excluir-cursos-ano-lectivo/{id}', [AnoLectivoConfiguracaoController::class, 'deleteCursosAnoLectivo'])->name('ano-lectivo.excluir-cursos-ano-lectivo');
    Route::delete('/ano-lectivo/excluir-turnos-ano-lectivo/{id}', [AnoLectivoConfiguracaoController::class, 'deleteTurnosAnoLectivo'])->name('ano-lectivo.excluir-turnos-ano-lectivo');
    Route::delete('/ano-lectivo/excluir-salas-ano-lectivo/{id}', [AnoLectivoConfiguracaoController::class, 'deleteSalasAnoLectivo'])->name('ano-lectivo.excluir-salas-ano-lectivo');

    // APP TRUNOS
    Route::get('/turnos/home', [TurnoController::class, 'turnos'])->name('web.turnos');
    Route::post('/turnos/home', [TurnoController::class, 'cadastrarTurnos'])->name('web.cadastrar-turnos');
    Route::get('/turnos/editar-turno/{id}', [TurnoController::class, 'editarTurnos']);
    Route::put('/turnos/editar-turno/{id}', [TurnoController::class, 'updateTurnos']);
    Route::delete('/turnos/excluir-turnos/{id}', [TurnoController::class, 'deleteTurnos']);
    Route::get('/turnos/activar-turnos/{id}', [TurnoController::class, 'activarTurnos']);
    Route::get('/turnos/imprimir', [TurnoController::class, 'turnosImprimir'])->name('turnos-imprmir');
    Route::get('/turnos/excel', [TurnoController::class, 'turnosExcel'])->name('turnos-excel');


    // APP INSTITUIÇÕES GESTÃO ESTAGIO
    Route::prefix('instituicoes_estagios')->group(function () {
        Route::get('home', [InstituicaoEstagioController::class, 'index'])->name('instituicoes_estagios.instituicao-estagio');
        Route::get('create', [InstituicaoEstagioController::class, 'create'])->name('instituicoes_estagios.cadastrar-instituicao-estagios');
        Route::post('home', [InstituicaoEstagioController::class, 'store'])->name('instituicoes_estagios.cadastrar-instituicao-store-estagios');
        Route::get('editar-instituicao/{id}', [InstituicaoEstagioController::class, 'edit'])->name('instituicoes_estagios.editar-instituicao-estagio');
        Route::put('editar-instituicao/{id}', [InstituicaoEstagioController::class, 'update'])->name('instituicoes_estagios.update-instituicao-estagio');
        Route::get('excluir-instituicao/{id}', [InstituicaoEstagioController::class, 'destroy'])->name('instituicoes_estagios.delete-instituicao-estagio');
        Route::get('show-instituicao/{id}', [InstituicaoEstagioController::class, 'show'])->name('instituicoes_estagios.show-instituicao-estagio');
        Route::get('/instituicao-estagio', [InstituicaoEstagioController::class, 'instituicao_estagio'])->name('instituicoes_estagios.instituicoes-estagios');
        Route::get('associar-estagios/{id?}', [InstituicaoEstagioController::class, 'associar_estagio'])->name('instituicoes_estagios.associar-estagio');
        Route::post('associar-estagios/{id?}', [InstituicaoEstagioController::class, 'associar_estagio_store'])->name('instituicoes_estagios.associar-estagio-store');
        Route::get('associar-estagio-editar/{id?}', [InstituicaoEstagioController::class, 'associar_estagio_editar'])->name('instituicoes_estagios.associar-estagio-editar');
        Route::put('associar-estagio-editar/{id}', [InstituicaoEstagioController::class, 'associar_estagio_update'])->name('creditos-educacionais.associar-estagio-update');
        Route::get('associar-estagio-delete/{id}', [InstituicaoEstagioController::class, 'associar_estagio_delete'])->name('instituicoes_estagios.associar-estagio-delete');
        Route::get('listar-estagiarios', [InstituicaoEstagioController::class, 'instituicao_listar_estagiarios'])->name('instituicoes_estagios.instituicao-listar-estagiarios');
        Route::get('remover-estagio-estagiario/{id}', [InstituicaoEstagioController::class, 'instituicao_remover_estagio_estagiario'])->name('instituicoes_estagios.instituicao-remover-estagio-estagiario');

        // APP TIPOS DE ESTAGIOS
        Route::get('/tipo-estagio/home', [TipoEstagioController::class, 'index'])->name('instituicoes_estagios.tipo-estagio');
        Route::get('/tipo-estagio/create', [TipoEstagioController::class, 'create'])->name('instituicoes_estagios.cadastrar-tipo-estagio');
        Route::post('/tipo-estagio/home', [TipoEstagioController::class, 'store'])->name('instituicoes_estagios.cadastrar-tipo-estagio-store');
        Route::get('/tipo-estagio/editar-bolsa/{id}', [TipoEstagioController::class, 'edit'])->name('instituicoes_estagios.editar-tipo-estagio');
        Route::put('/tipo-estagio/editar-bolsa/{id}', [TipoEstagioController::class, 'update'])->name('instituicoes_estagios.update-tipo-estagio');
        Route::get('/tipo-estagio/excluir-tipo-estagio/{id}', [TipoEstagioController::class, 'destroy'])->name('instituicoes_estagios.delete-tipo-estagio');
        Route::get('/tipo-estagio/show-tipo-estagio/{id}', [TipoEstagioController::class, 'show'])->name('instituicoes_estagios.show-tipo-estagio');
        Route::get('/tipo-estagio/imprimir', [TipoEstagioController::class, 'tipoEstagioImprimir'])->name('instituicoes_estagios.tipo-estagio-imprmir');
        Route::get('/tipo-estagio/excel', [TipoEstagioController::class, 'tipoEstagioExcel'])->name('instituicoes_estagios.tipo-estagio-excel');
    });

    // Route::get('/instituicao/estagio', [InstituicaoController::class, 'instituicao_bolsa'])->name('web.instituicao-estagios');

    Route::prefix('descontos-estudantes')->group(function () {
    
    });

    // APP INSTITUICAO
    ########################################## PAINES ############################################################
    Route::prefix('creditos-educacionais')->group(function () {
  
    });


    // APP Provincas
    Route::get('/provincias/home', [ProvinciaController::class, 'index'])->name('web.provincias');
    Route::post('/provincias/home', [ProvinciaController::class, 'store'])->name('web.cadastrar-provincias');
    Route::get('/provincias/editar-provincia/{id}', [ProvinciaController::class, 'edit']);
    Route::put('/provincias/editar-provincia/{id}', [ProvinciaController::class, 'update']);
    Route::delete('/provincias/excluir-provincias/{id}', [ProvinciaController::class, 'destroy']);
    Route::get('/provincias/activar-provincias/{id}', [ProvinciaController::class, 'activarProvincias']);
    Route::get('/provincias/imprimir', [ProvinciaController::class, 'ProvinciasImprimir'])->name('provincias-imprmir');
    Route::get('/provincias/excel', [ProvinciaController::class, 'ProvinciasExcel'])->name('provincias-excel');

    // ENSINOS
    Route::get('/ensinos/home', [EnsinoController::class, 'index'])->name('web.ensinos');
    Route::post('/ensinos/home', [EnsinoController::class, 'store'])->name('web.cadastrar-ensinos');
    Route::get('/ensinos/editar-ensino/{id}', [EnsinoController::class, 'edit']);
    Route::put('/ensinos/editar-ensino/{id}', [EnsinoController::class, 'update']);
    Route::delete('/ensinos/excluir-ensinos/{id}', [EnsinoController::class, 'destroy']);
    Route::get('/ensinos/activar-ensinos/{id}', [EnsinoController::class, 'activarensinos']);
    Route::get('/ensinos/imprimir', [EnsinoController::class, 'ensinosImprimir'])->name('ensinos-imprmir');
    Route::get('/ensinos/excel', [EnsinoController::class, 'ensinosExcel'])->name('ensinos-excel');

    Route::get('/ensinos-classes/home', [EnsinoClasseController::class, 'index'])->name('web.ensinos-classes');
    Route::post('/ensinos-classes/home', [EnsinoClasseController::class, 'store'])->name('web.cadastrar-ensinos-classes');
    Route::get('/ensinos-classes/editar-ensino-classes/{id}', [EnsinoClasseController::class, 'edit']);
    Route::put('/ensinos-classes/editar-ensino-classe/{id}', [EnsinoClasseController::class, 'update']);
    Route::delete('/ensinos-classes/excluir-ensinos-classes/{id}', [EnsinoClasseController::class, 'destroy']);
    Route::get('/ensinos-classes/activar-ensinos-classes/{id}', [EnsinoClasseController::class, 'activarensinos']);
    Route::get('/ensinos-classes/imprimir', [EnsinoClasseController::class, 'ensinosImprimir'])->name('ensinos-classes-imprmir');
    Route::get('/ensinos-classes/excel', [EnsinoClasseController::class, 'ensinosExcel'])->name('ensinos-classes-excel');



    // CATEGORAS
    Route::get('/laboratorios/home', [LaboratorioController::class, 'index'])->name('web.laboratorios');
    Route::post('/laboratorios/home', [LaboratorioController::class, 'store'])->name('web.cadastrar-laboratorios');
    Route::get('/laboratorios/editar-laboratorio/{id}', [LaboratorioController::class, 'edit']);
    Route::put('/laboratorios/editar-laboratorio/{id}', [LaboratorioController::class, 'update']);
    Route::delete('/laboratorios/excluir-laboratorios/{id}', [LaboratorioController::class, 'destroy']);
    Route::get('/laboratorios/activar-laboratorios/{id}', [LaboratorioController::class, 'activarlaboratorios']);
    Route::get('/laboratorios/imprimir', [LaboratorioController::class, 'laboratoriosImprimir'])->name('laboratorios-imprmir');
    Route::get('/laboratorios/excel', [LaboratorioController::class, 'laboratoriosExcel'])->name('laboratorios-excel');

    Route::get('/laboratorios-escolas/home', [LaboratorioController::class, 'laboratorioIndex'])->name('web.laboratorios-escolas');
    Route::get('/laboratorios-escolas/eliminar/{id}', [LaboratorioController::class, 'deletelaboratorio'])->name('web.laboratorios-escolas-eliminar');
    Route::get('/laboratorios-escolas/pdf', [LaboratorioController::class, 'laboratorioPDF'])->name('web.laboratorios-escolas-pdf');
    Route::get('/laboratorios-escolas/excel', [LaboratorioController::class, 'laboratorioExcel'])->name('web.laboratorios-escolas-excel');
    Route::get('/laboratorios-escolas/{id}/editar', [LaboratorioController::class, 'laboratorioEdit'])->name('web.laboratorios-escolas-edit');
    Route::post('/laboratorios-escolas/update', [LaboratorioController::class, 'laboratoriodisciplinas'])->name('web.laboratorios-escolas-update');
    Route::post('/laboratorios-escolas/store', [LaboratorioController::class, 'createlaboratorio'])->name('web.laboratorios-escolas-cadastrar');


    // APP Municipio Portal Provincial
    Route::get('/municipios-provincial/home', [MunicipioPortalProvinciaController::class, 'index'])->name('web.municipios-provincial');
    Route::post('/municipios-provincial/home', [MunicipioPortalProvinciaController::class, 'store'])->name('web.cadastrar-municipios-provincial');
    Route::get('/municipios-provincial/editar-municipio/{id}', [MunicipioPortalProvinciaController::class, 'edit']);
    Route::put('/municipios-provincial/editar-municipio/{id}', [MunicipioPortalProvinciaController::class, 'update']);
    Route::delete('/municipios-provincial/excluir-municipios/{id}', [MunicipioPortalProvinciaController::class, 'destroy']);
    Route::get('/municipios-provincial/activar-municipios/{id}', [MunicipioPortalProvinciaController::class, 'activarMunicipios']);
    Route::get('/municipios-provincial/imprimir', [MunicipioPortalProvinciaController::class, 'municipiosImprimir'])->name('municipios-provincial-imprmir');
    Route::get('/municipios-provincial/excel', [MunicipioPortalProvinciaController::class, 'municipiosExcel'])->name('municipios-provincial-excel');

    // APP Municipio Portal Municipio
    Route::get('/municipios/home', [MunicipioController::class, 'index'])->name('web.municipios');
    Route::post('/municipios/home', [MunicipioController::class, 'store'])->name('web.cadastrar-municipios');
    Route::get('/municipios/editar-municipio/{id}', [MunicipioController::class, 'edit']);
    Route::put('/municipios/editar-municipio/{id}', [MunicipioController::class, 'update']);
    Route::delete('/municipios/excluir-municipios/{id}', [MunicipioController::class, 'destroy']);
    Route::get('/municipios/activar-municipios/{id}', [MunicipioController::class, 'activarMunicipios']);
    Route::get('/municipios/imprimir', [MunicipioController::class, 'municipiosImprimir'])->name('municipios-imprmir');
    Route::get('/municipios/excel', [MunicipioController::class, 'municipiosExcel'])->name('municipios-excel');

    // APP Distrito Portal Provincial
    Route::get('/distrito-provincial/home', [DistritoPortalProvinciaController::class, 'index'])->name('web.distrito-provincial');
    Route::post('/distrito-provincial/home', [DistritoPortalProvinciaController::class, 'store'])->name('web.cadastrar-provincial-distrito');
    Route::get('/distrito-provincial/editar-distrito/{id}', [DistritoPortalProvinciaController::class, 'edit']);
    Route::put('/distrito-provincial/editar-distrito/{id}', [DistritoPortalProvinciaController::class, 'update']);
    Route::delete('/distrito-provincial/excluir-distrito/{id}', [DistritoPortalProvinciaController::class, 'destroy']);
    Route::get('/distrito-provincial/activar-distrito/{id}', [DistritoPortalProvinciaController::class, 'activardistrito']);
    Route::get('/distrito-provincial/imprimir', [DistritoPortalProvinciaController::class, 'distritoImprimir'])->name('distrito-provincial-imprmir');
    Route::get('/distrito-provincial/excel', [DistritoPortalProvinciaController::class, 'distritoExcel'])->name('distrito-provincial-excel');

    // APP Distrito Portal Municipio
    Route::get('/distrito/home', [DistritoController::class, 'index'])->name('web.distrito');
    Route::post('/distrito/home', [DistritoController::class, 'store'])->name('web.cadastrar-distrito');
    Route::get('/distrito/editar-distrito/{id}', [DistritoController::class, 'edit']);
    Route::put('/distrito/editar-distrito/{id}', [DistritoController::class, 'update']);
    Route::delete('/distrito/excluir-distrito/{id}', [DistritoController::class, 'destroy']);
    Route::get('/distrito/activar-distrito/{id}', [DistritoController::class, 'activardistrito']);
    Route::get('/distrito/imprimir', [DistritoController::class, 'distritoImprimir'])->name('distrito-imprmir');
    Route::get('/distrito/excel', [DistritoController::class, 'distritoExcel'])->name('distrito-excel');


    // APP CLASSES
    Route::get('/classes/home', [ClasseController::class, 'classes'])->name('web.classes');
    Route::post('/classes/home', [ClasseController::class, 'cadastrarClasses'])->name('web.cadastrar-classes');
    Route::get('/classes/editar-classes/{id}', [ClasseController::class, 'editarClasses']);
    Route::put('/classes/editar-classes/{id}', [ClasseController::class, 'updateClasses']);
    Route::delete('/classes/excluir-classes/{id}', [ClasseController::class, 'deleteClasses']);
    Route::get('/classes/activar-classes/{id}', [ClasseController::class, 'activarClasses']);
    Route::get('/classes/imprimir', [ClasseController::class, 'classesImprimir'])->name('classes-imprmir');
    Route::get('/classes/excel', [ClasseController::class, 'classesExcel'])->name('classes-excel');


    Route::resource('notificacoes', NotificacaoController::class);
    Route::get('/visualizar-solicitacoes', [NotificacaoController::class, 'solicitacoes'])->name('web.solicitacoes-dos-professores');
    Route::put('/visualizar-solicitacoes/{id}', [NotificacaoController::class, 'solicitacoesResposta'])->name('web.solicitacoes-dos-professores-resposta');
    Route::get('/transferincias-professores-pela-direccao', [NotificacaoController::class, 'transferenciaProfessoresDireccao'])->name('web.transferincias-professores-pela-direccao');
    Route::get('/transferincias-professores-pela-direccao/{id}/aprovacao-escola', [NotificacaoController::class, 'transferenciaProfessoresDireccaoAprovacaoEscola'])->name('web.transferincias-professores-pela-direccao-aprovacao-escola');

    Route::resource('solicitacao-documentos', SolicitacaoDocumentoController::class);
    Route::resource('direccoes-provincias', DireccaoProvinciaController::class);
    Route::resource('direccoes-municipais', DireccaoMunicipalController::class);
    Route::resource('logisticas', LogisticaController::class);
    Route::get('/stock-mercadorias/home', [LogisticaController::class, 'stock'])->name('web.stock-mercadorias');

    Route::get('/stock-mercadorias/cadastrar', [LogisticaController::class, 'stock_create'])->name('web.stock-mercadorias-create');
    Route::post('/stock-mercadorias/cadastrar', [LogisticaController::class, 'stock_post'])->name('web.stock-mercadorias-post');
    Route::get('/stock-mercadorias/{id}/editar', [LogisticaController::class, 'stock_edit'])->name('web.stock-mercadorias-edit');


    Route::get('/stock-mercadorias/distribuicao', [LogisticaController::class, 'stock_distribuicao'])->name('web.stock-mercadorias-distribuicao');
    Route::post('/stock-mercadorias/distribuicao', [LogisticaController::class, 'stock_distribuicao_post'])->name('web.stock-mercadorias-distribuica-post');
    Route::get('/stock-mercadorias/{id}/distribuicao', [LogisticaController::class, 'stock_distribuicao_edit'])->name('web.stock-mercadorias-distribuicao-edit');

    // WEB TIPO LOGIGISTICA MINISTERIO
    Route::get('/tipos-mercadorias/home', [TipoMercadoriasController::class, 'index'])->name('web.tipos-mercadorias');
    Route::get('/tipos-mercadorias/cadastrar', [TipoMercadoriasController::class, 'create'])->name('web.create-tipos-mercadorias');
    Route::post('/tipos-mercadorias/store', [TipoMercadoriasController::class, 'store'])->name('web.store-tipos-mercadorias');
    Route::get('/tipos-mercadorias/editar/{id}', [TipoMercadoriasController::class, 'edit'])->name('web.edit-tipos-mercadorias');
    Route::put('/tipos-mercadorias/editar/{id}', [TipoMercadoriasController::class, 'update'])->name('web.update-tipos-mercadorias');
    Route::get('/tipos-mercadorias/delete/{id}', [TipoMercadoriasController::class, 'delete'])->name('web.delete-tipos-mercadorias');
    Route::get('/tipos-mercadorias/imprimir', [TipoMercadoriasController::class, 'Imprimir'])->name('web.tipos-mercadorias-pdf');
    Route::get('/tipos-mercadorias/excel', [TipoMercadoriasController::class, 'excel'])->name('web.tipos-mercadorias-excel');

    // WEB TIPO LOGIGISTICA MINISTERIO
    Route::get('/mercadorias/home', [MercadoriaController::class, 'index'])->name('web.mercadorias');
    Route::get('/mercadorias/cadastrar', [MercadoriaController::class, 'create'])->name('web.create-mercadorias');
    Route::post('/mercadorias/store', [MercadoriaController::class, 'store'])->name('web.store-mercadorias');
    Route::get('/mercadorias/editar/{id}', [MercadoriaController::class, 'edit'])->name('web.edit-mercadorias');
    Route::put('/mercadorias/editar/{id}', [MercadoriaController::class, 'update'])->name('web.update-mercadorias');
    Route::get('/mercadorias/delete/{id}', [MercadoriaController::class, 'delete'])->name('web.delete-mercadorias');
    Route::get('/mercadorias/imprimir', [MercadoriaController::class, 'Imprimir'])->name('web.mercadorias-pdf');
    Route::get('/mercadorias/excel', [MercadoriaController::class, 'excel'])->name('web.mercadorias-excel');

    // WEB TIPO LOGIGISTICA MINISTERIO
    Route::get('/fornecedores/home', [FornecedorController::class, 'index'])->name('web.fornecedores');
    Route::get('/fornecedores/cadastrar', [FornecedorController::class, 'create'])->name('web.create-fornecedores');
    Route::post('/fornecedores/store', [FornecedorController::class, 'store'])->name('web.store-fornecedores');
    Route::get('/fornecedores/editar/{id}', [FornecedorController::class, 'edit'])->name('web.edit-fornecedores');
    Route::put('/fornecedores/editar/{id}', [FornecedorController::class, 'update'])->name('web.update-fornecedores');
    Route::get('/fornecedores/delete/{id}', [FornecedorController::class, 'delete'])->name('web.delete-fornecedores');
    Route::get('/fornecedores/imprimir', [FornecedorController::class, 'Imprimir'])->name('web.fornecedores-pdf');
    Route::get('/fornecedores/excel', [FornecedorController::class, 'excel'])->name('web.fornecedores-excel');

    /**
    * GESTÃO DAS SALAS DE AULA
    */
    Route::get('/salas-home', [SalaController::class, 'home'])->name('web.salas');
    Route::get('/salas-export', [SalaController::class, 'export'])->name('salas-export');
    Route::resource('/salas', SalaController::class);
    
    // GESTÃO DOS ANOS LECTIVOS
    Route::get('/ano-lectivo/home', [AnoLectivoController::class, 'home'])->name('web.ano-lectivo');
    Route::get('/ano-lectivo/{id}/actualizar-status', [AnoLectivoController::class, 'actualizarStatus'])->name('web.actualizar-status');
    Route::get('/anos-lectivos-export', [AnoLectivoController::class, 'export'])->name('anos-lectivos-export');
    Route::resource('/anos-lectivos', AnoLectivoController::class);
    
    Route::get('/ano-lectivo/classe', [AnoLectivoClasseController::class, 'home'])->name('web.ano-lectivo-classes');
    Route::resource('/ano-lectivo-classes', AnoLectivoClasseController::class);
    Route::get('/ano-lectivo/classes-ano-lectivo-export', [AnoLectivoClasseController::class, 'export'])->name('web.classes-ano-lectivo-export');
    
    Route::get('/ano-lectivo/turnos', [AnoLectivoTurnoController::class, 'home'])->name('web.ano-lectivo-turnos');
    Route::resource('/ano-lectivo-turnos', AnoLectivoTurnoController::class);
    Route::get('/ano-lectivo/turnos-ano-lectivo-export', [AnoLectivoTurnoController::class, 'export'])->name('web.turnos-ano-lectivo-export');
        
    Route::get('/ano-lectivo/cursos', [AnoLectivoCursoController::class, 'home'])->name('web.ano-lectivo-cursos');
    Route::resource('/ano-lectivo-cursos', AnoLectivoCursoController::class);
    Route::get('/ano-lectivo/cursos-ano-lectivo-export', [AnoLectivoCursoController::class, 'export'])->name('web.cursos-ano-lectivo-export');
    
    // GESTÃO DOS CAIXAS
    Route::get('/caixas-home', [CaixaController::class, 'home'])->name('web.caixas');
    Route::resource('caixas', CaixaController::class);
    Route::get('/caixas-export', [CaixaController::class, 'export'])->name('web.caixas-export');

    // GESTÃO BANCO
    Route::get('/bancos-home', [BancoController::class, 'home'])->name('web.bancos');
    Route::resource('bancos', BancoController::class);
    Route::get('/bancos-export', [BancoController::class, 'export'])->name('web.bancos-export');
    
    // GESTÃO ESCOLARIDADE
    Route::get('/escolaridades-home', [EscolaridadeController::class, 'home'])->name('web.escolaridades');
    Route::resource('escolaridades', EscolaridadeController::class);
    Route::get('/escolaridades-export', [EscolaridadeController::class, 'export'])->name('web.escolaridades-export');

    // GESTÃO DEPARTAMENTOS
    Route::get('/formacao-academico-home', [FormacaoAcademicoController::class, 'home'])->name('web.formacao-academico');
    Route::resource('formacao-academico', FormacaoAcademicoController::class);
    Route::get('/formacao-academico-export', [FormacaoAcademicoController::class, 'export'])->name('web.formacao-academico-export');

    // GESTÃO ESTENSOES OU SIGLAS
    Route::get('/extensao-home', [ExtensaoController::class, 'home'])->name('web.extensao');
    Route::resource('extensao', ExtensaoController::class);
    Route::get('/extensao-export', [ExtensaoController::class, 'export'])->name('web.extensao-export');
    
    // GESTÃO ESPECIALIDADES
    Route::get('/especialidades-home', [EspecialidadeController::class, 'home'])->name('web.especialidades');
    Route::resource('especialidades', EspecialidadeController::class);
    Route::get('/especialidades-export', [EspecialidadeController::class, 'export'])->name('web.especialidades-export');
    
    // GESTÃO CATEGORAS
    Route::get('/categorias-home', [CategoriaController::class, 'home'])->name('web.categorias');
    Route::resource('categorias', CategoriaController::class);
    Route::get('/categorias-export', [CategoriaController::class, 'export'])->name('web.categorias-export');
    
    // GESTÃO UNIVERSIDADES
    Route::get('/universidades-home', [UniversidadeController::class, 'home'])->name('web.universidades');
    Route::resource('universidades', UniversidadeController::class);
    Route::get('/universidades-export', [UniversidadeController::class, 'export'])->name('web.universidades-export');
    
    // GESTÃO FACULDADES
    Route::get('/faculdades-home', [FaculdadeController::class, 'home'])->name('web.faculdades');
    Route::resource('faculdades', FaculdadeController::class);
    Route::get('/faculdades-export', [FaculdadeController::class, 'export'])->name('web.faculdades-export');
    
    // GESTÃO TIPOS DE DESCONTOS
    Route::get('/descontos-home', [DescontoController::class, 'home'])->name('web.descontos');
    Route::resource('descontos', DescontoController::class);
    Route::get('/descontos-export', [DescontoController::class, 'export'])->name('web.descontos-export');
    
    Route::get('/estudantes/descontos-home', [EstudanteDescontoController::class, 'home'])->name('web.estudantes-descontos');
    Route::resource('estudantes-descontos', EstudanteDescontoController::class);
    Route::get('/estudantes-descontos-export', [EstudanteDescontoController::class, 'export'])->name('web.estudantes-descontos-export');    
    
    
    // GESTÃO BOLSA E INSTITUIÇÃO
    Route::get('/bolsas-home', [BolsaController::class, 'home'])->name('web.bolsas');
    Route::resource('bolsas', BolsaController::class);
    Route::get('/bolsas-export', [BolsaController::class, 'export'])->name('web.bolsas-export');
    
    Route::get('/instituicoes-home', [InstituicaoController::class, 'home'])->name('web.instituicoes');
    Route::resource('instituicoes', InstituicaoController::class);
    Route::get('/instituicoes-export', [InstituicaoController::class, 'export'])->name('web.instituicoes-export');
    
    Route::delete('associar-bolsas-delete/{id}', [InstituicaoController::class, 'delete_bolsa_associada'])->name('web.instituicoes-delete');
    Route::post('associar-bolsas-delete', [InstituicaoController::class, 'store_bolsa_associada'])->name('web.instituicoes-store');
    
    Route::get('/bolseiros-home', [BolseiroController::class, 'home'])->name('web.bolseiros');
    Route::resource('bolseiros', BolseiroController::class);
    Route::get('/bolseiros-export', [BolseiroController::class, 'export'])->name('web.bolseiros-export');
            
    // GESTÃO FINANCEIRA
    
    Route::get('dashboard', [FinanceiroController::class, 'indexPagamento'])->name('financeiros.financeiro-novos-pagamentos');
    
    Route::get('contas-pagar-home', [ContaPagarController::class, 'home'])->name('home.contas-pagar');
    Route::resource('contas-pagar', ContaPagarController::class);
    Route::get('/contas-pagar-export', [ContaPagarController::class, 'export'])->name('web.contas-pagar-export');
    
    Route::get('contas-receber-home', [ContaReceberController::class, 'home'])->name('home.contas-receber');
    Route::resource('contas-receber', ContaReceberController::class);
    Route::get('/contas-receber-export', [ContaReceberController::class, 'export'])->name('web.contas-receber-export');
    
    Route::get('gestao-dividas', [GestaoDividaController::class, 'home'])->name('home.gestao-dividas');
    Route::resource('dividas', GestaoDividaController::class);
    Route::get('/dividas-mudar-estado/{id}/{status}', [GestaoDividaController::class, 'mudar_status'])->name('web.dividas-mudar-estado');
    Route::get('/dividas-export', [GestaoDividaController::class, 'export'])->name('web.dividas-export');
    
    Route::get('isencoes-home', [IsencaoServicoController::class, 'home'])->name('home.isencoes');
    Route::resource('isencoes', IsencaoServicoController::class);
    
    
    // GESTÃO CANDIDATURAS
    Route::get('/candidaturas/home', [CandidaturaAcademicaController::class, 'candidaturas'])->name('web.candidaturas');
    Route::post('/candidaturas/home', [CandidaturaAcademicaController::class, 'cadastrarcandidaturas'])->name('web.cadastrar-candidaturas');
    Route::get('/candidaturas/editar-candidaturas/{id}', [CandidaturaAcademicaController::class, 'editarcandidaturas']);
    Route::put('/candidaturas/editar-candidaturas/{id}', [CandidaturaAcademicaController::class, 'updatecandidaturas']);
    Route::delete('/candidaturas/excluir-candidaturas/{id}', [CandidaturaAcademicaController::class, 'deletecandidaturas']);
    Route::get('/candidaturas/imprimir', [CandidaturaAcademicaController::class, 'candidaturasImprimir'])->name('candidaturas-imprmir');
    Route::get('/candidaturas/excel', [CandidaturaAcademicaController::class, 'ExcelImprimir'])->name('candidaturas-excel');
   
    
    ########################################## OPERAÇÕES DO CAIXA ############################################################
    Route::prefix('operacoes-caixas')->group(function () {
        Route::get('abertura', [MovimentoCaixaController::class, 'abertura'])->name('operacoes-caixas.abertura');
        Route::post('abertura', [MovimentoCaixaController::class, 'aberturacaixas'])->name('operacoes-caixas.abertura-caixas');
        Route::get('fechamento', [MovimentoCaixaController::class, 'fechamento'])->name('operacoes-caixas.fechamento');
        Route::post('fechamento', [MovimentoCaixaController::class, 'fechamentocaixas'])->name('operacoes-caixas.fechamento-caixas');
        Route::get('movimentos-caixa', [MovimentoCaixaController::class, 'movimentoscaixas'])->name('operacoes-caixas.movimentos-caixas');
        Route::get('movimentos-caixa-outro', [MovimentoCaixaController::class, 'movimentoscaixasOutro'])->name('operacoes-caixas.movimentos-caixas-outro');
        Route::get('reeniciar-caixas', [MovimentoCaixaController::class, 'reeniciarCaixas'])->name('operacoes-caixas.reeniciar-caixas');
        Route::get('retirar-valores-caixa/{id?}', [MovimentoCaixaController::class, 'retirarValoresCaixa'])->name('operacoes-caixas.retirar-valores-caixa');
        Route::post('retirar-valores-caixa', [MovimentoCaixaController::class, 'retirarValoresCaixaPost'])->name('operacoes-caixas.retirar-valores-caixa-post');
        Route::get('imprimir-movimento-caixa/{id?}', [MovimentoCaixaController::class, 'imprimirMovimentoCaixa'])->name('operacoes-caixas.imprimir-movimento-caixa');
        Route::get('imprimir', [MovimentoCaixaController::class, 'imprimir'])->name('operacoes-caixas.imprimir-movimento-caixa2');

        Route::get('tesourarias', [TesourariaController::class, 'index'])->name('tesourarias.index');
    });

    ########################################## TESOURARIA ############################################################

    ########################################## BIBLIOTECA ############################################################
    Route::prefix('biblioteca')->group(function () {

        Route::get('biblioteca', [BibliotecaController::class, 'controle'])->name('biblioteca.controle');
        Route::resource('genero-livros', GeneroLivroController::class);
        Route::resource('tipos-materiais', TipoMaterialController::class);
        Route::resource('editoras', EditoraController::class);
        Route::resource('autores', AutoresController::class);
        Route::resource('livros', LivrosController::class);
        Route::resource('emprestimo-livros', EmprestimoLivroController::class);
        Route::resource('devolucoes-emprestimo-livros', DevolucaoEmprestimoLivroController::class);
    });
    ########################################## GESTÃO PERMISSOES ############################################################
    Route::prefix('gestao-permissoes')->group(function () {

        Route::resource('/permissions-escola', PermissionEscolaController::class);
        Route::get('/permissions-escola/{id}/delete', [PermissionEscolaController::class, 'destroy'])->name('app.permissions-escola.delete');
        Route::resource('/roles-escola', RoleEscolaController::class);
        Route::get('/roles-escola/{id}/delete', [RoleEscolaController::class, 'destroy'])->name('app.roles-escola.delete');
    });


    ########################################## OPERAÇÕES DO CAIXA ############################################################
    Route::prefix('ficheiros-safts')->group(function () {
        Route::get('criar-saft', [GerarSaftController::class, 'create'])->name('ficheiros-safts.create');
        Route::post('criar-store', [GerarSaftController::class, 'store'])->name('ficheiros-safts.store');
        Route::get('refazer-saft', [GerarSaftController::class, 'refazer'])->name('ficheiros-safts.refazer');
        Route::post('refazer-saft', [GerarSaftController::class, 'refazer_saft'])->name('ficheiros-safts.refazer-store');
    });


    // APP MOVIMENTOS BANCOS
    Route::get('/movimento-bancos/abertura', [MovimentoBancoController::class, 'index'])->name('web.abertura-banco');
    Route::post('/movimento-bancos/abertura', [MovimentoBancoController::class, 'store'])->name('web.abertura-bancos');
    Route::get('/movimento-bancos/fechamento', [MovimentoBancoController::class, 'fechamento'])->name('web.fechamento-banco');
    Route::post('/movimento-bancos/fechamento', [MovimentoBancoController::class, 'fechamentobanco'])->name('web.fechamento-bancos-store');
    Route::get('/movimento-bancos/movimentos-banco', [MovimentoBancoController::class, 'movimentosBancos'])->name('web.movimentos-bancos');
    Route::get('/movimento-bancos/movimentos-bancos-outro', [MovimentoBancoController::class, 'movimentosBancosOutro'])->name('web.movimentos-bancos-outro');
    Route::get('/movimento-bancos/retirar-valores-banco/{id?}', [MovimentoBancoController::class, 'retirarValoresBanco'])->name('web.retirar-valores-banco');
    Route::post('/movimento-bancos/retirar-valores-banco', [MovimentoBancoController::class, 'retirarValoresBancoPost'])->name('web.retirar-valores-banco-post');
    Route::get('/movimento-bancos/imprimir-movimento-banco/{id?}', [MovimentoBancoController::class, 'imprimirMovimentoBanco'])->name('web.imprimir-movimento-banco');
    Route::get('/movimento-bancos/imprimir', [MovimentoBancoController::class, 'imprimir'])->name('web.imprimir-movimento-banco2');



    // APP DISICPLINAS
    Route::get('/disciplinas/home', [DisciplinaController::class, 'disciplinas'])->name('web.disciplinas');
    Route::post('/disciplinas/home', [DisciplinaController::class, 'cadastrarDisciplinas'])->name('web.cadastrar-disciplinas');
    Route::get('/disciplinas/editar-disciplinas/{id}', [DisciplinaController::class, 'editarDisciplinas']);
    Route::put('/disciplinas/editar-disciplinas/{id}', [DisciplinaController::class, 'updateDisciplinas']);
    Route::delete('/disciplinas/excluir-disciplinas/{id}', [DisciplinaController::class, 'deleteDisciplinas']);
    Route::get('/disciplinas/imprimir', [DisciplinaController::class, 'disciplinasImprimir'])->name('disciplinas-imprmir');
    Route::get('/disciplinas/excel', [DisciplinaController::class, 'ExcelImprimir'])->name('disciplinas-excel');




    // APP CURSOS
    Route::get('/cursos/home', [CursoController::class, 'cursos'])->name('web.cursos');
    Route::post('/cursos/home', [CursoController::class, 'cadastrarCursos'])->name('web.cadastrar-cursos');
    Route::get('/cursos/editar-curso/{id}', [CursoController::class, 'editarCursos']);
    Route::put('/cursos/editar-curso/{id}', [CursoController::class, 'updateCursos']);
    Route::delete('/cursos/excluir-cursos/{id}', [CursoController::class, 'deleteCursos']);
    Route::get('/cursos/activar-cursos/{id}', [CursoController::class, 'activarCursos']);
    Route::post('/cursos/disciplinas', [CursoController::class, 'cadastrarDisciplinasCursos'])->name('web.cadastrar-disciplinas-cursos');
    Route::get('/cursos/carregar-disciplinas-cursos/{id}', [CursoController::class, 'carregarDisciplinasCursoActivo'])->name('web.carregar-disciplinas-cursos');
    Route::delete('/cursos/excluir-disciplina-cursos/{id}', [CursoController::class, 'deleteDisciplinaCursos'])->name('web.delete-disciplina-cursos');
    Route::get('/cursos/editar-disciplina-cursos/{id}', [CursoController::class, 'editarDisciplinaCursos']);
    Route::put('/cursos/editar-disciplinas-cursos/{id}', [CursoController::class, 'updateDisciplinasCursos'])->name('web.update-disciplinas-cursos');
    Route::get('/cursos/imprimir', [CursoController::class, 'cursosImprimir'])->name('cursos-imprmir');
    Route::get('/cursos/excel', [CursoController::class, 'curosExcel'])->name('cursos-excel');

    // WEB APP CALENDARIOS
    Route::get('/calendarios/home', [ServicoController::class, 'calendarios'])->name('web.calendarios');
    Route::get('/calendarios/cadastrar', [ServicoController::class, 'calendariosCadatrar'])->name('web.calendarios-cadastrar');
    Route::post('/calendarios/home', [ServicoController::class, 'cadastrarCalendarios'])->name('web.cadastrar-calendarios');
    Route::post('/calendarios/cadastrar', [ServicoController::class, 'cadastrarServicos'])->name('web.cadastrar-servico');
    Route::get('/calendarios/editar-calendarios/{id}', [ServicoController::class, 'editarCalendarios'])->name('web.editar-servico');
    Route::put('/calendarios/editar-calendarios/{id}', [ServicoController::class, 'updateCalendarios'])->name('web.update-servico');
    Route::delete('/calendarios/excluir-calendarios/{id}', [ServicoController::class, 'deleteCalendarios']);
    Route::get('/calendarios/activar-calendarios/{id}', [ServicoController::class, 'activarCalendarios']);
    Route::get('/estudantes/carregar-valor-matricula-propina/{curso}/{classe}/{turno?}/{situacao?}', [ServicoController::class, 'carregarValorMatriculaConfirmacao']);
    Route::get('/estudantes/carregar-valor-servico/{servico}/{turma}', [ServicoController::class, 'carregarValorServico']);

    Route::get('/estudantes/carregar-servicos-turmas', [ServicoController::class, 'carregarServicoTurma']);
    Route::get('/estudantes/carregar-valor-servicos-turmas/{id}/{turma}/{anoLectivo?}', [ServicoController::class, 'carregarValorServicoTurma']);

    Route::prefix('documentacao')->group(function () {
        // documentos
        Route::get('/documentos', [DocumentoController::class, 'documentos'])->name('web.documento');
        Route::get('/documentos/facturacao', [DocumentoController::class, 'facturacao'])->name('web.documento.facturacao');
        Route::get('/documentos/informativo', [DocumentoController::class, 'informativo'])->name('web.documento.informativo');
        Route::get('/documentos/recibos', [DocumentoController::class, 'recibos'])->name('web.documento.recibos');
        Route::get('/documentos/criar', [DocumentoController::class, 'create'])->name('web.documento.create');
        Route::get('/documentos/carregar-clientes/{status}', [DocumentoController::class, 'carregar_cliente'])->name('web.carregar.cliente');
        Route::get('/documentos/carregar-servico/{destino}/{estudante}/{servico}', [DocumentoController::class, 'carregar_servico'])->name('web.carregar.servico');
        Route::get('/documentos/facturas-sem-pagamento', [DocumentoController::class, 'facturasSemPagamentos'])->name('web.documento.facturas-sem-pagamento');
        Route::get('/imprimir/facturas-sem-pagamento-correntes', [DocumentoController::class, 'facturasSemPagamentosCorrentes'])->name('web.documento.facturas-sem-pagamento-correntes');
        Route::get('/imprimir/facturas-sem-pagamento-vencidas', [DocumentoController::class, 'facturasSemPagamentosVencidas'])->name('web.documento.facturas-sem-pagamento-vencidas');
        Route::get('/imprimir/facturas-sem-pagamento-geral', [DocumentoController::class, 'facturasSemPagamentosGeral'])->name('web.documento.facturas-sem-pagamento-geral');
    });

    Route::get('/facturar/pagamentos-servicos/{id}', [FacturaController::class, 'facturaPagamentoServico'])->name('web.facturar-pagamento-servico');
    Route::post('/facturar/pagamentos-servicos', [FacturaController::class, 'facturaPagamentoServicoCreate'])->name('web.facturar-pagamento-servico-create');
    Route::get('/download/factura-pagamento-servico/{code}', [FacturaController::class, 'ComprovativoFacturaPagamentoServico'])->name('comprovativo-factura-pagamento-servico');
    Route::get('/relatorios/ficha-matricula/{ficha}/{tipo_documento?}', [FacturaController::class, 'fichaMatricula'])->name('web.ficha-matricula');
    Route::get('/relatorios/facturas-referente/{ficha}', [FacturaController::class, 'facturasReferente'])->name('web.facturas-referentes');

    Route::get('/download/factura-proforma/{code}/{opcao?}', [FacturaController::class, 'ComprovativoFacturaProforma'])->name('comprovativo-factura-proforma');
    Route::get('/download/factura-factura/{code}/{opcao?}', [FacturaController::class, 'ComprovativoFacturaFactura'])->name('comprovativo-factura-factura');
    Route::get('/download/factura-recibo/{code}/{opcao?}', [FacturaController::class, 'ComprovativoFacturaRecibo'])->name('comprovativo-factura-recibo');
    Route::get('/download/factura-recibo-recibo/{code}', [FacturaController::class, 'ComprovativoFacturaReciboRecibo'])->name('comprovativo-factura-recibo-recibo');
    Route::get('/download/factura-nota-credito/{code}/{opcao?}', [FacturaController::class, 'ComprovativoFacturaNotaCredito'])->name('comprovativo-factura-nota-credito');

    Route::get('/facturas', [FacturaController::class, 'facturas'])->name('web.facturas');
    Route::get('/cancelar-facturas', [FacturaController::class, 'cancelarFacturas'])->name('web.cancelar-facturas');
    Route::post('/cancelar-facturas-search', [FacturaController::class, 'cancelarFacturasSearch'])->name('web.cancelar-facturas-search');
    Route::get('/conversao-facturas', [FacturaController::class, 'conversaoFacturas'])->name('web.conversao-facturas');
    Route::post('/conversao-facturas-search', [FacturaController::class, 'conversaoFacturasSearch'])->name('web.conversao-facturas-search');

    Route::get('/cancelar-facturas-create/{factura?}', [FacturaController::class, 'cancelarFacturasCreate'])->name('web.cancelar-facturas-create');
    Route::get('/recuperar-facturas-create/{factura?}', [FacturaController::class, 'recuperarFacturasCreate'])->name('web.recuperar-facturas-create');
    Route::get('/converter-facturas/{factura?}', [FacturaController::class, 'converterFacturas'])->name('web.converter-facturas');
    Route::get('/converter-facturas-create/{factura?}', [FacturaController::class, 'converterFacturasCreate'])->name('web.converter-facturas-create');
    Route::get('/facturar/liquidar-facturas', [FacturaController::class, 'liquidarFacturas'])->name('web.liquidar-factura');
    Route::get('/facturar/liquidar-facturas/{ficha?}', [FacturaController::class, 'liquidarFacturasIndex'])->name('web.liquidar-facturar-index');
    Route::post('/facturar/liquidar-facturas-store', [FacturaController::class, 'liquidarFacturasStore'])->name('web.liquidar-facturar-store');
    Route::get('/emitir-recibo-facturas/{factura?}', [FacturaController::class, 'emitirReciboFacturas'])->name('web.emitir-recibo-facturas');
    Route::get('/emitir-recibo-facturas-create/{factura?}', [FacturaController::class, 'emitirReciboFacturasCreate'])->name('web.emitir-recibo-facturas-create');
    Route::get('/documentos/cancelar-facturas/{factura?}', [FacturaController::class, 'documentoCancelarFacturas'])->name('web.documento-cancelar-facturas');
    Route::post('/documentos/cancelar-facturas', [FacturaController::class, 'documentoCancelarFacturasCreate'])->name('web.documento-cancelar-facturas-create');

    // Controller estuadante START

    // WEB APP ESTUDANTES
    Route::get('/estudantes/{id}/carregar-foto', [EstudanteMatriculaController::class, 'estudante_carregar_fotos'])->name('web.estudante.carregar-foto');
    Route::post('/carregar-foto-estudante', [EstudanteMatriculaController::class, 'estudante_carregar_fotos_store'])->name('carregar-foto-estudante-store');
    Route::get('/estudantes/{id}/ver-cartao', [EstudanteMatriculaController::class, 'estudante_ver_cartao'])->name('web.estudante.ver-cartao');

    Route::get('/estudantes/matricula-adimitir-todos', [EstudanteMatriculaController::class, 'adimitir_todas_matriculas'])->name('web.adimitir-todas-matriculas');
    Route::post('/estudantes/matricula-adimitir-todos', [EstudanteMatriculaController::class, 'adimitir_todas_matriculas_store'])->name('web.adimitir-todas-matriculas-store');
    Route::get('/estudantes/matricula', [EstudanteController::class, 'estudantesMatricula'])->name('web.estudantes-matricula');
    Route::get('/estudantes/matriculados-confirmados-proximo-ano', [EstudanteController::class, 'estudantesMatriculadoConfirmado'])->name('web.estudantes-matriculados-confirmados');
    Route::get('/estudantes/activar-estudantes/{id}', [EstudanteController::class, 'activarEstudantes']);
    Route::get('/estudantes/definir-como-finalista/{id}', [EstudanteController::class, 'definir_como_finalista'])->name('web.definir-como-finalista');
    Route::get('/estudantes/activar-matricula-estudantes/{id}/{back?}', [EstudanteMatriculaController::class, 'activarMatriculaEstudantes'])->name('web.activar-matricula-estudante');

    // individualmente
    Route::get('/estudantes/adicionar-estudantes-turma/{id}', [EstudanteMatriculaController::class, 'adicionarEstudanteTurma'])->name('web.adicionar-matricula-turma');
    Route::post('/estudantes/adicionar-estudante-turma', [EstudanteMatriculaController::class, 'adicionarEstudanteTurmaStore'])->name('web.adicionar-estudantes-turma-concluir');

    Route::get('/estudantes/matricula-create/', [EstudanteMatriculaController::class, 'create'])->name('web.estudantes-matricula-create');
    Route::get('/estudantes/matricula-edit/{id}', [EstudanteMatriculaController::class, 'edit'])->name('web.estudantes-matricula-edit');
    Route::post('/estudantes/matricula-create/', [EstudanteMatriculaController::class, 'store'])->name('web.estudantes-matricula-store');
    Route::put('/estudantes/matricula-update/{id}', [EstudanteMatriculaController::class, 'update'])->name('web.estudantes-matricula-update');
    Route::get('/estudantes/marcar-felecido/{id}', [EstudanteMatriculaController::class, 'marcaFalecido'])->name('web.estudantes-marcar-falecido');
    Route::get('/estudantes/marcar-vivo/{id}', [EstudanteMatriculaController::class, 'marcaVivo'])->name('web.estudantes-marcar-vivo');
    Route::get('/estudantes/marcar-desistente/{id}', [EstudanteMatriculaController::class, 'marcaDesistente'])->name('web.estudantes-marcar-desistente');

    Route::delete('/estudantes/excluir-estudantes/{id}', [EstudanteController::class, 'deleteEstudantes'])->name('estudantes.excluir-estudantes');
    Route::delete('/estudantes/excluir-matricula-estudantes/{id}', [EstudanteController::class, 'deleteMatriculaEstudantes'])->name('estudantes.excluir-matricula-estudantes');
    Route::delete('/estudantes/rejeitar-matricula-estudantes/{id}', [EstudanteController::class, 'rejeitarMatriculaEstudantes']);
    Route::delete('/estudantes/reiaceitar-matricula-estudantes/{id}', [EstudanteController::class, 'reiaceitarMatriculaEstudantes']);

    Route::get('/estudantes/home', [EstudanteController::class, 'estudantes'])->name('web.estudantes');
    Route::get('/estudantes/listagem-geral', [EstudanteController::class, 'estudantesListagemGeral'])->name('web.estudantes-listagem-geral');
    Route::post('/estudantes/home', [EstudanteController::class, 'cadastrarEstudantes'])->name('web.cadastrar-estudantes');
    Route::get('/estudantes/situacao-financeira-para-nao-isento/{id}', [EstudanteController::class, 'situacaFinanceiraEstudantesParaNaoIsento'])->name('web.sistuacao-financeiro-para-nao-isento');
    // WEB APP ESTUDANTES
    Route::get('/estudantes/exame-acesso', [ExameAcessoController::class, 'index'])->name('web.exames-acesso.index');
    Route::get('/estudantes/inscricao-exame-acesso', [ExameAcessoController::class, 'exameAcesso'])->name('web.estudantes-inscricao-exameAcesso');
    Route::post('/estudantes/inscricao-exame-acesso', [ExameAcessoController::class, 'exameAcessoPost'])->name('web.estudantes-inscricao-exameAcesso-post');

    Route::get('/estudantes/inscricao', [EstudanteController::class, 'estudantesInscricao'])->name('web.estudantes-inscricao');
    Route::get('/estudantes/inscricao-create', [EstudanteController::class, 'estudantesInscricaoCreate'])->name('web.estudantes-inscricao-create');
    Route::post('/estudantes/inscricao-create/', [EstudanteController::class, 'estudantesInscricaoStore'])->name('web.estudantes-inscricao-store');
    Route::get('/estudantes/{id}/inscricao-show', [EstudanteController::class, 'estudantesInscricaoShow'])->name('web.estudantes-inscricao-show');
    Route::get('/estudantes/{id}/inscricao-status', [EstudanteController::class, 'estudantesInscricaoStatus'])->name('web.estudantes-inscricao-status');

    Route::get('/estudantes/inscricao-aceites', [EstudanteController::class, 'estudantesInscricaoAceites'])->name('web.estudantes-inscricao-aceites');
    Route::get('/estudantes/editar-estudantes/{id}', [EstudanteController::class, 'editarEstudantes']);
    Route::put('/estudantes/editar-estudantes/{id}', [EstudanteController::class, 'updateEstudantes']);

    Route::get('/estudantes/pesquisar-estudante', [EstudanteController::class, 'pesquisarEstudanteIndex'])->name('shcools.pesquisar-estudante-index');
    Route::post('/estudantes/pesquisar-estudante', [EstudanteController::class, 'pesquisarEstudante'])->name('shcools.pesquisar-estudante');

    Route::get('/estudantes/pesquisar-estudante/{id}', [EstudanteController::class, 'searchEstudantes'])->name('resultado-pesquisa');
    Route::get('/estudantes/mais-informacoes/{id}', [EstudanteController::class, 'maisInformacoesEstudantes'])->name('shcools.mais-informacao-estudante');
    Route::get('/estudantes/actualizar-saldo/{id}', [EstudanteController::class, 'actualizarSaldo'])->name('shcools.actualizar-saldo');
    Route::post('/estudantes/actualizar-saldo', [EstudanteController::class, 'actualizarSaldoStore'])->name('shcools.actualizar-saldo-store');
    Route::get('/estudantes/remover-saldo/{id}', [EstudanteController::class, 'removerSaldo'])->name('shcools.remover-saldo');
    Route::post('/estudantes/remover-saldo', [EstudanteController::class, 'removerSaldoStore'])->name('shcools.remover-saldo-store');
    Route::get('/estudantes/historicos/{id}', [EstudanteController::class, 'historicosEstudantes'])->name('web.historicos-estudante');
    Route::get('/estudantes/atribuir-bolsa/{id?}', [EstudanteController::class, 'estudantesAtribuirBolsa'])->name('web.estudante-atribuir-bolsa');
    Route::post('/estudantes/atribuir-bolsa', [EstudanteController::class, 'estudantesAtribuirBolsaStore'])->name('web.estudante-atribuir-bolsa-store');
    Route::get('/estudantes/editar-bolseiro-bolsa/{id?}', [EstudanteController::class, 'estudantesEditarBolseiroBolsa'])->name('web.estudante-editar-bolseiro-bolsa');
    Route::put('/estudantes/editar-bolseiro-bolsa/{id}', [EstudanteController::class, 'estudantesEditarBolseiroBolsaUpdate'])->name('web.estudante-atribuir-bolsa-update');
    Route::get('/estudantes/atribuir-estagio/{id?}', [EstudanteController::class, 'estudantesAtribuirEstagio'])->name('web.estudante-atribuir-estagio');
    Route::post('/estudantes/atribuir-estagio', [EstudanteController::class, 'estudantesAtribuirEstagioStore'])->name('web.estudante-atribuir-estagio-store');
    Route::get('/estudantes/editar-estagiario-estagio/{id?}', [EstudanteController::class, 'estudantesEditarEstagiarioEstagio'])->name('web.estudante-editar-estagiario-estagio');
    Route::put('/estudantes/editar-bolseiro-estagio/{id}', [EstudanteController::class, 'estudantesEditarBolseiroEstagiarioUpdate'])->name('web.estudante-atribuir-estagiario-update');


    Route::get('/estudantes/listar-depositos/{id}', [EstudanteController::class, 'listarDepositosEstudante'])->name('shcools.listar-depositos-estudante');
    // Controller estuadante END

    Route::get('/estudantes/mini-pautas/{id}', [EstudantePautasController::class, 'miniPautaEstudantes'])->name('web.mini-pauta-estudante');
    Route::get('/relatorios/pesquisa-turmas-mini-pauta-estuadante', [EstudantePautasController::class, 'pesquisarTurmaMiniPautaEstudante'])->name('web.pesquisa-turmas-mini-pauta-estudante');
    Route::get('/estudantes/pautas/{id}', [EstudantePautasController::class, 'pautaEstudantes'])->name('web.pauta-estudante');
    Route::get('/estudantes/certificado', [EstudantePautasController::class, 'certificadoEstudantes'])->name('web.certificado-estudante');

    Route::get('/estudantes/mapa-aproveitamento-estudante', [EstudantePautasController::class, 'mapaAproveitamentoGeralEstudante'])->name('web.mapa-aproveitamento-estudante');
    Route::post('/estudantes/mapa-aproveitamento-estudante', [EstudantePautasController::class, 'mapaAproveitamentoGeralEstudanteCreate'])->name('web.mapa-aproveitamento-estudante-create');

    // estudantes confitmalºoes
    Route::get('/estudantes/confirmacao', [EstudanteConfirmacaoController::class, 'estudantesConfirmacao'])->name('web.estudantes-confirmacao');
    Route::get('/estudantes/confirmacao-inscricao', [EstudanteConfirmacaoController::class, 'estudantesConfirmacaoInscricao'])->name('web.estudantes-confirmacao-inscricao');
    Route::get('/estudantes/confirmacao-novo-ano/{id}/{ano_lectivo}', [EstudanteConfirmacaoController::class, 'estudantesConfirmacaoNovoAno'])->name('web.estudantes-confirmacao-novo-ano');
    Route::post('/estudantes/confirmacao-novo-ano-post', [EstudanteConfirmacaoController::class, 'estudantesConfirmacaoNovoAnoPost'])->name('web.estudantes-confirmacao-novo-ano-post');
    // Gestão de cartões
    Route::get('/emissao-cartoes', [CartaoEstudanteController::class, 'emissao'])->name('web.emissao.cartao');
    Route::post('/buscar-estudante-emissao-cartoes', [CartaoEstudanteController::class, 'buscar'])->name('web.buscar.estudante-emissao.cartao');
    Route::post('/salvar-cartoes/{id}/estudante-emissao', [CartaoEstudanteController::class, 'salvarCartao'])->name('web.salvar.emissao-cartao.estudante');
    Route::get('/gestao-cartoes', [CartaoEstudanteController::class, 'index'])->name('web.index.cartao');
    Route::post('/gestao-cartoes/gerar', [CartaoEstudanteController::class, 'create'])->name('web.index.create');

    Route::prefix('backup')->group(function () {
        Route::get('/backups/exportar', [BackupController::class, 'exportar'])->name('backups-exportar');
        Route::post('/backups/importar', [BackupController::class, 'importar'])->name('backups-importar');
        Route::get('/backups/listar-banco-dados', [BackupController::class, 'listarBancos'])->name('backups-listar-banco-dados');
        Route::delete('/backups/delete-banco-dados', [BackupController::class, 'deleteBancos'])->name('backups-delete-banco-dados');
        Route::resource('/backups', BackupController::class);
    });


    Route::post('/estudantes/editar-foto', [WebController::class, 'estudantesFotoPerfil'])->name('web.estudantes-foto-perfil');
    Route::get('/estudantes/detalhes-pagamento-propina/{id}/{est}/{servico}/{quantidade?}/{ano?}', [WebController::class, 'estudantesDetalhesPagamentoPropina'])->name('web.adicionar-meses-pagamento');
    Route::get('/estudantes/detalhes-pagamento-propina-remover-mes/{id}/{est}/{servico}/{quantidade?}/{ano?}', [WebController::class, 'estudantesDetalhesPagamentoPropinaRemoverMes'])->name('web.remover-meses-pagamento');

    Route::get('/estudantes/remover-multa1-propina/{id}', [WebController::class, 'estudantesRemoverMulta1'])->name('web.estudante-remover-multa1');
    Route::get('/estudantes/remover-multa2-propina/{id}', [WebController::class, 'estudantesRemoverMulta2'])->name('web.estudante-remover-multa2');
    Route::get('/estudantes/remover-multa3-propina/{id}', [WebController::class, 'estudantesRemoverMulta3'])->name('web.estudante-remover-multa3');
    Route::get('/estudantes/adicionar-multa1-propina/{id}', [WebController::class, 'estudantesAdicionarMulta1'])->name('web.estudante-adicionar-multa1');
    Route::get('/estudantes/adicionar-multa2-propina/{id}', [WebController::class, 'estudantesAdicionarMulta2'])->name('web.estudante-adicionar-multa2');
    Route::get('/estudantes/adicionar-multa3-propina/{id}', [WebController::class, 'estudantesAdicionarMulta3'])->name('web.estudante-adicionar-multa3');

    Route::get('/estudantes/pesquisar-geral/{string}', [WebController::class, 'pesquisarEstudanteGeral'])->name('web.pesquisar-geral-estudante');

    Route::get('/estudantes/carregar-servicos-cartao', [WebController::class, 'carregarServicoTurma'])->name('web.carregar-servicos-turmas');
    Route::get('/estudantes/processos-estudantes', [WebController::class, 'processoEstudantes'])->name('web.processos-estudantes');
    Route::get('/estudantes/processos-financeiro-estudantes', [WebController::class, 'processoFinanceiroEstudantes'])->name('web.processos-financeiro-estudantes');
    Route::get('/estudantes/numero-processo-id/{id}', [WebController::class, 'numeroProcessoId'])->name('web.numero-processp-id');

    Route::get('/estudantes/processos-pedagogicos-estudantes', [WebController::class, 'processoPedagogicosEstudantes'])->name('web.processos-pedagogicos-estudantes');

    // SEGURANCA
    Route::get('/seguranca/meu-perfil', [SegurancaController::class, 'perfil'])->name('web.perfil');
    Route::post('/seguranca/editar-perfil', [SegurancaController::class, 'editarPerfil'])->name('web.editar-perfil');
    Route::post('/seguranca/definar-cor-cartao', [SegurancaController::class, 'DefinirCorCartao'])->name('web.definir-cor-cartao');
    Route::post('/seguranca/definar-tipo-impressao', [SegurancaController::class, 'DefinirTipoImpressao'])->name('web.definir-tipo-impressao');
    Route::get('/seguranca/definar-processo-pagamento', [SegurancaController::class, 'DefinirProcessoPagamento'])->name('web.definir-processo-pagamento');
    Route::get('/seguranca/definar-processo-admissao-estudantes', [SegurancaController::class, 'DefinirProcessoAdmissaoEstudante'])->name('web.definir-processo-admissao-estudante');

    // ENCARREGADO
    Route::get('/encarregados-adicionar-estudantes-encarragados/{id}', [EncarregadoController::class, 'indexAssociarEstudnate'])->name('encarregados.adicionar-estudantes-encarregado');
    Route::post('/encarregados-adicionar-estudantes-encarragados', [EncarregadoController::class, 'AdicionarEstudanteStore'])->name('encarregados.adicionar-estudantes-encarregado-store');
    Route::delete('/encarregados-remover-estudantes-encarragados/{id}', [EncarregadoController::class, 'ExcluirEstudanteEncarregado'])->name('encarregados.remover-estudantes-encarregado');
    Route::resource('encarregados', EncarregadoController::class);
    Route::get('/buscar-por-telefone-encarregado', [EncarregadoController::class, 'buscarPorTelefone'])->name('encarregados.buscar-por-telefone-encarregado');
    Route::get('/buscar-por-bilhete-estudante', [EncarregadoController::class, 'buscarEstudantePorBilhete'])->name('estudantes.buscar-por-bilhete-estudante');

    //TRANSFERENCIAS ESCOLAR

    Route::get('/transferencia-turmas', [TransferenciaEscolarController::class, 'list_turma'])->name('web.transferencia-turmas');
    Route::get('/transferencia-escolares', [TransferenciaEscolarController::class, 'list'])->name('web.transferencia-escolares');
    Route::get('/transferencia-escolares-aceitar/{id}', [TransferenciaEscolarController::class, 'aceitar'])->name('web.transferencia-escolares-aceitar');
    Route::get('/transferencia-escolares-rejeitar/{id}', [TransferenciaEscolarController::class, 'rejeitar'])->name('web.transferencia-escolares-rejeitar');
    Route::get('/transferencia-escolares-cancelar/{id}', [TransferenciaEscolarController::class, 'cancelar'])->name('web.transferencia-escolares-cancelar');
    Route::get('/transferencia-escolares-eliminar/{id}', [TransferenciaEscolarController::class, 'eliminar'])->name('web.transferencia-escolares-eliminar');
    Route::get('/transferencia-escolares-visualizar/{id}', [TransferenciaEscolarController::class, 'visualizar'])->name('web.transferencia-escolares-visualizar');
    Route::get('/transferencia-escolares-imprimir/{id}', [TransferenciaEscolarController::class, 'imprimir'])->name('web.transferencia-escolares-imprimir');
    Route::get('/transferencia-escola-estudantes/{id?}', [TransferenciaEscolarController::class, 'index'])->name('web.transferencia-escola-estudante');
    Route::post('/transferencia-escola-estudantes', [TransferenciaEscolarController::class, 'store'])->name('web.transferencia-escola-estudante-store');

    Route::get('/transferencia-turma-estudantes/{id?}', [TransferenciaEscolarController::class, 'index_turma'])->name('web.transferencia-turma-estudante');
    Route::post('/transferencia-turma-estudantes', [TransferenciaEscolarController::class, 'store_turma'])->name('web.transferencia-turma-estudante-store');
    Route::get('/transferencia-turma-rejeitar/{id}', [TransferenciaEscolarController::class, 'rejeitar_turma'])->name('web.transferencia-turma-rejeitar');
    Route::get('/transferencia-turma-eliminar/{id}', [TransferenciaEscolarController::class, 'eliminar_turma'])->name('web.transferencia-turma-eliminar');
    Route::get('/transferencia-turma-visualizar/{id}', [TransferenciaEscolarController::class, 'visualizar_turma'])->name('web.transferencia-turma-visualizar');
    Route::get('/transferencia-turma-cancelar/{id}', [TransferenciaEscolarController::class, 'cancelar_turma'])->name('web.transferencia-turma-cancelar');
    Route::get('/transferencia-turma-aceitar/{id}', [TransferenciaEscolarController::class, 'aceitar_turma'])->name('web.transferencia-turma-aceitar');

    //TRANSFERENCIAS ESCOLAR PROFESSORES

    Route::get('/transferencia-escolares-professores', [TransferenciaEscolaProfessorController::class, 'list'])->name('web.transferencia-escolares-professores');
    Route::get('/transferencia-escola-professores/{id?}', [TransferenciaEscolaProfessorController::class, 'index'])->name('web.transferencia-escola-professores');
    Route::post('/transferencia-escola-professores', [TransferenciaEscolaProfessorController::class, 'store'])->name('web.transferencia-escola-professores-store');

    ########################################## FINANCEIROS ############################################################
    Route::prefix('financeiros')->group(function () {
        Route::get('estudantes', [FinanceiroController::class, 'estudantes'])->name('financeiros.estudantes');
        
        Route::get('pagamentos-propina', [FinanceiroController::class, 'pagamentosPropina'])->name('financeiros.financeiro-pagamentos-propina');
        Route::get('listagem-servicos', [FinanceiroController::class, 'listagemServicos'])->name('financeiros.listagem-servicos');
        Route::get('listagem-servicos/{id}', [FinanceiroController::class, 'servicosEdit'])->name('financeiros.listagem-servicos-edit');
        Route::get('actualizar-factura/{id}', [FinanceiroController::class, 'actualizar_factura'])->name('financeiros.actualizar-factura');
        Route::post('actualizar-facturas', [FinanceiroController::class, 'actualizar_factura_store'])->name('financeiros.actualizar-factura-store');
        Route::get('carregar-mensalidades-por-mes', [FinanceiroController::class, 'dadosMensalidades'])->name('financeiros.carregar-mensalidades-por-mes');
    });

    //
    Route::get('/financeiro/depositos', [FinanceiroController::class, 'depositos'])->name('web.financeiro-depositos');
    Route::get('/estudantes/pagamento-propina/{id}', [FinanceiroController::class, 'estudantesPagamentoPropina'])->name('web.estudantes-pagamento-propina');
    Route::post('/estudantes/pagamento-propina', [FinanceiroController::class, 'estudantesPagamentoPropinaCreate'])->name('web.estudantes-pagamento-propina-create');
    Route::get('/estudantes/efectuar-pagamento-especias', [FinanceiroController::class, 'estudantesEfectuarPagamentoEspeciais'])->name('web.estudantes-efectuar-pagamento-especias');
    Route::post('/estudantes/efectuar-pagamento-especias', [FinanceiroController::class, 'estudantesEfectuarPagamentoEspeciaisStore'])->name('web.estudantes-efectuar-pagamento-especias-store');

    Route::get('/financeiro/pagamentos', [FinanceiroController::class, 'financeiroPagamento'])->name('web.financeiro-pagamentos');
    Route::get('/financeiro/concluir-pagamentos', [FinanceiroController::class, 'concluirPagamento'])->name('web.concluir-pagamentos');
    Route::get('/financeiro/concluir-pagamentos/{id}', [FinanceiroController::class, 'financeiroConcluirPagamentoCreate'])->name('web.financeiro-concluir-pagamento-create');


    Route::get('/financeiro/pagamentos-salario', [FinanceiroController::class, 'pagamentosSalario'])->name('web.financeiro-pagamentos-salario');
    Route::post('/financeiro/pagamentos-salario', [FinanceiroController::class, 'pagamentosSalarioCreate'])->name('web.financeiro-pagamentos-salario-create');
    Route::get('/financeiro/buscas-gerais', [FinanceiroController::class, 'buscasGerais'])->name('web.financeiro-buscas-gerais');
    Route::get('/financeiro/mes-folha-salario', [FinanceiroController::class, 'mesFolhaSalario'])->name('web.financeiro-mes-folha-salario');
    Route::get('/financeiro/propinas-por-cursos', [FinanceiroController::class, 'propinasPorCurso'])->name('web.financeiro-propinas-por-cursos');
    Route::get('/download/propinas-por-cursos', [WebDownloadController::class, 'propinasPorCursoPdf'])->name('pdf.propinas-por-cursos');

    Route::get('/financeiro/novo-pagamento-pagar', [FinanceiroController::class, 'novoPagamentoPagar'])->name('web.novo-pagamentos-pagar');
    Route::get('/financeiro/carregar-servicos-cartao/{id}', [FinanceiroController::class, 'carregarServicoEscola'])->name('web.carregar-servicos-escola');
    Route::get('/financeiro/detalhes-pagamento-servico/{id}/{servico}', [FinanceiroController::class, 'escolaDetalhesPagamentoPropina'])->name('web.adicionar-meses-pagamento-escola');
    Route::get('/financeiro/detalhes-pagamento-servico-remover-mes/{id}/{servico}', [FinanceiroController::class, 'escolaDetalhesPagamentoPropinaRemoverMes'])->name('web.remover-meses-pagamento-escola');
    Route::post('/financeiro/pagamento-servico', [FinanceiroController::class, 'escolaPagamentoServicoCreate'])->name('web.escola-pagamento-servico-create');

    Route::get('/financeiro/outras-gerais', [FinanceiroController::class, 'outrasBuscas'])->name('web.financeiro-outras-buascas');

    // WEB APP FUNCIONARIOS

    Route::get('/relatorios/mini-pauta-geral', [WebController::class, 'miniPautaGeral'])->name('web.mini-pauta-geral');
    Route::get('/relatorios/mini-pauta', [WebController::class, 'miniPauta'])->name('web.mini-pauta');
    Route::get('/turmas/boletins/estudantes', [WebController::class, 'beletins'])->name('web.turmas-boletins-estudantes');
    Route::post('/turmas/boletins/estudantes', [WebController::class, 'beletins_post'])->name('web.post-turmas-boletins-estudantes');
    Route::get('/relatorios/carregar-turmas-pautas/{id}', [WebController::class, 'carregarTurmasPautas']);
    Route::post('/relatorios/pesquisa-turmas-mini-pauta2', [WebController::class, 'pesquisarTurmaMiniPauta2'])->name('web.pesquisa-turmas-mini-pauta2');

    Route::get('/relatorios/download-lista-estudantes-novos', [WebController::class, 'pdfEstudantesPorNovos'])->name('web.download-lista-estudantes-novos');

    Route::prefix('relatorios')->group(function () {
        Route::get('/relatorios/estudantes', [RelatorioController::class, 'relatoriosEstudantes'])->name('web.relatorios-estudantes');

        Route::get('/relatorios/matriculas', [RelatorioController::class, 'relatoriosMatriculas'])->name('web.relatorios-matriculas');
        Route::get('/relatorios/matriculas-excel', [RelatorioController::class, 'relatoriosMatriculasExcel'])->name('web.relatorios-matriculas-excel');

        Route::get('/relatorios/candidatura-inscricao', [RelatorioController::class, 'relatoriosCandidaturaInscricao'])->name('web.relatorios-candidatura-inscricao');
        Route::get('/relatorios/candidatura-inscricao-excel', [RelatorioController::class, 'relatoriosCandidaturaInscricaoExcel'])->name('web.relatorios-candidatura-inscricao-excel');

        Route::get('/relatorios/home', [RelatorioController::class, 'relatoriosApp'])->name('web.relatorios-app');
        Route::get('/relatorios/turmas', [RelatorioController::class, 'relatoriosTurmasApp'])->name('web.relatorios-turmas-app');
        Route::get('/relatorios/classes', [RelatorioController::class, 'relatoriosClassesApp'])->name('web.relatorios-classes-app');
        Route::get('/relatorios/turnos', [RelatorioController::class, 'relatoriosTurnosApp'])->name('web.relatorios-turnos-app');
        Route::get('/relatorios/cursos', [RelatorioController::class, 'relatoriosCursosApp'])->name('web.relatorios-cursos-app');

        Route::get('/relatorios/lista-estudantes-turma/{id}', [RelatorioController::class, 'listaEstudantesTurma'])->name('web.lista-estudantes-turma');
        Route::get('/relatorios/lista-disciplinas-turma/{id}', [RelatorioController::class, 'listaDisciplinasTurma'])->name('web.lista-disciplinas-turma');
        Route::get('/relatorios/lista-estudantes-curso/{id}', [RelatorioController::class, 'listaEstudantesCurso'])->name('web.lista-estudantes-curso');
        Route::get('/relatorios/lista-estudantes-classe/{id}', [RelatorioController::class, 'listaEstudantesClasse'])->name('web.lista-estudantes-classe');
        Route::get('/relatorios/lista-estudantes-turno/{id}', [RelatorioController::class, 'listaEstudantesTurno'])->name('web.lista-estudantes-turno');
        Route::get('/relatorios/lista-estudantes-novos', [RelatorioController::class, 'listaEstudantesNovos'])->name('web.lista-estudantes-novos');
        Route::get('/relatorios/lista-estudantes-repitentes', [RelatorioController::class, 'listaEstudantesRepitentes'])->name('web.lista-estudantes-repitentes');



        // Route::get('/financeiro/buscas-gerais', [FinanceiroController::class, 'buscasGerais'])->name('web.financeiro-buscas-gerais');
    });


    #######################################################################################################################
    #######################################################################################################################

    ############################################## DOWNLOAD CONTROLLER ####################################################
    #######################################################################################################################
    // controlleres para os dawload
    Route::get('/download/teste', [WebDownloadController::class, 'teste_download'])->name('teste_download');
    Route::get('/download/teste-excel', [WebDownloadController::class, 'teste_download_excel'])->name('teste_download_excel');
    
    
    Route::get('/download/outras-buscas/{mensal?}/{filtro?}/{ano?}', [WebDownloadController::class, 'outrasBuscasBaixa'])->name('outras-baixa');
    Route::get('/download/ficha-pagamentos/{data1?}/{data2?}/{filtro?}', [WebDownloadController::class, 'financeiroPagamento'])->name('ficha-pagamentos');
    Route::get('/download/ficha-facturas-aliquidar', [WebDownloadController::class, 'facturaAliquidarPagamento'])->name('factura-aliquidar-pagamentos');
    Route::get('/download/ficha-pagamentos-cancelados', [WebDownloadController::class, 'financeiroPagamentoCancelado'])->name('ficha-pagamentos-cancelados');

    Route::get('/download/listar-estudantes-novos', [WebDownloadController::class, 'listarEstudantesNovos'])->name('listar-estudantes-novos');
    Route::get('/download/listar-estudantes-antigos', [WebDownloadController::class, 'listarEstudantesAntigos'])->name('listar-estudantes-antigo');
    Route::get('/download/listar-estudantes-curso/{id}', [WebDownloadController::class, 'listarEstudantesCurso'])->name('listar-estudantes-curso');
    Route::get('/download/listar-estudantes-turno/{id}', [WebDownloadController::class, 'listarEstudantesTurno'])->name('listar-estudantes-turno');
    Route::get('/download/listar-estudantes-classe/{id}', [WebDownloadController::class, 'listarEstudantesClasse'])->name('listar-estudantes-classe');
    Route::get('/download/ficha-matricula/{ficha}', [WebDownloadController::class, 'fichaMatricula'])->name('ficha-matricula');
    Route::get('/download/ficha-matricula2/{ficha}', [WebDownloadController::class, 'fichaMatricula2'])->name('ficha-matricula2');

    Route::get('/download/ficha-matricula-segunda-via/{ficha}', [WebDownloadController::class, 'fichaMatriculaSegundaVia'])->name('ficha-matricula-segunda-via');
    Route::get('/download/mini-pauta-geral/{turma}/{disciplina}', [WebDownloadController::class, 'miniPautaGeral'])->name('ficha-mini-pauta-geral');
    Route::get('/download/mini-pauta-geral-excel/{turma}/{disciplina}', [WebDownloadController::class, 'miniPautaGeralExcel'])->name('ficha-mini-pauta-geral-excel');
    Route::get('/download/mini-pauta/{turma}/{disciplina}/{trimestre}', [WebDownloadController::class, 'miniPauta'])->name('ficha-mini-pauta');
    Route::get('/download/mini-pauta-excel/{turma}/{disciplina}/{trimestre}', [WebDownloadController::class, 'miniPautaExcel'])->name('ficha-mini-pauta-excel');
    Route::get('/download/mini-pauta-todas/{turma}/{trimestre}', [WebDownloadController::class, 'miniPautaTodas'])->name('ficha-mini-pauta-todas');
    Route::get('/download/mini-pauta-todas-excel/{turma}/{trimestre}', [WebDownloadController::class, 'miniPautaTodasExcel'])->name('ficha-mini-pauta-todas-excel');
    Route::get('/download/extrato-estudante', [WebDownloadController::class, 'extratoEstudante'])->name('ficha-extrato-estudante');


    Route::get('/download/pauta-estudante', [WebDownloadController::class, 'pautaEstudante'])->name('ficha-pauta-estudante');

    Route::get('/download/mapa-efectividade', [WebDownloadController::class, 'mapaEfectividadePrint'])->name('dow.ficha-mapa-efectividade');
    Route::get('/download/mapa-efectividade-excel', [WebDownloadController::class, 'mapaEfectividadePrintExcel'])->name('dow.ficha-mapa-efectividade-excel');

    Route::get('/download/funcionario-extrato/{id}', [WebDownloadController::class, 'funcionarioExtrato'])->name('ficha-funcionario-extrato');
    Route::get('/download/funcionario-contrato/{id}', [WebDownloadController::class, 'funcionarioContrato'])->name('ficha-funcionario-contrato');
    Route::get('/download/funcionario-turmas/{id}', [WebDownloadController::class, 'funcionarioTurmas'])->name('ficha-funcionario-turma');
    Route::get('/download/funcionario-geral/{id}', [WebDownloadController::class, 'funcionarioGeral'])->name('ficha-funcionario-geral');
    Route::get('/download/folha-salario', [WebDownloadController::class, 'folhaSalario'])->name('down.ficha-salario');
    Route::get('/download/folha-salario-mensal/{mes}', [WebDownloadController::class, 'folhaSalarioMensal'])->name('down.ficha-salario-mensal');
    Route::get('/download/ficha-pagamento-propina/{code}', [WebDownloadController::class, 'fichaPagamentoPropina'])->name('ficha-pagamento-propina');
    Route::get('/download/ficha-pagamento-servico/{code}', [WebDownloadController::class, 'fichaPagamentoServico'])->name('ficha-pagamento-servico');
    Route::get('/download/ficha-pagamento-outros/{code}', [WebDownloadController::class, 'fichaPagamentoOutros'])->name('ficha-pagamento-outros');
    Route::get('/download/ficha-pagamento-salario/{code}', [WebDownloadController::class, 'fichaPagamentoSalario'])->name('ficha-pagamento-salario');

    // DOWNLOAD TURMA
    Route::get('/turmas/download-estudantes-turma/{code}', [PrinterController::class, 'downloadEstudantesTurmas'])->name('dow.estudantes_turmas');
    Route::get('/turmas/download-estudantes-turma-genero-nascimento/{code}', [PrinterController::class, 'downloadEstudantesTurmasGeneroNascimento'])->name('dow.estudantes_turmas_gen_nas');
    Route::get('/turmas/download-professores-turma/{code}', [PrinterController::class, 'downloadProfessoresTurmas'])->name('dow.professores_turmas');
    Route::get('/turmas/download-matriculas-turma/{code}', [PrinterController::class, 'downloadMatriculasTurmas'])->name('dow.matriculas-turmas');
    Route::get('/turmas/download-confirmacoes-turma/{code}', [PrinterController::class, 'downloadConfirmacoesTurmas'])->name('dow.confirmacoes-turmas');
    Route::get('/turmas/download-controlo-propinas-turma/{code}', [PrinterController::class, 'downloadControloPropinasTurmas'])->name('dow.controlo-propinas-turmas');



    Route::get('/turmas/download-lista-presenca-estudantes-turma/{code}', [WebDownloadController::class, 'downloadListaPresencaTurmas'])->name('dow.lista-presenca_turmas');
    Route::get('/turmas/download-estudantes-boletin/{code}/{ano}/{trimestre}', [WebDownloadController::class, 'downloadBoletinEstudante'])->name('dow.boletin-estudante');
    Route::get('/turmas/download-estudantes-ficha-tecnica', [WebDownloadController::class, 'downloadFichaTecnicaEstudante'])->name('dow.ficha-tecnica-estudante');
    Route::get('/turmas/distribuicao-rotas', [WebDownloadController::class, 'distribuicaoRotas'])->name('dow.distribuicao-rotas');
    // GRAFICO
    Route::get('/grafico', [WebGraficoController::class, 'graficoMatriculasConfirmacoes'])->name('graf.matriculas-confirmacoes');


    #######################################################################################################################
    #######################################################################################################################

    ##################################################################################################################
    ######################################## START  TURMAS ##############################################################
    ##################################################################################################################


    // WEB APP TURMAS
    Route::get('/turmas/home', [TurmaController::class, 'turmas'])->name('web.turmas');
    Route::get('/turmas/configurar-turmas/{id}', [TurmaController::class, 'turmasConfiguracao'])->name('web.turmas-configuracao');
    Route::get('/turmas/adicionar-estuantes-turmas/{id}', [TurmaController::class, 'adicionarEstuantesTurmas'])->name('web.adicionar-estuantes-turmas');
    Route::post('/turmas/adicionar-estuantes-turmas', [TurmaController::class, 'adicionarEstuantesTurmasStore'])->name('web.adicionar-estuantes-turmas-store');
    Route::get('/turmas/remover-estudantes-turmas/{turma_id}/{estudante_id}', [TurmaController::class, 'removerEstuantesTurmas'])->name('web.remover-estuantes-turmas');

    Route::post('/turmas/home', [TurmaController::class, 'cadastrarTurmas'])->name('web.cadastrar-turmas');
    Route::get('/turmas/editar-turmas/{id}', [TurmaController::class, 'editarTurmas']);
    Route::put('/turmas/editar-turmas/{id}', [TurmaController::class, 'updateTurmas']);
    Route::delete('/turmas/excluir-turmas/{id}', [TurmaController::class, 'deleteTurmas'])->name('turmas.excluir-turmas');
    Route::get('/turmas/mais-informacoes/{id}', [TurmaController::class, 'showTurmas'])->name('web.apresentar-turma-informacoes');
    Route::get('/turmas/activar-turmas/{id}', [TurmaController::class, 'activarTurmas'])->name('turmas.activar-turmas');
    Route::get('/turmas/horarios', [TurmaController::class, 'horarios'])->name('web.turmas-horarios');
    // Route::get('/turmas/encerramento-ano-lectivo-sore/{id}', [TurmaController::class, 'encerrar_ano_lectivo_store'])->name('web.turmas-encerramento-ano-lectivo-store');
    Route::get('/turmas/encerramento-ano-lectivo/{id}/{status?}', [TurmaController::class, 'encerrar_ano_lectivo'])->name('web.turmas-encerramento-ano-lectivo');
    Route::get('/turmas/actualizar-multas/{id}', [TurmaController::class, 'actualizar_multas_geral'])->name('web.turmas-actualizar-multas');
    Route::get('/turmas/criar-grade-curricular/{id}', [TurmaController::class, 'criarGradeCurricularTurmas'])->name('web.criar-grade-curricular-turmas');
    Route::get('/turmas/actualizar-grade-curricular/{id}', [TurmaController::class, 'actualizarGradeCurricularTurmas'])->name('web.actualizar-grade-curricular-turmas');

    // /turmas/configurar-turmas/

    Route::post('/turmas/cadastrar-disciplinas', [TurmaController::class, 'cadastrarDisciplinasTurmas'])->name('web.cadastrar-disciplinas-turmas');
    Route::post('/turmas/cadastrar-horario', [TurmaController::class, 'cadastrarHorarioTurmas'])->name('web.cadastrar-horario-turmas');
    Route::delete('/turmas/remover-horario-turma/{id}', [TurmaController::class, 'removerHorarioTurma'])->name('web.remover-horario-turma');
    Route::get('/turmas/editar-horario-turma/{id}', [TurmaController::class, 'editarHorarioTurmas'])->name('web.editar-horario-turma');
    Route::post('/turmas/editar-horario', [TurmaController::class, 'updateHorarioTurmas'])->name('web.editar-horario-turmas-update');
    Route::delete('/turmas/remover-disciplina-turma/{id}', [TurmaController::class, 'removerDisciplinaTurma'])->name('web.remover-disciplina-turma');
    Route::get('/turmas/laod-disciplinas-turma/{id}', [TurmaController::class, 'loadDisciplinasTurma'])->name('web.load-disciplinas-turma');

    Route::prefix('pedagogicos')->group(function () {
        Route::get('/', [TurmaController::class, 'lancamentoNasTurmas'])->name('pedagogicos.lancamento-nas-turmas');
        Route::get('/turmas/estatistica-turmas-unica/', [EstatisticaTurmaController::class, 'EstatisticaTurmasUnica'])->name('pedagogicos.estatistica-turmas-unica');

        ################################ Estatistica Turma Controller ###################################################EstatisticaTurmaController
        Route::get('/turmas/estatistica-turmas-unica/pdf', [EstatisticaTurmaController::class, 'EstatisticaTurmasUnicaPdf'])->name('pedagogicos.estatistica-turmas-unica-pdf');
        Route::get('/turmas/estatistica-turmas-unica/excel', [EstatisticaTurmaController::class, 'EstatisticaTurmasUnicaExcel'])->name('pedagogicos.estatistica-turmas-unica-excel');
        ##################################################################################################################
    });

    Route::get('/turmas/lancamentos-notas/{id?}', [TurmaController::class, 'lancamentoNotas'])->name('pedagogicos.lancamento-notas');

    ################################ Estatistica Turma Controller ###################################################
    Route::get('/provincial/estatistica-turmas-unica/', [EstatisticaTurmaController::class, 'EstatisticaProvincialTurmasUnica'])->name('app.provincial-estatistica-turmas-unica');
    Route::get('/provincial/estatistica-turmas-unica/pdf', [EstatisticaTurmaController::class, 'EstatisticaProvincialTurmasUnicaPdf'])->name('app.provincial-estatistica-turmas-unica-pdf');
    ##################################################################################################################



    Route::get('/turmas/laod-horario-turma/{id}', [TurmaController::class, 'loadHorarioTurma'])->name('web.load-horario-turma');
    Route::get('/turmas/laod-servico-turma/{id}', [TurmaController::class, 'loadServicoTurma'])->name('web.load-servico-turma');
    Route::delete('/turmas/remover-meses-turma-pagar/{id}', [TurmaController::class, 'removerMesesTurmaPagar'])->name('web.remover-meses-turma-pagar');

    // Route::get('/turmas/transferencia-estudante', [TurmaController::class, 'transferenciaEstudante'])->name('web.transferencia-estudante');
    Route::get('/turmas/carregar-disciplinas-turma/{id}', [TurmaController::class, 'carregamentoDisciplinasTurma']);
    Route::get('/turmas/carregar-turmas-estudante/{curso}/{turno}/{classe}', [TurmaController::class, 'carregarTurmaEstudante']);
    Route::get('/turmas/carregar-turmas-estudante/{id}', [TurmaController::class, 'carregarTurmaEstudanteId'])->name('web.carregar-estudante-turma-id');

    Route::get('/turmas/configuracao-servicos', [TurmaController::class, 'configuracaoServico'])->name('web.configuracao-turmas');
    Route::get('/turmas/carregamento-destino-servico/{values}', [TurmaController::class, 'carregamentoDestinoServico'])->name('web.carregamento-destino-servico');
    Route::delete('/turmas/remover-servico-turma/{id}', [TurmaController::class, 'removerServicoTurma'])->name('web.remover-servico-turma');
    Route::post('/turmas/cadastrar-servicos', [TurmaController::class, 'cadastrarServicoTurma'])->name('web.cadastrar-servico-turma');
    Route::put('/turmas/cadastrar-servico-turma-editar/{id}', [TurmaController::class, 'editarServicoTurma'])->name('web.editar-servico-turma');

    Route::post('/turmas/adicionar-professor-turma', [TurmaController::class, 'adicionarProfessorTurma'])->name('web.adicionar-professor-turma');
    Route::delete('/turmas/excluir-professor-disciplinas/{id}', [TurmaController::class, 'deleteDisciplinaProfessorTurma']);


    Route::post('/turmas/adicionar-estudante-indovidual', [TurmaController::class, 'adiocionarEstudanteTurmasIndividual'])->name('web.adicionar-estudantes-turma-individual');
    Route::post('/turmas/adicionar-estudante-indovidual-concluir', [TurmaController::class, 'adiocionarEstudanteTurmasIndividualConcluir'])->name('web.adicionar-estudantes-turma-individual-concluir');

    Route::post('/turmas/gerar-lista-presenca', [TurmaController::class, 'gerarListaPresenca'])->name('web.gerar-lista-presenca_estudantes-turma');
    Route::get('/turmas/carregar-valores-form/{id}', [TurmaController::class, 'carregamentoValoresForm']);
    Route::post('/turmas/finalizar-lancamento-notas', [TurmaController::class, 'finalizarLancamentoNota'])->name('web.finanlizar-lancamento-notas');
    Route::get('/turmas/estatistica', [TurmaController::class, 'estatisticaTurmas'])->name('web.estatistica-turmas');
    Route::get('/turmas/faltas', [TurmaController::class, 'faltasTurmas'])->name('web.faltas-turmas');
    Route::get('/turmas/faltas-estudantes', [TurmaController::class, 'faltasTurmasEstudantes'])->name('web.faltas-turmas-estudantes');
    Route::get('/turmas/faltas-estudantes-justificar', [TurmaController::class, 'faltasTurmasEstudantesJustificar'])->name('web.faltas-turmas-estudantes-justifcar');
    Route::get('/turmas/faltas-estudantes-justificar-post', [TurmaController::class, 'faltasTurmasEstudantesJustificarPost'])->name('web.faltas-turmas-estudantes-justifcar-post');
    Route::post('/turmas/faltas-estudantes-post', [TurmaController::class, 'faltasTurmasEstudantesPost'])->name('web.faltas-turmas-estudantes-post');
    Route::get('/turmas/faltas-funcionarios', [TurmaController::class, 'faltasTurmasFuncionarios'])->name('web.faltas-turmas-funcionarios');
    Route::get('/turmas/faltas-funcionarios-get', [TurmaController::class, 'faltasTurmasFuncionariosGet'])->name('web.faltas-turmas-funcionarios-get');
    Route::post('/turmas/faltas-funcionarios-post', [TurmaController::class, 'faltasTurmasFuncionariosPost'])->name('web.faltas-turmas-funcionarios-post');
    Route::get('/turmas/faltas-funcionarios-justificar/{id}', [WebController::class, 'faltasTurmasFuncionariosjustificar'])->name('web.faltas-turmas-funcionarios-justifcar');
    Route::get('/turmas/mapa-efectividade', [TurmaController::class, 'mapaEfectividade'])->name('web.mapa-efectividade');
    Route::get('/turmas/documentacao-estudantes', [TurmaController::class, 'documentacaoEstudante'])->name('web.documentacao-estudantes');
    Route::get('/turmas/delcaracao-estudantes/{id}', [TurmaController::class, 'declaracaoEstudante'])->name('web.declaracao-estudantes');
    Route::get('/turmas/notas-estudantes/{id}', [TurmaController::class, 'notasEstudante'])->name('web.notas-estudante-turmas');


    ##################################################################################################################
    ######################################## END TURMAS ##############################################################
    ##################################################################################################################



    ##################################################################################################################
    ######################################## NOVAS ROUTAS DE IMPRESSAO ##############################################################
    ##################################################################################################################

    Route::get('/turmas/imprimir', [PrinterController::class, 'turmasImprimir'])->name('turmas-imprmir');
    Route::get('/turmas/excel', [PrinterController::class, 'turmasExcel'])->name('turmas-excel');
    Route::get('/turma-estudantes/{id}/imprimir', [PrinterController::class, 'turmaEstudantesImprimir'])->name('turmas-estudantes-imprmir');
    Route::get('/turma-professores/{id}/imprimir', [PrinterController::class, 'turmaProfessoresImprimir'])->name('turmas-professores-imprmir');
    Route::get('/turma-disciplinas/{id}/imprimir', [PrinterController::class, 'turmaDisciplinasImprimir'])->name('turma-disciplinas-imprmir');
    Route::get('/turma-horarios/{id}/imprimir', [PrinterController::class, 'turmaHorarioImprimir'])->name('turma-horarios-imprmir');
    Route::get('/turma-servicos/{id}/imprimir', [PrinterController::class, 'turmaServicoImprimir'])->name('turma-servicos-imprmir');

    Route::get('listagem-servicos-imprimir', [PrinterController::class, 'listagemServicosImprimir'])->name('financeiros.listagem-servicos-imprmir');


    Route::get('/servicos/imprimir', [PrinterController::class, 'calendariosImprimir'])->name('calandarios-imprmir');
    Route::get('/servicos/excel', [PrinterController::class, 'calendariosExcel'])->name('calandarios-excel');

    Route::get('/disciplinas-curso/{id}/imprimir', [PrinterController::class, 'disciplinasCursosImprimir'])->name('disciplinas-curso-imprmir');
    Route::get('/funcionarios/imprimir', [PrinterController::class, 'funcionariosImprimir'])->name('funcionarios-imprmir');
    Route::get('/estudantes/imprimir', [PrinterController::class, 'estudantesImprimir'])->name('estudantes-imprmir');
    Route::get('/estudantes/estagiarios-imprimir', [PrinterController::class, 'estudantesEstagiarioImprimir'])->name('estudantes-estagiarios-imprmir');
    Route::get('/estudantes/boseiros-imprimir', [PrinterController::class, 'estudantesBolseiroImprimir'])->name('estudantes-bolseiros-imprmir');
    Route::get('/estudantes-matriculas/imprimir', [PrinterController::class, 'estudantesMatriculasImprimir'])->name('estudantes-matriculas-imprmir');
    Route::get('/estudantes-matriculados-confirmados/imprimir', [PrinterController::class, 'estudantesMatriculadoConfirmadoImprimir'])->name('estudantes-matriculados-confirmados-imprmir');
    Route::get('/estudantes-inscricoes/imprimir', [PrinterController::class, 'estudantesInscricoesImprimir'])->name('estudantes-inscricoes-imprmir');
    Route::get('/estudantes-inscricoes-exame-acesso/imprimir', [PrinterController::class, 'estudantesInscricoesExameAcessoImprimir'])->name('estudantes-inscricoes-imprmir-exame-acesso');
    Route::get('/estudantes-inscricoes-aceites/imprimir', [PrinterController::class, 'estudantesInscricoesAceiteImprimir'])->name('estudantes-inscricoes-aceite-imprmir');
    Route::get('/listagem-todas-escolas/imprimir', [PrinterController::class, 'listagemEscolaImprimir'])->name('print.listagem-escola-imprmir');
    Route::get('/munucipio-listagem-todas-escolas/imprimir', [PrinterController::class, 'municipiolistagemEscolaImprimir'])->name('print.municipio-listagem-escola-imprmir');
    Route::get('/provincial-listagem-todas-escolas/imprimir', [PrinterController::class, 'provinciallistagemEscolaImprimir'])->name('print.provincial-listagem-escola-imprmir');

    // EXCEL
    Route::get('/estudantes/imprimir-excel', [PrinterExcelController::class, 'estudantesImprimirExcel'])->name('estudantes-imprmir-excel');
    Route::get('/estudantes/estagiarios-imprimir-excel', [PrinterExcelController::class, 'estudantesEstagiarioImprimirExcel'])->name('estudantes-estagiarios-imprmir-excel');
    Route::get('/estudantes/bolseiros-imprimir-excel', [PrinterExcelController::class, 'estudantesBolseiroImprimirExcel'])->name('estudantes-bolseiros-imprmir-excel');
    Route::get('/estudantes-matriculas/imprimir-excel', [PrinterExcelController::class, 'estudantesMatriculasImprimirExcel'])->name('estudantes-matriculas-imprmir-excel');
    Route::get('/estudantes/listagem-estudantes-turma/{code}', [PrinterExcelController::class, 'estudanteTurma'])->name('estudantes-turmas-excel');

    Route::get('/listagem-direccoes-provincias/imprimir', [DireccaoProvinciaController::class, 'imprimir'])->name('print.listagem-direccoes-provincias-imprmir');

    Route::get('/listagem-professores-escolas/imprimir', [PrinterController::class, 'listagemProfessorEscolaImprimir'])->name('print.listagem-professores-escola-imprmir');
    Route::get('/listagem-estudantes-escolas/imprimir', [PrinterController::class, 'listagemEstudanteEscolaImprimir'])->name('print.listagem-estudantes-escola-imprmir');
    Route::get('/listagem-todos-professores/imprimir', [PrinterController::class, 'listagemTodosProfessorImprimir'])->name('print.listagem-todos-professores-imprmir');
    Route::get('/listagem-todos-estudantes/imprimir', [PrinterController::class, 'listagemTodosEstudantesImprimir'])->name('print.listagem-todos-estudantes-imprmir');
    Route::get('/encarregados/imprimir', [PrinterController::class, 'encarregadosImprimir'])->name('encarregados-imprmir');
    Route::get('/estatistica-estudantes/imprimir', [PrinterController::class, 'estatisticaEstudanteImprimir'])->name('print.estatistica-estudantes-imprmir');

    Route::get('/listagem-professores-provincial/imprimir', [PrinterController::class, 'listagemProfessorProvincialImprimir'])->name('print.listagem-professores-provincial-imprmir');
    Route::get('/pesquisa-sem-resultado/{message?}', [outraRotasController::class, 'pesquisaSemResultado'])->name('pesquisa-sem-resultado');


    Route::prefix('graficos')->group(function () {
        Route::get('/grafico/turma', [GraficoController::class, 'graficoTurma'])->name('grafico.turma');
        Route::get('/grafico/funcionarios', [GraficoController::class, 'graficoFuncionarios'])->name('grafico.funcionarios');

        Route::get('/grafico/provincial-funcionarios', [GraficoController::class, 'provincialGraficoFuncionarios'])->name('grafico.provincial-funcionarios');
        Route::get('/grafico/municipal-funcionarios', [GraficoController::class, 'municipalGraficoFuncionarios'])->name('grafico.municipal-funcionarios');

        Route::get('/provincial-grafico/estatistica', [GraficoController::class, 'provincialGraficoEstatistica'])->name('provincial-grafico-turma');
        Route::get('/municipal-grafico/estatistica', [GraficoController::class, 'municipalGraficoEstatistica'])->name('municipal-grafico-turma');
    });


    Route::get('/candidatura/aprovar/{ficha}', [AprovarCandidaturaController::class, 'aprovarCandidatura'])->name('web.aprovar-candidatura');
    Route::get('/finalizar-candidatura/aprovar/{ficha}', [AprovarCandidaturaController::class, 'finalizarAprovarCandidatura'])->name('web.finalizar-aprovar-candidatura');


    ##################################################################################################################
    ######################################## FORMULARIOS ##############################################################
    ##################################################################################################################

    Route::get('/provincial-formularios/fichas', [FormularioProvincialController::class, 'provincialFormularioFicha'])->name('app.provincial-fornulario-ficha');

    Route::get('/formulario-provincial/iniciacao', [FormularioProvincialController::class, 'iniciacao'])->name('app.formulario.provincial.iniciacao');
    Route::get('/formulario-provincial/ficha-afep-iniciacao', [FormularioProvincialController::class, 'fichaAFEPIniciacao'])->name('app.formulario.provincial.ficha-afep-iniciacao');

    Route::get('/formulario-provincial/primario-regular', [FormularioProvincialController::class, 'primarioRegular'])->name('app.formulario.provincial.primario.regular');
    Route::get('/formulario-provincial/ficha-afep-ensino-primario-regular', [FormularioProvincialController::class, 'fichaAFEPEnsinoPrimarioRegular'])->name('app.formulario.provincial.ficha-afep-ensino-primario.regular');

    Route::get('/formulario-provincial/primario-ciclo-regular', [FormularioProvincialController::class, 'primarioCicloRegular'])->name('app.formulario.provincial.primario-ciclo.regular');
    Route::get('/formulario-provincial/ficha-afep-ensino-primario-ciclo-regular', [FormularioProvincialController::class, 'fichaAFEPEnsinoPrimarioCicloRegular'])->name('app.formulario.provincial.ficha-afep-ensino-primario-ciclo.regular');

    Route::get('/formulario-provincial/segundo-ciclo-regular', [FormularioProvincialController::class, 'segundoCicloRegular'])->name('app.formulario.provincial.segundo-ciclo.regular');
    Route::get('/formulario-provincial/ficha-afep-ensino-segundo-ciclo-regular', [FormularioProvincialController::class, 'fichaAFEPEnsinoSegundoCicloRegular'])->name('app.formulario.provincial.ficha-afep-ensino-segundo-ciclo.regular');


    Route::get('/formulario/iniciacao', [FormularioController::class, 'iniciacao'])->name('web.formulario.iniciacao');
    Route::get('/formulario/ficha-afep-iniciacao', [FormularioController::class, 'fichaAFEPIniciacao'])->name('web.formulario.ficha-afep-iniciacao');

    Route::get('/formulario/primario-regular', [FormularioController::class, 'primarioRegular'])->name('web.formulario.primario.regular');
    Route::get('/formulario/ficha-afep-ensino-primario-regular', [FormularioController::class, 'fichaAFEPEnsinoPrimarioRegular'])->name('web.formulario.ficha-afep-ensino-primario.regular');

    Route::get('/formulario/primario-ciclo-regular', [FormularioController::class, 'primarioCicloRegular'])->name('web.formulario.primario-ciclo.regular');
    Route::get('/formulario/ficha-afep-ensino-primario-ciclo-regular', [FormularioController::class, 'fichaAFEPEnsinoPrimarioCicloRegular'])->name('web.formulario.ficha-afep-ensino-primario-ciclo.regular');

    Route::get('/formulario/segundo-ciclo-regular', [FormularioController::class, 'segundoCicloRegular'])->name('web.formulario.segundo-ciclo.regular');
    Route::get('/formulario/ficha-afep-ensino-segundo-ciclo-regular', [FormularioController::class, 'fichaAFEPEnsinoSegundoCicloRegular'])->name('web.formulario.ficha-afep-ensino-segundo-ciclo.regular');


    Route::get('/formulario/imprimir-estudante-dificienca-relatorio', [FormularioController::class, 'imprimirRelatorioEstudanteDificiencia'])->name('web.formulario.imprimir-estudante-dificienca-relatorio');
    Route::get('/formulario/imprimir-turmas-turnos-relatorio', [FormularioController::class, 'imprimirRelatorioTurmasTurnos'])->name('web.formulario.imprimir-turmas-turnos-relatorio');
    Route::get('/formulario/imprimir-professores-por-nivel-relatorio', [FormularioController::class, 'imprimirRelatorioProfessorPorNivel'])->name('web.formulario.imprimir-professores-por-nivel-relatorio');
    Route::get('/formulario/imprimir-professores-por-idade-relatorio', [FormularioController::class, 'imprimirRelatorioProfessorPorIdade'])->name('web.formulario.imprimir-professores-por-idade-relatorio');

    ##################################################################################################################
    ######################################## COmunucados ##############################################################
    ##################################################################################################################

    Route::get('escolher-comunicados', [ComunicadoController::class, 'home'])->name('comunicados.home');
    Route::get('comunicadar-encarregados', [ComunicadoController::class, 'comunicar_encarregados'])->name('comunicados.comunicadar-encarregados');
    Route::post('comunicadar-encarregados', [ComunicadoController::class, 'comunicar_encarregados_store'])->name('comunicados.comunicadar-encarregados-store');
    Route::resource('comunicados', ComunicadoController::class);
    Route::get('comunicados-imprimir/{id}', [ComunicadoController::class, 'imprimir'])->name('comunicados-imprimir');
    Route::resource('qr-code', QRCodeController::class);
    Route::get('/controle/entrada-saidas-estudantes', [QRCodeController::class, 'home'])->name('web.controle-entrada-saida-estudantes');
    Route::post('confirmar-entrada', [QRCodeController::class, 'confirmar_entrada'])->name('web.confirmar-entrada');
    Route::post('confirmar-saida', [QRCodeController::class, 'confirmar_saida'])->name('web.confirmar-saida');
});

Route::post("enviar-comprovativo", [SiteController::class, 'comprovativo'])->name('enviar-comprovativo');

Route::get('/', [WebController::class, 'homePrincipal'])->name('site.home-principal');

// Route::get('/', [SiteController::class, 'home'])->middleware('ensure.param')->name('site.home-principal');
Route::get('/cursos-disponiveis', [SiteController::class, 'cursos'])->middleware('ensure.param')->name('site.cursos-disponiveis');
Route::get('/cursos-disponiveis-detalhe', [SiteController::class, 'cursos_detalhe'])->middleware('ensure.param')->name('site.cursos-disponiveis-detalhe');
Route::get('/noticia-disponiveis-detalhe', [SiteController::class, 'noticia_detalhe'])->middleware('ensure.param')->name('site.noticia-disponiveis-detalhe');
Route::get('/candidatura-inscricoes', [SiteController::class, 'candidaturas'])->middleware('ensure.param')->name('site.candidatura-inscricoes');
Route::get('/preencher-dados-submiter-comprovativo', [SiteController::class, 'formulario_comprovativo'])->middleware('ensure.param')->name('site.formulario-submiter-comprovativo');
Route::get('/preencher-dados-candidatura-inscricoes', [SiteController::class, 'formulario'])->middleware('ensure.param')->name('site.formulario-candidatura-inscricoes');
Route::post('/preencher-dados-candidatura-inscricoes', [SiteController::class, 'formulario_post'])->name('site.formulario-candidatura-inscricoes-post');
Route::get('/consultar-candidatura-inscricoes', [SiteController::class, 'consultar_candidatura'])->middleware('ensure.param')->name('site.consultar-candidatura-inscricoes');
Route::get('/candidatura/ficha-candidatura/{ficha}', [SiteController::class, 'ficha_candidatura'])->name('site.ficha-candidatura');
Route::get('/candidatura/ficha-factura-candidatura/{ficha}/{matricula}', [SiteController::class, 'ficha_factura_candidatura'])->name('site.ficha-factura-candidatura');

/**
 * paises
 */
Route::get('/carregar-bolsas-instituicao/{id}', [HelperController::class, 'getBolsasInstituicao'])->name('web.carregar-bolsas-instituicao');
Route::get('/get_states/{id}', [HelperController::class, 'getStates'])->name('web.carregar-provincias');
Route::get('/carregar-municipios/{id}', [HelperController::class, 'getMunicipio'])->name('web.carregar-municipios');
Route::get('/carregar-distritos/{id}', [HelperController::class, 'getDistritos'])->name('web.carregar-distritos');
Route::get('/carregar-privincia-municipios-distrito-estrageiros/{id}', [HelperController::class, 'getEnderecoEstrageiro'])->name('web.carregar-privincia-municipios-distrito-');
Route::get('/carregar-escolas-distrito/{id}', [HelperController::class, 'getEscolaDistritos'])->name('web.carregar-escolas-distritos');
Route::get('/carregar-escolas-municipio/{id}', [HelperController::class, 'getEscolaMunicipio'])->name('web.carregar-escolas-municipios');
Route::get('/get_states_name/{id}', [HelperController::class, 'getStatesName'])->name('web.carregar-pronvicia-nome');
Route::get('/get_dados_escolas/{id}', [HelperController::class, 'getEscolaDados'])->name('web.carregar-dados-escolas');
Route::get('/get-dados-ano-lectivo/{id}', [HelperController::class, 'getAnoLectivoDados'])->name('web.carregar-ano-lectivo');
Route::get('/carregar-destino-funcionarios/{id}', [HelperController::class, 'getDestinoFuncionario'])->name('web.carregar-destino-funcionarios');
Route::get('/carregar-cargos-departamentos/{id}', [HelperController::class, 'getCargoDepartamento'])->name('web.carregar-cargos-departamentos');

Route::get('/carregar-disciplinas-turma/{id}', [HelperController::class, 'carregarDisciplinaTurma'])->name('web.carregar-disciplinas-turma');
Route::get('/carregar-todos-anolectivos-escolas/{id}', [HelperController::class, 'carregarTodosAnoLectivosEscola'])->name('web.carregar-todos-anolectivos-escola');
Route::get('/carregar-todas-turmas-anolectivos-escolas/{id}', [HelperController::class, 'carregarTodasTurmasAnoLectivosEscola'])->name('web.carregar-todas-turmas-anolectivos-escola');
Route::get('/voltar/{url?}', [HelperController::class, 'route_back_all_page'])->name('web.route_back_all_page');

Route::get('/carregar-ano-lectivos-escolas/{id}', [HelperController::class, 'getAnoLectivoEscola'])->name('web.carregar-ano-lectivos-escola');
Route::get('/carregar-turmas-professores-escolas/{id}/{prof?}', [HelperController::class, 'getTurmasProfessorEscola'])->name('web.carregar-turma-professor-escola');
Route::get('/carregar-disciplinas-turmas-professores-escolas/{id}', [HelperController::class, 'getDisciplinaTurmasProfessorEscola'])->name('web.carregar-disciplinas-turma-professor-escola');


// ROUTAS AUXILIARES

Route::get('/actualizacoes-hash/{tipo_documento}', [HomeController::class, 'actualizacoes_hash'])->name('shcools.actualizacoes-hash');
Route::get('/actualizacoes_recentes', [HomeController::class, 'actualizacoes_recentes'])->name('shcools.actualizacoes_recentes');
Route::get('/actualizacoes-dos-nomes', [HomeController::class, 'actualizacoes_dos_nomes'])->name('shcools.actualizacoes_dos_nomes');
Route::get('/actualizacoes-cartoes', [HomeController::class, 'actualizacoes_cartao'])->name('shcools.actualizacoes_dos_cartao');

