<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

require_once("../../conexao/conexao.php");

try {
    $CPF = filter_input(INPUT_POST, "CPF", FILTER_SANITIZE_STRING);
    $comando = $conexao->prepare("DELETE FROM usuarios WHERE CPF = :CPF");
    $comando->bindParam(":CPF", $CPF);
    $comando->execute();

    if($comando->rowCount() > 0) {
        header("location: /views/usuarios/visualizar.php");
    } else {
        echo 'Sem ação';
        exit;
    }

} catch (PDOException $e) {
    $mensagem_erro = $e->getMessage();
    $url = "location: /views/usuarios/excluir.php?CPF=$CPF&mensagem_erro=$mensagem_erro";
    header($url);
}

$conexao = null;
