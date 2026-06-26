<?php include "header.php" ?>

<?php include "conexaoBD.php" ?>

<?php
    //Recebe o valor do filtro via método GET
    $filtroAnuncio = $_GET['statusAnuncio'] ?? 'todos';
    
    //Query para a consulta SQL a ser realizada
    if($filtroAnuncio == 'todos'){
        $listarAnuncios = "SELECT * FROM anuncios";
    }
    else{
        $listarAnuncios = "SELECT * FROM anuncios WHERE statusAnuncio = '$filtroAnuncio' ";
    }

    //Executa a query para consulta no BD
    /** @var mysqli $conn */
    $res = mysqli_query($conn, $listarAnuncios);
?>

<style>
    /* Remove sublinhado dos links e mantém a cor padrão */
    .card-link {
        text-decoration: none;
        color: inherit;
    }

    /* Aplica um efeito suave no hover do card */
    .card-hover {
        position: relative;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
    }

    /* Efeito ao passar o mouse */
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }

    /* Camada escura que aparece no hover (overlay) */
    .card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
    }

    /* Torna o overlay visível no hover */
    .card-hover:hover .card-overlay {
        opacity: 1;
    }

    /* Faixa de anúncio finalizado */
    .faixa-finalizado {
        right: 0;
        position: absolute;
        width: 50%;
        background: #dc3545;
        color: white;
        text-align: center;
        font-weight: bold;
        font-size: 0.7rem;
        padding: 5px 0;
        z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }

    /* Deixa a imagem em preto e branco */
    .imagem-finalizada {
        filter: grayscale(100%);
        opacity: 0.8;
    }
</style>

<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">

        <form method="get" class="mb-5" action="index.php">
            <div class="row justify-content-center">
                <div class="col-md-5 d-flex gap-2">
                    <select name="statusAnuncio" class="form-select">
                        <option value="todos" <?php if($filtroAnuncio == 'todos'){echo "selected";} ?> >Exibir todos os cubos</option>
                        <option value="disponivel" <?php if($filtroAnuncio == 'disponivel'){echo "selected";} ?> >Exibir apenas disponíveis</option>
                        <option value="finalizado" <?php if($filtroAnuncio == 'finalizado'){echo "selected";} ?> >Exibir apenas esgotados/finalizados</option>
                    </select>
                    
                    <button type="submit" class="btn btn-outline-dark text-nowrap">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>

        <?php
            $totalAnuncios = mysqli_num_rows($res);
            echo "<div class='alert alert-info text-center mb-5'>Há <strong>$totalAnuncios</strong> cubos/jogos disponíveis em nosso sistema!</div>";
        ?>
    
        <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

            <?php
                // Verifica se a consulta retornou resultados
                if (mysqli_num_rows($res) > 0){
                    // Enquanto houver registros no banco, exibirá os cards
                    while($anuncio = mysqli_fetch_assoc($res)){
            ?>

            <div class="col mb-5">
                <a class="card-link" href="visualizarAnuncio.php?idAnuncio=<?php echo $anuncio['idAnuncio']; ?>">
                    <div class="card h-100 card-hover">

                        <?php
                            if($anuncio['statusAnuncio'] == 'finalizado'){
                                echo "<div class='faixa-finalizado'>FINALIZADO</div>";
                            }
                        ?>

                        <div class="card-overlay">
                            <i class="bi bi-eye me-2"></i> Ver Detalhes
                        </div>

                        <img class="card-img-top <?php if($anuncio['statusAnuncio'] == 'finalizado'){echo 'imagem-finalizada';} ?>"
                             src="<?php echo htmlspecialchars($anuncio['fotoAnuncio']) ?>"
                             alt="<?php echo htmlspecialchars($anuncio['tituloAnuncio']) ?>"
                             style="height: 200px; object-fit: cover;" />

                        <div class="card-body p-4">
                            <div class="text-center">
                                <span class="badge bg-light text-dark border mb-2"><?php echo htmlspecialchars($anuncio['categoriaAnuncio']) ?></span>
                                
                                <h5 class="fw-bolder fs-6">
                                    <?php echo htmlspecialchars($anuncio['tituloAnuncio']) ?>
                                </h5>

                                <p class="text-primary fw-bold mt-2 mb-0">
                                    R$ <?php echo number_format($anuncio['valorAnuncio'], 2, ',', '.') ?>
                                </p>
                            </div>
                        </div>

                    </div>
                </a>
            </div>

            <?php
                    } // Fechamento do while
                } // Fechamento do if
                else {
                    echo "<div class='alert alert-warning text-center w-100'>Nenhum cubo mágico encontrado nesta categoria.</div>";
                }
            ?>
        </div>

    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/scripts.js"></script>

<?php include "footer.php" ?>