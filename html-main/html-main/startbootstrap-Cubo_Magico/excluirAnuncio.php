<?php
// 1. Força o PHP a mostrar erros na tela se algo der errado
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Garante que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔐 TRAVA DE SEGURANÇA: Só o Administrador pode deletar produtos
if (!isset($_SESSION['logado']) || $_SESSION['nivelUsuario'] !== 'administrador') {
    header("Location: index.php");
    exit();
}

// Verifica se o ID do anúncio foi passado na URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    include "conexaoBD.php";
    /** @var mysqli $conn */

    // Captura e limpa o ID
    $idAnuncio = mysqli_real_escape_string($conn, $_GET['id']);

    // --- OPCIONAL: Deletar a foto física da pasta img/ para não acumular lixo no servidor ---
    $buscaFoto = "SELECT fotoAnuncio FROM anuncios WHERE idAnuncio = '$idAnuncio'";
    $resultadoFoto = mysqli_query($conn, $buscaFoto);
    if ($resultadoFoto && mysqli_num_rows($resultadoFoto) > 0) {
        $linhaFoto = mysqli_fetch_assoc($resultadoFoto);
        $caminhoFoto = $linhaFoto['fotoAnuncio'];
        
        // Se o arquivo existir fisicamente e não for uma imagem padrão, deleta do servidor
        if (file_exists($caminhoFoto) && $caminhoFoto != "img/padrao.png") {
            unlink($caminhoFoto);
        }
    }

    // --- EXCLUSÃO NO BANCO DE DADOS ---
    $deletarAnuncio = "DELETE FROM anuncios WHERE idAnuncio = '$idAnuncio'";

    if (mysqli_query($conn, $deletarAnuncio)) {
        // Define a mensagem de sucesso usando a sessão flash que configuramos antes
        $_SESSION['mensagem_sucesso'] = "Produto excluído com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] = "Erro ao excluir o produto: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}

// Redireciona de volta para o painel na aba de produtos
header("Location: listarRegistrosTabela.php?aba=produtos");
exit();
?>