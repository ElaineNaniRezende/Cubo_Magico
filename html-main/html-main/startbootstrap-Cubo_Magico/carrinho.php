<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "conexaoBD.php";
/** @var mysqli $conn */
include "header.php";

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    $_SESSION['mensagem_erro'] = "Você precisa fazer login para acessar o carrinho.";
    header("Location: formLogin.php");
    exit();
}

$idUsuario = $_SESSION['idUsuario'];

$query = "SELECT c.idCarrinho, c.quantidade, a.tituloAnuncio, a.valorAnuncio, a.fotoAnuncio, a.idAnuncio 
          FROM carrinho c 
          INNER JOIN anuncios a ON c.idAnuncio = a.idAnuncio 
          WHERE c.idUsuario = '$idUsuario'
          ORDER BY c.idCarrinho DESC";

$resultado = mysqli_query($conn, $query);
?>

<section class="py-5 bg-light min-vh-100">
    <div class="container">
        <div class="row mb-5 text-center">
            <div class="col-12">
                <h2 class="fw-extrabold text-dark tracking-tight text-uppercase mb-2" style="font-size: 2rem;">
                    <span class="border-bottom border-warning border-3 pb-2"><i class="bi bi-cart3 me-2 text-warning"></i>Meu Carrinho</span>
                </h2>
                <p class="text-muted mt-3">Gerencie suas escolhas antes de fechar o pedido</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-body p-4 bg-white">
                        <?php if (mysqli_num_rows($resultado) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead class="bg-light text-secondary text-uppercase fs-7 fw-bold" style="letter-spacing: 0.5px;">
                                        <tr class="border-bottom">
                                            <th scope="col" class="py-3 ps-3">Produto</th>
                                            <th scope="col" class="py-3">Preço</th>
                                            <th scope="col" class="py-3 text-center">Qtd</th>
                                            <th scope="col" class="py-3">Subtotal</th>
                                            <th scope="col" class="py-3 text-center">Remover</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $totalGeral = 0;
                                        while($item = mysqli_fetch_assoc($resultado)): 
                                            $subtotal = $item['valorAnuncio'] * $item['quantidade'];
                                            $totalGeral += $subtotal;
                                            
                                            $foto = !empty($item['fotoAnuncio']) ? $item['fotoAnuncio'] : 'img/3x3.jpg';
                                        ?>
                                            <tr class="border-bottom align-middle">
                                                <td class="py-3 ps-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <img src="<?php echo $foto; ?>" class="rounded-3 shadow-sm border object-cover" style="width: 65px; height: 65px; object-fit: cover;" alt="Foto do Produto">
                                                        <div>
                                                            <a href="detalhesProduto.php?id=<?php echo $item['idAnuncio']; ?>" class="text-decoration-none text-dark fw-bold h6 mb-0 d-block hover-warning"><?php echo $item['tituloAnuncio']; ?></a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3 fw-semibold text-secondary">R$ <?php echo number_format($item['valorAnuncio'], 2, ',', '.'); ?></td>
                                                
                                                <td class="py-3 text-center">
                                                    <div class="d-inline-flex align-items-center border rounded-3 bg-light p-1 shadow-sm">
                                                        <a href="atualizarQuantidade.php?id=<?php echo $item['idCarrinho']; ?>&operacao=subtrair" class="btn btn-sm btn-light border-0 px-2 py-1 text-secondary hover-dark d-flex align-items-center">
                                                            <i class="bi bi-dash-lg" style="font-size: 0.85rem;"></i>
                                                        </a>
                                                        
                                                        <span class="px-3 fw-bold text-dark" style="min-width: 35px; display: inline-block;">
                                                            <?php echo $item['quantidade']; ?>
                                                        </span>
                                                        
                                                        <a href="atualizarQuantidade.php?id=<?php echo $item['idCarrinho']; ?>&operacao=somar" class="btn btn-sm btn-light border-0 px-2 py-1 text-secondary hover-dark d-flex align-items-center">
                                                            <i class="bi bi-plus-lg" style="font-size: 0.85rem;"></i>
                                                        </a>
                                                    </div>
                                                </td>

                                                <td class="py-3 fw-bold text-dark">R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                                                <td class="py-3 text-center">
                                                    <a href="removerCarrinho.php?id=<?php echo $item['idCarrinho']; ?>" class="btn btn-sm btn-outline-danger rounded-circle p-2 border-0 hover-bg-danger" style="width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center;">
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
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="bi bi-cart-x text-muted fs-2"></i>
                                </div>
                                <h5 class="fw-bold text-dark">Seu carrinho está vazio</h5>
                                <p class="text-muted small">Nenhum cubo mágico foi adicionado ainda.</p>
                                <a href="index.php" class="btn btn-warning fw-bold text-dark px-4 py-2 mt-2 shadow-sm rounded-pill">Explorar Produtos</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (mysqli_num_rows($resultado) > 0): ?>
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 bg-dark text-white">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4 text-warning">Resumo do Pedido</h4>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3 text-white-50">
                                <span>Total de itens:</span>
                                <span class="fw-bold text-white"><?php echo mysqli_num_rows($resultado); ?></span>
                            </div>
                            
                            <hr class="border-secondary my-3">
                            
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fs-5 text-warning fw-semibold">Valor Total:</span>
                                <span class="fs-3 fw-extrabold text-success">R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></span>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="finalizarCompra.php" class="btn btn-warning btn-lg fw-bold text-dark shadow rounded-3 py-3 text-uppercase tracking-wider fs-6">
                                    <i class="bi bi-shield-check me-2"></i>Finalizar Comprar
                                </a>
                                <a href="index.php" class="btn btn-link btn-sm text-white-50 text-decoration-none mt-2 hover-white">
                                    <i class="bi bi-arrow-left me-1"></i> Continuar Comprando
                                </a>
                            </div>
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