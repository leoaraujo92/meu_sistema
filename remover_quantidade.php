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
        echo "Quantidade atualizada com sucesso!<br>";
        echo "<a href='listar.php'>Voltar para a lista</a>";
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
    <a href="listar.php">Voltar para a lista</a>
</body>
</html>

<?php
$conn->close();
?>
