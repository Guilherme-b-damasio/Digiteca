<?php
include('../../seguranca/seguranca.php');
session_start();
if (!administrador_logado()) {
    header("location: /index.php");
    exit;
}

include('../../layout/header.html');
include('../../layout/navbar.php');
require_once("../../conexao/conexao.php");

$search = isset($_GET["search"]) ? $_GET["search"] : '';
$tipobusca = isset($_GET["tipobusca"]) ? $_GET["tipobusca"] : 'Nome';
$FILTRO_BUSCA = $tipobusca == "CPF" ? "CPF" : "NOME";

$itemsPerPage = 5;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

$countSql = "SELECT COUNT(*) FROM usuarios WHERE $FILTRO_BUSCA LIKE :search";
$countStmt = $conexao->prepare($countSql);
$countStmt->bindValue(':search', "%$search%");
$countStmt->execute();
$totalRows = $countStmt->fetchColumn();
$totalPages = ceil($totalRows / $itemsPerPage);

$sql = "SELECT NOME, DATA_NASCIMENTO, CPF, EMAIL, TELEFONE FROM usuarios WHERE $FILTRO_BUSCA LIKE :search LIMIT :limit OFFSET :offset";
$stmt = $conexao->prepare($sql);
$stmt->bindValue(':search', "%$search%");
$stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$resultado = $stmt->fetchAll();
?>

<style>
/* Adicione seus estilos CSS aqui */
.btn-secondary, .btn-danger {
    margin-right: 5px;
}

.pagination .page-link {
    color: #007bff;
}

.pagination .page-item.active .page-link {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.pagination .page-item:not(.active) .page-link:hover {
    color: #fff;
    background-color: #0056b3;
    border-color: #0056b3;
}

.pagination {
    display: flex;
    justify-content: center;
    padding: 20px 0;
}
</style>

<div class="container mx-auto mt-4">
    <div class="alert alert-info" role="alert">Visualizar Usuários</div>
    
    <!-- Formulário de pesquisa -->
    <form id="search-form" action="/views/usuarios/visualizar.php" method="get" class="input-group mb-3">
        <input type="text" name="search" class="form-control" placeholder="Digite sua pesquisa" value="<?php echo $search; ?>">
        <select name="tipobusca" class="form-select">
            <option value="Nome" <?php echo $tipobusca == 'Nome' ? 'selected' : ''; ?>>Nome</option>
            <option value="CPF" <?php echo $tipobusca == 'CPF' ? 'selected' : ''; ?>>CPF</option>
        </select>
        <button type="submit" class="btn btn-info">Pesquisar</button>
    </form>

    <?php if ($resultado): ?>
        <!-- Código para exibir os usuários -->
        <?php foreach ($resultado as $linha): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $linha["EMAIL"]; ?></h5>
                    <h6 class="card-title">CPF <?php echo $linha["CPF"]; ?></h6>
                    <h6 class="card-title">Telefone: <?php echo $linha["TELEFONE"]; ?></h6>
                    <a href="/views/usuarios/editar.php?CPF=<?php echo $linha["CPF"]; ?>" class="btn btn-secondary">Editar</a>
                    <a href="/views/usuarios/excluir.php?CPF=<?php echo $linha["CPF"]; ?>" class="btn btn-danger">Excluir</a>
                </div>
            </div>
        <?php endforeach; ?>
        
        <!-- Paginação -->
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                    <li class="page-item <?php echo $page == $currentPage ? 'active' : ''; ?>">
                        <a class="page-link" href="?search=<?php echo htmlentities($search) ?>&tipobusca=<?php echo htmlentities($tipobusca) ?>&page=<?php echo $page; ?>"><?php echo $page; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php else: ?>
        <div class="alert alert-secondary" role="alert">Nenhum usuário encontrado.</div>
    <?php endif; ?>
</div>

<?php include('../../layout/footer.html'); ?>
