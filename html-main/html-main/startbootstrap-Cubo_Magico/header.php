<?php
// Garante que a sessão está ativa para ler os dados do usuário logado
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
    @media (min-width: 992px) {
      .navbar-nav .dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0; /* Evita que o menu suma ao mover o mouse */
      }
    }
    /* Classe auxiliar caso a cor de fundo personalizada não esteja no arquivo css */
    .bg-brand {
      background-color: #212529 !important;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-brand shadow-sm">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <i class="bi bi-cube-fill text-warning me-2" style="font-size:1.6rem"></i>
        <span class="fw-bold">Cubo Mágico</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto align-items-center">
          
          <li class="nav-item">
            <a class="nav-link text-white px-3" href="index.php"><i class="bi bi-house-door"></i> Início</a>
          </li>

          <?php if (isset($_SESSION['logado']) && isset($_SESSION['nivelUsuario']) && $_SESSION['nivelUsuario'] == 'administrador'): ?>
            <li class="nav-item">
              <a class="btn btn-warning btn-sm fw-bold px-3 me-2 text-dark border-0 shadow-sm" href="listarRegistrosTabela.php">
                <i class="bi bi-shield-lock-fill"></i> Painel Admin
              </a>
            </li>
          <?php endif; ?>

          <li class="nav-item dropdown">
            <a class="nav-link text-white dropdown-toggle px-3" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i> 
              <?php 
                // Se estiver logado, mostra o nome do usuário, senão mostra "Minha conta"
                echo isset($_SESSION['logado']) ? $_SESSION['nomeUsuario'] : 'Minha conta'; 
              ?>
            </a>
            <div class="dropdown-menu dropdown-menu-end p-3 text-center shadow border-0" style="width: 230px; margin-top: 5px;">
              <?php if(isset($_SESSION['logado'])): ?>
                <p class="small text-dark mb-1 fw-bold">Olá, <?php echo $_SESSION['nomeUsuario']; ?>!</p>
                <p class="text-muted small mb-3" style="font-size: 0.75rem;">Nível: <?php echo ucfirst($_SESSION['nivelUsuario']); ?></p>
                <a href="logout.php" class="btn btn-danger btn-sm w-100 fw-bold shadow-sm">Sair <i class="bi bi-box-arrow-right"></i></a>
              <?php else: ?>
                <a href="formLogin.php" class="btn btn-dark w-100 fw-bold mb-2 shadow-sm">Entrar</a>
                <div class="text-muted small mb-2">ou</div>
                <span class="small" style="color: black;">Cliente novo? <br><a href="formUsuario.php" class="text-primary fw-bold">Cadastre-se aqui</a></span>
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
      </div>
    </div>
  </nav>

  <header id="home" class="bg-dark text-white text-center py-5 border-bottom border-warning" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('img/hero-bg.jpg') center/cover;">
    <div class="container py-3">
      <h1 class="display-4 fw-bold text-warning text-uppercase tracking-wide">Cubo Mágico Telêmaco</h1>
      <p class="lead text-light-50 fs-5 mb-0">A maior seleção de cubos mágicos profissionais e acessórios do Paraná.</p>
    </div>
  </header>