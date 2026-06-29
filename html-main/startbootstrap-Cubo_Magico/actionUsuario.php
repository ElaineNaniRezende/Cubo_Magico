<?php
// 1. Inicia a sessão na primeira linha para podermos verificar quem está logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ATIVAÇÃO DE ERROS PARA DETECÇÃO NO NAVEGADOR
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Inclui a conexão com o banco
include "conexaoBD.php";
/** @var mysqli $conn */

// 3. Protege a função de filtragem
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
    
    // Bloco de variáveis para armazenar as informações
    $nomeUsuario = $dataNascimentoUsuario = $cidadeUsuario = $emailUsuario = $senhaUsuario = $confirmarSenhaUsuario = "";
    $diaNascimentoUsuario = $mesNascimentoUsuario = $anoNascimentoUsuario = "";

    // Controle de erros
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
    $fotoUsuario  = "img/foto_cliente.png"; 
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

    // Se houver erro, exibe a mensagem de erro padrão
    if ($erroPreenchimento || $erroUpload) {
        include "header.php";
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
        include "footer.php";
    } else {
        $nivelPadrao = "usuario";

        $inserirUsuario = "INSERT INTO usuarios (fotoUsuario, nomeUsuario, dataNascimentoUsuario, cidadeUsuario, emailUsuario, senhaUsuario, nivelUsuario)
                           VALUES ('$fotoUsuario', '$nomeUsuario', '$dataNascimentoUsuario', '$cidadeUsuario', '$emailUsuario', '$senhaUsuario', '$nivelPadrao')";

        if (mysqli_query($conn, $inserirUsuario)) {
            
            // ✨ REDIRECIONAMENTO AUTOMÁTICO DO ADMINISTRADOR (Antes de renderizar o HTML)
            // Criamos uma checagem abrangente: se houver qualquer indício de que você está logada como admin, volta na hora!
            $isAdmin = false;
            if (isset($_SESSION['nivelUsuario']) && ($_SESSION['nivelUsuario'] == 'admin' || $_SESSION['nivelUsuario'] == 'administrador')) {
                $isAdmin = true;
            } elseif (isset($_SESSION['nomeUsuario']) && strpos(strtolower($_SESSION['nomeUsuario']), 'admin') !== false) {
                // Checagem extra de segurança caso o nível esteja guardado com outro nome
                $isAdmin = true;
            }

            if ($isAdmin) {
                header("Location: listarRegistrosTabela.php");
                exit();
            }
            
            // SÓ CHEGA AQUI SE FOR UM VISITANTE COMUM SE CADASTRANDO
            include "header.php";
            echo "
                <div class='container py-5'>
                    <div class='alert alert-success text-center'><strong>SUCESSO!</strong> Seu cadastro foi realizado.</div>
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
                        <a href='formLogin.php' class='btn btn-success fw-bold shadow-sm px-4'>
                            <i class='bi bi-box-arrow-in-right me-1'></i> Ir para a Tela de Login
                        </a>
                    </div>
                </div>
            ";
            include "footer.php";
            
        } else {
            include "header.php";
            echo "
                <div class='container py-5'>
                    <div class='alert alert-danger text-center'>
                        Erro ao tentar inserir dados no banco! <br>
                        <small>" . mysqli_error($conn) . "</small>
                    </div>
                </div>
            ";
            include "footer.php";
        }
    }

} else {
    header("location:formUsuario.php");
    exit();
}
?>