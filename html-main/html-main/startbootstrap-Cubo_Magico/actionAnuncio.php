<?php 
// Garante que a sessão está ativa para capturar quem está logado
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
            // Verifica o método de requisição do servidor
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                // Define as variáveis limpas
                $fotoAnuncio = $tituloAnuncio = $categoriaAnuncio = $descricaoAnuncio = $valorAnuncio = $dataAnuncio = $horaAnuncio = "";
                $erroPreenchimento = false;

                // Captura o ID do Administrador logado na sessão para a Chave Estrangeira
                $idUsuario = $_SESSION['idUsuario'];

                // Captura a data e a hora atuais do servidor
                $dataAnuncio = date("Y-m-d");
                $horaAnuncio = date("H:i:s");

                // Validação do campo tituloAnuncio
                if (empty($_POST["tituloAnuncio"])) {
                    echo "<div class='alert alert-warning text-center fw-bold rounded-3 shadow-sm mb-3'><i class='bi bi-exclamation-triangle-fill me-2'></i>O campo TÍTULO DO ANÚNCIO é obrigatório!</div>";
                    $erroPreenchimento = true;
                } else {
                    $tituloAnuncio = filtrar_entrada($_POST["tituloAnuncio"]);
                }

                // Validação do campo categoriaAnuncio
                if (empty($_POST["categoriaAnuncio"])) {
                    echo "<div class='alert alert-warning text-center fw-bold rounded-3 shadow-sm mb-3'><i class='bi bi-exclamation-triangle-fill me-2'></i>O campo CATEGORIA é obrigatório!</div>";
                    $erroPreenchimento = true;
                } else {
                    $categoriaAnuncio = filtrar_entrada($_POST["categoriaAnuncio"]);
                }

                // Validação do campo descricaoAnuncio
                if (empty($_POST["descricaoAnuncio"])) {
                    echo "<div class='alert alert-warning text-center fw-bold rounded-3 shadow-sm mb-3'><i class='bi bi-exclamation-triangle-fill me-2'></i>O campo DESCRIÇÃO é obrigatório!</div>";
                    $erroPreenchimento = true;
                } else {
                    $descricaoAnuncio = filtrar_entrada($_POST["descricaoAnuncio"]);
                }

                // Validação do campo valorAnuncio
                if (empty($_POST["valorAnuncio"])) {
                    echo "<div class='alert alert-warning text-center fw-bold rounded-3 shadow-sm mb-3'><i class='bi bi-exclamation-triangle-fill me-2'></i>O campo VALOR é obrigatório!</div>";
                    $erroPreenchimento = true;
                } else {
                    $valorAnuncio = filtrar_entrada($_POST["valorAnuncio"]);
                }

                // --- PROCESSAMENTO DO UPLOAD DA FOTO ---
                $diretorio    = "img/"; 
                $nomeArquivo  = basename($_FILES['fotoAnuncio']['name']);
                $fotoAnuncio  = $diretorio . $nomeArquivo; 
                $tipoDaImagem = strtolower(pathinfo($fotoAnuncio, PATHINFO_EXTENSION)); 
                $erroUpload   = false; 

                if ($_FILES["fotoAnuncio"]["size"] != 0) {
                    
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

                    // Se não houve erros de tamanho/formato, tenta mover o arquivo para a pasta do projeto
                    if (!$erroUpload) {
                        if (!move_uploaded_file($_FILES["fotoAnuncio"]["tmp_name"], $fotoAnuncio)) {
                            echo "<div class='alert alert-danger text-center fw-bold rounded-3 shadow-sm mb-3'><i class='bi bi-x-circle-fill me-2'></i>Erro ao mover a foto para o diretório de destino. Certifique-se de que a pasta '$diretorio' existe!</div>";
                            $erroUpload = true;
                        }
                    }

                } else {
                    echo "<div class='alert alert-warning text-center fw-bold rounded-3 shadow-sm mb-3'><i class='bi bi-exclamation-triangle-fill me-2'></i>A FOTO do produto é obrigatória!</div>";
                    $erroUpload = true;
                }

                // --- INSERÇÃO NO BANCO DE DADOS ---
                if (!$erroPreenchimento && !$erroUpload) {
                    
                    include "conexaoBD.php";
                    /** @var mysqli $conn */

                    // Query ajustada para o nome correto das colunas e tabelas em minúsculo
                    $inserirAnuncio = "INSERT INTO anuncios (Usuarios_idUsuario, fotoAnuncio, tituloAnuncio, categoriaAnuncio, descricaoAnuncio, valorAnuncio, dataAnuncio, horaAnuncio, statusAnuncio) 
                                       VALUES ('$idUsuario', '$fotoAnuncio', '$tituloAnuncio', '$categoriaAnuncio', '$descricaoAnuncio', '$valorAnuncio', '$dataAnuncio', '$horaAnuncio', 'disponivel')";

                    if (mysqli_query($conn, $inserirAnuncio)) {
                        
                        // Mostra painel de resumo elegante em caso de sucesso
                        echo "
                            <div class='card shadow border-0 rounded-3 overflow-hidden animate__animated animate__fadeIn'>
                                <div class='card-header bg-success text-white text-center py-3'>
                                    <h5 class='mb-0 fw-bold text-uppercase'><i class='bi bi-check-circle-fill me-2'></i> Produto Cadastrado com Sucesso!</h5>
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
                                            <tr>
                                                <th class='bg-light text-secondary ps-3'>Data de Cadastro</th>
                                                <td class='text-muted'>" . date('d/m/Y', strtotime($dataAnuncio)) . " às $horaAnuncio</td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class='mt-4 d-flex gap-2 justify-content-center'>
                                        <a href='formAnuncio.php' class='btn btn-outline-dark fw-bold btn-sm px-3'><i class='bi bi-plus-circle'></i> Cadastrar Outro</a>
                                        <a href='index.php' class='btn btn-warning fw-bold btn-sm px-4 text-dark'><i class='bi bi-house-door'></i> Ir para a Loja</a>
                                    </div>
                                </div>
                            </div>
                        ";
                    } else {
                        echo "
                            <div class='alert alert-danger text-center shadow-sm rounded-3' role='alert'>
                                <i class='bi bi-x-octagon-fill fs-4 me-2'></i>
                                <h4 class='alert-heading fw-bold d-inline'>Erro ao salvar no Banco!</h4>
                                <p class='mb-0 mt-2'>" . mysqli_error($conn) . "</p>
                            </div>
                        ";
                    }
                    mysqli_close($conn);
                }

            } else {
                // Se tentarem acessar o link direto, joga de volta pro formulário
                header("Location: formAnuncio.php");
                exit();
            }

            // Função nativa de higienização de strings
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