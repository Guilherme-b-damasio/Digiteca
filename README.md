<div align="center">
    <img height="30" alt="Javascript" src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white">
	<img height="30" alt="Javascript" src="https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white">
	<img height="30" alt="Javascript" src="https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white">
	<img height="30" alt="css3" src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white">
    <img height="30" alt="html5" src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white">
    <img height="30" alt="Javascript" src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black">

</div>

<h3 align="center">Programa para gerenciamento de livros em uma bibliotecas.</h3>

<h3>Informações gerais</h3>

### Tópicos

- [Tópicos](#tópicos)
- [Introdução](#introdução)
- [Preparando o ambiente](#preparando-o-ambiente)
- [Acessando o programa](#acessando-o-programa)
- [Aviso](#aviso)


### Introdução
Este é um programa para gerenciamento de livros, usuários e empréstimos para bibliotecas físicas. Segue um gif demonstrativo do programa.

![img](docs/usando.gif)

### Preparando o ambiente

1. Primeiramente é necessário instalar o lamp, clique [aqui](https://bitnami.com/stack/lamp/installer), baixe, instale e deixe-o pronto de acordo com seu sistema operacional.
2. Depois você precisa abrir o lamp, iniciar o "Apache Web Server" e o "MySQL Database".
3. Agora as coisas podem ser um pouco diferentes. Se você está acostumado a usar o git, continue, caso contrário, vá para o passo 6.
4. Basta clonar o repositório dentro da pasta "htdocs" do lamp.
Exemplo no ubuntu:

```shell
# Acessar a pasta
cd /opt/lampp/htdocs

# clonar o repositório
[git clone https://github.com/Guilherme-b-damasio/Digiteca.git
```

5. Agora basta ir para o passo 11.
6. No seu navegador, acesse o repositório
7. Clique no botão verde escrito "code"
8. Clique em "download zip". Um arquivo .zip será baixado para o seu computador.
9. Extraia o arquivo baixado, você terá uma pasta chamada "digiteca".
10. Mova a pasta "digiteca" para o "htdocs" do lamp.
> Desta forma, dentro da pasta "htdocs" do lamp teremos a pasta "digiteca".
11. Agora abra *PHP My Admin* no seguinte endereço http://localhost/phpmyadmin/
12. Clique em "Novo"
13. Crie um banco de dados chamado exatamente "digiteca"
14. Clique no botão "Importar".
15. Clique em "Escolher arquivo"
16. Selecione o arquivo "digiteca.sql".
> Este arquivo está dentro da pasta "sql", dentro da "digiteca" que você colocou em "htdocs"
17. Após escolher o arquivo, clique em "Executar".
> Os dados e as tabelas serão importados para o banco digiteca que você criou anteriormente.

Agora sim, a biblioteca já está pronta para ser usada.

### Acessando o programa
Acesse o endereço [http://localhost/digiteca/](http://localhost/digiteca/) e a tela da biblioteca será carregada.


#### Aviso
Como demonstração, deixamos alguns dados cadastrados. Para excluir um usuário por exemplo, primeiro remova todos os empréstimos deste usuário.


