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

    $idUsuario = $_SESSION['idUsuario'];
    $idAnuncio = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Captura a quantidade vinda do formulário de forma dinâmica. Se não vier nada, assume 1 por segurança.
    $quantidade = (isset($_GET['quantidade']) && (int)$_GET['quantidade'] > 0) ? (int)$_GET['quantidade'] : 1;

    // Verifica se o produto já existe no carrinho do usuário para não duplicar linhas à toa
    $verificar = "SELECT idCarrinho, quantidade FROM carrinho WHERE idUsuario = '$idUsuario' AND idAnuncio = '$idAnuncio'";
    $resVerificar = mysqli_query($conn, $verificar);

    if (mysqli_num_rows($resVerificar) > 0) {
        // Se já existe, soma a quantidade enviada com a quantidade atual do banco de dados
        $linha = mysqli_fetch_assoc($resVerificar);
        $novaQtd = $linha['quantidade'] + $quantidade;
        $queryCarrinho = "UPDATE carrinho SET quantidade = '$novaQtd' WHERE idCarrinho = '{$linha['idCarrinho']}'";
    } else {
        // Se não existe no carrinho, insere o novo registro com a quantidade exata escolhida
        $queryCarrinho = "INSERT INTO carrinho (idUsuario, idAnuncio, quantidade) VALUES ('$idUsuario', '$idAnuncio', '$quantidade')";
    }

    if (mysqli_query($conn, $queryCarrinho)) {
        // Sucesso total! Redireciona o usuário direto para a tela do carrinho com visual premium
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