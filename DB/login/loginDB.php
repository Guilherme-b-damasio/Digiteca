 <?php

session_start(); //iniciando um sessão

include('../../seguranca/seguranca.php');
require_once("../../conexao/conexao.php");

$teste_SenhaLogin = campo_e_valido("txtSenhaLogin", "Senha");
$teste_EmailLogin = campo_e_valido("txtEmailLogin", "Email");

if ($teste_SenhaLogin[0] == false) { exit; }
if ($teste_EmailLogin[0] == false) { exit; }

$txtSenhaLogin = $teste_SenhaLogin[1];
$txtEmailLogin = $teste_EmailLogin[1];

try {
    $comandoSQL = "SELECT * FROM administrador WHERE login = \"$txtEmailLogin\" AND senha = \"$txtSenhaLogin\"";
    $select = $conexao->query($comandoSQL);
    $resultado = $select->fetchAll();

   if($resultado) {
        $_SESSION["txtLOGIN"] = false;
        $_SESSION["txtSENHA"] = false;
        header('location:../../home.php');
    } else {
        $comandoSQL2 = "SELECT * FROM usuarios WHERE email = \"$txtEmailLogin\" AND senha = \"$txtSenhaLogin\"";
        $select= $conexao->query($comandoSQL2);
        $resultado =$select->fetchAll();
        if($resultado) {
            $_SESSION["txtLOGIN"] = $txtEmailLogin;
            $_SESSION["txtSENHA"] = false;
            $_SESSION["CPF"] = $resultado[0]["CPF"]; // Armazenando o CPF na sessão
            echo "CPF na sessão: " . $_SESSION["CPF"]; // Linha de depuração
            header('location:../../home_user.php');
        } else {
            $mensagem_erro = "Login ou senha inválido";
            header("location: /index.php?ISBN=$ISBN&mensagem_erro=$mensagem_erro");
        }
    }

} catch (PDOException $e) {
    $mensagem_erro = $e->getMessage();
    header("location: /index.php?ISBN=$ISBN&mensagem_erro=$mensagem_erro");
}

$conexao = null;
