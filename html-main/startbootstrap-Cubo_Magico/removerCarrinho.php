<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    include "conexaoBD.php";
    /** @var mysqli $conn */

    $idCarrinho = mysqli_real_escape_string($conn, $_GET['id']);
    $idUsuario = $_SESSION['idUsuario'];

    // Garante que o usuário só delete algo que realmente pertence a ele
    $query = "DELETE FROM carrinho WHERE idCarrinho = '$idCarrinho' AND idUsuario = '$idUsuario'";

    mysqli_query($conn, $query);
    mysqli_close($conn);
}

header("Location: carrinho.php");
exit();
?>