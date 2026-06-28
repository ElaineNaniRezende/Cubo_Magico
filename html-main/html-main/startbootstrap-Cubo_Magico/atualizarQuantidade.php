<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: formLogin.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['operacao'])) {
    include "conexaoBD.php";
    /** @var mysqli $conn */

    $idCarrinho = mysqli_real_escape_string($conn, $_GET['id']);
    $operacao = $_GET['operacao'];
    $idUsuario = $_SESSION['idUsuario'];

    // Busca a quantidade atual para garantir a regra de negócio
    $queryBusca = "SELECT quantidade FROM carrinho WHERE idCarrinho = '$idCarrinho' AND idUsuario = '$idUsuario'";
    $resBusca = mysqli_query($conn, $queryBusca);

    if (mysqli_num_rows($resBusca) > 0) {
        $linha = mysqli_fetch_assoc($resBusca);
        $qtdAtual = $linha['quantidade'];

        if ($operacao === 'somar') {
            $novaQtd = $qtdAtual + 1;
            $sql = "UPDATE carrinho SET quantidade = '$novaQtd' WHERE idCarrinho = '$idCarrinho'";
            mysqli_query($conn, $sql);
        } elseif ($operacao === 'subtrair') {
            $novaQtd = $qtdAtual - 1;
            
            // Regra de negócio: Se a quantidade for chegar a 0, nós removemos o item do carrinho
            if ($novaQtd <= 0) {
                $sql = "DELETE FROM carrinho WHERE idCarrinho = '$idCarrinho'";
            } else {
                $sql = "UPDATE carrinho SET quantidade = '$novaQtd' WHERE idCarrinho = '$idCarrinho'";
            }
            mysqli_query($conn, $sql);
        }
    }
    mysqli_close($conn);
}

// Volta piscando para a página do carrinho atualizada
header("Location: carrinho.php");
exit();