<?php
# Impede que usuários acessem a página se não estiverem logados
include('seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

// Função para verificar se o dispositivo é móvel
function isMobileDevice() {
    return (isset($_SERVER['HTTP_USER_AGENT']) && 
            preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|' .
            'compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|' .
            'midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)' .
            '|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|' .
            'wap|windows ce|xda|xiino|android|ipad|playbook|silk/i', $_SERVER['HTTP_USER_AGENT']));
}

// Inclui o código HTML
include('layout/header.html');
include('layout/navbar_user.php');
?>

	<style>
		<?php if (isMobileDevice()): ?>
		/* Estilos específicos para dispositivos móveis */
		.container_home {
			padding: 0 15px;
		}
		<?php else: ?>
		/* Estilos específicos para dispositivos não móveis */
		.container_home {
			max-width: 1200px; /* Exemplo de largura máxima para não móveis */
			margin: 0 auto;
		}
		<?php endif; ?>


    </style>
    <link rel="stylesheet" href="assets/css/main.app.css">
    <link rel="stylesheet" href="assets/css/book.css">
    <link rel="stylesheet" href="assets/css/menuMobile.css">

    <div class="container_home"<?php if (isMobileDevice()) echo ' style="max-width: 100%; margin: 0 auto;"'; ?>>
        <div class="header-container">
            

            <div class="title-section">    
                <h2 class="main-title main-title-margin">Bem-vindo à Biblioteca</h2>
                <p class="subtitle">Explore um mundo de conhecimento</p>
            </div>
        </div>

        <?php if (!isMobileDevice()): // Verifica se não é um dispositivo móvel ?>
            <a href="views/livros/visualizar_user.php">
                <div class="book">
                    <div class="book__pg-shadow"></div>
                    <div class="book__pg"></div>
                    <div class="book__pg book__pg--2"></div>
                    <div class="book__pg book__pg--3"></div>
                    <div class="book__pg book__pg--4"></div>
                    <div class="book__pg book__pg--5"></div>
                </div>     

            </a>

            <div class="btn-container">
                <a href="views/livros/visualizar_user.php">
                    <button  class="btnVerLivros">Ver livros</button>
                </a>
            </div>
        <?php endif; ?>

    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Se for um dispositivo móvel, habilitar o menu interativo
            <?php if (isMobileDevice()): ?>
                $('.site-header').prepend('<div class="menu"> \
                    <div class="containerMenu"> \
                        <div class="toggle"></div> \
                        <span class="hidden"><a href="views/livros/visualizar_user.php">Livros</a></span> \
                        <span class="hidden"><a href="views/emprestimos/visualizar_user.php">Emprestimos</a></span> \
                        <span class="hidden"><a href="../../index.php">Sair</a></span> \
                    </div> \
                </div>');
                
                $('.toggle').on('click', function() {
                    $('.menu').toggleClass('expanded');
                    $('span').toggleClass('hidden');
                    $('.container, .toggle').toggleClass('close');
                });

            <?php endif; ?>
        });

			$(document).ready(function() {
				function adjustNav() {
					if ($(window).width() < 768) {
						// Desativa os dropdowns
						$('.nav-item.dropdown').off('click');
						$('.nav-item.dropdown').on('click', function(e) {
							e.stopPropagation(); // Impede a abertura do dropdown
						});
					} else {
						// Reativa os dropdowns para telas maiores, se necessário
						$('.nav-item.dropdown').off('click').on('click', function(e) {
							// Código para reativar o comportamento de dropdown, se necessário
						});
					}
				}

				// Ajusta a navbar ao carregar a página
				adjustNav();

				// Ajusta a navbar cada vez que a janela for redimensionada
				$(window).resize(function() {
					adjustNav();
				});
			});
    </script>

<?php include('layout/footer.html'); ?>
