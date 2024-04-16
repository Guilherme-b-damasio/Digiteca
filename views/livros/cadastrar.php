<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

include('../../layout/header.html');
include('../../layout/navbar.php');
?>

	<div class="container" style="margin-top: 3rem;">

		<!-- Cabecalho da Pagina -->
 		<div class="card text-white mb-2" style="background-color: #FF7B00;">
			<div class="card-body">
				<div class="text-center" style="font-size: 1.2em;">Efetuar Cadastro de Livros</div>
			</div>
		</div>

		<div class="card bg-light">
			<div class="card-body">
				<!-- Aqui começa o nosso formulario -->
				<form action="/DB/livros/cadastrar.php" method="post" enctype="multipart/form-data">

					<!-- Título -->
					<div class="form-group mb-3">
						<label for="tituloCompleto">Título</label>
						<input type="text" class="form-control" name="tituloDoLivro" placeholder="Título" required>
					</div>

					<!-- Autor -->
					<div class="form-group mb-3">
						<label for="autorPrincipal">Autor Principal</label>
						<input type="text" class="form-control" name="autorPrincipal" placeholder="Autor Principal" required>
					</div>

					<!-- Descrição -->
					<div class="form-group mb-3">
						<label for="descricaoDoLivro">Descrição</label>
						<textarea class="form-control" name="descricaoDoLivro" rows="3" required></textarea>
					</div>

					<!-- Gênero -->
					<div class="form-group mb-3">
						<label for="generoPrincipal">Gênero</label>
						<input type="text" class="form-control" name="generoPrincipal" placeholder="Gênero" required>
					</div>

					<!-- Editora -->
					<div class="form-group mb-3">
						<label for="nomeDaEditora">Editora</label>
						<input type="text" class="form-control" name="nomeDaEditora" placeholder="Editora" required>
					</div>

					<!-- Ano de Publicação -->
					<div class="form-group mb-3">
						<label for="anoDePublicacao">Ano de Publicação</label>
						<input type="date" class="form-control" name="anoDePublicacao" placeholder="Publicação" required>
					</div>

					<!-- ISBN -->
					<div class="form-group mb-3">
						<label for="codigoISBN">Código ISBN</label>
						<input type="text" class="form-control" name="codigoISBN" placeholder="Código ISBN" required>
					</div>

					<!-- Unidades Disponíveis -->
					<div class="form-group mb-3">
						<label for="unidadesDisponiveis">Unidades Disponiveis</label>
						<input type="number" class="form-control" name="unidadesDisponiveis" placeholder="Unidades Disponiveis" required>
					</div>

					  <!-- Campo para Upload de Imagem -->
					<div class="form-group mb-3">
						<label for="imagemDoLivro">Imagem do Livro</label>
						<input type="file" class="form-control" name="imagemDoLivro" required>
					</div>
					

					<button type="reset" class="btn btn-secondary btn-lg"  onclick="history.go(-1)">Voltar</button>
					<button type="submit" class="btn btn-primary btn-lg">Salvar</button>
				</form>
		    </div>
	    </div>
    </div>

<?php include('../../layout/footer.html'); ?>
