<?php 
// 1. Força o PHP a mostrar erros na tela se algo der errado
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Garante que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔐 TRAVA DE SEGURANÇA: Só administrador pode acessar essa página
if (!isset($_SESSION['logado']) || $_SESSION['nivelUsuario'] !== 'administrador') {
    header("Location: index.php");
    exit();
}

include "conexaoBD.php";
/** @var mysqli $conn */ 

// Verifica se o ID do anúncio foi passado na URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: listarRegistrosTabela.php?aba=produtos");
    exit();
}

$idAnuncio = $_GET['id'];

// Busca os dados atuais do produto no banco
$query = "SELECT * FROM anuncios WHERE idAnuncio = '$idAnuncio'";
$resultado = mysqli_query($conn, $query);

if (mysqli_num_rows($resultado) == 0) {
    // Se não achar o produto, volta pro painel
    header("Location: listarRegistrosTabela.php?aba=produtos");
    exit();
}

$prod = mysqli_fetch_assoc($resultado);

include "header.php"; 
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="card shadow border-0 rounded-3">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h4 class="mb-0 fw-bold text-uppercase tracking-wide">
                            <i class="bi bi-pencil-square text-warning me-2"></i> Editar Cubo Mágico
                        </h4>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="actionEditarAnuncio.php" method="POST" class="was-validated" enctype="multipart/form-data">
                            
                            <input type="hidden" name="idAnuncio" value="<?php echo $prod['idAnuncio']; ?>">

                            <div class="mb-3 text-center">
                                <label class="form-label d-block fw-bold text-secondary">Foto Atual do Produto</label>
                                <img src="<?php echo $prod['fotoAnuncio']; ?>" style="max-width: 150px; max-height: 150px; object-fit: cover;" class="img-thumbnail rounded shadow-sm mb-2" alt="Foto atual">
                                <input type="hidden" name="fotoAtual" value="<?php echo $prod['fotoAnuncio']; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="fotoAnuncio" class="form-label fw-bold text-secondary">Alterar Foto (Deixe em branco para manter a atual)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-secondary"><i class="bi bi-image"></i></span>
                                    <input type="file" class="form-control border-start-0" id="fotoAnuncio" name="fotoAnuncio">
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="tituloAnuncio" name="tituloAnuncio" value="<?php echo $prod['tituloAnuncio']; ?>" required>
                                <label for="tituloAnuncio">Título do Anúncio / Modelo</label>
                                <div class="invalid-feedback">Insira o título do anúncio.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" id="categoriaAnuncio" name="categoriaAnuncio" required>

                                    <option value="Cubo 2x2x2 (Pocket Cube)" <?php echo ($prod['categoriaAnuncio'] == 'Cubo 2x2x2 (Pocket Cube)') ? 'selected' : ''; ?>>
                                        Cubo 2x2x2 (Pocket Cube)
                                    </option>

                                    <option value="Cubo 3x3x3 (Clássico)" <?php echo ($prod['categoriaAnuncio'] == 'Cubo 3x3x3 (Clássico)') ? 'selected' : ''; ?>>
                                        Cubo 3x3x3 (Clássico)
                                    </option>

                                    <option value="Cubo 4x4x4 (Rubik's Revenge)" <?php echo ($prod['categoriaAnuncio'] == "Cubo 4x4x4 (Rubik's Revenge)") ? 'selected' : ''; ?>>
                                        Cubo 4x4x4 (Rubik's Revenge)
                                    </option>

                                    <option value="Cubo 5x5x5 (Professor's Cube)" <?php echo ($prod['categoriaAnuncio'] == "Cubo 5x5x5 (Professor's Cube)") ? 'selected' : ''; ?>>
                                        Cubo 5x5x5 (Professor's Cube)
                                    </option>

                                    <option value="Cubo 6x6x6" <?php echo ($prod['categoriaAnuncio'] == 'Cubo 6x6x6') ? 'selected' : ''; ?>>
                                        Cubo 6x6x6
                                    </option>

                                    <option value="Cubo 7x7x7" <?php echo ($prod['categoriaAnuncio'] == 'Cubo 7x7x7') ? 'selected' : ''; ?>>
                                        Cubo 7x7x7
                                    </option>

                                    <option value="Pyraminx" <?php echo ($prod['categoriaAnuncio'] == 'Pyraminx') ? 'selected' : ''; ?>>
                                        Pyraminx
                                    </option>

                                    <option value="Megaminx" <?php echo ($prod['categoriaAnuncio'] == 'Megaminx') ? 'selected' : ''; ?>>
                                        Megaminx
                                    </option>

                                    <option value="Skewb" <?php echo ($prod['categoriaAnuncio'] == 'Skewb') ? 'selected' : ''; ?>>
                                        Skewb
                                    </option>

                                    <option value="Square-1" <?php echo ($prod['categoriaAnuncio'] == 'Square-1') ? 'selected' : ''; ?>>
                                        Square-1
                                    </option>

                                </select>
                                <label for="categoriaAnuncio">Categoria Produto</label>
                                <div class="invalid-feedback">Selecione uma categoria válida.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="descricaoAnuncio" name="descricaoAnuncio" style="height: 120px" required><?php echo $prod['descricaoAnuncio']; ?></textarea>
                                <label for="descricaoAnuncio">Descrição Detalhada</label>
                                <div class="invalid-feedback">Por favor, adicione uma descrição sobre o produto.</div>
                            </div>

                            <div class="mb-4">
                                <label for="valorAnuncio" class="form-label fw-bold text-secondary">Preço de Venda (R$)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark text-white fw-bold">R$</span>
                                    <input type="number" step="0.01" class="form-control form-control-lg fw-bold text-success" id="valorAnuncio" name="valorAnuncio" value="<?php echo $prod['valorAnuncio']; ?>" required>
                                </div>
                                <div class="invalid-feedback">Insira um valor numérico válido.</div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark shadow-sm">
                                    <i class="bi bi-save2-fill me-2"></i> Salvar Alterações
                                </button>
                                <a href="listarRegistrosTabela.php?aba=produtos" class="btn btn-light btn-sm text-secondary border mt-1">
                                    <i class="bi bi-arrow-left-short"></i> Cancelar e Voltar
                                </a>
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