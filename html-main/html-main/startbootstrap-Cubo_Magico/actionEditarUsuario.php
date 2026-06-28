<?php
// Força o PHP a mostrar erros na tela se algo der errado
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔐 TRAVA DE SEGURANÇA: Só administrador pode processar essa página
if (!isset($_SESSION['logado']) || $_SESSION['nivelUsuario'] !== 'administrador') {
    header("Location: index.php");
    exit();
}

// Verifica se a requisição veio pelo formulário via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    include "conexaoBD.php";
    /** @var mysqli $conn */

    // Captura os dados do formulário e limpa usando a função de proteção
    $idUsuario             = filtrar_entrada($_POST["idUsuario"]);
    $nomeUsuario           = filtrar_entrada($_POST["nomeUsuario"]);
    $dataNascimentoUsuario = filtrar_entrada($_POST["dataNascimentoUsuario"]);
    $cidadeUsuario         = filtrar_entrada($_POST["cidadeUsuario"]);
    $emailUsuario          = filtrar_entrada($_POST["emailUsuario"]);
    $nivelUsuario          = filtrar_entrada($_POST["nivelUsuario"]);

    include "header.php";
    echo "<div class='container py-5'>";

    // --- TRATAMENTO DA FOTO DE PERFIL ---
    $uploadSucesso = true;
    $fotoQuery = ""; // String vazia caso o admin não queira mudar a foto

    // Verifica se uma nova imagem foi de fato selecionada
    if ($_FILES["fotoUsuario"]["size"] != 0) {
        $diretorio    = "img/";
        $nomeArquivo  = basename($_FILES['fotoUsuario']['name']);
        $caminhoFoto  = $diretorio . $nomeArquivo;
        $tipoDaImagem = strtolower(pathinfo($caminhoFoto, PATHINFO_EXTENSION));

        // Valida tamanho máximo de 5MB
        if ($_FILES["fotoUsuario"]["size"] > 5000000) {
            echo "<div class='alert alert-danger text-center fw-bold rounded-3 shadow-sm mb-3'><i class='bi bi-x-circle-fill me-2'></i>A nova foto deve ter tamanho máximo de 5MB!</div>";
            $uploadSucesso = false;
        }

        // Valida formatos de imagem permitidos
        if ($tipoDaImagem != "jpg" && $tipoDaImagem != "jpeg" && $tipoDaImagem != "png" && $tipoDaImagem != "webp") {
            echo "<div class='alert alert-danger text-center fw-bold rounded-3 shadow-sm mb-3'><i class='bi bi-x-circle-fill me-2'></i>Apenas formatos JPG, JPEG, PNG ou WEBP são permitidos!</div>";
            $uploadSucesso = false;
        }

        // Se passar nas validações, move para a pasta img/
        if ($uploadSucesso) {
            if (move_uploaded_file($_FILES["fotoUsuario"]["tmp_name"], $caminhoFoto)) {
                // Se moveu com sucesso, monta o pedaço da Query SQL para atualizar o caminho da foto
                $fotoQuery = ", fotoUsuario = '$caminhoFoto'";
            } else {
                echo "<div class='alert alert-danger text-center fw-bold rounded-3 shadow-sm mb-3'><i class='bi bi-x-circle-fill me-2'></i>Erro ao mover a nova foto para o diretório de destino!</div>";
                $uploadSucesso = false;
            }
        }
    }

    // Se o upload não falhou, atualizamos os dados no banco
    if ($uploadSucesso) {
        
        // Query SQL usando UPDATE para modificar o registro correto pelo ID
        $atualizarUsuario = "UPDATE usuarios SET 
                                nomeUsuario = '$nomeUsuario', 
                                dataNascimentoUsuario = '$dataNascimentoUsuario', 
                                cidadeUsuario = '$cidadeUsuario', 
                                emailUsuario = '$emailUsuario', 
                                nivelUsuario = '$nivelUsuario' 
                                $fotoQuery 
                             WHERE idUsuario = '$idUsuario'";

        if (mysqli_query($conn, $atualizarUsuario)) {
            echo "
                <div class='row justify-content-center'>
                    <div class='col-md-6 text-center shadow p-5 bg-white rounded-3 border'>
                        <div class='text-success mb-3'><i class='bi bi-check-circle-fill display-1'></i></div>
                        <h3 class='fw-bold text-dark mb-2'>Usuário Atualizado!</h3>
                        <p class='text-muted mb-4'>Os novos dados de <strong>$nomeUsuario</strong> foram salvos com sucesso no sistema.</p>
                        <a href='listarRegistrosTabela.php' class='btn btn-dark fw-bold px-4 py-2 shadow-sm'><i class='bi bi-arrow-left-short fs-5 align-middle'></i> Voltar para o Painel</a>
                    </div>
                </div>
            ";
        } else {
            echo "
                <div class='alert alert-danger text-center shadow-sm rounded-3'>
                    <i class='bi bi-x-octagon-fill fs-4 me-2'></i>
                    <h4 class='alert-heading fw-bold d-inline'>Erro ao atualizar no banco!</h4>
                    <p class='mb-0 mt-2'>" . mysqli_error($conn) . "</p>
                </div>
            ";
        }
    }

    echo "</div>";
    mysqli_close($conn);
    include "footer.php";

} else {
    header("Location: listarRegistrosTabela.php");
    exit();
}

// Função de segurança e higienização dos dados
function filtrar_entrada($dado) {
    $dado = trim($dado);
    $dado = stripslashes($dado);
    $dado = htmlspecialchars($dado);
    return $dado;
}
?>