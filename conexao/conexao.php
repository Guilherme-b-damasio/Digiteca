<?php
$host = "localhost";
$user = "root";
$pass = "";
$banco = "";

try
{
    // Adiciona a opção de codificação UTF-8 à conexão PDO
    $conexao = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $user, $pass, [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ]);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo "Erro durante a conexão com o banco de dados.\n\n" . $e->getMessage();
}
