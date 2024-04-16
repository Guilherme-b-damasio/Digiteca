<!-- Barra de Menu -->

<link rel="stylesheet" type="text/css" href="/assets/css/navbar.css">
<link rel="stylesheet" type="text/css" href="/assets/css/navbar.css">

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #FF7B00;">
   
    <a href="/home.php">
        <div class="imgLOGOnav">        
            <img class="imgLognav" src="/assets/images/logo.png" alt="">
        </div>
    </a>


    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/home.php">Inicio</a>
            </li>

            <li class="nav-item dropdown active" >
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Livros</a>
                <div class="dropdown-menu dropdown-menu-right"  aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/views/livros/cadastrar.php">Cadastrar Livros</a>
                    <a class="dropdown-item" href="/views/livros/visualizar.php">Visualizar Livros</a>
              </div>
            </li>

            <li class="nav-item dropdown active" >
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Usuários</a>
                <div class="dropdown-menu dropdown-menu-right"  aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/views/usuarios/visualizar.php">Visualizar Usuários</a>
              </div>
            </li>

            <li class="nav-item dropdown active" >
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Emprestimos</a>
                <div class="dropdown-menu dropdown-menu-right"  aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/views/emprestimos/cadastrar.php">Emprestar um livro</a>
                    <a class="dropdown-item" href="/views/emprestimos/visualizar.php">Visualizar Livros Emprestados</a>
              </div>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="/index.php">Desconectar</a>
            </li>

        </ul>
    </div>
</nav>
