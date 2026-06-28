<?php
// Inicia a sessão para sabermos quem está navegando
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui a conexão com o banco de dados
include "conexaoBD.php";
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['metodoPagamento'])) {
    
    // Defesa de Sessão: Garante que só usuários logados fecham pedidos
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        header("Location: formLogin.php");
        exit();
    }
    
    $idUsuario = $_SESSION['idUsuario'];
    
    // Operação de DELETE do CRUD do Carrinho (Simulação de compra finalizada)
    $queryLimpar = "DELETE FROM carrinho WHERE idUsuario = '$idUsuario'";
    $executou = mysqli_query($conn, $queryLimpar);
    
    if ($executou) {
        // Exibe o alerta visual de sucesso e atualiza a página limpando os dados do POST
        echo "<script>alert('Pedido finalizado com sucesso! Seu carrinho foi esvaziado.'); window.location.href='index.php';</script>";
        exit();
    }
}
// =========================================================================

// Inclui o cabeçalho moderno do seu site
include "header.php";
?>

<style>
    body {
        background-color: #f8f9fa; /* Fundo leve para destacar os cards brancos */
    }
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #ffffff;
        border: none !important;
    }
    /* Efeito chique ao passar o mouse */
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08) !important;
    }
    .product-img-container {
        position: relative;
        overflow: hidden;
        background-color: #f1f1f1;
    }
    .product-card img {
        transition: transform 0.5s ease;
    }
    /* Zoom sutil na foto ao passar o mouse */
    .product-card:hover img {
        transform: scale(1.04);
    }
    .btn-details {
        border: 2px solid #212529;
        color: #212529;
        background: transparent;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
    }
    .btn-details:hover {
        background: #212529;
        color: #ffc107; /* Amarelo combinando com a identidade da sua marca */
    }
</style>

<section class="py-5">
    <div class="container px-4 px-lg-5 mt-2">
        
        <div class="row justify-content-center mb-5">
            <div class="col-md-6 col-lg-5">
                <form action="index.php" method="GET" class="d-flex gap-2 bg-white p-2 rounded-3 shadow-sm border-0">
                    <select class="form-select border-0 bg-light text-secondary fw-medium py-2 ps-3" name="filtrarCategoria" style="outline: none; box-shadow: none;">
                        <option value="Todos" <?php if(!isset($_GET['filtrarCategoria']) || $_GET['filtrarCategoria'] == 'Todos') echo 'selected'; ?>>Exibir todos os cubos</option>
                        <option value="Cubo 2x2x2" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Cubo 2x2x2') echo 'selected'; ?>>Cubo 2x2x2</option>
                        <option value="Cubo 3x3x3" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Cubo 3x3x3') echo 'selected'; ?>>Cubo 3x3x3</option>
                        <option value="Cubo 4x4x4 e Maiores" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Cubo 4x4x4 e Maiores') echo 'selected'; ?>>Cubo 4x4x4 e Maiores</option>
                        <option value="Modificações / Pyraminx / Megaminx" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Modificações / Pyraminx / Megaminx') echo 'selected'; ?>>Modificações (Pyraminx...)</option>
                        <option value="Acessórios e Lubrificantes" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Acessórios e Lubrificantes') echo 'selected'; ?>>Acessórios e Lubrificantes</option>
                    </select>
                    <button type="submit" class="btn btn-dark d-flex align-items-center gap-2 fw-bold px-4 rounded-3 shadow-sm">
                        <i class="bi bi-funnel-fill text-warning"></i> Filtrar
                    </button>
                </form>
            </div>
        </div>

        <?php
        // Lógica de Consulta ao Banco com Filtro
        $categoriaSelecionada = isset($_GET['filtrarCategoria']) ? $_GET['filtrarCategoria'] : 'Todos';

        if ($categoriaSelecionada != 'Todos') {
            $sqlAnuncios = "SELECT * FROM anuncios WHERE categoriaAnuncio = '$categoriaSelecionada' AND statusAnuncio = 'disponivel' ORDER BY idAnuncio DESC";
        } else {
            $sqlAnuncios = "SELECT * FROM anuncios WHERE statusAnuncio = 'disponivel' ORDER BY idAnuncio DESC";
        }

        $resAnuncios = mysqli_query($conn, $sqlAnuncios);
        $totalProdutos = mysqli_num_rows($resAnuncios);

        // Badge indicador do total de modelos
        echo "
            <div class='text-center mb-5'>
                <span class='badge bg-white text-dark shadow-sm border px-3 py-2 rounded-pill fw-medium text-muted'>
                    A nossa vitrine conta com <strong class='text-dark'>$totalProdutos</strong> modelo(s) disponível(is)
                </span>
            </div>
        ";
        ?>

        <div class="row gx-4 gy-4 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            
            <?php
            if ($totalProdutos > 0) {
                while ($produto = mysqli_fetch_assoc($resAnuncios)) {
                    $idAnuncio    = $produto['idAnuncio'];
                    $fotoAnuncio  = !empty($produto['fotoAnuncio']) ? $produto['fotoAnuncio'] : 'img/3x3.jpg';
                    $tituloAnuncio = $produto['tituloAnuncio'];
                    $valorAnuncio = $produto['valorAnuncio'];
                    $categoria    = $produto['categoriaAnuncio'];
                    ?>

                    <div class="col">
                        <div class="card h-100 shadow-sm rounded-3 overflow-hidden product-card">
                            
                            <div class="product-img-container">
                                <span class="badge bg-dark text-white position-absolute mt-2 ms-2 top-0 start-0 px-2 py-1 small fw-normal opacity-75" style="z-index: 2; font-size: 0.75rem;">
                                    <?php echo $categoria; ?>
                                </span>
                                <img class="card-img-top" src="<?php echo $fotoAnuncio; ?>" alt="Foto de <?php echo $tituloAnuncio; ?>" style="height: 220px; object-fit: cover;" />
                            </div>
                            
                            <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                                <div class="mb-2">
                                    <h6 class="fw-bold text-dark text-truncate mb-2" title="<?php echo $tituloAnuncio; ?>" style="height: 24px;">
                                        <?php echo $tituloAnuncio; ?>
                                    </h6>
                                </div>
                                <div>
                                    <div class="fs-5 fw-black text-dark tracking-wide">
                                        R$ <?php echo number_format($valorAnuncio, 2, ',', '.'); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer p-3 pt-0 border-top-0 bg-transparent text-center">
                                <div class="d-grid">
                                    <a class="btn btn-details btn-sm rounded-2 py-2 text-uppercase text-center" href="detalhesProduto.php?id=<?php echo $idAnuncio; ?>">
                                        <i class="bi bi-eye me-1"></i> Detalhes
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <?php
                }
            } else {
                // Estado vazio caso não encontre nenhum registro
                echo "
                    <div class='col-12 text-center py-5'>
                        <div class='text-muted'>
                            <i class='bi bi-search fs-1 mb-3 d-block text-secondary'></i>
                            <h5 class='fw-bold'>Nenhum cubo encontrado nesta categoria!</h5>
                            <p class='small'>O administrador logo irá abastecer nossa loja com novos modelos.</p>
                        </div>
                    </div>
                ";
            }
            
            // Fecha a conexão após a execução das queries
            mysqli_close($conn);
            ?>

        </div>
    </div>
</section>

<?php include "footer.php"; ?>