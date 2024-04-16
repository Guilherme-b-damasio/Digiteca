<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

#include('../../layout/header.html');
#include('../../layout/navbar.php');
?>


<div class="container form-cad-usuario">
    <div class="content-wrap">
        <div class="card card-header" id="card-style">
			<form class="form-group" action="/DB/usuarios/cadastrar.php" method="post">
				<div class="form-group">
			    	<label>CPF</label>
			    	<input type="text" class="form-control" name="txtCPF" placeholder="Informe o CPF">
			  	</div>

			  	<div class="form-group">
			    	<label>Nome</label>
			    	<input type="text" class="form-control" name="txtNOME" placeholder="Informe o nome">
			  	</div>

			  	<div class="form-group">
			  	  	<label>Login</label>
			  	  	<input type="login" class="form-control" name="txtEMAIL" placeholder="Informa o login">
			  	</div>

			  	<div class="form-group">
			    	<label>Celular principal</label>
			    	<input type="text" class="form-control" name="txtTELEFONE" placeholder="Informe o celular">
			  	</div>

			  	<div class="form-group">
			    	<label>Data de nascimento</label>
			    	<input type="date" class="form-control" name="txtDATA_NASCIMENTO" placeholder="Informe a data de nascimento">
				</div>

				<div class="form-group">
					<label>Senha</label>
					<input type="password" class="form-control" name="txtSENHA" placeholder="Informe a senha de usuário">
				</div>

			  <button type="button" class="btn btn-danger" onclick="history.go(-1)">Cancelar</button>
			  <button type="submit" class="btn btn-success">Cadastrar</button>
			</form>
		</div>
	</div>
</div>

<?php include('../../layout/footer.html');?>
