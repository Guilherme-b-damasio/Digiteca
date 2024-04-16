<?php
// Inclui scripts necessários e verifica se o usuário está logado
include('../../seguranca/seguranca.php');
session_start();
if (!administrador_logado()) {
    header("location: /index.php");
    exit;
}

include('../../layout/header.html');
include('../../layout/navbar.php');
require_once("../../conexao/conexao.php");

$CPF = filter_input(INPUT_GET, "CPF", FILTER_SANITIZE_STRING);
if (!$CPF) {
    echo "CPF é inválido!";
    exit;
}

$consulta = $conexao->query("SELECT * FROM usuarios WHERE CPF='$CPF'");
$linha = $consulta->fetch(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="/assets/css/btn.css">
<link rel="stylesheet" href="/assets/css/editarUsuario.css">

<div class="container">
    <div class="card">
        <div class="card-body">
            Editar Usuários Cadastrados
        </div>
    </div>

    <?php if (isset($_GET["mensagem_erro"])): ?>
        <div class="alert alert-danger">
            Erro ao tentar executar atualização: <?php echo $_GET["mensagem_erro"]; ?>
        </div>
    <?php endif; ?>

        <div class="card-body">
            <form action="/DB/usuarios/editar.php" method="post">
                <input type="hidden" name="txtCPFAtualizar" value="<?php echo $CPF; ?>">

                <!-- CPF -->
                <div class="form-group mb-3">
                    <label>CPF</label>
                    <input type="text" class="form-control" name="txtCPF" value="<?php echo $linha["CPF"]; ?>" required>
                </div>

                <!-- Nome -->
                <div class="form-group mb-3">
                    <label>NOME</label>
                    <input type="text" class="form-control" name="txtNOME" value="<?php echo $linha["NOME"]; ?>" required>
                </div>

                <!-- Email -->
                <div class="form-group mb-3">
                    <label>Login</label>
                    <input type="text" class="form-control" name="txtEMAIL" value="<?php echo $linha["EMAIL"]; ?>" required>
                </div>

                <!-- Telefone -->
                <div class="form-group mb-3">
                    <label>TELEFONE</label>
                    <input type="text" class="form-control" name="txtTELEFONE" value="<?php echo $linha["TELEFONE"]; ?>" required>
                </div>

                <!-- Data de Nascimento -->
                <div class="form-group mb-3">
                    <label>DATA DE NASCIMENTO</label>
                    <input type="date" class="form-control" name="txtDATA_NASCIMENTO" value="<?php echo $linha["DATA_NASCIMENTO"]; ?>">
                </div>

                <!-- Senha -->
                <div class="form-group mb-3">
                    <label>SENHA</label>
                    <input type="password" class="form-control" id="senha" name="txtSENHA" value="<?php echo $linha["senha"]; ?>">
                    <button type="button" class="btn-mostrar-senha" onclick="mostrarSenha()">Mostrar Senha</button>
                </div>

                <div class="form-group mb-3">
                    <a href="/views/usuarios/visualizar.php" class="btn btn-secondary">Voltar</a>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>


<script>
    function mostrarSenha() {
        var campoSenha = document.getElementById("senha");
        campoSenha.type = campoSenha.type === "password" ? "text" : "password";
    }
</script>

<?php include('../../layout/footer.html'); ?>
