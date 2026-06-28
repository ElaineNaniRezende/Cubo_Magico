<?php
// Força o PHP a mostrar erros na tela se algo der errado
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui a conexão com o banco
include "conexaoBD.php";
/** @var mysqli $conn */

// Captura o ID enviado pelo botão da tabela
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idUsuario = $_GET['id'];

    include "header.php";
    echo "<div class='container py-5'>";

    // Comando SQL para deletar o registro pelo ID correspondente
    $deletarUsuario = "DELETE FROM usuarios WHERE idUsuario = '$idUsuario'";

    if (mysqli_query($conn, $deletarUsuario)) {
        echo "
            <div class='row justify-content-center'>
                <div class='col-md-6 text-center shadow p-5 bg-white rounded-3 border'>
                    <div class='text-danger mb-3'><i class='bi bi-trash3-fill display-1'></i></div>
                    <h3 class='fw-bold text-dark mb-2'>Usuário Excluído!</h3>
                    <p class='text-muted mb-4'>O registro foi removido permanentemente do banco de dados.</p>
                    <a href='listarRegistrosTabela.php' class='btn btn-dark fw-bold px-4 py-2 shadow-sm'><i class='bi bi-arrow-left-short fs-5 align-middle'></i> Voltar para o Painel</a>
                </div>
            </div>
        ";
    } else {
        echo "
            <div class='alert alert-danger text-center shadow-sm rounded-3'>
                <i class='bi bi-x-octagon-fill fs-4 me-2'></i>
                <h4 class='alert-heading fw-bold d-inline'>Erro ao excluir do banco!</h4>
                <p class='mb-0 mt-2'>" . mysqli_error($conn) . "</p>
            </div>
        ";
    }

    echo "</div>";
    mysqli_close($conn);
    include "footer.php";

} else {
    // Se tentarem acessar o arquivo direto sem passar ID, joga de volta pro painel
    header("Location: listarRegistrosTabela.php");
    exit();
}
?>