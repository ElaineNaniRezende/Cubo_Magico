<?php
// Inicia a sessão para ter acesso a ela
session_start();

// Limpa todas as variáveis salvas na sessão (desloga o usuário)
session_unset();

// Destrói a sessão completamente
session_destroy();

// Redireciona de volta de forma limpa para a página inicial
header("Location: index.php");
exit();
?>