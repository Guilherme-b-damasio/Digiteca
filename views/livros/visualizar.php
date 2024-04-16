<?php
include('../../seguranca/seguranca.php');
session_start();
if (!administrador_logado()) {
    header("location: /index.php");
    exit;
}

include('../../layout/header.html');
include('../../layout/navbar.php');
require_once('../../conexao/conexao.php');

$searchTerm = isset($_GET['search']) ? "%" . trim($_GET['search']) . "%" : null;
$itemsPerPage = 10; // Número de itens por página
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página atual
$offset = ($currentPage - 1) * $itemsPerPage; // Calcula o offset

// Execute a consulta SQL diretamente sem verificar o cache
$countSql = "SELECT COUNT(*) FROM livros" . ($searchTerm ? " WHERE TITULO LIKE ? OR ISBN LIKE ? OR EDITORA LIKE ?" : "") . " ORDER BY CAST(ISBN AS UNSIGNED)";

$countStmt = $conexao->prepare($countSql);
if ($searchTerm) {
    $countStmt->bindParam(1, $searchTerm);
    $countStmt->bindParam(2, $searchTerm);
    $countStmt->bindParam(3, $searchTerm);
    $countStmt->execute();
} else {
    $countStmt->execute();
}
$totalRows = $countStmt->fetchColumn();
$totalPages = ceil($totalRows / $itemsPerPage);

$sql = "SELECT * FROM livros" . ($searchTerm ? " WHERE TITULO LIKE ? OR ISBN LIKE ? OR EDITORA LIKE ?" : "") . " ORDER BY CAST(ISBN AS UNSIGNED) LIMIT $itemsPerPage OFFSET $offset";

$stmt = $conexao->prepare($sql);
if ($searchTerm) {
    $stmt->bindParam(1, $searchTerm);
    $stmt->bindParam(2, $searchTerm);
    $stmt->bindParam(3, $searchTerm);
    $stmt->execute();
} else {
    $stmt->execute();
}

$resultado = $stmt->fetchAll();
?>

<!-- Estilos para botões de paginação e formulário de pesquisa -->
<style>
.search-container form {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.search-container input[type="text"], .search-container button {
    padding: 5px 10px;
    font-size: 16px;
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

<div class="container">
    <!-- Cabeçalho da Página -->
    <div class="card text-white mb-3" style="background-color: #FF7B00; margin-top: 20px;">
        <div class="card-body text-center">
            <h5 class="card-title">Visualizar Livros Cadastrados</h5>
        </div>
    </div>

    <!-- Formulário de Pesquisa -->
    <div class="search-container mb-4">
        <form action="" method="get" class="d-flex justify-content-center">
            <input type="text" placeholder="Pesquisar livros..." name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="form-control w-50 mr-2">
            <button type="submit" class="btn btn-primary">Pesquisar</button>
        </form>
    </div>

    <!-- Tabela de Livros -->
    <?php if ($resultado): ?>
    <div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Título</th>
                    <th scope="col">ISBN</th>
                    <th scope="col">Editora</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultado as $linha): ?>
                <tr>
                    <td><?php echo htmlspecialchars($linha["TITULO"]); ?></td>
                    <td><?php echo htmlspecialchars($linha["ISBN"]); ?></td>
                    <td><?php echo htmlspecialchars($linha["EDITORA"]); ?></td>
                    <td>
                        <a href="/views/livros/editar.php?ISBN=<?php echo $linha["ISBN"]; ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="/views/livros/excluir.php?ISBN=<?php echo $linha["ISBN"]; ?>" class="btn btn-sm btn-danger">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p>Nenhum livro encontrado.</p>
    <?php endif; ?>

    <!-- Paginação -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $currentPage + 2);
            if ($currentPage > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo htmlentities($searchTerm); ?>">Anterior</a></li>
            <?php endif;
            for ($page = $startPage; $page <= $endPage; $page++): ?>
                <li class="page-item <?php echo $page == $currentPage ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $page; ?>&search=<?php echo htmlentities($searchTerm); ?>"><?php echo $page; ?></a></li>
            <?php endfor;
            if ($currentPage < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo htmlentities($searchTerm); ?>">Próximo</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<?php include('../../layout/footer.html'); ?>
