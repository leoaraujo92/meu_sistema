<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  
</head>

<body>

<div class="container">
    <div class="row" style="margin-bottom: 1.6em;">
        <header class="col-sm-7">
            <a href="index.php"><img src="logo.png" alt="logo" style="width: 150px;"></a> 
        </header>
        <ul class="col-sm-5 list-unstyled d-flex justify-content-between align-items-center">
            <a href="index.php"><li class="list-inline-item btn btn-outline-dark p-3">Cadastrar produto</li></a>
            <a href="listar.php"><li class="list-inline-item btn btn-outline-dark p-3">Produtos cadastrados</li></a>
        </ul>
    </div>
</div>

<div class="container">

    <div class="row">  
        
        <div class="col-md-7">

            <h2>Cadastro de Produto</h2>
            
            <form action="salvar.php" method="POST">
                <label>Nome:</label>
                <input type="text" name="nome" value="Arroz" required>

                <label>Quantidade:</label>
                <input type="number" name="quantidade"  required>

                <label>Preço:</label>
                <input type="number" step="0.01" name="preco" required>

                <label>Data de Fabricação:</label>
                <input type="date" name="data_fabricacao" required>

                <label>Data de Validade:</label>
                <input type="date" name="data_validade" required>

                    <div class="d-flex justify-content-end ">
                        <button id="form" class="btn btn-success" type="submit">Salvar</button>
                    </div>
                    
            </form>

        </div>


        <div class="col-md-5">
        <h3>ATENÇÃO</h3>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "estoque";
                
        $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
             }
                
        $hoje = new DateTime();
        $hoje_str = $hoje->format('Y-m-d');
                
        $sql_vencidos = "SELECT COUNT(*) as total_produtos, SUM(quantidade) as total_itens 
            FROM produtos 
            WHERE data_validade < '$hoje_str'";
        $result_vencidos = $conn->query($sql_vencidos);
        $vencidos = $result_vencidos->fetch_assoc();
                
        $data_7dias = $hoje->add(new DateInterval('P7D'))->format('Y-m-d');
        $sql_50off = "SELECT COUNT(*) as total_produtos, SUM(quantidade) as total_itens 
            FROM produtos 
            WHERE data_validade >= '$hoje_str' 
            AND data_validade <= '$data_7dias'";
        $result_50off = $conn->query($sql_50off);
        $produtos_50off = $result_50off->fetch_assoc();
                
        $hoje = new DateTime();
        $data_30dias = $hoje->add(new DateInterval('P30D'))->format('Y-m-d');
        $hoje = new DateTime();
        $data_8dias = $hoje->add(new DateInterval('P8D'))->format('Y-m-d');
        $sql_20off = "SELECT COUNT(*) as total_produtos, SUM(quantidade) as total_itens 
            FROM produtos 
            WHERE data_validade >= '$data_8dias' 
            AND data_validade <= '$data_30dias'";
        $result_20off = $conn->query($sql_20off);
        $produtos_20off = $result_20off->fetch_assoc();
                
        $hoje = new DateTime();
        $data_30dias_futuro = $hoje->add(new DateInterval('P30D'))->format('Y-m-d');
        $sql_validade_longa = "SELECT COUNT(*) as total_produtos, SUM(quantidade) as total_itens 
            FROM produtos 
            WHERE data_validade > '$data_30dias_futuro'";
        $result_validade_longa = $conn->query($sql_validade_longa);
        $produtos_validade_longa = $result_validade_longa->fetch_assoc();
                
        $conn->close();

            function formatarContagem($num_produtos, $num_itens) {
                if ($num_produtos == 0) {
                    return "Nenhum produto";
                }
                    
        $produto_text = $num_produtos == 1 ? "produto" : "produtos";
        $item_text = $num_itens == 1 ? "item" : "itens";
                    
                return "<span class='info-count'>$num_produtos $produto_text</span>, " . 
                "<span class='info-count'>$num_itens $item_text</span>";
            }
                ?>
                
        <div class="info-item vencidos">
            <strong>Produtos Vencidos:</strong><br>
            <?= formatarContagem($vencidos['total_produtos'] ?? 0, $vencidos['total_itens'] ?? 0) ?>
        </div>
                
        <div class="info-item desconto-50">
            <strong>Produtos com 50% de desconto (0-7 dias para vencer):</strong><br>
            <?= formatarContagem($produtos_50off['total_produtos'] ?? 0, $produtos_50off['total_itens'] ?? 0) ?>
        </div>
                
        <div class="info-item desconto-20">
            <strong>Produtos com 20% de desconto (8-30 dias para vencer):</strong><br>
            <?= formatarContagem($produtos_20off['total_produtos'] ?? 0, $produtos_20off['total_itens'] ?? 0) ?>
        </div>
                
        <div class="info-item validade-longa">
            <strong>Produtos com mais de 30 dias para vencer:</strong><br>
            <?= formatarContagem($produtos_validade_longa['total_produtos'] ?? 0, $produtos_validade_longa['total_itens'] ?? 0) ?>
        </div>
                
    </div>   
            
    </div>

        
</div>

    <script>
       var form = document.querySelector("#form")

       button.addEventListener("click", function(event){
            window.alert("ola")
            event.preventDefault();

            const formData = new FormData(form);

            fetch('salva.php' , {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.querySelector("#resultado").innerHTML = data;
            })
            .catch(error => {
                console.error('Erro: ', error);
            })
 
        })

    </script>
</body>
</html>