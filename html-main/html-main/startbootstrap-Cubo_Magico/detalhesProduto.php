<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "conexaoBD.php";
/** @var mysqli $conn */

// Captura o ID do produto vindo da URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idAnuncio = $_GET['id'];
    
    // Busca os dados do cubo no banco de dados
    $sql = "SELECT * FROM anuncios WHERE idAnuncio = '$idAnuncio' AND statusAnuncio = 'disponivel'";
    $res = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($res) > 0) {
        $produto = mysqli_fetch_assoc($res);
        $tituloAnuncio      = $produto['tituloAnuncio'];
        $descricaoAnuncio   = $produto['descricaoAnuncio']; // Coluna de descrição do seu banco
        $valorAnuncio       = $produto['valorAnuncio'];
        $categoriaAnuncio   = $produto['categoriaAnuncio'];
        $fotoAnuncio        = !empty($produto['fotoAnuncio']) ? $produto['fotoAnuncio'] : 'img/3x3.jpg';
    } else {
        echo "<script>alert('Produto não encontrado ou indisponível!'); window.location.href='index.php';</script>";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

include "header.php";
?>

<section class="py-5 bg-white">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            
            <div class="col-md-6 mb-4 mb-md-0">
                <img class="card-img-top mb-5 mb-md-0 rounded-3 shadow border" src="<?php echo $fotoAnuncio; ?>" alt="Foto de <?php echo $tituloAnuncio; ?>" style="width: 100%; max-height: 500px; object-fit: cover;" />
            </div>
            
            <div class="col-md-6">
                <div class="small text-muted text-uppercase mb-1 tracking-wider">
                    <span class="badge bg-dark px-2 py-1 fw-normal"><?php echo $categoriaAnuncio; ?></span>
                </div>
                <h1 class="display-5 fw-bolder text-dark mb-3"><?php echo $tituloAnuncio; ?></h1>
                
                <div class="fs-3 fw-bold text-success mb-4">
                    R$ <?php echo number_format($valorAnuncio, 2, ',', '.'); ?>
                </div>
                
                <h5 class="fw-bold text-secondary mb-2">Descrição do Produto:</h5>
                <p class="lead text-muted mb-5" style="font-size: 1.05rem; line-height: 1.6;">
                    <?php 
                        // Exibe a descrição ou uma mensagem padrão caso esteja vazia
                        echo !empty($descricaoAnuncio) ? nl2br($descricaoAnuncio) : "Este sensacional cubo mágico é perfeito para treinar sua mente, melhorar seus tempos e desafiar seus limites. Modelo profissional de alta durabilidade e giros suaves."; 
                    ?>
                </p>
                
                <div class="d-flex gap-3">
                    <a class="btn btn-warning btn-lg fw-bold text-dark px-4 py-3 shadow-sm d-inline-flex align-items-center" href="actionCarrinho.php?acao=adicionar&id=<?php echo $idAnuncio; ?>">
                        <i class="bi bi-cart-plus-fill me-2 fs-4"></i> Adicionar ao Carrinho
                    </a>
                    <a href="index.php" class="btn btn-outline-secondary btn-lg px-3 py-3">
                        Voltar à Loja
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php 
mysqli_close($conn);
include "footer.php"; 
?>