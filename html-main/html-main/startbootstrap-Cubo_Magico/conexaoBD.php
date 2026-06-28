<?php

    $hostBD   = "localhost"; //Define o local do servidor de BD
    $userBD   = "root"; //Define o usuário do BD (Padrão: root)
    $senhaBD  = ""; //Define a senha do BD (Padrão local: em branco "")
    $database = "cubomagico"; //Define com qual base será realizada a conexão

    //Função do PHP para estabelecer a conexão com o BD
    $conn     = mysqli_connect($hostBD, $userBD, $senhaBD, $database);

    //Verificar se há conexão com o BD
    if(!$conn){
        echo "<p>Erro ao tentar conectar a aplicação à base de dados <strong>$database</strong></p>";
    }

    // 🌟 COMPLEMENTO MÁGICO PARA O VS CODE ZERAR OS ERROS:
    // Garante que o editor entenda que essa variável é global e do tipo mysqli
    global $conn;
    /** @var mysqli $conn */

?>