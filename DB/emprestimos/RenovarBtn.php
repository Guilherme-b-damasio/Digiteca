<?php
include('../../seguranca/seguranca.php');
session_start();

// Verifica se o usuário é um administrador e está logado
if (!administrador_logado()) {
    header("location: index.php");
    exit;
}

require_once("../../conexao/conexao.php");

$testeIDEMPRESTIMO = campo_e_valido("txtIDEMPRESTIMO", "ID");
if (!$testeIDEMPRESTIMO[0]) {
    // Tratar erro de validação conforme necessário
    exit;
}

$txtIDEMPRESTIMO = $testeIDEMPRESTIMO[1]; // Obtém o ID do empréstimo validado anteriormente

try {
    $conexao->beginTransaction();

    // Verifica se o empréstimo já foi renovado
    $consulta = $conexao->prepare("SELECT RENOVADO, DATA_VENCIMENTO FROM emprestimo WHERE ID = :txtIDEMPRESTIMO");
    $consulta->execute([':txtIDEMPRESTIMO' => $txtIDEMPRESTIMO]);
    $resultado = $consulta->fetch();

    if ($resultado && $resultado['RENOVADO'] == 0) {
        // Marca o empréstimo como renovado e atualiza a data de vencimento
        $comando = $conexao->prepare(
            "UPDATE emprestimo
             SET TEMPO_EMPRESTIMO = TEMPO_EMPRESTIMO + 45, 
                 DATA_VENCIMENTO = :novaDataVencimento,
                 RENOVADO = 1
             WHERE ID = :txtIDEMPRESTIMO;"
        );
        $comando->execute([
            ':txtIDEMPRESTIMO' => $txtIDEMPRESTIMO,
            ':novaDataVencimento' => $novaDataVencimentoFormatada // Use a data calculada no PHP
        ]);

        $conexao->commit();
        // Redireciona ou informa o usuário do sucesso da operação
        header('Location: /views/emprestimos/visualizar.php'); // Exemplo de redirecionamento
    } else {
        // O empréstimo já foi renovado, não permitir nova renovação
        $conexao->rollBack();
        // Informe o usuário que o empréstimo não pode ser renovado novamente
        $mensagem_erro = "Não é possivel fazer a renovação do livro pois já foi feita uma vez";
        header("location: /views/emprestimos/visualizar.php?&mensagem_erro=$mensagem_erro");
    }
} catch (PDOException $e) {
    $conexao->rollBack();
    echo "Erro ao atualizar o empréstimo no banco de dados: " . $e->getMessage();
}

$conexao = null;
?>
