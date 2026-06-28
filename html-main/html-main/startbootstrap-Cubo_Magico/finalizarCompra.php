<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: formLogin.php");
    exit();
}

include "conexaoBD.php";
/** @var mysqli $conn */
include "header.php";

$idUsuario = $_SESSION['idUsuario'];

// Busca os itens do carrinho para calcular os valores reais do banco
$query = "SELECT c.quantidade, a.valorAnuncio, a.tituloAnuncio, a.fotoAnuncio 
          FROM carrinho c 
          INNER JOIN anuncios a ON c.idAnuncio = a.idAnuncio 
          WHERE c.idUsuario = '$idUsuario'";
$resultado = mysqli_query($conn, $query);

$totalGeral = 0;
$totalItens = 0;
$itensCarrinho = [];

while ($item = mysqli_fetch_assoc($resultado)) {
    $totalGeral += ($item['valorAnuncio'] * $item['quantidade']);
    $totalItens += $item['quantidade'];
    $itensCarrinho[] = $item;
}

if ($totalItens == 0) {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
?>

<main class="py-5 bg-checkout min-vh-100">
    <div class="container" style="max-width: 1100px;">
        
        <div class="mb-4 text-center text-md-start">
            <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Finalizar seu Pedido</h2>
            <p class="text-muted small mb-0">Insira seus dados e escolha a forma de pagamento para concluir.</p>
        </div>

        <form action="index.php" method="POST" class="row g-4">
            
            <div class="col-lg-7">
                <div class="d-flex flex-column gap-4">
                    
                    <div class="card-custom">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="step-number">1</span>
                            <h5 class="fw-bold m-0 text-dark fs-6 text-uppercase tracking-wide">Informações de Entrega</h5>
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label-custom">Nome Completo do Destinatário</label>
                                <input type="text" class="form-control input-custom" value="<?php echo $_SESSION['nomeUsuario'] ?? ''; ?>" required placeholder="Ex: Elaine Bernardo">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">CEP</label>
                                <input type="text" class="form-control input-custom" placeholder="00000-000" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label-custom">Endereço / Logradouro</label>
                                <input type="text" class="form-control input-custom" placeholder="Av, Rua, Alamedas..." required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Número</label>
                                <input type="text" class="form-control input-custom" placeholder="123" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label-custom">Bairro</label>
                                <input type="text" class="form-control input-custom" placeholder="Centro" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Cidade</label>
                                <input type="text" class="form-control input-custom" value="Curiúva" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Estado</label>
                                <input type="text" class="form-control input-custom" value="Paraná" required>
                            </div>
                        </div>
                    </div>

                    <div class="card-custom">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="step-number">2</span>
                            <h5 class="fw-bold m-0 text-dark fs-6 text-uppercase tracking-wide">Método de Pagamento</h5>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="metodoPagamento" id="pay-pix" value="pix" checked onclick="alternarFormulario('pix')">
                                <label class="btn btn-outline-payment w-100 py-3 rounded-3" for="pay-pix">
                                    <i class="bi bi-qr-code fs-4 mb-1 d-block text-success"></i>
                                    <span class="fw-bold small d-block">Pagar com Pix</span>
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="metodoPagamento" id="pay-card" value="cartao" onclick="alternarFormulario('cartao')">
                                <label class="btn btn-outline-payment w-100 py-3 rounded-3" for="pay-card">
                                    <i class="bi bi-credit-card fs-4 mb-1 d-block text-primary"></i>
                                    <span class="fw-bold small d-block">Cartão de Crédito</span>
                                </label>
                            </div>
                        </div>

                        <div id="container-pix" class="payment-box p-3 rounded-3 text-center bg-light border border-dashed">
                            <i class="bi bi-lightning-charge-fill text-success fs-4 mb-1 d-block"></i>
                            <span class="d-block fw-semibold text-dark small">Código PIX gerado ao finalizar</span>
                            <p class="text-muted small mb-0 px-2 mt-1">O código "Copia e Cola" aparecerá na tela seguinte. O envio é imediato.</p>
                        </div>

                        <div id="container-cartao" class="payment-box d-none">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label-custom">Número do Cartão</label>
                                    <input type="text" class="form-control input-custom" placeholder="0000 0000 0000 0000">
                                </div>
                                <div class="col-12">
                                    <label class="form-label-custom">Nome Completo do Titular</label>
                                    <input type="text" class="form-control input-custom" placeholder="Como impresso no cartão">
                                </div>
                                <div class="col-6">
                                    <label class="form-label-custom">Validade</label>
                                    <input type="text" class="form-control input-custom" placeholder="MM/AA">
                                </div>
                                <div class="col-6">
                                    <label class="form-label-custom">Código CVV</label>
                                    <input type="text" class="form-control input-custom" placeholder="123">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card-custom position-sticky" style="top: 24px;">
                    <h5 class="fw-bold mb-4 text-dark fs-6 text-uppercase tracking-wide">Resumo da Compra</h5>
                    
                    <div class="mb-3 scroll-itens pe-1" style="max-height: 160px; overflow-y: auto;">
                        <?php foreach ($itensCarrinho as $item): 
                            $fotoItem = !empty($item['fotoAnuncio']) ? $item['fotoAnuncio'] : 'img/3x3.jpg';
                        ?>
                            <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?php echo $fotoItem; ?>" class="rounded-2 border" style="width: 44px; height: 44px; object-fit: cover;" alt="Cubo">
                                    <div>
                                        <span class="d-block small fw-bold text-dark text-truncate" style="max-width: 160px;"><?php echo $item['tituloAnuncio']; ?></span>
                                        <small class="text-muted">Quantidade: <?php echo $item['quantidade']; ?></small>
                                    </div>
                                </div>
                                <span class="small fw-bold text-dark">R$ <?php echo number_format($item['valorAnuncio'] * $item['quantidade'], 2, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="py-3 border-top border-light">
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span>Subtotal</span>
                            <span>R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between text-muted small mb-0">
                            <span>Frete</span>
                            <span class="text-success fw-bold">Grátis</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 mb-4 border-top">
                        <span class="fw-bold text-dark">Valor Total:</span>
                        <span class="fw-extrabold text-dark fs-3">R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></span>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 py-3 rounded-3 fw-bold text-uppercase tracking-wide shadow-sm btn-submit-order">
                        <i class="bi bi-bag-check me-2"></i>Finalizar Pedido
                    </button>
                    
                    <a href="carrinho.php" class="btn btn-link btn-sm text-center w-100 text-muted text-decoration-none mt-3 small hover-underline">
                        <i class="bi bi-arrow-left me-1"></i>Voltar ao carrinho
                    </a>
                </div>
            </div>

        </form>
    </div>
</main>

<script>
function alternarFormulario(tipo) {
    const pixBox = document.getElementById('container-pix');
    const cartaoBox = document.getElementById('container-cartao');
    
    if (tipo === 'pix') {
        pixBox.classList.remove('d-none');
        cartaoBox.classList.add('d-none');
    } else {
        pixBox.classList.add('d-none');
        cartaoBox.classList.remove('d-none');
    }
}
</script>

<style>
    .bg-checkout {
        background-color: #f6f8fa;
    }
    /* Cards brancos modernos com cantos suaves */
    .card-custom {
        background: #ffffff;
        border: 1px solid #e1e4e6;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }
    /* Círculo do passo a passo (1 e 2) */
    .step-number {
        background: #111111;
        color: #ffffff;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
    }
    /* Inputs refinados */
    .input-custom {
        border: 1px solid #ced4da;
        padding: 10px 14px;
        font-size: 0.9rem;
        border-radius: 8px;
        transition: border-color 0.2s ease;
    }
    .input-custom:focus {
        border-color: #111111;
        box-shadow: none;
    }
    .form-label-custom {
        font-size: 0.78rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 4px;
    }
    /* Estilização das caixas de escolha de pagamento */
    .btn-outline-payment {
        border: 2px solid #e1e4e6;
        background: #fff;
        color: #495057;
        transition: all 0.2s ease;
    }
    .btn-check:checked + .btn-outline-payment {
        border-color: #111111 !important;
        background: #fafafa;
        color: #111111;
    }
    .border-dashed {
        border-style: dashed !important;
    }
    .scroll-itens::-webkit-scrollbar {
        width: 4px;
    }
    .scroll-itens::-webkit-scrollbar-thumb {
        background: #e1e4e6;
        border-radius: 4px;
    }
    .btn-submit-order {
        background: #111111;
        border: none;
        transition: background 0.2s ease;
    }
    .btn-submit-order:hover {
        background: #2a2a2a;
    }
    .hover-underline:hover {
        text-decoration: underline !important;
        color: #111111 !important;
    }
</style>

<?php 
mysqli_close($conn);
include "footer.php"; 
?>