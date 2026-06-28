<?php
session_start(); // Sempre no topo! Função para iniciar uma sessão

include "conexaoBD.php"; // Inclui o arquivo de conexão com o BD para consultar usuários

// Verifica se os dados foram enviados pelo formulário
if (isset($_POST['emailUsuario']) && isset($_POST['senhaUsuario'])) {

    /** @var mysqli $conn */
    $emailUsuario = mysqli_real_escape_string($conn, $_POST['emailUsuario']); // Filtra a entrada de dados
    $senhaUsuario = mysqli_real_escape_string($conn, $_POST['senhaUsuario']);

    // Query para buscar dados de Login
    $buscarLogin = "SELECT *
                    FROM usuarios
                    WHERE emailUsuario = '{$emailUsuario}'
                    AND senhaUsuario = md5('{$senhaUsuario}') ";

    // Executa a Query
    $efetuarLogin = mysqli_query($conn, $buscarLogin);

    // Verifica se encontrou um usuário
    if ($registro = mysqli_fetch_assoc($efetuarLogin)){

        // Cria variáveis de sessão originais do seu projeto
        $_SESSION['idUsuario']    = $registro['idUsuario'];
        $_SESSION['nomeUsuario']  = $registro['nomeUsuario'];
        $_SESSION['emailUsuario'] = $registro['emailUsuario'];
        $_SESSION['nivelUsuario'] = $registro['nivelUsuario'];
        $_SESSION['logado']       = true;

        // Redireciona o usuário para a página inicial
        header("Location: index.php");
        exit();

    }
    else{
        // Se errar, volta mandando o erro exato que o seu formLogin espera
        header("Location: formLogin.php?erroLogin=dadosInvalidos");
        exit();
    }
} else {
    // Se tentarem acessar o arquivo direto, joga pro formulário
    header("Location: formLogin.php");
    exit();
}
?>