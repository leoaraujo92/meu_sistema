<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  
</head>

<div class="container">
    <div class="row" style="margin-bottom: 1.6em;">
        <header class="col-sm-7">
            <a href="index.php"><img src="logo.png" alt="logo" style="width: 150px;"></a> 
        </header>
        <ul class="col-sm-5 list-unstyled d-flex justify-content-between align-items-center">
            <a href="index.php"><li class="list-inline-item btn btn-outline-dark">Cadastrar produto</li></a>
            <a href="listar.php"><li class="list-inline-item btn btn-outline-dark">Produtos cadastrados</li></a>
        </ul>
    </div>

</div>



<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "estoque";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Capturar os dados do formulário
$nome = $_POST['nome'];
$quantidade = $_POST['quantidade'];
$preco = $_POST['preco'];
$data_fabricacao = $_POST['data_fabricacao'];
$data_validade = $_POST['data_validade'];

// Inserir os dados no banco
$sql = "INSERT INTO produtos (nome, quantidade, preco, data_fabricacao, data_validade) 
        VALUES ('$nome', '$quantidade', '$preco', '$data_fabricacao', '$data_validade')";

if ($conn->query($sql) === TRUE) {

    echo "  <div class='container d-flex justify-content-center h2'>
                Produto cadastrado com sucesso!
            </div>";
    
} else {
    echo "Erro: " . $conn->error;
}

// Fechar conexão
$conn->close();
?>
