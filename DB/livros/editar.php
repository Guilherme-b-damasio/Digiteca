<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../seguranca/seguranca.php');
session_start();
if (administrador_logado() == false) {
    header("location: /Digiteca/index.php");
    exit;
}

require_once("../../conexao/conexao.php");

// Coleta os dados do formulário
$tituloDoLivro = $_POST["tituloDoLivro"];
$autorPrincipal = $_POST["autorPrincipal"];
$descricaoDoLivro = $_POST["descricaoDoLivro"];
$generoPrincipal = $_POST["generoPrincipal"];
$nomeDaEditora = $_POST["nomeDaEditora"];
$anoDePublicacao = $_POST["anoDePublicacao"];
$codigoISBN = $_POST["codigoISBN"];
$unidadesDisponiveis = $_POST["unidadesDisponiveis"];
$ISBN = $_POST["ISBN"];

try {
    // Inicializa a variável para a imagem
    $imagemDoLivro = null;

    // Verifica se um arquivo foi enviado
    if (isset($_FILES['imagemDoLivro']) && $_FILES['imagemDoLivro']['error'] == 0) {
        // Lê o conteúdo do arquivo
        $imagemDoLivro = file_get_contents($_FILES['imagemDoLivro']['tmp_name']);
    }


    if ($imagemDoLivro != "") {
    // Preparar o comando SQL com placeholders
    $sql = "UPDATE livros SET
        TITULO = :tituloDoLivro,
        AUTOR = :autorPrincipal,
        DESCRICAO = :descricaoDoLivro,
        GENERO = :generoPrincipal,
        EDITORA = :nomeDaEditora,
        ANO_PUBLICACAO = :anoDePublicacao,
        ISBN = :codigoISBN,
        UNIDADES_DISPONIVEIS = :unidadesDisponiveis,
        IMAGEM = :imagemDoLivro
    WHERE ISBN = :ISBN";
    } else {
        $sql = "UPDATE livros SET
            TITULO = :tituloDoLivro,
            AUTOR = :autorPrincipal,
            DESCRICAO = :descricaoDoLivro,
            GENERO = :generoPrincipal,
            EDITORA = :nomeDaEditora,
            ANO_PUBLICACAO = :anoDePublicacao,
            ISBN = :codigoISBN,
            UNIDADES_DISPONIVEIS = :unidadesDisponiveis
        WHERE ISBN = :ISBN";
    }
    $comando = $conexao->prepare($sql);

    // Vincular os valores aos placeholders
    $comando->bindParam(':tituloDoLivro', $tituloDoLivro);
    $comando->bindParam(':autorPrincipal', $autorPrincipal);
    $comando->bindParam(':descricaoDoLivro', $descricaoDoLivro);
    $comando->bindParam(':generoPrincipal', $generoPrincipal);
    $comando->bindParam(':nomeDaEditora', $nomeDaEditora);
    $comando->bindParam(':anoDePublicacao', $anoDePublicacao);
    $comando->bindParam(':codigoISBN', $codigoISBN);
    $comando->bindParam(':unidadesDisponiveis', $unidadesDisponiveis);
    
    if ($imagemDoLivro != "") {
        $comando->bindParam(':imagemDoLivro', $imagemDoLivro, PDO::PARAM_LOB);
    }
    
    $comando->bindParam(':ISBN', $ISBN);

    // Executar o comando
    $comando->execute();

    if ($comando->rowCount() > 0) {
        header('location: /views/livros/visualizar.php');
    } else {
        $mensagem_erro = "Nenhuma informação atualizada!";
        header("location: /views/livros/editar.php?ISBN=$ISBN&mensagem_erro=$mensagem_erro");
    }

} catch (PDOException $e) {
    $mensagem_erro = $e->getMessage();
    header("location: /views/livros/editar.php?ISBN=$ISBN&mensagem_erro=$mensagem_erro");
}

$conexao = null;
?>
