<?php
session_start(); // Inicie a sessão no início do script
include('seguranca/seguranca.php');

// Redirecionamento se não estiver logado
if (!administrador_logado()) {
    header("Location: /index.php");
    exit;
}

// Inclui o código HTML para o cabeçalho e a barra de navegação
include('layout/header.html');
include('layout/navbar.php');
?>
<link rel="stylesheet" href="assets/css/main.app.css">
<link rel="stylesheet" href="assets/css/book.css">

	<div class="container_home">

		<div class="header-container">

			<div class="title-section">    
				<h2 class="main-title">Bem-vindo à Biblioteca</h2>
				<p class="subtitle">Explore um mundo de conhecimento</p>
			</div>
		</div>

		<div class="book">
			<div class="book__pg-shadow"></div>
			<div class="book__pg"></div>
			<div class="book__pg book__pg--2"></div>
			<div class="book__pg book__pg--3"></div>
			<div class="book__pg book__pg--4"></div>
			<div class="book__pg book__pg--5"></div>
		</div>

	</div>
<script>
   
</script>

<?php include('layout/footer.html'); ?>
