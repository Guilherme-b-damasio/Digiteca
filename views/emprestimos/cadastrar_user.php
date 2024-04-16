<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

include("../../recursos.php");
include('../../layout/header.html');
include('../../layout/navbar_user.php');

function isMobileDevice() {
    return (isset($_SERVER['HTTP_USER_AGENT']) && 
            preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|' .
            'compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|' .
            'midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)' .
            '|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|' .
            'wap|windows ce|xda|xiino|android|ipad|playbook|silk/i', $_SERVER['HTTP_USER_AGENT']));
}

?>

<link rel="stylesheet" href="../../assets/css/navbar.css">
<link rel="stylesheet" href="../../assets/css/menuMobile.css">

<div class="container mx-auto mt-4">
    <div class="alert alert-info" role="alert">
        Emprestar um Livro
    </div>

    <?php
       if ( isset($_GET["mensagem_erro"]) == true ) {
            $mensagem_erro = $_GET["mensagem_erro"];
            print_r ("<div class=\"alert alert-danger\" role=\"alert\">Erro: $mensagem_erro</div>");
         }
    ?>

    <form action="/DB/emprestimos/cadastrar_user.php" method="post">

        <div class="form-group">
            <label >Data do Empréstimo</label>
            <input class="form-control" id="data_emprestimo" name="txtDATA_EMPRESTADO" type="text" value="<?php
                // Utilizando DateTime para formatar a data
                $dataHoje = new DateTime(obter_data_dd_mm_yyyy());
                echo $dataHoje->format('d/m/Y');
            ?>"readonly>
        </div>

        <div class="form-group">
            <label>Dias que o livro permanecerá emprestado</label>
            <select class="form-control" name="txtTEMPO_EMPRESTIMO" readonly>
                <option value="45">45 dias</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block">Emprestar</button>

    </form>
</div>
<script>
$(document).ready(function() {
            // Se for um dispositivo móvel, habilitar o menu interativo
            <?php if (isMobileDevice()): ?>
                $('.site-header').prepend('<div class="menu"> \
                    <div class="containerMenu"> \
                        <div class="toggle"></div> \
                        <span class="hidden"><a href="../livros/visualizar_user.php">Livros</a></span> \
                        <span class="hidden"><a href="../emprestimos/visualizar_user.php">Emprestimos</a></span> \
                        <span class="hidden"><a href="../../index.php">Sair</a></span> \
                    </div> \
                </div>');
                
                $('.toggle').on('click', function() {
                    $('.menu').toggleClass('expanded');
                    $('span').toggleClass('hidden');
                    $('.container, .toggle').toggleClass('close');
                });

            <?php endif; ?>
        });
</script>
<?php include('../../layout/footer.html');?>
