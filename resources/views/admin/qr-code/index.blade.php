@extends('layouts.escolas')
@section('styles')
<style>
    .qr-wrapper {
        position: relative;
        width: 100%;
        max-width: 400px;
        aspect-ratio: 1/1;
        margin: 0 auto;
    }

    #reader {
        width: 100%;
        height: 100%;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .scanner-frame {
        position: absolute;
        border: 3px solid #00ff00;
        border-radius: 10px;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        box-sizing: border-box;
        z-index: 2;
    }

    .scan-line {
        position: absolute;
        width: 100%;
        height: 3px;
        background: #00ff00;
        animation: scan 2s infinite linear;
        z-index: 3;
    }

    @keyframes scan {
        0% { top: 0; }
        100% { top: calc(100% - 3px); }
    }

    .qr-msg {
        margin-top: 20px;
        color: #28a745;
        text-align: center;
        font-weight: bold;
    }
</style>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
    <div class="container-fluid bg-dark text-white min-vh-100 d-flex justify-content-center align-items-center flex-column">
        <h2 class="mb-4">Leitor de QR Code</h2>
        
        <div class="qr-wrapper">
            <div id="reader"></div>
            <div class="scanner-frame"></div>
            <div class="scan-line"></div>
        </div>
    
        <div class="qr-msg">
            Aproxime o cartão do estudante com o QR Code
        </div>

        <!-- Modal de Dados do Estudante -->
        <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="qrModalLabel">Dados do Estudante</h5>
                        <button type="button" class="btn-close fechar_modal" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row carregar_dados_estudante">
                        </div>
                    </div>
                    <div class="modal-footer text-center justify-center">
                        <a href="#" id="confirmar_presenca_entrada_estudante_id" class="bg-success btn-app col-md-6 col-12 text-center">
                            <i class="fas fa-sign-in-alt"></i> Confirmar Entrada
                        </a>
                        <a href="#" id="confirmar_presenca_saida_estudante_id" class="bg-danger btn-app col-md-5 col-12 text-center">
                            <i class="fas fa-sign-out-alt"></i> Confirmar Saída
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@section('scripts')
<script>
    
let qrModal = new bootstrap.Modal(document.getElementById("qrModal"));
let turmaId = null;
let estudanteId = null;

document.addEventListener("DOMContentLoaded", function () {
    function onScanSuccess(decodedText, decodedResult) {
        // Opcional: Enviar os dados para o backend Laravel via AJAX
        fetch("{{ route('qr-code.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ codigo_qr: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                            
                let mesesHtml = '<h5 class="mt-3">Situação Mensal</h5><div class="border p-2 row">';
                data.cartao.forEach((item) => {
                    let cor = item.status === 'Pago' ? 'bg-success' : (item.status === 'Isento' ? 'bg-secondary':  (item.status === 'divida' ? 'bg-danger' : 'bg-warning') ) ;
                    mesesHtml += `
                        <div class="col-6 col-md-4 mb-2 border p-2">
                            <span class="text-dark">${descricao_mes(item.month_name)}:</span> 
                            <span class="badge ${cor}">${item.status}</span>
                        </div>
                    `;
                });
                mesesHtml += '</div>';
                
                turmaId = data.turma_id;
                estudanteId = data.estudante_id;
                
                $(".carregar_dados_estudante").html(""); // Limpa conteúdo anterior
                $(".carregar_dados_estudante").append(`
                    <div class="col-12 col-md-7">
                        <p class="text-dark"><strong>Nome:</strong> ${data.nome}</p>
                        <p class="text-dark"><strong>Classe Anterior:</strong> ${data.classe_at}</p>
                        <p class="text-dark"><strong>Classe:</strong> ${data.classe}</p>
                        <p class="text-dark"><strong>Curso:</strong> ${data.curso}</p>
                        <p class="text-dark"><strong>Turno:</strong> ${data.turno}</p>
                        <p class="text-dark"><strong>Turma:</strong> ${data.turma}</p>
                        <p class="text-dark"><strong>Ano Lectivo:</strong> ${data.ano}</p>
                    </div>
                    <div class="col-12 col-md-5">
                        <img id="estudanteImagem" src="${data.imagem}" class="img-thumbnail" alt="${data.nome}" title="${data.nome}">
                    </div>
                    <div class="col-12 col-md-12">
                        ${mesesHtml}
                    </div>
                `);
            } else {
                $(".carregar_dados_estudante").html(`
                    <div class="col-12">
                        <p class="text-danger fw-bold">Estudante não encontrado</p>
                    </div>
                `);
            }
            // Abrir o modal usando Bootstrap
            qrModal.show();
            
            // Fechar a modal ao clicar no botão de fechar
            document.querySelector("#qrModal .fechar_modal").addEventListener("click", function () {
                qrModal.hide();
            });

        })
        .catch(error => console.error("Erro ao buscar estudante:", error));
    }

    let html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" }, // Usa a câmera traseira
        { fps: 10, qrbox: { width: 250, height: 250 } },
        onScanSuccess
    ).catch(err => console.log("Erro ao iniciar scanner:", err));
});

document.getElementById("confirmar_presenca_entrada_estudante_id").addEventListener("click", function () {
    progressBeforeSend();
    
    fetch("{{ route('web.confirmar-entrada') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },

        body: JSON.stringify({ estudante_id: estudanteId, turma_id: turmaId })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.sucesso) {
            showMessage('Sucesso!', 'Saída registrada com sucesso!', 'success');
        } else {
            showMessage('Erro!', 'Erro ao registrar Saída!', 'error');
        }
        qrModal.show();
        window.location.reload();
    })
    .catch(error => console.error("Erro:", error));
});

document.getElementById("confirmar_presenca_saida_estudante_id").addEventListener("click", function () {
    
    progressBeforeSend();
    
    fetch("{{ route('web.confirmar-saida') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ estudante_id: estudanteId, turma_id: turmaId  })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.sucesso) {
            showMessage('Sucesso!', 'Saída registrada com sucesso!', 'success');
        } else {
            showMessage('Erro!', 'Erro ao registrar Saída!', 'error');
        }
        qrModal.show();
        window.location.reload();
    })
    .catch(error => console.error("Erro:", error));
});


function descricao_mes(mes)
{
    if(mes == "Sep"){ return "Setembro"; } else
    if(mes == "Oct"){ return "Outobro"; } else
    if(mes == "Nov"){ return "Novembro"; } else
    if(mes == "Dec"){ return "Dezembro"; } else
    if(mes == "Jan"){ return "Janeiro"; } else
    if(mes == "Feb"){ return "Fevereiro"; } else
    if(mes == "Mar"){ return "Março"; } else
    if(mes == "Apr"){ return "Abril"; } else
    if(mes == "May"){ return "Maio"; } else
    if(mes == "Jun"){ return "Junho"; } else
    if(mes == "Jul"){ return "Julho"; } else
    if(mes == "Aug"){ return "Agosto"; } else { return "Outros"; }
}
</script>
@endsection
