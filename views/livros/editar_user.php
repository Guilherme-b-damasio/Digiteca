<?php
// Inclui scripts necessários e verifica se o usuário está logado
include('../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) { header("location: /index.php"); exit; }

include("../../layout/header.html");
include('../../layout/navbar_user.php');
require_once("../../conexao/conexao.php");

if(!filter_input(INPUT_GET, "ISBN", FILTER_SANITIZE_STRING)) {
    echo "ISBN é inválido!";
} else {
    $ISBN = filter_input(INPUT_GET, "ISBN", FILTER_SANITIZE_STRING);
    $consulta = $conexao->query("SELECT *, TO_BASE64(IMAGEM) AS IMAGEM_BASE64 FROM livros WHERE ISBN='$ISBN'");
    $linha = $consulta->fetch(PDO::FETCH_ASSOC);
    $_SESSION['current_ISBN'] = $ISBN;
}


function isMobileDevice() {
    return (isset($_SERVER['HTTP_USER_AGENT']) && 
            preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|' .
            'compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|' .
            'midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)' .
            '|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|' .
            'wap|windows ce|xda|xiino|android|ipad|playbook|silk/i', $_SERVER['HTTP_USER_AGENT']));
}

?>


<link rel="stylesheet" href="/assets/css/editar_user.css">
<link rel="stylesheet" href="/assets/css/navbar.css">
<link rel="stylesheet" href="/assets/css/menuMobile.css">
<!--<link rel="stylesheet" href="/digiteca/assets/css/bnt.css">-->

<div class="container">
    <!-- Cabeçalho da Página 
    <div class="card text-white bg-primary mb-3">
        <div class="card-body">
            <div class="text-center" style="font-size: 1.2em;">Informações do Livro</div>
        </div>
    </div>-->
    <div class="Voltar">
        <a href="/views/livros/visualizar_user.php" class="btn btnVoltar" id="btnVoltar">Voltar
        </a>
    </div>

    <div class="row">
        <div class="colImg">
            <!-- Exibição da Imagem do Livro -->
            <?php if ($linha["IMAGEM_BASE64"]): ?>
                <img class="img-reduzida" src="data:image/jpeg;base64,<?php echo $linha["IMAGEM_BASE64"]; ?>" alt="<?php echo htmlspecialchars($linha["TITULO"]); ?>">
            <?php else: ?>
                        <img class="img-padrao" src="/assets/images/padraoLIVRO.jpg" alt="Imagem Padrão">
            <?php endif; ?>   
            
            <a href="/views/emprestimos/cadastrar_user.php?ISBN=<?php echo $linha["ISBN"]; ?>" class="btn btnEmprestar" id="btnEmprestar">Emprestar</a>

        </div>

        <div class="colLivro">
            <!-- Informações do Livro -->
            <h3><?php echo htmlspecialchars($linha["TITULO"]); ?></h3>
            <p><strong>Autor:</strong> <?php echo htmlspecialchars($linha["AUTOR"]); ?></p>
            <p><strong>Descrição:</strong> <?php echo htmlspecialchars($linha["DESCRICAO"]); ?></p>
            <p><strong>Gênero:</strong> <?php echo htmlspecialchars($linha["GENERO"]); ?></p>
            <p><strong>Editora:</strong> <?php echo htmlspecialchars($linha["EDITORA"]); ?></p>
            <p><strong>Ano de Publicação:</strong> <?php echo htmlspecialchars($linha["ANO_PUBLICACAO"]); ?></p>
            <p><strong>Unidades Disponíveis:</strong> <?php echo htmlspecialchars($linha["UNIDADES_DISPONIVEIS"]); ?></p>
           
        </div>
    </div>
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

<?php include('../../layout/footer.html'); ?>


