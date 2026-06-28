<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "conexaoBD.php";
/** @var mysqli $conn */
include "header.php";

// 🔐 TRAVA DE SEGURANÇA: Só quem está logado pode ver o seu carrinho
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    $_SESSION['mensagem_erro'] = "Você precisa fazer login para acessar o carrinho.";
    header("Location: formLogin.php");
    exit();
}

$idUsuario = $_SESSION['idUsuario'];

// Busca os itens do carrinho fazendo um INNER JOIN com a tabela de anúncios para pegar os dados do cubo
$query = "SELECT c.idCarrinho, c.quantidade, a.tituloAnuncio, a.valorAnuncio, a.fotoAnuncio, a.idAnuncio 
          FROM carrinho c 
          INNER JOIN anuncios a ON c.idAnuncio = a.idAnuncio 
          WHERE c.idUsuario = '$idUsuario'
          ORDER BY c.idCarrinho DESC";

$resultado = mysqli_query($conn, $query);
?>

<section class="py-5 bg-light min-vh-100">
    <div class="container px-4">
        <div class="text-center mb-5">
            <h2 class="fw-bolder text-dark text-uppercase"><i class="bi bi-cart3 me-2 text-warning"></i>Seu Carrinho</h2>
            <p class="lead text-muted">Confira os cubos mágicos selecionados antes de fechar o pedido.</p>
            <hr class="w-25 mx-auto text-warning" style="height: 3px;">
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
                    <?php if (mysqli_num_rows($resultado) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col" class="ps-3">Produto</th>
                                        <th scope="col">Preço</th>
                                        <th scope="col" class="text-center" style="width: 100px;">Qtd</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col" class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $totalGeral = 0;
                                    while($item = mysqli_fetch_assoc($resultado)): 
                                        $subtotal = $item['valorAnuncio'] * $item['quantidade'];
                                        $totalGeral += $subtotal;
                                    ?>
                                        <tr>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="<?php echo $item['fotoAnuncio']; ?>" class="rounded shadow-sm border" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <div>
                                                        <a href="detalhesProduto.php?id=<?php echo $item['idAnuncio']; ?>" class="text-decoration-none text-dark fw-bold"><?php echo $item['tituloAnuncio']; ?></a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-bold text-secondary">R$ <?php echo number_format($item['valorAnuncio'], 2, ',', '.'); ?></td>
                                            <td class="text-center fw-bold"><?php echo $item['quantidade']; ?></td>
                                            <td class="text-success fw-bold">R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                                            <td class="text-center">
                                                <a href="removerCarrinho.php?id=<?php echo $item['idCarrinho']; ?>" class="btn btn-sm btn-outline-danger" title="Remover item">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 fs-5">Seu carrinho está vazio no momento.</p>
                            <a href="index.php" class="btn btn-warning fw-bold text-dark mt-2 shadow-sm">Voltar às Compras</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (mysqli_num_rows($resultado) > 0): ?>
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-3 p-4 bg-dark text-white">
                        <h4 class="fw-bold mb-4 text-warning border-bottom pb-2">Resumo do Pedido</h4>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-white-50">Itens selecionados:</span>
                            <span class="fw-bold"><?php echo mysqli_num_rows($resultado); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-4 border-top pt-3 fs-4">
                            <span class="fw-bold text-warning">Total:</span>
                            <span class="fw-bold text-success">R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></span>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="finalizarCompra.php" class="btn btn-warning btn-lg fw-bold text-dark shadow-sm">
                                <i class="bi bi-credit-card-2-back-fill me-2"></i> Finalizar Compra
                            </a>
                            <a href="index.php" class="btn btn-outline-light btn-sm">Continuar Comprando</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
mysqli_close($conn);
include "footer.php";
?>