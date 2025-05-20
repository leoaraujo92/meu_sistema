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
    echo "Produto cadastrado com sucesso! <br>";
    echo "<a href='index.php'>Voltar</a>";
} else {
    echo "Erro: " . $conn->error;
}

// Fechar conexão
$conn->close();
?>
