<?php
include('../../seguranca/seguranca.php');
session_start();

// Verifica se o usuário é um administrador e está logado
if(administrador_logado() == false) {
    header("location: /index.php");
    exit;
}

require_once("../../conexao/conexao.php");

// Validação dos campos do formulário
//$teste_LIVRO_ISBN = campo_e_valido("txtLIVRO_ISBN", "Livro"); $teste_LIVRO_ISBN[0] == false ||
$teste_DATA_EMPRESTADO = campo_e_valido("txtDATA_EMPRESTADO", "Data emprestimo");
$teste_EMPO_EMPRESTIMO = campo_e_valido("txtTEMPO_EMPRESTIMO", "Tempo do emprestimo");

if ($teste_DATA_EMPRESTADO[0] == false || $teste_EMPO_EMPRESTIMO[0] == false) {
    $mensagem_erro = ("Tempo de emprestimo não valido");
    header("location: /views/emprestimos/cadastrar_user.php?ISBN=$ISBN&mensagem_erro=$mensagem_erro");
    exit; // Interrompe a execução se houver empréstimos pendentes
}

$txtCPF_PESSOA = $_SESSION["CPF"]; // Usando o CPF da sessão

// Primeiro, verifica se o usuário já tem empréstimos não devolvidos
$consultaEmprestimos = $conexao->prepare(
    "SELECT COUNT(*) AS qtd
     FROM emprestimo
     WHERE CPF_PESSOA = :txtCPF_PESSOA
     AND STATUS_LIVRO <> 'DEVOLVIDO'"
);
$consultaEmprestimos->execute(array(':txtCPF_PESSOA' => $txtCPF_PESSOA));
$resultadoEmprestimos = $consultaEmprestimos->fetch();

if($resultadoEmprestimos['qtd'] > 0) {

    $mensagem_erro = "Você possui empréstimos pendentes que precisam ser devolvidos antes de realizar um novo empréstimo.";
    header("location: /views/emprestimos/cadastrar_user.php?ISBN=$ISBN&mensagem_erro=$mensagem_erro");
    exit; // Interrompe a execução se houver empréstimos pendentes
}

$txtLIVRO_ISBN = $_SESSION["current_ISBN"];
$txtCPF_PESSOA = $_SESSION["CPF"]; // Usando o CPF da sessão
echo $txtCPF_PESSOA;
$txtDATA_EMPRESTADO = $teste_DATA_EMPRESTADO[1];
$txtTEMPO_EMPRESTIMO = $teste_EMPO_EMPRESTIMO[1];
$txtSTATUSLIVRO = "A DEVOLVER";
$txtSTATUS_ENVIO = "ENVIAR";

// Converte a data para o formato do banco de dados
$dataFormatada = DateTime::createFromFormat('d/m/Y', $txtDATA_EMPRESTADO);
if ($dataFormatada) {
    $txtDATA_EMPRESTADO = $dataFormatada->format('Y-m-d');
} else {
    echo "Formato de data inválido.";
    exit;
}



try {
    // Inicia a transação
    $conexao->beginTransaction();

    // Insere o empréstimo
    $comando = $conexao->prepare(
        "INSERT INTO emprestimo
        (
            LIVRO_ISBN,
            CPF_PESSOA,
            DATA_EMPRESTADO,
            TEMPO_EMPRESTIMO,
            STATUS_LIVRO,
            STATUS_ENVIO
        )
        VALUES
        (
            :txtLIVRO_ISBN,
            :txtCPF_PESSOA,
            :txtDATA_EMPRESTADO,
            :txtTEMPO_EMPRESTIMO,
            :txtSTATUSLIVRO,
            :txtSTATUS_ENVIO
        )"
    );

    $comando->execute(array(
        ':txtLIVRO_ISBN' => $txtLIVRO_ISBN,
        ':txtCPF_PESSOA' => $txtCPF_PESSOA,
        ':txtDATA_EMPRESTADO' => $txtDATA_EMPRESTADO,
        ':txtTEMPO_EMPRESTIMO' => $txtTEMPO_EMPRESTIMO,
        ':txtSTATUSLIVRO' => $txtSTATUSLIVRO,
        ':txtSTATUS_ENVIO' => $txtSTATUS_ENVIO
    ));

    // Verifica se a inserção foi bem-sucedida
    if($comando->rowCount() > 0) {
        // Atualiza a quantidade de unidades disponíveis
        $update = $conexao->prepare(
            "UPDATE livros SET UNIDADES_DISPONIVEIS = UNIDADES_DISPONIVEIS - 1 
             WHERE ISBN = :txtLIVRO_ISBN AND UNIDADES_DISPONIVEIS > 0"
        );
        $update->bindParam(':txtLIVRO_ISBN', $txtLIVRO_ISBN);
        $update->execute();

        // Verifica se a atualização foi bem-sucedida
        if ($update->rowCount() > 0) {
            $conexao->commit();
            header('location:/views/emprestimos/visualizar_user.php');
        } else {
            // Se a atualização falhar, cancela a transação
            $conexao->rollBack();
            echo "Erro ao atualizar unidades disponíveis";
        }
    } else {
        echo "Erro ao gravar os dados do empréstimo";
    }
} catch (PDOException $e) {
    $conexao->rollBack();
    echo "Erro ao gravar informação no banco de dados: " . $e->getMessage();
}

$conexao = null;
?>
