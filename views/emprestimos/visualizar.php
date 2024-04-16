<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../seguranca/seguranca.php');
session_start();
if (!administrador_logado()) {
    header("location: /index.php");
    exit;
}

include('../../layout/header.html');
include('../../layout/navbar.php');
require_once("../../conexao/conexao.php");
require_once("../../recursos.php");
?>

<link rel="stylesheet" href="../../assets/css/navbar.css">

<div class="container mx-auto mt-4">
    <form id="search-form" name="pesquisa" action="/views/emprestimos/visualizar.php" method="get">
        <div class="input-group">
            <div class="input-group-prepend col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Digite sua pesquisa" aria-describedby="basic-addon2">
            </div>
            <select name="tipobusca" class="">
                <option value="STATUS">Status</option>
                <option value="Nome do livro">Nome do livro</option>
                <option value="Nome da pessoa">Nome da pessoa</option>
                <option value="CPF">CPF</option>
                <option value="ISBN">ISBN</option>
            </select>
            <input name="busca-date" type="date" value="DATA"></input>
            <button type="submit" class="btn btn-info">Pesquisar</button>
        </div>
    </form>

    <?php
    if (isset($_GET["mensagem_erro"])) {
        $mensagem_erro = $_GET["mensagem_erro"];
        echo "<div class=\"alert alert-danger\" role=\"alert\">Erro: $mensagem_erro</div>";
    }

   

    // Construção dinâmica da cláusula WHERE para pesquisa
    $searchTerm = $_GET['search'] ?? '';
    $tipoBusca = $_GET['tipobusca'] ?? '';
    $buscaDate = $_GET['busca-date'] ?? '';
    $params = []; // Array para armazenar parâmetros de consulta

    // Lógica de filtragem baseada nos critérios de pesquisa
    $filtroBusca = '1=1'; // Base da cláusula WHERE que sempre é verdadeira

    // Adapte a lógica abaixo conforme necessário
    if (!empty($searchTerm)) {
        switch ($tipoBusca) {
            case 'Nome do livro':
                $filtroBusca .= " AND livros.TITULO LIKE :searchTerm";
                $params[':searchTerm'] = "%$searchTerm%";
                break;
            // Adicione mais casos conforme necessário
        }
    }
    if (!empty($buscaDate)) {
        $filtroBusca .= " AND emprestimo.DATA_EMPRESTADO = :buscaDate";
        $params[':buscaDate'] = $buscaDate;
    }

    // Contagem total de registros para calcular o número de páginas
    $countSql = "SELECT COUNT(*) FROM emprestimo INNER JOIN livros ON livros.ISBN = emprestimo.LIVRO_ISBN INNER JOIN usuarios ON usuarios.CPF = emprestimo.CPF_PESSOA WHERE $filtroBusca";
    $stmt = $conexao->prepare($countSql);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();


    $totalRows = $stmt->fetchColumn();
    $itemsPerPage = 10;
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($currentPage - 1) * $itemsPerPage;

    // Suponha que você já tenha a variável $totalRows definida anteriormente no seu código
    $totalPages = ceil($totalRows / $itemsPerPage);

    $maxPageLinks = 5;

    // Calcula o início e o fim da paginação
    $startPage = max(1, $currentPage - floor($maxPageLinks / 2));
    $endPage = min($totalPages, $startPage + $maxPageLinks - 1);

    // Ajusta o início e o fim para garantir que sempre mostre $maxPageLinks quando possível
    if ($endPage - $startPage < $maxPageLinks - 1) {
        $startPage = max(1, $endPage - $maxPageLinks + 1);
    }


    // Consulta para buscar os dados com paginação
    $sql = "SELECT emprestimo.ID, emprestimo.STATUS_LIVRO, usuarios.NOME, emprestimo.DATA_EMPRESTADO, emprestimo.TEMPO_EMPRESTIMO, livros.TITULO, emprestimo.DATA_VENCIMENTO, emprestimo.STATUS_ENVIO FROM emprestimo INNER JOIN livros ON livros.ISBN = emprestimo.LIVRO_ISBN INNER JOIN usuarios ON usuarios.CPF = emprestimo.CPF_PESSOA WHERE $filtroBusca ORDER BY emprestimo.DATA_VENCIMENTO LIMIT :limit OFFSET :offset";
    $stmt = $conexao->prepare($sql);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    ?>

    <table class="table-custom">

    <style>

    .input-group {
        display: flex; /* Ativa o Flexbox */
        align-items: center; /* Alinha os itens verticalmente ao centro */
        justify-content: start; /* Alinha os itens à esquerda */
        gap: 10px; /* Espaçamento entre os elementos */
    }

    .input-group > * {
        flex: 1; /* Faz com que cada elemento ocupe o espaço disponível de forma igual */
    }

    .busca-date{
        font-size: 2px; /* Tamanho da fonte */
        color: #333; /* Cor do texto */
        background-color: #fff; /* Cor de fundo */
        margin: 5px; /* Espaçamento externo */
        width: 10%; /* Largura */
        height: 40px; /* Altura */
        cursor: pointer; /* Tipo do cursor */

    }

    .btn-info{
        flex:none;
        background-color: #ff7b00; /* Cor de fundo vermelha */
        color: rgb(255, 255, 255); /* Texto branco */
        border: none; /* Sem borda */
        width: 10%;
        height: 42px;
        padding: 10px 10px; /* Espaçamento interno */
        margin-top: 0.4%; /* Margem superior para distância dos elementos acima */
        border-radius: 5px; /* Bordas arredondadas */
        font-size: 70%; /* Tamanho da fonte */
        transition: background-color 0.3s ease; /* Transição suave para hover */

    }

    .btn-table{
        flex:none;
        background-color: #ff7b00; /* Cor de fundo vermelha */
        color: rgb(255, 255, 255); /* Texto branco */
        border: none; /* Sem borda */
        width: 100%;
        height: 50%;
        padding: 10px 10px; /* Espaçamento interno */
        margin-top: 0.4%; /* Margem superior para distância dos elementos acima */
        margin-bottom: 10%;
        border-radius: 5px; /* Bordas arredondadas */
        font-size: 70%; /* Tamanho da fonte */
        transition: background-color 0.3s ease; /* Transição suave para hover */
    }


    .btn-table:hover{
        background-color: #3B4A9B; /* Cor de fundo para o estado hover */
        color: white;
    }
    
    .btn-info:hover{
        background-color: #3B4A9B; /* Cor de fundo para o estado hover */
        color: white;
    }

    .table-custom {
        border-collapse: collapse;
        width: 100%;
        margin-top: 1%;
        color: black;
    }

    .table-custom thead {
        background-color: #ff7b00; /* Cor de fundo do cabeçalho */
        color: white; /* Cor do texto do cabeçalho */
    }

    .table-custom th, .table-custom td {
        /*border-radius: 2%;*/
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid black; /* Cor da linha da tabela */
    }

    .table-custom tbody tr:nth-child(odd) {
        background-color: #E9E9E9; /* Linhas ímpares serão brancas */
    }

    .table-custom tbody tr:nth-child(even) {
        background-color: #F5F5F5; /* Linhas pares serão cinza claro */
    }

    .table-custom tbody tr:nth-child(odd):hover,
    .table-custom tbody tr:nth-child(even):hover {
        background-color: gray; /* Cor de fundo ao passar o mouse sobre qualquer linha */
        color: black;
    }

    select[name="tipobusca"] {
        font-size: 13px; /* Tamanho da fonte */
        color: #333; /* Cor do texto */
        background-color: #fff; /* Cor de fundo */
        border: 1px solid #ccc; /* Estilo da borda */
        padding: 5px 10px; /* Espaçamento interno */
        margin: 5px; /* Espaçamento externo */
        width: 170px; /* Largura */
        height: 40px; /* Altura */
        cursor: pointer; /* Tipo do cursor */
    }

    select[name="tipobusca"]:hover {
        background-color: #f2f2f2; /* Cor de fundo ao passar o mouse */
    }

    select[name="tipobusca"]:focus {
        border-color: #ff7b00; /* Cor da borda ao focar */
        outline: none; /* Remove o contorno padrão */
    }
</style>

        <thead>
            <tr>
                <th>Título</th>
                <th>Emprestado para</th>
                <th>Data do Empréstimo</th>
                <th>Dias Emprestado</th>
                <th>Data de Vencimento</th>      
                <th>Status</th>
                <th>Enviado?</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Lógica para buscar e exibir dados dos empréstimos
            $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
            $tipoBusca = isset($_GET['tipobusca']) ? $_GET['tipobusca'] : '';
            $statusLivro = null;
            $searchDate = isset($_GET['busca-date']) ? $_GET['busca-date'] : '';

            // Define o filtro de busca baseado na escolha do usuário
            switch ($tipoBusca) {
                case 'Nome do livro':
                    $filtroBusca = "livros.TITULO LIKE '%$searchTerm%'";
                 

                    if($searchDate != "") {
                        $filtroBusca .= " AND emprestimo.DATA_EMPRESTADO LIKE '%$searchDate%'";
                    }
                    break;

                case 'Nome da pessoa':
                    $filtroBusca = "usuarios.NOME LIKE '%$searchTerm%'";

                    if($searchDate != "") {
                        $filtroBusca .= " AND emprestimo.DATA_EMPRESTADO LIKE '%$searchDate%'";
                    }
                    break;


                case 'CPF':
                    $filtroBusca = "usuarios.CPF LIKE '%$searchTerm%'";

                    if($searchDate != "") {
                        $filtroBusca .= " AND emprestimo.DATA_EMPRESTADO LIKE '%$searchDate%'";
                    }
                    break;

                case 'ISBN':
                    $filtroBusca = "livros.ISBN LIKE '%$searchTerm%'";
                    if($searchDate != "") {
                        $filtroBusca .= " AND emprestimo.DATA_EMPRESTADO LIKE '%$searchDate%'";
                    }
                    break;

                case 'STATUS':
                     if (strcasecmp($searchTerm, "Atrasado") == 0) {
                        // Filtrar apenas empréstimos atrasados
                        $filtroBusca = "emprestimo.STATUS_LIVRO IN ('NÃO DEVOLVIDO', 'A DEVOLVER') AND ADDDATE(emprestimo.DATA_EMPRESTADO, INTERVAL emprestimo.TEMPO_EMPRESTIMO DAY) < CURDATE()";
                        if($searchDate != "") {
                            $filtroBusca = "emprestimo.STATUS_LIVRO IN ('NÃO DEVOLVIDO', 'A DEVOLVER') AND ADDDATE(emprestimo.DATA_EMPRESTADO, INTERVAL emprestimo.TEMPO_EMPRESTIMO DAY) < CURDATE()";
                        }


                    } elseif (strcasecmp($searchTerm, "No Prazo") == 0) {
                          // Filtrar empréstimos que estão no prazo
                           $filtroBusca = "emprestimo.STATUS_LIVRO IN ('NÃO DEVOLVIDO', 'A DEVOLVER') AND ADDDATE(emprestimo.DATA_EMPRESTADO, INTERVAL emprestimo.TEMPO_EMPRESTIMO DAY) >= CURDATE()";
                        } elseif (strcasecmp($searchTerm, "Devolvido") == 0) {
                            // Filtrar empréstimos devolvidos
                            $filtroBusca = "emprestimo.STATUS_LIVRO = 'DEVOLVIDO'";
                        } else {
                            $filtroBusca = "1";
                        }
                        
                        if($searchDate != "") {
                            $filtroBusca .= " AND emprestimo.DATA_EMPRESTADO LIKE '%$searchDate%'";
                        }
            
                        break;


                default:
                        $filtroBusca = "5";
                  break;
            }

            
           
            $sql = "SELECT emprestimo.ID, emprestimo.STATUS_LIVRO, usuarios.NOME, emprestimo.DATA_EMPRESTADO, emprestimo.TEMPO_EMPRESTIMO, livros.TITULO, emprestimo.DATA_VENCIMENTO, emprestimo.STATUS_ENVIO
                    FROM emprestimo
                    INNER JOIN livros ON livros.ISBN = emprestimo.LIVRO_ISBN
                    INNER JOIN usuarios ON usuarios.CPF = emprestimo.CPF_PESSOA
                    WHERE $filtroBusca
                    ORDER BY emprestimo.id desc
                    LIMIT :limit OFFSET :offset";

            $stmt = $conexao->prepare($sql);
            $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetchAll();

            foreach ($resultado as $linha) {
                $dataEmprestimo = new DateTime($linha["DATA_EMPRESTADO"]);
                $dataVencimento = (clone $dataEmprestimo)->modify('+' . $linha["TEMPO_EMPRESTIMO"] . ' days');

                echo "<tr>";
                echo "<td>" . htmlspecialchars($linha['TITULO']) . "</td>";
                echo "<td>" . htmlspecialchars($linha['NOME']) . "</td>";
                echo "<td>" . $dataEmprestimo->format('d/m/Y') . "</td>";
                echo "<td>" . htmlspecialchars($linha['TEMPO_EMPRESTIMO']) . " dias</td>";
                echo "<td>" . $dataVencimento->format('d/m/Y') . "</td>";

                // Verifica o status do livro e a data de vencimento
                if ($linha["STATUS_LIVRO"] == "NÃO DEVOLVIDO" or $linha["STATUS_LIVRO"] == "A DEVOLVER") {
                    if ($dataVencimento < new DateTime()) {
                        // Livro atrasado
                        echo "<td><span class='badge badge-danger'>Atrasado</span></td>";
                    } else {
                        // Livro no prazo
                        echo "<td><span class='badge badge-success'>No Prazo</span></td>";
                    }
                } else {
                    // Livro devolvido
                    echo "<td><span class='badge badge-secondary'>Devolvido</span></td>";
                }

                if ($linha["STATUS_ENVIO"] == "ENVIAR"){
                    echo "<td><span class='badge badge-danger'>Não enviado</span></td>";
                }
                else{
                    echo "<td><span class='badge badge-success'>Enviado</span></td>";
                }

                echo "</td>";
                echo "<td>";
                if ($linha["STATUS_LIVRO"] == "NÃO DEVOLVIDO" or $linha["STATUS_LIVRO"] == "A DEVOLVER") {
                    echo "<form action=\"/DB/emprestimos/MarcarDevolvido.php\" method=\"post\" onsubmit=\"return disableButton(this)\">";
                    echo "<input type=\"hidden\" name=\"txtIDEMPRESTIMO\" value=\"{$linha["ID"]}\">";
                    echo "<button type=\"submit\" class=\"btn-table\">Devolver</button>";
                    echo "</form>";

                    echo "<form action=\"/DB/emprestimos/RenovarBtn.php\" method=\"post\" onsubmit=\"return disableButton(this)\">";
                    echo "<input type=\"hidden\" name=\"txtIDEMPRESTIMO\" value=\"{$linha["ID"]}\">";
                    echo "<input type=\"hidden\" name=\"txtDATAEMPRESTADO\" value=\"{$linha["DATA_EMPRESTADO"]}\">";
                    echo "<button type=\"submit\" class=\"btn-table\">Renovar</button>";
                    echo "</form>";

                }

                if($linha["STATUS_ENVIO"] == "ENVIAR"){
                    echo "<form action=\"/DB/emprestimos/MarcarEnviado.php\" method=\"post\" onsubmit=\"return disableButton(this)\">";
                    echo "<input type=\"hidden\" name=\"txtIDEMPRESTIMO\" value=\"{$linha["ID"]}\">";
                    echo "<button type=\"submit\" class=\"btn-table\">Enviar</button>";
                    echo "</form>";
                }
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Paginação -->
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $currentPage - 1; ?>&search=<?= $searchTerm; ?>&tipobusca=<?= $tipoBusca; ?>&busca-date=<?= $buscaDate; ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($page = $startPage; $page <= $endPage; $page++): ?>
                <li class="page-item <?= $page == $currentPage ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?= $page; ?>&search=<?= $searchTerm; ?>&tipobusca=<?= $tipoBusca; ?>&busca-date=<?= $buscaDate; ?>"><?= $page; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $currentPage + 1; ?>&search=<?= $searchTerm; ?>&tipobusca=<?= $tipoBusca; ?>&busca-date=<?= $buscaDate; ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<script>
    function disableButton(form) {
        form.querySelector('button[type="submit"]').disabled = true;
        return true;
    }
</script>

<?php include('../../layout/footer.html'); ?>
