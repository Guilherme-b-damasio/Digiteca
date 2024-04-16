<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../seguranca/seguranca.php');
session_start();
if (administrador_logado() == false) {
    header("location: /index.php"); 
    exit;
}

require_once("../../conexao/conexao.php");

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitiza e armazena as entradas do usuário
    $tituloDoLivro = filter_input(INPUT_POST, "tituloDoLivro", FILTER_SANITIZE_STRING);
    $autorPrincipal = filter_input(INPUT_POST, "autorPrincipal", FILTER_SANITIZE_STRING);
    $descricaoDoLivro = filter_input(INPUT_POST, "descricaoDoLivro", FILTER_SANITIZE_STRING);
    $generoPrincipal = filter_input(INPUT_POST, "generoPrincipal", FILTER_SANITIZE_STRING);
    $nomeDaEditora = filter_input(INPUT_POST, "nomeDaEditora", FILTER_SANITIZE_STRING);
    $anoDePublicacao = filter_input(INPUT_POST, "anoDePublicacao", FILTER_SANITIZE_STRING);
    $codigoISBN = filter_input(INPUT_POST, "codigoISBN", FILTER_SANITIZE_STRING);
    $unidadesDisponiveis = filter_input(INPUT_POST, "unidadesDisponiveis", FILTER_SANITIZE_STRING);

    // Processamento da imagem
    $imagem = null;
    $tipoImagem = null;
    if (isset($_FILES["imagemDoLivro"]) && $_FILES["imagemDoLivro"]["error"] == 0) {
        $imagem = file_get_contents($_FILES["imagemDoLivro"]["tmp_name"]);
        $tipoImagem = $_FILES["imagemDoLivro"]["type"];
    } else {
        echo "Erro no upload da imagem";
        exit;
    }

    // Preparando a consulta SQL
    $sql = "INSERT INTO livros (
        TITULO, AUTOR, DESCRICAO, GENERO, EDITORA, ANO_PUBLICACAO, ISBN, UNIDADES_DISPONIVEIS, IMAGEM, TIPO_IMAGEM
    ) VALUES (
        :tituloDoLivro, :autorPrincipal, :descricaoDoLivro, :generoPrincipal, :nomeDaEditora, :anoDePublicacao, :codigoISBN, :unidadesDisponiveis, :imagem, :tipoImagem
    )";

    try {
        $comando = $conexao->prepare($sql);
        $comando->execute(array(
            ':tituloDoLivro' => $tituloDoLivro,
            ':autorPrincipal' => $autorPrincipal,
            ':descricaoDoLivro' => $descricaoDoLivro,
            ':generoPrincipal' => $generoPrincipal,
            ':nomeDaEditora' => $nomeDaEditora,
            ':anoDePublicacao' => $anoDePublicacao,
            ':codigoISBN' => $codigoISBN,
            ':unidadesDisponiveis' => $unidadesDisponiveis,
            ':imagem' => $imagem,
            ':tipoImagem' => $tipoImagem
        ));

        if ($comando->rowCount() > 0) {
            header('location: /views/livros/visualizar.php');
        } else {
            echo "Ops, Erro ao gravar os dados";
        }
    } catch (PDOException $e) {
        echo "Erro ao gravar a informação. " . $e->getMessage();
    }

    $conexao = null;
}
?>
