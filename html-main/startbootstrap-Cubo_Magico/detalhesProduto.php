<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "conexaoBD.php";
/** @var mysqli $conn */

// Captura o ID do produto vindo da URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idAnuncio = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Busca os dados do cubo no banco de dados
    $sql = "SELECT * FROM anuncios WHERE idAnuncio = '$idAnuncio' AND statusAnuncio = 'disponivel'";
    $res = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($res) > 0) {
        $produto = mysqli_fetch_assoc($res);
        $tituloAnuncio      = $produto['tituloAnuncio'];
        $descricaoAnuncio   = $produto['descricaoAnuncio']; 
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
            
            <div class="col-md-6 mb-4 mb-md-0 text-center">
                <img class="card-img-top mb-5 mb-md-0 rounded-4 shadow border" src="<?php echo $fotoAnuncio; ?>" alt="Foto de <?php echo $tituloAnuncio; ?>" style="width: 100%; max-width: 450px; max-height: 450px; object-fit: cover;" />
            </div>
            
            <div class="col-md-6">
                <div class="small text-muted text-uppercase mb-2 tracking-wider">
                    <span class="badge bg-dark px-3 py-2 fw-normal rounded-pill"><?php echo $categoriaAnuncio; ?></span>
                </div>
                <h1 class="display-5 fw-extrabold text-dark mb-3" style="letter-spacing: -0.5px;"><?php echo $tituloAnuncio; ?></h1>
                
                <div class="fs-2 fw-bold text-success mb-4">
                    R$ <?php echo number_format($valorAnuncio, 2, ',', '.'); ?>
                </div>
                
                <h5 class="fw-bold text-secondary mb-2">Descrição do Produto:</h5>
                <p class="lead text-muted mb-4" style="font-size: 1.05rem; line-height: 1.6;">
                    <?php 
                        echo !empty($descricaoAnuncio) ? nl2br($descricaoAnuncio) : "Este sensacional cubo mágico é perfeito para treinar sua mente, melhorar seus tempos e desafiar seus limites. Modelo profissional de alta durabilidade e giros suaves."; 
                    ?>
                </p>
                
                <hr class="my-4 text-muted bg-opacity-10">

                <form action="actionCarrinho.php" method="GET" class="d-flex flex-wrap gap-3 align-items-center">
                    <input type="hidden" name="acao" value="adicionar">
                    <input type="hidden" name="id" value="<?php echo $idAnuncio; ?>">

                    <div class="form-floating" style="width: 100px;">
                        <input type="number" class="form-control text-center fw-bold border-secondary rounded-3" id="quantidadeInput" name="quantidade" value="1" min="1" required>
                        <label for="quantidadeInput" class="text-muted fw-semibold">Qtd</label>
                    </div>

                    <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark px-4 py-3 shadow-sm d-inline-flex align-items-center rounded-3 hover-shadow" style="min-height: 58px;">
                        <i class="bi bi-cart-plus-fill me-2 fs-5"></i> Adicionar ao Carrinho
                    </button>
                    
                    <a href="index.php" class="btn btn-outline-secondary btn-lg px-3 py-3 d-inline-flex align-items-center rounded-3" style="min-height: 58px;">
                        Voltar à Loja
                    </a>
                </form>
            </div>

        </div>
    </div>
</section>

<?php 
mysqli_close($conn);
include "footer.php"; 
?>