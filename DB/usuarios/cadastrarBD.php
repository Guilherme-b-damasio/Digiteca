<?php
session_start();
require_once("../../conexao/conexao.php");

$mensagemErro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['etapa2'])) {
    $dados_etapa1 = $_SESSION['dados_etapa1'] ?? null;
    $txtTELEFONE = filter_input(INPUT_POST, 'txtTELEFONE', FILTER_SANITIZE_STRING);
    $txtDATA_NASCIMENTO = filter_input(INPUT_POST, 'txtDATA_NASCIMENTO', FILTER_SANITIZE_STRING);
    $txtCPF = filter_input(INPUT_POST, 'txtCPF', FILTER_SANITIZE_STRING);

    $erros = validarDados($dados_etapa1, $txtTELEFONE, $txtDATA_NASCIMENTO, $txtCPF, $conexao);

    if (empty($erros)) {
        $sucesso = inserirUsuario($dados_etapa1, $txtTELEFONE, $txtDATA_NASCIMENTO, $txtCPF, $conexao);
        if ($sucesso) {
            unset($_SESSION['dados_etapa1'], $_SESSION['etapa_cadastro']);
            header('Location: /index.php');
            exit;
        } else {
            $mensagemErro = 'Erro ao gravar os dados no banco de dados.';
        }
    } else {
        $mensagemErro = implode("<br>", $erros);
    }
}

$conexao = null;

function validarDados($dados_etapa1, $telefone, $dataNascimento, $cpf, $conexao) {
    $erros = [];

    if (!$dados_etapa1 || empty($dados_etapa1['txtNOME']) || empty($dados_etapa1['txtEMAIL']) || empty($dados_etapa1['txtSENHA']) || empty($telefone) || empty($dataNascimento) || !preg_match("/^[0-9]{11}$/", $cpf)) {
        $erros[] = "Todos os campos são obrigatórios e devem ser válidos.";
        $mensagem_erro = "Todos os campos são obrigatórios e devem ser válidos.";
        header("location: /layout/cadastro.php?&mensagem_erro=$mensagem_erro");
        exit; // Adiciona um exit para parar a execução do script após o redirecionamento
    }

    // Verifica se o CPF ou EMAIL já estão cadastrados
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE CPF = :CPF OR EMAIL = :EMAIL");
    $stmt->bindParam(':CPF', $cpf);
    $stmt->bindParam(':EMAIL', $dados_etapa1['txtEMAIL']);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $erros[] = 'CPF ou Email já cadastrado. Por favor, utilize outro CPF ou Login.';
        $mensagem_erro = "CPF ou Login já cadastrado. Por favor, utilize outro CPF ou Login.";
        header("location: /layout/cadastro.php?&mensagem_erro=$mensagem_erro");
        exit; // Adiciona um exit para parar a execução do script após o redirecionamento
    }

    return $erros;
}

function inserirUsuario($dados, $telefone, $dataNascimento, $cpf, $conexao) {
    try {
        $comando = $conexao->prepare("INSERT INTO usuarios (CPF, NOME, EMAIL, TELEFONE, DATA_NASCIMENTO, senha) VALUES (:CPF, :NOME, :EMAIL, :TELEFONE, :DATA_NASCIMENTO, :SENHA)");
        $comando->execute([
            ':CPF' => $cpf,
            ':NOME' => $dados['txtNOME'],
            ':EMAIL' => $dados['txtEMAIL'],
            ':TELEFONE' => $telefone,
            ':DATA_NASCIMENTO' => $dataNascimento,
            ':SENHA' => $dados['txtSENHA'],
        ]);
        return $comando->rowCount() > 0;
    } catch (PDOException $e) {
        // Log error ou handle exception
        return false;
    }
}
?>


