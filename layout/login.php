

<!DOCTYPE html>
<html lang="pt-br">

<?php
    function isMobileDevice() {
    return (isset($_SERVER['HTTP_USER_AGENT']) && 
            preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|' .
            'compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|' .
            'midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)' .
            '|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|' .
            'wap|windows ce|xda|xiino|android|ipad|playbook|silk/i', $_SERVER['HTTP_USER_AGENT']));
} ?>

<head>
    <title>Login - DIGITECA</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="assets/css/login.css"> 
    <!-- Incluir outros arquivos CSS ou JS se necessário -->
</head>
<body>
    <div class="container">
        <div class="container-login">
            <div class="wrap-login">
                <!-- Início do Código PHP para sessão e conexão -->
                <?php
                session_start();
                include('footer.html');
                ?>

                <!-- Formulário de login com ação apontando para 'DB/login/loginDB.php' -->
                <form class="login-form" id="systemLogin" method="POST" action="DB/login/loginDB.php">
                    <span class="login-form-title">
                        Faça login
                    </span>

                    <div class="wrap-input margin-top-35 margin-bottom-35">
                        <input class="input-form" type="login" name="txtEmailLogin" id="loginEmail" autocomplete="off" required autofocus>
                        <span class="focus-input-form" data-placeholder="Login"></span>
                    </div>

                    <div class="wrap-input margin-bottom-35">
                        <input class="input-form" type="password" name="txtSenhaLogin" id="loginSenha" required>
                        <span class="focus-input-form" data-placeholder="Senha"></span>
                    </div>

                    <div class="checkbox mb-3">
                        <label class="form-cadastro">
                            <a href="layout\cadastro.php">Cadastro</a>
                        </label>
                    </div>

                    <div class="container-login-form-btn">
                        <button class="login-form-btn" type="submit">
                            Login
                        </button>
                    </div>

                    <?php
                        if ( isset($_GET["mensagem_erro"]) == true ) {
                            $mensagem_erro = $_GET["mensagem_erro"];
                            print_r ("<div class=\"alert alert-danger\" role=\"alert\">Houve um erro no seu login: $mensagem_erro</div>");
                            }
                    ?>


                </form>

           

            <?php if (!isMobileDevice()): // Verifica se não é um dispositivo móvel ?>
                </div>
                    <div class="imgLogg">
                        <img src="assets/images/login.png" width="370" height="370" class="margin-left-50" />
                    </div>
                </div>
                
            <?php endif; ?>
            
    </div>

    <!-- Scripts para interação dos inputs e funcionalidades adicionais -->
    <script>
        let inputs = document.getElementsByClassName('input-form');
        for (let input of inputs) {
            input.addEventListener("blur", function() {
                if(input.value.trim() != ""){
                    input.classList.add("has-val");
                } else {
                    input.classList.remove("has-val");
                }
            });
        }
    </script>
    <?php include('footer.html'); ?>
</body>
</html>
