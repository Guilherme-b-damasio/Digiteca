<?php
# Impede que usuários acessem a página se não estiverem logados
include('../../seguranca/seguranca.php');
session_start();
if(administrador_logado() == false) {header("location: /index.php"); exit;}

include('../../layout/header.html');
include('../../layout/navbar.php');
require_once("../../conexao/conexao.php");

if(!filter_input(INPUT_GET, "ISBN", FILTER_SANITIZE_STRING)) {
    echo "ISBN é inválido!";
} else {

    $ISBN = filter_input(INPUT_GET, "ISBN", FILTER_SANITIZE_STRING);
    $consulta = $conexao->query("SELECT * FROM livros WHERE ISBN='$ISBN'");
    $linha = $consulta->fetch(PDO::FETCH_ASSOC);


    // Consulta para obter a imagem do livro
    $consultaImagem = $conexao->query("SELECT TO_BASE64(IMAGEM) AS IMAGEM_BASE64 FROM livros WHERE ISBN='$ISBN'");
    $linhaImagem = $consultaImagem->fetch(PDO::FETCH_ASSOC);

    // Salvar a imagem na variável $imagemDoLivro
    $imagemDoLivro = $linhaImagem["IMAGEM_BASE64"];

    
}
?>
    <style>
        .img-reduzida, .img-padrao {
            width: 20%; /* Ajuste para ocupar toda a largura da coluna */
            height: auto; /* Mantém a proporção da imagem */
            border-radius: 10px;
            margin-bottom: 10px;
        }


    </style>

    <div class="container" style="margin-top: 1.4rem;">
        <!-- Cabecalho da Pagina -->
         <div class="card text-white mb-2" style="background-color: #FF7B00;">
            <div class="card-body">
                <div class="text-center" style="font-size: 1.2em; background-color: #FF7B00;">Editar Livros Cadastrados</div>
            </div>
        </div>
    
        
                    
        <?php
           if ( isset($_GET["mensagem_erro"]) == true ) {
              $mensagem_erro = $_GET["mensagem_erro"];
              print_r ("<div class=\"alert alert-danger\" role=\"alert\">Erro: $mensagem_erro</div>");
             }
        ?>


        <div class="card bg-light">
            <div class="card-body">

                <!-- Aqui começa o nosso formulario -->
                <form action="/DB/livros/editar.php" method="post" enctype="multipart/form-data">

                    <input type="hidden" name="ISBN" value="<?php echo $ISBN ?>">

                      <!-- Exibição da Imagem do Livro -->
                      <?php if ($imagemDoLivro != ""): ?>
                            <img class="img-reduzida" src="data:image/jpeg;base64,<?php echo $imagemDoLivro; ?>" alt="<?php echo htmlspecialchars($linha["TITULO"]); ?>">
                     <?php else: ?>
                            <img class="img-padrao" src="../../assets/images/padraoLIVRO.jpg" alt="Imagem Padrão">
                    <?php endif; ?> 


                    <!-- Título -->
                    <div class="form-group mb-3">
                        <label for="tituloCompleto">Título</label>
                        <input type="text" class="form-control" name="tituloDoLivro"
                        value="<?php echo $linha["TITULO"]; ?>" required>
                    </div>
                    

                    <!-- Autor -->
                    <div class="form-group mb-3">
                        <label for="autorPrincipal">Autor Principal</label>
                        <input type="text" class="form-control" name="autorPrincipal"  placeholder="Autor Principal" value="<?php echo $linha["AUTOR"]; ?>" required>
                    </div>

                    <!-- Descrição -->
                    <div class="form-group mb-3">
                        <label for="descricaoDoLivro">Descrição</label>
                         <textarea type="text" name="descricaoDoLivro" class="form-control" rows="3" required value="<?php echo $linha["DESCRICAO"]; ?>"><?php echo $linha["DESCRICAO"]; ?></textarea>
                    </div>

                    <!-- Gênero -->
                    <div class="form-group mb-3">
                        <label for="generoPrincipal">Gênero</label>
                        <input type="text" class="form-control" name="generoPrincipal"
                        value="<?php echo $linha["GENERO"]; ?>" required>
                    </div>

                    <!-- Editora -->
                    <div class="form-group mb-3">
                        <label for="nomeDaEditora">Editora</label>
                        <input type="text" class="form-control" name="nomeDaEditora"
                        value="<?php echo $linha["EDITORA"]; ?>" required>
                    </div>

                    <!-- Ano de Publicação -->
                    <div class="form-group mb-3">
                        <label for="anoDePublicacao">Ano de Publicação</label>
                        <input type="date" class="form-control" name="anoDePublicacao"
                        value="<?php echo $linha["ANO_PUBLICACAO"]; ?>">
                    </div>

                    <!-- ISBN -->
                    <div class="form-group mb-3">
                        <label for="codigoISBN">Código ISBN</label>
                        <input type="text" class="form-control" name="codigoISBN"  placeholder="Código ISBN" value="<?php echo $linha["ISBN"]; ?>" required>
                    </div>

                    <!-- Unidades Disponíveis -->
					<div class="form-group mb-3">
						<label for="unidadesDisponiveis">Unidades Disponiveis</label>
						<input type="number" class="form-control" name="unidadesDisponiveis"  placeholder="Unidades Disponiveis" value="<?php echo $linha["UNIDADES_DISPONIVEIS"]; ?>" required>
					</div>

                    <!-- Campo para Upload de Imagem -->
                    <div class="form-group mb-3">
                        <label for="imagemDoLivro">Imagem do Livro</label>
                        <input type="file" class="form-control" name="imagemDoLivro" accept="image/*">
                    </div>


                   
                    <button class="btn btn-secondary btn-lg" type="reset" onclick="history.go(-1)">Voltar</button>
                    <button class="btn btn-primary btn-lg" type="submit">Salvar</button>
                </form>
            </div>
        </div>
    </div>

<?php include('../../layout/footer.html'); ?>
