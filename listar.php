<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  
</head>

<div class="container-fluid">
    <div class="row" style="margin-bottom: 1.6em;">
        <header class="col-sm-7">
            <a href="index.php"><img src="logo.png" alt="logo" style="width: 150px;"></a> 
        </header>
        <ul class="col-sm-5 list-unstyled d-flex justify-content-around align-items-center">
            <a href="index.php"><li class="list-inline-item btn btn-outline-dark p-3">Cadastrar produto</li></a>
            <a href="listar.php"><li class="list-inline-item btn btn-outline-dark p-3">Produtos cadastrados</li></a>
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

// Verifica os parâmetros de ordenação
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'data_fabricacao';
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';
$valid_columns = ['id', 'nome', 'quantidade', 'preco', 'data_fabricacao', 'data_validade'];

// Verifica os filtros selecionados
$filtros = [
    'validade_30' => isset($_GET['validade_30']),
    'validade_7' => isset($_GET['validade_7']),
    'validade_0' => isset($_GET['validade_0']),
    'fora_prazo' => isset($_GET['fora_prazo'])
];

// Se nenhum filtro estiver selecionado, mostra todos por padrão
if (!array_filter($filtros)) {
    $filtros = [
        'validade_30' => true,
        'validade_7' => true,
        'validade_0' => true,
        'fora_prazo' => true
    ];
}

// Garante que a coluna de ordenação é válida
if (!in_array($order_by, $valid_columns)) {
    $order_by = 'data_fabricacao';
}

// Modifica a consulta SQL para incluir a ordenação selecionada
$sql = "SELECT *, preco as preco_original FROM produtos ORDER BY $order_by $order, data_validade ASC";
$result = $conn->query($sql);

// Função para formatar data no padrão brasileiro
function formatarData($dataMySQL) {
    if(empty($dataMySQL)) return '';
    $data = DateTime::createFromFormat('Y-m-d', $dataMySQL);
    return $data ? $data->format('d/m/Y') : $dataMySQL;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f2f2f2;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .validade-30 { background-color: #ccffcc !important; }
        .validade-7 { background-color: #ffffcc !important; }
        .validade-0 { background-color: #ffcccc !important; }
        .fora-prazo { background-color: #e6ccff !important; }
        .filtro-checkbox {
            margin: 15px 0;
            padding: 10px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .filtro-checkbox label {
            display: inline-block;
            margin-right: 15px;
            cursor: pointer;
        }
        button[type="submit"] {
            padding: 5px 15px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background: #45a049;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
    
        <div class="row">

            <div class=" col-md-2">

                <form  style="margin-top: 25px;" method="get" action="listar.php">
                <h4 class="m-b-0 p-0">Filtro</h4>
                    <input type="hidden" name="order_by" value="<?php echo htmlspecialchars($order_by); ?>">
                    <input type="hidden" name="order" value="<?php echo htmlspecialchars($order); ?>">
                    
                    <label class="d-flex justify-content-between">
                        <span><small>Produtos com +30 dias de validade</small></span> 
                        <input style="width: 1.4em;" type="checkbox" name="validade_30" <?php echo $filtros['validade_30'] ? 'checked' : ''; ?>>
                    </label>
                    
                    <label class="d-flex justify-content-between">
                        <span><small>Produtos com 8-30 dias de validade</small></span>
                        <input style="width: 1.4em;" type="checkbox" name="validade_7" <?php echo $filtros['validade_7'] ? 'checked' : ''; ?>>
                    </label>
                    
                    <label  class="d-flex justify-content-between">
                       <span><small>Produtos com 0-7 dias de validade</small></span> 
                        <input style="width: 1.4em;" type="checkbox" name="validade_0" <?php echo $filtros['validade_0'] ? 'checked' : ''; ?>>
                    </label>
                    
                    <label class="d-flex justify-content-between">
                        <span><small>Produtos vencidos</small></span> 
                        <input style="width: 1.4em;" type="checkbox" name="fora_prazo" <?php echo $filtros['fora_prazo'] ? 'checked' : ''; ?>>
                    </label>
                    
                    <button class="btn btn-outline-sucess" type="submit">Aplicar Filtros</button>
                </form>
            </div>
    
                    <!--TABELA-->
            <table class="col-md-10 table">
            <tr>
                <th><a href="listar.php?<?php 
                    echo "order_by=id&order=" . ($order_by == 'id' ? ($order == 'asc' ? 'desc' : 'asc') : 'asc');
                    foreach ($_GET as $key => $value) {
                        if ($key != 'order_by' && $key != 'order') {
                        echo "&$key=" . htmlspecialchars($value);
                        }
                    }
                ?>">ID <?php if ($order_by == 'id') echo ($order == 'asc' ? '↑' : '↓'); ?></a></th>
            
                <th><a href="listar.php?<?php 
                    echo "order_by=nome&order=" . ($order_by == 'nome' ? ($order == 'asc' ? 'desc' : 'asc') : 'asc');
                    foreach ($_GET as $key => $value) {
                        if ($key != 'order_by' && $key != 'order') {
                        echo "&$key=" . htmlspecialchars($value);
                        }
                    }
                    ?>">Nome <?php if ($order_by == 'nome') echo ($order == 'asc' ? '↑' : '↓'); ?></a></th>
            
                <th><a href="listar.php?<?php 
                    echo "order_by=quantidade&order=" . ($order_by == 'quantidade' ? ($order == 'asc' ? 'desc' : 'asc') : 'asc');
                    foreach ($_GET as $key => $value) {
                        if ($key != 'order_by' && $key != 'order') {
                        echo "&$key=" . htmlspecialchars($value);
                        }
                    }
                    ?>">Quantidade <?php if ($order_by == 'quantidade') echo ($order == 'asc' ? '↑' : '↓'); ?></a></th>
            
                <th><a href="listar.php?<?php 
                    echo "order_by=preco&order=" . ($order_by == 'preco' ? ($order == 'asc' ? 'desc' : 'asc') : 'asc');
                    foreach ($_GET as $key => $value) {
                        if ($key != 'order_by' && $key != 'order') {
                            echo "&$key=" . htmlspecialchars($value);
                        }
                    }
                    ?>">Preço Original <?php if ($order_by == 'preco') echo ($order == 'asc' ? '↑' : '↓'); ?></a></th>
            
                <th>Preço Final</th>
            
                <th><a href="listar.php?<?php 
                    echo "order_by=data_fabricacao&order=" . ($order_by == 'data_fabricacao' ? ($order == 'asc' ? 'desc' : 'asc') : 'asc');
                    foreach ($_GET as $key => $value) {
                        if ($key != 'order_by' && $key != 'order') {
                            echo "&$key=" . htmlspecialchars($value);
                        }
                    }
                    ?>">Data de Fabricação <?php if ($order_by == 'data_fabricacao') echo ($order == 'asc' ? '↑' : '↓'); ?></a></th>
            
                <th><a href="listar.php?<?php 
                    echo "order_by=data_validade&order=" . ($order_by == 'data_validade' ? ($order == 'asc' ? 'desc' : 'asc') : 'asc');
                    foreach ($_GET as $key => $value) {
                        if ($key != 'order_by' && $key != 'order') {
                            echo "&$key=" . htmlspecialchars($value);
                        }
                    }
                    ?>">Data de Validade <?php if ($order_by == 'data_validade') echo ($order == 'asc' ? '↑' : '↓'); ?></a></th>
            
                <th>Ações</th>
            </tr>

            <?php
                while ($row = $result->fetch_assoc()) {
                    $preco_original = $row["preco"];
                    $preco_final = $preco_original;
                    $hoje = new DateTime();
                    $validade = new DateTime($row["data_validade"]);
                    $dias_para_validade = $validade->diff($hoje)->days;
                    $fora_prazo = $validade < $hoje;

                    // Define a classe e status conforme os dias para validade
                    $classe = '';
                    $status_validade = '';

                if ($fora_prazo) {
                    $classe = 'fora-prazo';
                    $status_validade = 'fora_prazo';
                    $preco_final = 0.00; // Preço zero para produtos vencidos
                } elseif ($dias_para_validade <= 7) {
                    $classe = 'validade-0';
                    $status_validade = 'validade_0';
                    $preco_final = $preco_original * 0.5; // 50% de desconto
                } elseif ($dias_para_validade <= 30) {
                    $classe = 'validade-7';
                    $status_validade = 'validade_7';
                    $preco_final = $preco_original * 0.8; // 20% de desconto
                } else {
                    $classe = 'validade-30';
                    $status_validade = 'validade_30';
                    $preco_final = $preco_original;
                }

                // Verifica se deve exibir o produto com base nos filtros selecionados
                $exibir = $filtros[$status_validade];

                if ($exibir) {
                    echo "<tr class='{$classe}'>
                        <td>{$row['id']}</td>
                        <td>" . htmlspecialchars($row['nome']) . "</td>
                        <td>{$row['quantidade']}</td>
                        <td>R$ " . number_format($preco_original, 2, ',', '.') . "</td>
                        <td>R$ " . number_format($preco_final, 2, ',', '.') . "</td>
                        <td>" . formatarData($row['data_fabricacao']) . "</td>
                        <td>" . formatarData($row['data_validade']) . "</td>
                        <td class='acao'>
                            <a href='editar.php?id={$row["id"]}'>Editar</a>
                            <a href='remover_quantidade.php?id={$row["id"]}'>Remover Quantidade</a>
                        </td>
                      </tr>";
                    }
                }
                ?>
        </table>
        <br>
        
    </div>



        </div>
    </div>
    
    
    <!-- Filtro de exibição com checkboxes -->
    
        
</body>
</html>

        <?php
            $conn->close();
        ?>

    
    