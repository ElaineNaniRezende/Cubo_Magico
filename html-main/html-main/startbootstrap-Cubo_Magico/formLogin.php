<?php include "header.php" ?>

    <section class="py-5">

        <div class="d-flex justify-content-center mb-3">

            <div class="row">
                <div class="col">

                    <?php
                        //Verifica se há algum parâmetro chamado 'erroLogin' sendo recebido por GET
                        if(isset($_GET['erroLogin'])){
                            $erroLogin = $_GET['erroLogin'];

                            if($erroLogin == 'dadosInvalidos'){
                                echo "<div class='alert alert-warning text-center'><strong>USUÁRIO ou SENHA</strong> inválidos!</div>";
                            }
                        }
                    ?>
                    
                    <h2>Acessar o Sistema:</h2>

                    <form action="actionLogin.php" method="POST" class="was-validated">

                        <div class="form-floating mt-3 mb-3">
                            <input type="email" class="form-control" id="emailUsuario" placeholder="Email" name="emailUsuario" required>
                            <label for="emailUsuario">Email</label>
                            <div class="valid-feedback"></div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-floating mt-3 mb-3">
                            <input type="password" class="form-control" id="senhaUsuario" placeholder="Senha" name="senhaUsuario" required>
                            <label for="senhaUsuario">Senha</label>
                            <div class="valid-feedback"></div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-success">Login</button>
                    </form>
                    <br>
                    <p>Ainda não é cadastrado? <a href="formUsuario.php" title="Cadastrar-se">Clique aqui!</a>&nbsp;<i class="bi bi-emoji-smile"></i></p>

                </div>
            </div>

        </div>

    </section>

<?php include "footer.php" ?>