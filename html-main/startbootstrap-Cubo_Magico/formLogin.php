<?php include "header.php"; ?>

<?php
// Exibe a mensagem vinda da sessão (ex.: ao tentar acessar o carrinho sem login)
if (isset($_SESSION['mensagem_erro'])) {
    echo '
    <div class="container mt-3">
        <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
            ' . $_SESSION['mensagem_erro'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>';

    // Remove a mensagem para que ela não apareça novamente
    unset($_SESSION['mensagem_erro']);
}
?>

<section class="py-5">

    <div class="d-flex justify-content-center mb-3">

        <div class="row">
            <div class="col">

                <?php
                // Erro de usuário/senha inválidos
                if (isset($_GET['erroLogin'])) {
                    if ($_GET['erroLogin'] == 'dadosInvalidos') {
                        echo "<div class='alert alert-warning text-center'><strong>USUÁRIO ou SENHA</strong> inválidos!</div>";
                    }
                }
                ?>

                <h2>Acessar o Sistema:</h2>

                <form action="actionLogin.php" method="POST" class="was-validated">

                    <div class="form-floating mt-3 mb-3">
                        <input type="email" class="form-control" id="emailUsuario" placeholder="Email" name="emailUsuario" required>
                        <label for="emailUsuario">Email</label>
                    </div>

                    <div class="form-floating mt-3 mb-3">
                        <input type="password" class="form-control" id="senhaUsuario" placeholder="Senha" name="senhaUsuario" required>
                        <label for="senhaUsuario">Senha</label>
                    </div>

                    <button type="submit" class="btn btn-success">Login</button>

                </form>

                <br>

                <p>
                    Ainda não é cadastrado?
                    <a href="formUsuario.php">Clique aqui!</a>
                    <i class="bi bi-emoji-smile"></i>
                </p>

            </div>
        </div>

    </div>

</section>

<?php include "footer.php"; ?>