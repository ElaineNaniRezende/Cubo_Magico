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
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-brand shadow-sm">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <i class="bi bi-cube" style="font-size:1.6rem"></i>
        <span class="ms-2">Cubo Mágico</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto align-items-center">
          
          <li class="nav-item dropdown">
            <a class="nav-link text-white dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i> 
              <?php 
                // Se estiver logado, mostra o nome do usuário, senão mostra "Minha conta"
                echo isset($_SESSION['logado']) ? $_SESSION['nomeUsuario'] : 'Minha conta'; 
              ?>
            </a>
            <div class="dropdown-menu dropdown-menu-end p-3 text-center" style="width: 230px;">
              <?php if(isset($_SESSION['logado'])): ?>
                <p class="small text-dark">Olá, <?php echo $_SESSION['nomeUsuario']; ?>!</p>
                <a href="logout.php" class="btn btn-danger w-100 fw-bold">Sair <i class="bi bi-box-arrow-right"></i></a>
              <?php else: ?>
                <a href="formLogin.php" class="btn btn-dark w-100 fw-bold mb-2">Entrar ></a>
                <div class="text-muted small mb-2">ou</div>
                <span class="small" style="color: black;">Cliente novo? <a href="formUsuario.php" class="text-dark fw-bold">Cadastre-se</a></span>
              <?php endif; ?>
            </div>
          </li>

          <li class="nav-item"><a class="nav-link" href="#carrinho">Carrinho</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <header id="home" class="bg-hero text-white text-center py-5">
    <div class="container">
      <h1 class="display-5 fw-bold">Cubo Mágico Telêmaco</h1>
      <p class="lead">A maior seleção de cubos mágicos e acessórios.</p>
    </div>
  </header>