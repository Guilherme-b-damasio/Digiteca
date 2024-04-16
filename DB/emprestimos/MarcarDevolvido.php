<?php
include('../../seguranca/seguranca.php');
session_start();

// Verifica se o usuário é um administrador e está logado
if(administrador_logado() == false) {
    header("location: /Digiteca/index.php");
    exit;
}

require_once("../../conexao/conexao.php");

$testeIDEMPRESTIMO = campo_e_valido("txtIDEMPRESTIMO", "ID");
if ($testeIDEMPRESTIMO[0] == false) { exit; }
$txtIDEMPRESTIMO = $testeIDEMPRESTIMO[1];

try {
    // Inicia uma transação
    $conexao->beginTransaction();

    // Atualiza o status do empréstimo para 'DEVOLVIDO'
    $comando = $conexao->prepare(
        "UPDATE emprestimo
         SET STATUS_LIVRO = 'DEVOLVIDO'
         WHERE ID = :txtIDEMPRESTIMO;"
    );

    $comando->execute(array(':txtIDEMPRESTIMO' => $txtIDEMPRESTIMO));

    if($comando->rowCount() > 0) {
        // Obtém o ISBN do livro emprestado
        $consulta = $conexao->prepare(
            "SELECT LIVRO_ISBN FROM emprestimo WHERE ID = :txtIDEMPRESTIMO;"
        );
        $consulta->execute(array(':txtIDEMPRESTIMO' => $txtIDEMPRESTIMO));
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        $livroISBN = $resultado['LIVRO_ISBN'];

        // Incrementa a unidade disponível do livro
        $atualizaUnidades = $conexao->prepare(
            "UPDATE livros
             SET UNIDADES_DISPONIVEIS = UNIDADES_DISPONIVEIS + 1
             WHERE ISBN = :livroISBN;"
        );

        $atualizaUnidades->execute(array(':livroISBN' => $livroISBN));

        // Confirma a transação
        $conexao->commit();

        header('location:/views/emprestimos/visualizar.php');
    } else {
        echo "Erro ao gravar os dados";
    }

} catch (PDOException $e) {
    // Desfaz a transação em caso de erro
    $conexao->rollBack();
    echo "Erro ao gravar informação no banco de dados. \n\n" . $e->getMessage();
}

$conexao = null;
?>
