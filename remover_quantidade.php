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

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Busca o produto
    $sql = "SELECT * FROM produtos WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();
    } else {
        die("Produto não encontrado.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $remover = (int) $_POST['remover'];
    
    // Busca a quantidade atual
    $sql = "SELECT quantidade FROM produtos WHERE id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $nova_quantidade = $row['quantidade'] - $remover;
    if($nova_quantidade < 0){
        $nova_quantidade = 0;
    }
    
    $sql = "UPDATE produtos SET quantidade = $nova_quantidade WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "  <div class='container d-flex justify-content-center h2'>
                Quantidade atualizada com sucesso!
            </div>";
        exit;
    } else {
        echo "Erro: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remover Quantidade</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
    <h2>Remover Quantidade do Produto</h2>
    <?php if(isset($produto)): ?>
        <p><strong>Produto:</strong> <?= $produto['nome'] ?></p>
        <p><strong>Quantidade Atual:</strong> <?= $produto['quantidade'] ?></p>
        <form action="remover_quantidade.php" method="POST">
            <input type="hidden" name="id" value="<?= $produto['id'] ?>">
            <label>Quantidade a remover:</label>
            <input type="number" name="remover" min="1" required>
            <button type="submit">Remover</button>
        </form>
    <?php endif; ?>
    <br>
    
    </div>
    
</body>
</html>

<?php
$conn->close();
?>
