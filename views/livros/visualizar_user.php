<?php
include('../../seguranca/seguranca.php');
session_start();
if (administrador_logado() == false) {
    header("location: /index.php");
    exit;
}

function isMobileDevice() {
    return (isset($_SERVER['HTTP_USER_AGENT']) && 
            preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|' .
            'compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|' .
            'midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)' .
            '|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|' .
            'wap|windows ce|xda|xiino|android|ipad|playbook|silk/i', $_SERVER['HTTP_USER_AGENT']));
}

include('../../layout/header.html');
include('../../layout/navbar_user.php');
require_once('../../conexao/conexao.php');

// Verifica se o termo de pesquisa foi enviado
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$livrosPorPagina = 16;
$ultimoId = isset($_GET['ultimoId']) ? (int)$_GET['ultimoId'] : 0; // Último ID visto na página anterior
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Monta a consulta SQL base
$comandoSQL = "SELECT *, TO_BASE64(IMAGEM) AS IMAGEM_BASE64 FROM livros WHERE UNIDADES_DISPONIVEIS > 0";

// Aplica filtro de pesquisa, se houver
if (!empty($searchTerm)) {
    $comandoSQL .= " AND (TITULO LIKE :searchTerm OR EDITORA LIKE :searchTerm)";
}

// Aplica a paginação baseada em cursor
if ($ultimoId > 0) {
    $comandoSQL .= " AND ISBN > :ultimoId";
}

$comandoSQL .= " ORDER BY CAST(ISBN AS UNSIGNED) ASC LIMIT :limit";

// Prepara e executa a consulta
$select = $conexao->prepare($comandoSQL);
if (!empty($searchTerm)) {
    $select->bindValue(':searchTerm', "%$searchTerm%");
}
if ($ultimoId > 0) {
    $select->bindValue(':ultimoId', $ultimoId, PDO::PARAM_INT);
}
$select->bindValue(':limit', $livrosPorPagina, PDO::PARAM_INT);
$select->execute();
$resultado = $select->fetchAll();

// Calcula o total de páginas
$totalLivros = $conexao->query("SELECT COUNT(*) FROM livros WHERE UNIDADES_DISPONIVEIS > 0")->fetchColumn();
$totalPaginas = ceil($totalLivros / $livrosPorPagina);
?>

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/cards.css">
<link rel="stylesheet" href="/assets/css/navbar.css">
<link rel="stylesheet" href="/assets/css/menuMobile.css">

<div class="container ">

    <!-- Formulário de pesquisa -->
    <div class="search-container">
        <form action="" method="get">
            <input type="text" placeholder="Pesquisar livros..." name="search">
            <button type="submit">Pesquisar</button>
        </form>
    </div>

    <div class="livros-container">
        <div class="row">
            <?php foreach ($resultado as $linha): ?>
                <div class="col-md-4 mb-4">
                    <div class="card-custom">
                        <?php if ($linha["IMAGEM_BASE64"]): ?>
                            <img class="card-img-top lazyload" src="data:image/jpeg;base64,<?php echo $linha["IMAGEM_BASE64"]; ?>" alt="<?php echo htmlspecialchars($linha["TITULO"]); ?>">

                        <?php else: ?>
                            <img class="card-img-top" src="../../assets/images/padraoLIVRO.webp" alt="Imagem Padrão">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($linha["TITULO"]); ?></h5>
                            <!--
                            <p class="card-text">ISBN: <?php echo htmlspecialchars($linha["ISBN"]); ?></p>
                            <p class="card-text">Editora: <?php echo htmlspecialchars($linha["EDITORA"]); ?></p>-->


                        </div>

                        <div class="card-footer">
                            <a href="/views/livros/editar_user.php?ISBN=<?php echo $linha["ISBN"]; ?>" class="btn">Descrição</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Paginação -->
    <div class="pagination">
        <?php
            if (!empty($resultado)) {
                $ultimoLivro = end($resultado);
                $ultimoId = $ultimoLivro['ISBN'];
                $linkProximaPagina = "/views/livros/visualizar_user.php?ultimoId=$ultimoId";
                if (!empty($searchTerm)) {
                    $linkProximaPagina .= "&search=" . urlencode($searchTerm);
                }
                echo "<a href='$linkProximaPagina'>Próxima Página</a>";
            }?>

    <button class="btn-voltar" onclick="window.history.back();">Voltar</button>

    </div>

    <style>
    /* Estilização básica para ambos os botões */
    .pagination a, .btn-voltar {
        color: #ffffff; /* Cor do texto */
        background-color: #FF7B00; /* Cor de fundo */
        border: none; /* Remove a borda */
        padding: 10px 20px; /* Espaçamento interno */
        text-decoration: none; /* Remove o sublinhado do texto */
        font-size: 16px; /* Tamanho do texto */
        border-radius: 5px; /* Bordas arredondadas */
        transition: background-color 0.3s, transform 0.2s; /* Transição suave para hover */
        box-shadow: 0 2px 4px rgba(0,0,0,0.2); /* Sombra sutil */
        cursor: pointer; /* Cursor do mouse como ponteiro */
    }

    /* Efeito ao passar o mouse por cima do botão */
    .pagination a:hover, .btn-voltar:hover {
        background-color: #0056b3; /* Cor de fundo mais escura */
        transform: translateY(-2px); /* Efeito de "levanta" */
    }

    /* Efeito ao clicar no botão */
    .pagination a:active, .btn-voltar:active {
        transform: translateY(1px); /* Efeito de "pressiona" */
    }

    /* Especificações adicionais para o botão Voltar, se necessário */
    .btn-voltar {
        margin-right: 10px; /* Margem à direita */
    }

    /* Estilização da área de paginação para centralizar os botões */
    .pagination {
        display: flex;
        justify-content: center; /* Centraliza os botões na área de paginação */
        align-items: center;
        gap: 15px; /* Espaçamento entre os botões */
        padding: 20px 0; /* Espaçamento vertical */
    }

    /* Estilos responsivos para dispositivos móveis */
    @media (max-width: 768px) {
        .livros-container .row {
            max-width: 100%; /* Permite que as linhas ocupem mais espaço em dispositivos menores */
        }

        .col-md-4 {
            flex: 0 0 50%; /* Faz cada card de livro ocupar metade da largura da tela em dispositivos móveis */
            max-width: 50%; /* Evita que os cards excedam 50% da largura da tela */
        }

        .card-img-top {
            max-width: 100%; /* Reduz ainda mais a largura máxima da imagem para se adaptar a telas menores */
        }
    }

    /* Estilos para tablets e dispositivos com telas um pouco maiores */
    @media (min-width: 769px) and (max-width: 1024px) {
        .col-md-4 {
            flex: 0 0 33.33333%; /* Permite 3 cards por linha em tablets */
            max-width: 33.33333%;
        }

        .card-img-top {
            max-width: 70%; /* Ajusta a largura da imagem para tablets */
        }
    }


</style>

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

</div>

<?php include('../../layout/footer.html'); ?>
