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
    // Se o botão de remover foi clicado
    if (isset($_POST['remover'])) {
        $sql = "DELETE FROM produtos WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "Produto removido com sucesso!<br>";
            echo "<a href='listar.php'>Voltar para a lista</a>";
            exit;
        } else {
            echo "Erro: " . $conn->error;
        }
    } else {
        // Atualiza nome e preço
        $novo_nome = $_POST['nome'];
        $novo_preco = $_POST['preco'];
        $sql = "UPDATE produtos SET nome = '$novo_nome', preco = '$novo_preco' WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "Produto atualizado com sucesso!<br>";
            echo "<a href='listar.php'>Voltar para a lista</a>";
            exit;
        } else {
            echo "Erro: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Editar Produto</h2>
    <?php if(isset($produto)): ?>
    <form action="editar.php" method="POST">
        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?= $produto['nome'] ?>" required>
        
        <label>Preço:</label>
        <input type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?>" required>
        
        <button type="submit">Atualizar</button>
    </form>
    <br>
    <form action="editar.php" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este produto?');">
        <input type="hidden" name="id" value="<?= $produto['id'] ?>">
        <button type="submit" name="remover" value="true" style="background-color:#d9534f;">Remover Produto</button>
    </form>
    <?php endif; ?>
    <br>
    <a href="listar.php">Voltar para a lista</a>
</body>
</html>

<?php
$conn->close();
?>
