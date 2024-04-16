<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /Digiteca/index.php"); exit;}

require_once("../../conexao/conexao.php");

$teste_CPF = campo_e_valido("txtCPF", "cpf");
$teste_NOME = campo_e_valido("txtNOME", "nome");
$teste_SOBRENOME = campo_e_valido("txtSOBRENOME", "sobrenome");
$teste_EMAIL = campo_e_valido("txtEMAIL", "E-mail");
$teste_TELEFONE = campo_e_valido("txtTELEFONE", "telefone");
$teste_DATA_NASCIMENTO = campo_e_valido("txtDATA_NASCIMENTO", "Data de Nascimento");
$teste_SENHA = campo_e_valido("txtSENHA", "password");

if ($teste_CPF[0] == false) { exit; }
if ($teste_NOME[0] == false) { exit; }
if ($teste_SOBRENOME[0] == false) { exit; }
if ($teste_EMAIL[0] == false) { exit; }
if ($teste_TELEFONE[0] == false) { exit; }
if ($teste_DATA_NASCIMENTO[0] == false) { exit; }
if ($teste_SENHA[0] == false) {exit; }

$txtNOME = $teste_NOME[1];
$txtSOBRENOME = $teste_SOBRENOME[1];
$txtCPF = $teste_CPF[1];
$txtEMAIL = $teste_EMAIL[1];
$txtTELEFONE = $teste_TELEFONE[1];
$txtDATA_NASCIMENTO = $teste_DATA_NASCIMENTO[1];
$txtSENHA = $teste_SENHA[1];

try {
    $comando = $conexao->prepare(
        "INSERT INTO usuarios
        (CPF, NOME, EMAIL, TELEFONE, DATA_NASCIMENTO, senha)
        VALUES (:txtCPF, :txtNOME, :txtEMAIL, :txtTELEFONE, :txtDATA_NASCIMENTO, :txtSENHA)"
    );

    $comando->execute(array(
        ':txtCPF' => $txtCPF,
        ':txtNOME' => $txtNOME,
        ':txtSOBRENOME' => $txtSOBRENOME,
        ':txtEMAIL' => $txtEMAIL,
        ':txtTELEFONE' => $txtTELEFONE,
        ':txtDATA_NASCIMENTO' => $txtDATA_NASCIMENTO,
        ':txtSENHA' => $txtSENHA
    ));

    if($comando->rowCount() > 0)
    {
        $_SESSION["txtLOGIN"] = true;
        $_SESSION["txtSENHA"] = true;
        header('location:/views/usuarios/visualizar.php');
    }
    else
    {
        echo "Erro ao gravar os dados";
    }

} catch (PDOException $e) {
    echo("Erro ao gravar informação no banco de dados. \n\n".$e->getMessage());
}

$conexao = null;
