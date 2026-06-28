<?php 
// 1. Força o PHP a mostrar o erro na tela se algo der errado (evita a página em branco)
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

include "header.php"; 
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                <div class="card shadow border-0 rounded-3">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h4 class="mb-0 fw-bold text-uppercase tracking-wide">
                            <i class="bi bi-plus-circle-fill text-warning me-2"></i> Cadastrar Novo Cubo
                        </h4>
                    </div>
                    
                    <div class="card-body p-4">
                        <form action="actionAnuncio.php" method="POST" class="was-validated" enctype="multipart/form-data">

                            <div class="mb-3">
                                <label for="fotoAnuncio" class="form-label fw-bold text-secondary">Foto do Cubo Mágico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-secondary"><i class="bi bi-image"></i></span>
                                    <input type="file" class="form-control border-start-0" id="fotoAnuncio" name="fotoAnuncio" required>
                                </div>
                                <div class="invalid-feedback">Por favor, selecione uma foto para o produto.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="tituloAnuncio" placeholder="Ex: Cubo Mágico 3x3x3 Cyclone Boys" name="tituloAnuncio" required>
                                <label for="tituloAnuncio">Título do Anúncio / Modelo</label>
                                <div class="invalid-feedback">Insira o título do anúncio.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select" id="categoriaAnuncio" name="categoriaAnuncio" required>
                                    <option value="" disabled selected>Selecione uma Categoria...</option>
                                    <option value="Cubo 2x2x2">Cubo 2x2x2</option>
                                    <option value="Cubo 3x3x3">Cubo 3x3x3</option>
                                    <option value="Cubo 4x4x4 e Maiores">Cubo 4x4x4 e Maiores</option>
                                    <option value="Modificações / Pyraminx / Megaminx">Modificações (Pyraminx, Megaminx...)</option>
                                    <option value="Acessórios e Lubrificantes">Acessórios e Lubrificantes</option>
                                </select>
                                <label for="categoriaAnuncio">Categoria do Cubo</label>
                                <div class="invalid-feedback">Selecione uma categoria válida.</div>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="descricaoAnuncio" placeholder="Informe detalhes técnicos do produto..." name="descricaoAnuncio" style="height: 120px" required></textarea>
                                <label for="descricaoAnuncio">Descrição Detalhada</label>
                                <div class="invalid-feedback">Por favor, adicione uma descrição sobre o produto.</div>
                            </div>

                            <div class="mb-4">
                                <label for="valorAnuncio" class="form-label fw-bold text-secondary">Preço de Venda (R$)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark text-white fw-bold">R$</span>
                                    <input type="number" step="0.01" class="form-control form-control-lg fw-bold text-success" id="valorAnuncio" placeholder="0,00" name="valorAnuncio" required>
                                </div>
                                <div class="invalid-feedback">Insira um valor numérico válido (ex: 49.90).</div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark shadow-sm">
                                    <i class="bi bi-check-circle-fill me-2"></i> Publicar Produto
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

<?php include "footer.php" ?>