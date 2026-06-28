<?php
// 1. Liga o detector de erros antes de qualquer outra coisa
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Inicia a sessão de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Inclui a conexão
include "conexaoBD.php";
/** @var mysqli $conn */

// 4. Captura o ID da URL de forma segura
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $idUsuario = $_GET['id'];
    
    // Busca os dados atuais do usuário
    $sqlUsuario = "SELECT * FROM usuarios WHERE idUsuario = '$idUsuario'";
    $resUsuario = mysqli_query($conn, $sqlUsuario);
    
    if (mysqli_num_rows($resUsuario) > 0) {
        $usuario = mysqli_fetch_assoc($resUsuario);
        
        $nomeUsuario           = $usuario['nomeUsuario'];
        $dataNascimentoUsuario = $usuario['dataNascimentoUsuario'];
        $cidadeUsuario         = $usuario['cidadeUsuario'];
        $emailUsuario          = $usuario['emailUsuario'];
        $nivelUsuario          = $usuario['nivelUsuario'];
        $fotoUsuario           = $usuario['fotoUsuario'];
    } else {
        echo "<script>alert('Usuário não encontrado!'); window.location.href='listarRegistrosTabela.php';</script>";
        exit();
    }
} else {
    header("Location: listarRegistrosTabela.php");
    exit();
}

include "header.php";
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="card shadow border-0 rounded-3">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h4 class="mb-0 fw-bold text-uppercase tracking-wide">
                            <i class="bi bi-pencil-square text-warning me-2"></i> Editar Usuário
                        </h4>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="actionEditarUsuario.php" method="POST" class="was-validated" enctype="multipart/form-data">
                            
                            <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>">

                            <div class="text-center mb-4">
                                <label class="form-label d-block fw-bold text-secondary">Foto de Perfil Atual</label>
                                <img src="<?php echo !empty($fotoUsuario) ? $fotoUsuario : 'img/3x3.jpg'; ?>" class="rounded-circle border shadow-sm mb-2" style="width:100px; height:100px; object-fit:cover;">
                                <div class="input-group mt-2">
                                    <span class="input-group-text bg-white border-end-0 text-secondary"><i class="bi bi-image"></i></span>
                                    <input type="file" class="form-control border-start-0" id="fotoUsuario" name="fotoUsuario">
                                </div>
                                <div class="form-text text-start">Deixe em branco se não quiser alterar a foto atual.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="nomeUsuario" name="nomeUsuario" value="<?php echo $nomeUsuario; ?>" required>
                                <label for="nomeUsuario">Nome Completo</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="date" class="form-control" id="dataNascimentoUsuario" name="dataNascimentoUsuario" value="<?php echo $dataNascimentoUsuario; ?>" required>
                                <label for="dataNascimentoUsuario">Data de Nascimento</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="cidadeUsuario" name="cidadeUsuario" value="<?php echo $cidadeUsuario; ?>" required>
                                <label for="cidadeUsuario">Cidade</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="emailUsuario" name="emailUsuario" value="<?php echo $emailUsuario; ?>" required>
                                <label for="emailUsuario">E-mail</label>
                            </div>

                            <div class="form-floating mb-4">
                                <select class="form-select" id="nivelUsuario" name="nivelUsuario" required>
                                    <option value="cliente" <?php if($nivelUsuario == 'cliente') echo 'selected'; ?>>Cliente comum</option>
                                    <option value="administrador" <?php if($nivelUsuario == 'administrador') echo 'selected'; ?>>Administrador</option>
                                </select>
                                <label for="nivelUsuario">Nível de Permissão</label>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark shadow-sm">
                                    <i class="bi bi-save-fill me-2"></i> Salvar Alterações
                                </button>
                                <a href="listarRegistrosTabela.php" class="btn btn-light btn-sm text-secondary border mt-1">Cancelar</a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php 
mysqli_close($conn);
include "footer.php"; 
?>