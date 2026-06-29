<?php
// Inicia a sessão para sabermos quem está navegando
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui a conexão com o banco de dados
include "conexaoBD.php";
/** @var mysqli $conn */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['metodoPagamento'])) {
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        header("Location: formLogin.php");
        exit();
    }
    $idUsuario = $_SESSION['idUsuario'];
    $queryLimpar = "DELETE FROM carrinho WHERE idUsuario = '$idUsuario'";
    $executou = mysqli_query($conn, $queryLimpar);
    if ($executou) {
        echo "<script>alert('Pedido finalizado com sucesso! Seu carrinho foi esvaziado.'); window.location.href='index.php';</script>";
        exit();
    }
}

// Inclui o cabeçalho moderno do seu site
include "header.php";
?>

<style>
    body {
        background-color: #f6f8fa; 
    }
    /* Banner Principal Minimalista em Azul */
    .hero-banner {
        background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
        padding: 50px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    .hero-title {
        color: #ffffff;
        font-weight: 800;
        letter-spacing: -1px;
    }
    .hero-subtitle {
        color: #a2a9b4;
        font-weight: 400;
        max-width: 600px;
        margin: 0 auto;
    }
    /* Cards Modernos */
    .product-card {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
        background: #ffffff;
        border: 1px solid #e1e4e6 !important;
    }
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.06) !important;
    }
    .product-img-container {
        position: relative;
        overflow: hidden;
        background-color: #f8f9fa;
    }
    .product-card img {
        transition: transform 0.5s ease;
    }
    .product-card:hover img {
        transform: scale(1.03);
    }
    /* Botão de Detalhes em Azul */
    .btn-details {
        border: 1px solid #e1e4e6;
        color: #495057;
        background: #ffffff;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }
    .btn-details:hover {
        background: #0077b6; /* Destaque em Azul */
        border-color: #0077b6;
        color: #ffffff !important;
    }
    /* Grade Customizada para 5 colunas */
    .row-cols-custom > * {
        flex: 0 0 100%;
        max-width: 100%;
    }
    @media (min-width: 576px) { .row-cols-custom > * { flex: 0 0 50%; max-width: 50%; } }
    @media (min-width: 768px) { .row-cols-custom > * { flex: 0 0 33.3333%; max-width: 33.3333%; } }
    @media (min-width: 992px) { .row-cols-custom > * { flex: 0 0 25%; max-width: 25%; } }
    @media (min-width: 1200px) { .row-cols-custom > * { flex: 0 0 20%; max-width: 20%; } } /* 5 colunas */
</style>

<header class="hero-banner text-center">
    <div class="container px-4">
        <h1 class="hero-title display-5 mb-2">CUBO MÁGICO</h1>
        <p class="hero-subtitle small mb-0">A maior seleção de cubos mágicos profissionais e jogos.</p>
    </div>
</header>

<section class="py-5">
    <div class="container-fluid px-4 px-lg-5">
        
        <?php
        $categoriaSelecionada = isset($_GET['filtrarCategoria']) ? $_GET['filtrarCategoria'] : 'Todos';

        if ($categoriaSelecionada != 'Todos') {
            $sqlAnuncios = "SELECT * FROM anuncios WHERE categoriaAnuncio = '$categoriaSelecionada' AND statusAnuncio = 'disponivel' ORDER BY idAnuncio DESC";
        } else {
            $sqlAnuncios = "SELECT * FROM anuncios WHERE statusAnuncio = 'disponivel' ORDER BY idAnuncio DESC";
        }

        $resAnuncios = mysqli_query($conn, $sqlAnuncios);
        $totalProdutos = mysqli_num_rows($resAnuncios);

        echo "
            <div class='text-center mb-5'>
                <span class='badge bg-white text-muted border px-3 py-2 rounded-pill fw-normal shadow-sm' style='font-size: 0.8rem;'>
                    Vitrine com <strong class='text-dark'>$totalProdutos</strong> modelo(s) encontrado(s)
                </span>
            </div>
        ";
        ?>

        <div class="row row-cols-custom gx-3 gy-4 justify-content-center">
            
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
                        <div class="card h-100 rounded-3 overflow-hidden product-card">
                            
                            <div class="product-img-container">
                                <span class="badge bg-dark text-white position-absolute mt-2 ms-2 top-0 start-0 px-2 py-1 fw-normal opacity-75" style="z-index: 2; font-size: 0.7rem; letter-spacing: 0.3px;">
                                    <?php echo $categoria; ?>
                                </span>
                                <img class="card-img-top" src="<?php echo $fotoAnuncio; ?>" alt="Foto de <?php echo $tituloAnuncio; ?>" style="height: 190px; object-fit: cover;" />
                            </div>
                            
                            <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                                <div class="mb-2">
                                    <h6 class="fw-bold text-dark text-truncate mb-1" title="<?php echo $tituloAnuncio; ?>" style="font-size: 0.9rem;">
                                        <?php echo $tituloAnuncio; ?>
                                    </h6>
                                </div>
                                <div>
                                    <div class="fw-bold" style="font-size: 1.05rem; color: #0077b6 !important;">
                                        R$ <?php echo number_format($valorAnuncio, 2, ',', '.'); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer p-3 pt-0 border-top-0 bg-transparent text-center">
                                <div class="d-grid">
                                    <a class="btn btn-details btn-sm rounded-2 py-2 text-uppercase" href="detalhesProduto.php?id=<?php echo $idAnuncio; ?>">
                                        Ver Detalhes
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>

                    <?php
                }
            } else {
                echo "
                    <div class='col-12 text-center py-5'>
                        <div class='text-muted'>
                            <i class='bi bi-search fs-2 mb-2 d-block text-secondary'></i>
                            <h6 class='fw-bold'>Nenhum cubo encontrado!</h6>
                        </div>
                    </div>
                ";
            }
            mysqli_close($conn);
            ?>

        </div>
    </div>
</section>

<?php include "footer.php"; ?>