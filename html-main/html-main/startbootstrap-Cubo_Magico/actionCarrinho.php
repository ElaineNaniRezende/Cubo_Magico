<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔐 TRAVA DE SEGURANÇA: Só quem está logado pode adicionar coisas ao carrinho
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    $_SESSION['mensagem_erro'] = "Você precisa fazer login para adicionar itens ao carrinho!";
    header("Location: formLogin.php");
    exit();
}

// Verifica se a ação é de adicionar e se o ID do anúncio veio na URL
if (isset($_GET['acao']) && $_GET['acao'] === 'adicionar' && isset($_GET['id']) && !empty($_GET['id'])) {
    
    include "conexaoBD.php";
    /** @var mysqli $conn */

    $idUsuario  = $_SESSION['idUsuario'];
    $idAnuncio  = mysqli_real_escape_string($conn, $_GET['id']);
    $quantidade = 1; // Como é um clique direto no link, adicionamos 1 unidade por padrão

    // Verifica se o produto já existe no carrinho do usuário para não duplicar linhas à toa
    $verificar = "SELECT idCarrinho, quantidade FROM carrinho WHERE idUsuario = '$idUsuario' AND idAnuncio = '$idAnuncio'";
    $resVerificar = mysqli_query($conn, $verificar);

    if (mysqli_num_rows($resVerificar) > 0) {
        // Se já existe, apenas soma +1 na quantidade atual
        $linha = mysqli_fetch_assoc($resVerificar);
        $novaQtd = $linha['quantidade'] + $quantidade;
        $queryCarrinho = "UPDATE carrinho SET quantidade = '$novaQtd' WHERE idCarrinho = '{$linha['idCarrinho']}'";
    } else {
        // Se não existe no carrinho, insere um novo registro conectado pelas FKs
        $queryCarrinho = "INSERT INTO carrinho (idUsuario, idAnuncio, quantidade) VALUES ('$idUsuario', '$idAnuncio', '$quantidade')";
    }

    if (mysqli_query($conn, $queryCarrinho)) {
        // Sucesso total! Redireciona o usuário direto para a tela do carrinho
        header("Location: carrinho.php");
        exit();
    } else {
        echo "Erro ao gerenciar banco de dados: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
} else {
    // Se tentarem acessar o arquivo sem parâmetros válidos, volta para a loja
    header("Location: index.php");
    exit();
}
?>