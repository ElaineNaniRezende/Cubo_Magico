<?php
// ATIVAÇÃO DE ERROS PARA DETECÇÃO NO NAVEGADOR
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Inclui a conexão logo na primeira linha. O VS Code passa a reconhecer o $conn no arquivo inteiro!
include "conexaoBD.php";
/** @var mysqli $conn */

// 2. Protege a função de filtragem. Se ela já existir no header.php, o VS Code NÃO vai dar erro!
if (!function_exists('filtrar_entrada')) {
    function filtrar_entrada($dado) {
        $dado = trim($dado); 
        $dado = stripslashes($dado); 
        $dado = htmlspecialchars($dado); 
        return $dado;
    }
}

// Verifica o método de requisição do servidor
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Define o bloco de variáveis para armazenar as informações recebidas do formulário
    $nomeUsuario = $dataNascimentoUsuario = $cidadeUsuario = $emailUsuario = $senhaUsuario = $confirmarSenhaUsuario = "";
    $diaNascimentoUsuario = $mesNascimentoUsuario = $anoNascimentoUsuario = "";

    // Variável booleana para controle de erros de preenchimento
    $erroPreenchimento = false;

    // Validação do campo nomeUsuario
    if (empty($_POST["nomeUsuario"])) {
        $erroPreenchimento = true;
    } else {
        $nomeUsuario = filtrar_entrada($_POST["nomeUsuario"]);

        if (!preg_match('/^[\p{L} ]+$/u', $nomeUsuario)) {
            $erroPreenchimento = true;
        }
    }

    // Validação do campo dataNascimentoUsuario
    if (empty($_POST["dataNascimentoUsuario"])) {
        $erroPreenchimento = true;
    } else {
        $dataNascimentoUsuario = filtrar_entrada($_POST["dataNascimentoUsuario"]);

        if (strlen($dataNascimentoUsuario) == 10) {
            $diaNascimentoUsuario = substr($dataNascimentoUsuario, 8, 2);
            $mesNascimentoUsuario = substr($dataNascimentoUsuario, 5, 2);
            $anoNascimentoUsuario = substr($dataNascimentoUsuario, 0, 4);

            if (!checkdate($mesNascimentoUsuario, $diaNascimentoUsuario, $anoNascimentoUsuario)) {
                $erroPreenchimento = true;
            }
        } else {
            $erroPreenchimento = true;
        }
    }

    // Validação do campo cidadeUsuario
    if (empty($_POST["cidadeUsuario"])) {
        $erroPreenchimento = true;
    } else {
        $cidadeUsuario = filtrar_entrada($_POST["cidadeUsuario"]);
    }

    // Validação do campo emailUsuario
    if (empty($_POST["emailUsuario"])) {
        $erroPreenchimento = true;
    } else {
        $emailUsuario = filtrar_entrada($_POST["emailUsuario"]);
        
        // Uso direto da conexão reconhecida globalmente
        $verificarEmail = "SELECT emailUsuario FROM usuarios WHERE emailUsuario LIKE '$emailUsuario' ";
        $res = mysqli_query($conn, $verificarEmail);

        if ($res && mysqli_num_rows($res) > 0) {
            $erroPreenchimento = true;
        }
    }

    // Validação do campo senhaUsuario
    if (empty($_POST["senhaUsuario"])) {
        $erroPreenchimento = true;
    } else {
        $senhaUsuario = md5(filtrar_entrada($_POST["senhaUsuario"]));
    }

    // Validação do campo confirmarSenhaUsuario
    if (empty($_POST["confirmarSenhaUsuario"])) {
        $erroPreenchimento = true;
    } else {
        $confirmarSenhaUsuario = md5(filtrar_entrada($_POST["confirmarSenhaUsuario"]));

        if ($senhaUsuario != $confirmarSenhaUsuario) {
            $erroPreenchimento = true;
        }
    }

    // Configuração da foto
    $diretorio    = "img/";
    $fotoUsuario  = "img/3x3.jpg"; 
    $erroUpload   = false; 

    if (isset($_FILES['fotoUsuario']) && $_FILES['fotoUsuario']['error'] == UPLOAD_ERR_OK && $_FILES["fotoUsuario"]["size"] != 0) {
        $fotoUsuario  = $diretorio . basename($_FILES['fotoUsuario']['name']); 
        $tipoDaImagem = strtolower(pathinfo($fotoUsuario, PATHINFO_EXTENSION)); 

        if ($_FILES["fotoUsuario"]["size"] > 5000000) {
            $erroUpload = true;
        }

        if ($tipoDaImagem != "jpg" && $tipoDaImagem != "jpeg" && $tipoDaImagem != "png" && $tipoDaImagem != "webp") {
            $erroUpload = true;
        }

        if (!$erroPreenchimento && !$erroUpload) {
            if (!move_uploaded_file($_FILES["fotoUsuario"]["tmp_name"], $fotoUsuario)) {
                $erroUpload = true;
            }
        }
    }

    // Inclui o cabeçalho do site
    include "header.php";

    // Mostra erro ou faz o INSERT
    if ($erroPreenchimento || $erroUpload) {
        echo "
            <div class='container py-5'>
                <div class='alert alert-warning text-center'>
                    <strong>Erro no preenchimento!</strong> Verifique os dados informados e tente novamente.
                </div>
                <div class='text-center mt-4'>
                    <a href='formUsuario.php' class='btn btn-secondary'>Voltar</a>
                </div>
            </div>
        ";
    } else {
        $nivelPadrao = "usuario";

       // Query estruturada de inserção usando o nome correto da coluna (cidadeUsuario)
$inserirUsuario = "INSERT INTO usuarios (fotoUsuario, nomeUsuario, dataNascimentoUsuario, cidadeUsuario, emailUsuario, senhaUsuario, nivelUsuario)
                    VALUES ('$fotoUsuario', '$nomeUsuario', '$dataNascimentoUsuario', '$cidadeUsuario', '$emailUsuario', '$senhaUsuario', '$nivelPadrao')";

        if (mysqli_query($conn, $inserirUsuario)) {
            echo "
                <div class='container py-5'>
                    <div class='alert alert-success text-center'><strong>USUÁRIO</strong> cadastrado com sucesso!</div>
                    <div class='container mt-3 text-center'>
                        <img src='$fotoUsuario' style='width:150px' class='img-thumbnail mb-3'>
                    </div>
                    <table class='table table-striped border'>
                        <tr><th style='width: 30%;'>NOME</th><td>$nomeUsuario</td></tr>
                        <tr><th>DATA DE NASCIMENTO</th><td>$diaNascimentoUsuario/$mesNascimentoUsuario/$anoNascimentoUsuario</td></tr>
                        <tr><th>CIDADE</th><td>$cidadeUsuario</td></tr>
                        <tr><th>EMAIL</th><td>$emailUsuario</td></tr>
                    </table>
                    <div class='text-center mt-4'>
                        <a href='formLogin.php' class='btn btn-success'>Ir para a Tela de Login</a>
                    </div>
                </div>
            ";
        } else {
            echo "
                <div class='container py-5'>
                    <div class='alert alert-danger text-center'>
                        Erro ao tentar inserir dados no banco! <br>
                        <small>" . mysqli_error($conn) . "</small>
                    </div>
                </div>
            ";
        }
    }

    // Fecha o layout com o rodapé
    include "footer.php";

} else {
    header("location:formUsuario.php");
    exit();
}
?>