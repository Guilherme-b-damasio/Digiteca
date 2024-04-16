<?php
session_start();


// Verifica se os dados da etapa 1 foram enviados
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['etapa1'])) {
    // Armazenar os dados da etapa 1 na sessão
    $_SESSION['dados_etapa1'] = [
        'txtNOME' => $_POST['txtNOME'],
        'txtEMAIL' => $_POST['txtEMAIL'],
        'txtSENHA' => $_POST['txtSENHA']
    ];

    // Avançar para a etapa 2
    $_SESSION['etapa_cadastro'] = 2;
    header("Location: cadastro.php");
    exit;
}

$etapa = isset($_SESSION['etapa_cadastro']) ? $_SESSION['etapa_cadastro'] : 1;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Cadastro - DIGITECA</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css">
</head>
<body>
    <div class="container">
        <div class="container-login">
            <div class="wrap-login">
                <?php if ($etapa == 1): ?>
                    <form class="login-form" method="POST" action="cadastro.php">
                        <span class="login-form-title">Cadastro - Etapa 1</span>
                        <div class="wrap-input margin-bottom-35">
                            <input class="input-form" type="text" name="txtNOME" required autofocus>
                            <span class="focus-input-form" data-placeholder="Nome"></span>
                        </div>
                        <div class="wrap-input margin-bottom-35">
                            <input class="input-form" type="text" name="txtEMAIL" required>
                            <span class="focus-input-form" data-placeholder="Login"></span>
                        </div>
                        <div class="wrap-input margin-bottom-35">
                            <input class="input-form" type="password" name="txtSENHA" required>
                            <span class="focus-input-form" data-placeholder="Senha"></span>
                        </div>
                        <input type="hidden" name="etapa1" value="1">
                        <button class="login-form-btn" type="submit">Próxima Etapa</button>
                    </form>
                <?php endif; ?>

                <?php if ($etapa == 2): ?>

                    <!--inicia o formulario-->
                    <form class="login-form" method="POST" action="/DB/usuarios/cadastrarBD.php">
                        <span class="login-form-title">Cadastro - Etapa 2</span>
                        <div class="wrap-input margin-bottom-35">
                            <input class="input-form" type="text" name="txtTELEFONE" required>
                            <span class="focus-input-form" data-placeholder="Telefone"></span>
                        </div>
                        <div class="wrap-input margin-bottom-35">
                            <input class="input-form" type="date" name="txtDATA_NASCIMENTO" required>
                            <span class="focus-input-form" data-placeholder=""></span>
                        </div>
                        <div class="wrap-input margin-bottom-35">
                            <input class="input-form" type="text" name="txtCPF" required>
                            <span class="focus-input-form" data-placeholder="CPF"></span>
                        </div>
                        <input type="hidden" name="etapa2" value="2">
                        <button class="login-form-btn" type="submit">Concluir Cadastro</button>
                    </form>

                    <!-- HTML para exibir a mensagem de erro -->
                    <?php
                        if ( isset($_GET["mensagem_erro"]) == true ) {
                            $mensagem_erro = $_GET["mensagem_erro"];
                            print_r ("<div class=\"alert alert-danger\" role=\"alert\">erro: $mensagem_erro</div>");
                        }
                    ?>
                <?php endif; ?>
            </div>
        </div>
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
</body>
</html>
