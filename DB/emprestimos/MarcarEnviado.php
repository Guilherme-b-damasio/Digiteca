<?php
// Inicia a sessão antes de qualquer output
session_start();

// Impede que usuários acessem a página se não estiverem logados
require_once('../../seguranca/seguranca.php'); // Usando 'require_once' para garantir que o script seja carregado.

// Verifica se o usuário está logado como administrador
if (administrador_logado() === false) {
    header("Location: /index.php");
    exit;
}

// Inclui o arquivo de conexão
require_once("../../conexao/conexao.php");

// Validação do ID do empréstimo
$testeIDEMPRESTIMO = campo_e_valido("txtIDEMPRESTIMO", "ID");
if ($testeIDEMPRESTIMO[0] == false) { echo "erro no id", exit; }
$txtIDEMPRESTIMO = $testeIDEMPRESTIMO[1];


$txtIDEMPRESTIMO = $testeIDEMPRESTIMO[1];

try {
    $comando = $conexao->prepare(
        "UPDATE emprestimo
        SET STATUS_ENVIO = 'ENVIADO'
        WHERE ID = :txtIDEMPRESTIMO;"
    );

    // Executa a atualização com o parâmetro devidamente vinculado
    $comando->execute(array(':txtIDEMPRESTIMO' => $txtIDEMPRESTIMO));

    // Verifica se a atualização foi bem-sucedida
    if ($comando->rowCount() > 0) {
        header('Location: /views/emprestimos/visualizar.php');
        exit;
    } else {
        echo "Erro ao atualizar os dados";
    }
} catch (PDOException $e) {
    echo "Erro ao gravar informação no banco de dados. \n\n" . $e->getMessage();
}

// Fecha a conexão com o banco de dados
$conexao = null;
?>
