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
require_once("../../conexao/conexao.php");
require_once("../../recursos.php");
?>

<link rel="stylesheet" href="../../assets/css/navbar.css">
<link rel="stylesheet" href="../../assets/css/visualizarUSER.css">
<link rel="stylesheet" href="../../assets/css/menuMobile.css">

<style>

    .table thead th {
            background-color: #e9ecef; /* Cor de fundo padrão para todos os cabeçalhos */
        }

    /* Estilos específicos para destacar as colunas */
    .table thead th.titulo,
    .table thead th.emprestado-para,
    .table thead th.data-emprestimo {
        background-color: #007bff; /* Cor de fundo específica para destacar */
        color: white; /* Cor do texto para garantir contraste */
    }

    @media screen and (max-width: 768px) {
        .table-responsive-mobile {
            display: block;
            overflow-x: auto;
            width: 100%;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive-mobile table {
            width: 100%;
        }

        .table-responsive-mobile thead {
            display: none;
        }

        .table-responsive-mobile td {
            display: block;
            text-align: right;
            font-size: 0.8em;
            border-right: 2px solid #e9ecef;
            padding-left: 50%;
            position: relative;
            text-align: right;
        }

        .table-responsive-mobile td:before {
            content: attr(data-label);
            position: absolute;
            left: 0;
            width: 45%;
            padding-left: 15px;
            font-weight: bold;
            text-align: left;
        }

        .table-responsive-mobile tr {
            border-bottom: 1px solid #dee2e6;
        }

        .badge {
            display: inline-block;
            width: auto;
            text-align: center;
        }
    }
</style>

<div class="container mx-auto mt-4 table-responsive-mobile">
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Título</th>
                <th>Emprestado para</th>
                <th>Data do Empréstimo</th>
                <th>Dias Emprestado</th>
                <th>Data de Vencimento</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $emailUsuarioLogado = $_SESSION["txtLOGIN"];
            $query = isset($_GET['search']) ? " AND livros.TITULO LIKE '%" . $_GET['search'] . "%'" : "";
            $sql = "SELECT emprestimo.ID, emprestimo.STATUS_LIVRO, usuarios.NOME, emprestimo.DATA_EMPRESTADO, emprestimo.TEMPO_EMPRESTIMO, livros.TITULO
                    FROM emprestimo
                    INNER JOIN livros ON livros.ISBN = emprestimo.LIVRO_ISBN
                    INNER JOIN usuarios ON usuarios.CPF = emprestimo.CPF_PESSOA
                    WHERE usuarios.EMAIL = '$emailUsuarioLogado' $query
                    GROUP BY ID
                    ORDER BY emprestimo.DATA_EMPRESTADO DESC";
            $select = $conexao->query($sql);
            $resultado = $select->fetchAll();

            foreach ($resultado as $linha) {
                $dataEmprestimo = new DateTime($linha["DATA_EMPRESTADO"]);
                $dataVencimento = (clone $dataEmprestimo)->modify('+' . $linha["TEMPO_EMPRESTIMO"] . ' days');
                $hoje = new DateTime();

                echo "<tr>";
                // Adicione o atributo data-label para cada td conforme o exemplo abaixo
                echo "<td data-label='Título'>" . htmlspecialchars($linha['TITULO']) . "</td>";
                echo "<td data-label='Emprestado para'>" . htmlspecialchars($linha['NOME']) . "</td>";
                echo "<td data-label='Data do Empréstimo'>" . $dataEmprestimo->format('d/m/Y') . "</td>";
                echo "<td data-label='Dias Emprestado'>" . htmlspecialchars($linha['TEMPO_EMPRESTIMO']) . " dias</td>";
                echo "<td data-label='Data de Vencimento'>" . $dataVencimento->format('d/m/Y') . "</td>";
                echo "<td data-label='Status'>";
                if ($linha["STATUS_LIVRO"] == "NÃO DEVOLVIDO" or $linha["STATUS_LIVRO"] == "A DEVOLVER") {
                    if ($hoje > $dataVencimento) {
                        echo "<span class='badge badge-danger'>Atrasado</span>";
                    } else {
                        echo "<span class='badge badge-success'>No Prazo</span>";
                    }
                } else {
                    echo "<span class='badge badge-secondary'>Devolvido</span>";
                }
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
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
