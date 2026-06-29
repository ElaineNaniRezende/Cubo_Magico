<?php
// Garante que a sessão está activa para ler os dados do usuário logado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Loja de Cubos Mágicos</title>
  <link rel="icon" href="assets/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">

  <style>
    /* Técnica Flexbox para empurrar o rodapé para o final da página */
    html, body {
      height: 100%;
    }
    body {
      display: flex;
      flex-direction: column;
    }
    /* Esta classe faz a tag <section> ou o conteúdo principal ocupar todo o espaço restante */
    section, .main-content {
      flex: 1 0 auto;
    }
    footer {
      flex-shrink: 0;
    }

    @media (min-width: 992px) {
      .navbar-nav .dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0;
      }
    }

    .bg-brand {
      background-color: #212529 !important;
    }
    .text-azure {
      color: #0077b6 !important; /* Seu tom de azul elegante */
    }
  </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-brand shadow-sm">
  <div class="container">

    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <i class="bi bi-cube-fill text-azure me-2" style="font-size:1.6rem"></i>
      <span class="fw-bold">Cubo Mágico</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">

      <ul class="navbar-nav ms-auto align-items-center">

        <li class="nav-item">
          <a class="nav-link text-white px-3" href="index.php">
            <i class="bi bi-house-door"></i> Início
          </a>
        </li>

        <?php if (isset($_SESSION['logado']) && isset($_SESSION['nivelUsuario']) && $_SESSION['nivelUsuario'] == 'administrador'): ?>
          <li class="nav-item">
            <a class="btn btn-sm fw-bold px-3 me-2 text-white border-0 shadow-sm"
               href="listarRegistrosTabela.php" style="background-color: #0077b6;">
              <i class="bi bi-shield-lock-fill"></i> Painel Admin
            </a>
          </li>
        <?php endif; ?>

        <li class="nav-item dropdown">
          <a class="nav-link text-white dropdown-toggle px-3"
             href="#"
             role="button"
             data-bs-toggle="dropdown">

            <i class="bi bi-person-circle"></i>

            <?php
            echo isset($_SESSION['logado'])
                ? $_SESSION['nomeUsuario']
                : 'Minha conta';
            ?>

          </a>

          <div class="dropdown-menu dropdown-menu-end p-3 text-center shadow border-0"
               style="width:230px; margin-top:5px;">

            <?php if(isset($_SESSION['logado'])): ?>

              <p class="small text-dark mb-1 fw-bold">
                Olá, <?php echo $_SESSION['nomeUsuario']; ?>!
              </p>

              <p class="text-muted small mb-3" style="font-size:0.75rem;">
                Nível: <?php echo ucfirst($_SESSION['nivelUsuario']); ?>
              </p>

              <a href="logout.php"
                 class="btn btn-danger btn-sm w-100 fw-bold shadow-sm">
                 Sair <i class="bi bi-box-arrow-right"></i>
              </a>

            <?php else: ?>

              <a href="formLogin.php"
                 class="btn btn-dark w-100 fw-bold mb-2 shadow-sm">
                 Entrar
              </a>

              <div class="text-muted small mb-2">ou</div>

              <span class="small" style="color:black;">
                Cliente novo?<br>
                <a href="formUsuario.php" class="text-azure fw-bold">
                  Cadastre-se aqui
                </a>
              </span>

            <?php endif; ?>

          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white px-3 position-relative" href="carrinho.php">
            <i class="bi bi-cart3 fs-5"></i>
            <span class="ms-1 d-lg-none">Carrinho</span>
          </a>
        </li>

      </ul>

      <form action="index.php"
            method="GET"
            class="d-flex ms-lg-3 my-2 my-lg-0"
            style="max-width:300px;">

        <div class="input-group">

          <select class="form-select bg-dark text-white border-secondary py-1 px-3 small"
                  name="filtrarCategoria"
                  style="font-size:0.8rem; border-radius:6px 0 0 6px;"
                  onchange="this.form.submit()">

            <option value="Todos" <?php if(!isset($_GET['filtrarCategoria']) || $_GET['filtrarCategoria'] == 'Todos') echo 'selected'; ?>>
                Todos os Produtos
            </option>

            <option value="Cubo 2x2x2 (Pocket Cube)" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Cubo 2x2x2 (Pocket Cube)') echo 'selected'; ?>>
                Cubo 2x2x2 (Pocket Cube)
            </option>

            <option value="Cubo 3x3x3 (Clássico)" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Cubo 3x3x3 (Clássico)') echo 'selected'; ?>>
                Cubo 3x3x3 (Clássico)
            </option>

            <option value="Cubo 4x4x4 (Rubik's Revenge)" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == "Cubo 4x4x4 (Rubik's Revenge)") echo 'selected'; ?>>
                Cubo 4x4x4 (Rubik's Revenge)
            </option>

            <option value="Cubo 5x5x5 (Professor's Cube)" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == "Cubo 5x5x5 (Professor's Cube)") echo 'selected'; ?>>
                Cubo 5x5x5 (Professor's Cube)
            </option>

            <option value="Cubo 6x6x6" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Cubo 6x6x6') echo 'selected'; ?>>
                Cubo 6x6x6
            </option>

            <option value="Cubo 7x7x7" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Cubo 7x7x7') echo 'selected'; ?>>
                Cubo 7x7x7
            </option>

            <option value="Pyraminx" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Pyraminx') echo 'selected'; ?>>
                Pyraminx
            </option>

            <option value="Megaminx" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Megaminx') echo 'selected'; ?>>
                Megaminx
            </option>

            <option value="Skewb" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Skewb') echo 'selected'; ?>>
                Skewb
            </option>

            <option value="Square-1" <?php if(isset($_GET['filtrarCategoria']) && $_GET['filtrarCategoria'] == 'Square-1') echo 'selected'; ?>>
                Square-1
            </option>

          </select>

          <button class="btn btn-outline-secondary btn-sm py-1" type="submit" style="border-radius: 0 6px 6px 0;">
            <i class="bi bi-search text-white" style="font-size:0.75rem;"></i>
          </button>

        </div>

      </form>

    </div>
  </div>
</nav>