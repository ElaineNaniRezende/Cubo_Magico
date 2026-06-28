<?php
// 1. Força o PHP a mostrar erros na tela se algo der errado
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Garante que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔐 TRAVA DE SEGURANÇA: Só o Administrador pode processar essa página
if (!isset($_SESSION['logado']) || $_SESSION['nivelUsuario'] !== 'administrador') {
    header("Location: index.php");
    exit();
}

include "header.php"; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">

            <?php
            // Verifica se a requisição veio via POST
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                include "conexaoBD.php";
                /** @var mysqli $conn */

                // Captura os dados do formulário e higieniza
                $idAnuncio        = filtrar_entrada($_POST["idAnuncio"]);
                $tituloAnuncio    = filtrar_entrada($_POST["tituloAnuncio"]);
                $categoriaAnuncio = filtrar_entrada($_POST["categoriaAnuncio"]);
                $descricaoAnuncio = filtrar_entrada($_POST["descricaoAnuncio"]);
                $valorAnuncio     = filtrar_entrada($_POST["valorAnuncio"]);
                $fotoAtual        = $_POST["fotoAtual"]; // Caminho da foto caso não mude

                $erroUpload = false;
                $fotoAnuncio = $fotoAtual; // Por padrão, mantém a foto antiga

                // --- PROCESSAMENTO DO UPLOAD DE NOVA FOTO ---
                if (isset($_FILES['fotoAnuncio']) && $_FILES['fotoAnuncio']['size'] != 0) {
                    
                    $diretorio    = "img/"; 
                    $nomeArquivo  = basename($_FILES['fotoAnuncio']['name']);
                    $caminhoNovo  = $diretorio . $nomeArquivo; 
                    $tipoDaImagem = strtolower(pathinfo($caminhoNovo, PATHINFO_EXTENSION)); 

                    // Valida tamanho máximo de 5MB
                    if ($_FILES["fotoAnuncio"]["size"] > 5000000) {
                        echo "<div class='alert alert-danger text-center fw-bold rounded-3 shadow-sm mb-3'><i class='bi bi-x-circle-fill me-2'></i>A FOTO deve ter tamanho máximo de 5MB!</div>";
                        $erroUpload = true;
                    }

                    // Valida formatos permitidos
                    if ($tipoDaImagem != "jpg" && $tipoDaImagem != "jpeg" && $tipoDaImagem != "png" && $tipoDaImagem != "webp") {
                        echo "<div class='alert alert-danger text-center fw-bold rounded-3 shadow-sm mb-3'><i class='bi bi-x-circle-fill me-2'></i>Formatos permitidos para FOTO: JPG, JPEG, PNG ou WEBP!</div>";
                        $erroUpload = true;
                    }

                    // Se não houver erros, move o arquivo novo
                    if (!$erroUpload) {
                        if (move_uploaded_file($_FILES["fotoAnuncio"]["tmp_name"], $caminhoNovo)) {
                            $fotoAnuncio = $caminhoNovo; 
                        } else {
                            echo "<div class='alert alert-danger text-center fw-bold rounded-3 shadow-sm mb-3'><i class='bi bi-x-circle-fill me-2'></i>Erro ao mover a nova foto para o diretório de destino.</div>";
                            $erroUpload = true;
                        }
                    }
                }

                // Se houver erro de upload, para aqui
                if ($erroUpload) {
                    echo "<div class='text-center mt-3'><a href='formEditarAnuncio.php?id=$idAnuncio' class='btn btn-dark fw-bold btn-sm'>Voltar para a Edição</a></div>";
                } else {
                    
                    // --- ATUALIZAÇÃO NO BANCO DE DADOS ---
                    $atualizarAnuncio = "UPDATE anuncios SET 
                                            fotoAnuncio = '$fotoAnuncio', 
                                            tituloAnuncio = '$tituloAnuncio', 
                                            categoriaAnuncio = '$categoriaAnuncio', 
                                            descricaoAnuncio = '$descricaoAnuncio', 
                                            valorAnuncio = '$valorAnuncio' 
                                         WHERE idAnuncio = '$idAnuncio'";

                    if (mysqli_query($conn, $atualizarAnuncio)) {
                        
                        // 🌟 Exibe a tela de confirmação de alteração com sucesso!
                        echo "
                            <div class='card shadow border-0 rounded-3 overflow-hidden'>
                                <div class='card-header bg-success text-white text-center py-3'>
                                    <h5 class='mb-0 fw-bold text-uppercase'><i class='bi bi-check-circle-fill me-2'></i> Produto Alterado com Sucesso!</h5>
                                </div>
                                <div class='card-body p-4 text-center'>
                                    <img src='$fotoAnuncio' style='max-width: 180px; max-height: 180px; object-fit: cover;' class='img-thumbnail rounded shadow-sm mb-4' alt='Foto do Cubo'>
                                    
                                    <div class='table-responsive rounded border'>
                                        <table class='table table-hover align-middle mb-0 text-start'>
                                            <tr>
                                                <th class='bg-light text-secondary ps-3' style='width: 35%;'>Título do Cubo</th>
                                                <td class='fw-bold text-dark'>$tituloAnuncio</td>
                                            </tr>
                                            <tr>
                                                <th class='bg-light text-secondary ps-3'>Categoria</th>
                                                <td><span class='badge bg-secondary'>$categoriaAnuncio</span></td>
                                            </tr>
                                            <tr>
                                                <th class='bg-light text-secondary ps-3'>Descrição</th>
                                                <td class='text-muted small'>$descricaoAnuncio</td>
                                            </tr>
                                            <tr>
                                                <th class='bg-light text-secondary ps-3'>Valor de Venda</th>
                                                <td class='fw-bold text-success fs-5'>R$ " . number_format($valorAnuncio, 2, ',', '.') . "</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class='mt-4 d-flex gap-2 justify-content-center'>
                                        <a href='listarRegistrosTabela.php?aba=produtos' class='btn btn-warning fw-bold btn-sm px-4 text-dark'><i class='bi bi-sliders'></i> Ir para o Painel</a>
                                    </div>
                                </div>
                            </div>
                        ";
                    } else {
                        echo "
                            <div class='alert alert-danger text-center shadow-sm rounded-3' role='alert'>
                                <i class='bi bi-x-octagon-fill fs-4 me-2'></i>
                                <h4 class='alert-heading fw-bold d-inline'>Erro ao atualizar no Banco!</h4>
                                <p class='mb-0 mt-2'>" . mysqli_error($conn) . "</p>
                            </div>
                        ";
                    }
                }
                mysqli_close($conn);

            } else {
                header("Location: listarRegistrosTabela.php?aba=produtos");
                exit();
            }

            function filtrar_entrada($dado) {
                $dado = trim($dado);
                $dado = stripslashes($dado);
                $dado = htmlspecialchars($dado);
                return $dado;
            }
            ?>

        </div>
    </div>
</div>

<?php include "footer.php" ?>