<?php 
// Força o PHP a mostrar erros na tela se algo der errado
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Garante que a sessão está ativa para capturar as mensagens de sucesso/erro
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "conexaoBD.php";
/** @var mysqli $conn */ 

include "header.php"; 

// Captura qual aba deve ficar ativa (se não tiver nenhuma na URL, a padrão é 'usuarios')
$abaAtiva = isset($_GET['aba']) ? $_GET['aba'] : 'usuarios';
?>

<style>
    .card-dashboard {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
    }
    .card-dashboard:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .nav-tabs .nav-link {
        color: #495057;
        font-weight: 600;
        border: none;
        padding: 12px 20px;
    }
    .nav-tabs .nav-link.active {
        color: #ffc107 !important;
        background-color: #212529 !important;
        border-radius: 8px 8px 0 0;
    }
    .table-responsive {
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
    }
</style>

<section class="py-5 bg-light min-vh-100">
    <div class="container">
        
        <div class="text-center mb-5">
            <h2 class="fw-bolder text-dark text-uppercase tracking-wide">Painel de Controle</h2>
            <p class="lead text-muted">Gerenciamento centralizado de usuários, produtos e anúncios da loja.</p>
            <hr class="w-25 mx-auto text-warning" style="height: 3px;">
        </div>

        <?php
        // Busca contagens para os blocos de estatísticas
        $resUser = mysqli_query($conn, "SELECT COUNT(*) as total FROM usuarios");
        $dataUser = mysqli_fetch_assoc($resUser);
        $totalUsuarios = $dataUser['total'];

        $resAnuncio = mysqli_query($conn, "SELECT COUNT(*) as total FROM anuncios");
        $dataAnuncio = mysqli_fetch_assoc($resAnuncio);
        $totalAnuncios = $dataAnuncio['total'];
        ?>

        <div class="row g-4 mb-5 justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card card-dashboard shadow-sm bg-white p-4 rounded-3 border-start border-warning border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold">Total de Usuários</h6>
                            <h2 class="fw-black text-dark m-0"><?php echo $totalUsuarios; ?></h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning fs-3">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-5 col-lg-4">
                <div class="card card-dashboard shadow-sm bg-white p-4 rounded-3 border-start border-dark border-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted text-uppercase small fw-bold">Cubos Anunciados</h6>
                            <h2 class="fw-black text-dark m-0"><?php echo $totalAnuncios; ?></h2>
                        </div>
                        <div class="bg-dark bg-opacity-10 p-3 rounded-circle text-dark fs-3">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs border-bottom-0 mb-4 justify-content-center gap-2">
            <li class="nav-item">
                <a class="nav-link shadow-sm border <?php echo ($abaAtiva == 'usuarios') ? 'active' : ''; ?>" href="listarRegistrosTabela.php?aba=usuarios">
                    <i class="bi bi-people-fill me-2"></i> Gerenciar Usuários
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link shadow-sm border <?php echo ($abaAtiva == 'produtos') ? 'active' : ''; ?>" href="listarRegistrosTabela.php?aba=produtos">
                    <i class="bi bi-box-seam-fill me-2"></i> Gerenciar Produtos / Anúncios
                </a>
            </li>
        </ul>

        <div class="tab-content">
            
            <?php if ($abaAtiva == 'usuarios'): ?>
            <div class="tab-pane fade show active">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark m-0"><i class="bi bi-person-lines-fill me-2 text-warning"></i>Lista de Usuários</h5>
                    <a href="formUsuario.php" class="btn btn-warning btn-sm fw-bold text-dark shadow-sm">
                        <i class="bi bi-person-plus-fill me-1"></i> Cadastrar Novo Usuário
                    </a>
                </div>

                <div class="table-responsive border shadow-sm rounded-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>FOTO</th>
                                <th>NOME</th>
                                <th>DATA NASC.</th>
                                <th>CIDADE</th>
                                <th>EMAIL</th>
                                <th class="text-center" style="width: 160px;">AÇÕES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $resUsers = mysqli_query($conn, "SELECT * FROM usuarios ORDER BY idUsuario DESC");
                            while($user = mysqli_fetch_assoc($resUsers)){
                                $id = $user['idUsuario'];
                                $foto = !empty($user['fotoUsuario']) ? $user['fotoUsuario'] : 'img/3x3.jpg';
                                $dataNasc = date('d/m/Y', strtotime($user['dataNascimentoUsuario']));
                                echo "
                                <tr>
                                    <td class='ps-3 fw-bold'>#$id</td>
                                    <td><img src='$foto' class='rounded-circle border shadow-sm' style='width:40px; height:40px; object-fit:cover;'></td>
                                    <td class='fw-bold text-secondary'>{$user['nomeUsuario']}</td>
                                    <td>$dataNasc</td>
                                    <td><span class='badge bg-light text-dark border'>{$user['cidadeUsuario']}</span></td>
                                    <td class='text-muted small'>{$user['emailUsuario']}</td>
                                    <td class='text-center'>
                                        <div class='btn-group' role='group'>
                                            <a href='formEditarUsuario.php?id=$id' class='btn btn-sm btn-outline-primary' title='Editar'><i class='bi bi-pencil-square'></i></a>
                                            <a href='excluirUsuario.php?id=$id' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Excluir este usuário permanentemente?\");' title='Excluir'><i class='bi bi-trash3-fill'></i></a>
                                        </div>
                                    </td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($abaAtiva == 'produtos'): ?>
            <div class="tab-pane fade show active">
                
                <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
                    <div class="alert alert-success alert-dismissible fade show text-center fw-bold shadow-sm mb-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['mensagem_sucesso']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['mensagem_sucesso']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['mensagem_erro'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show text-center fw-bold shadow-sm mb-3" role="alert">
                        <i class="bi bi-x-octagon-fill me-2"></i> <?php echo $_SESSION['mensagem_erro']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['mensagem_erro']); ?>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark m-0"><i class="bi bi-grid-3x3-gap-fill me-2 text-warning"></i>Lista de Produtos Ofertados</h5>
                    <a href="formAnuncio.php" class="btn btn-dark btn-sm fw-bold text-warning shadow-sm">
                        <i class="bi bi-plus-circle-fill me-1"></i> Cadastrar Novo Cubo
                    </a>
                </div>

                <div class="table-responsive border shadow-sm rounded-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>PRODUTO</th>
                                <th>CATEGORIA</th>
                                <th>PREÇO</th>
                                <th class="text-center" style="width: 160px;">AÇÕES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $resAnuncios = mysqli_query($conn, "SELECT * FROM anuncios ORDER BY idAnuncio DESC");
                            if (mysqli_num_rows($resAnuncios) > 0) {
                                while($prod = mysqli_fetch_assoc($resAnuncios)){
                                    $idProd = $prod['idAnuncio'];
                                    $valorFormatado = "R$ " . number_format($prod['valorAnuncio'], 2, ',', '.');
                                    echo "
                                    <tr>
                                        <td class='ps-3 fw-bold'>#$idProd</td>
                                        <td class='fw-bold text-secondary'>{$prod['tituloAnuncio']}</td>
                                        <td><span class='badge bg-dark text-white opacity-75'>{$prod['categoriaAnuncio']}</span></td>
                                        <td class='text-success fw-bold'>$valorFormatado</td>
                                        <td class='text-center'>
                                            <div class='btn-group' role='group'>
                                                <a href='formEditarAnuncio.php?id=$idProd' class='btn btn-sm btn-outline-primary' title='Editar'><i class='bi bi-pencil-square'></i></a>
                                                <a href='excluirAnuncio.php?id=$idProd' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Excluir este anúncio permanentemente?\");' title='Excluir'><i class='bi bi-trash3-fill'></i></a>
                                            </div>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Nenhum produto cadastrado até o momento.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

        </div> 
    </div>
</section>

<?php 
mysqli_close($conn);
include "footer.php"; 
?>