<?php
session_start();
if (!isset($_SESSION["carrinho"])) {
    $_SESSION["carrinho"] = [];
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["produto"])) {
  $produto = $_POST["produto"];
  $arquivoEstoque = "../estoque/estoque.json";
  $temEstoque = true;
  if (file_exists($arquivoEstoque)) {
    $jsonEst = file_get_contents($arquivoEstoque);
    $dadosEst = json_decode($jsonEst, true);
    if (!isset($dadosEst[$produto]) || (isset($dadosEst[$produto]['quantidade']) && $dadosEst[$produto]['quantidade'] <= 0)) {
      $temEstoque = false;
    }
  }

  if ($temEstoque) {
    $_SESSION["carrinho"][] = $produto;
  } else {
    $mensagem = "Produto sem estoque e não pode ser adicionado ao carrinho.";
  }
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["remover"])) {
    $remover = $_POST["remover"];
    $index = array_search($remover, $_SESSION["carrinho"]);
    if ($index !== false) {
        unset($_SESSION["carrinho"][$index]);
        $_SESSION["carrinho"] = array_values($_SESSION["carrinho"]);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["comprar"])) {
    $arquivoEstoque = "../estoque/estoque.json";
    $arquivoVendas = "../estoque/vendas.json";

    if (file_exists($arquivoEstoque)) {
        $json = file_get_contents($arquivoEstoque);
        $estoque = json_decode($json, true);

        
        $vendas = [];
        if (file_exists($arquivoVendas)) {
            $vendas = json_decode(file_get_contents($arquivoVendas), true);
        }

        
        $contagem = array_count_values($_SESSION["carrinho"]);

        foreach ($contagem as $item => $qtdComprada) {
            if (isset($estoque[$item]) && $estoque[$item]["quantidade"] >= $qtdComprada) {
                
                $estoque[$item]["quantidade"] -= $qtdComprada;

                
                $valorUnitario = $estoque[$item]["valor"];
                $valorTotal = $valorUnitario * $qtdComprada;

                $vendas[] = [
                    "produto" => $item,
                    "quantidade" => $qtdComprada,
                    "valor_total" => $valorTotal
                ];
            }
        }

        
        file_put_contents($arquivoEstoque, json_encode($estoque, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        
        file_put_contents($arquivoVendas, json_encode($vendas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    $_SESSION["carrinho"] = []; 
    $mensagem = "Compra realizada com sucesso!";
}

$arquivo = "../estoque/estoque.json";
$estoque = [];
if (file_exists($arquivo)) {
    $json = file_get_contents($arquivo);
    $estoque = json_decode($json, true);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Carrinho</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .carrinho-lista { margin-top: 30px; }
    .item-carrinho {
      background-color: #1a1a1a;
      border: 1px solid rgb(220, 30, 30);
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .item-carrinho img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 6px;
      border: 1px solid #444;
    }
    .item-info { flex: 1; }
    .item-carrinho h4 { color: #fff; margin-bottom: 6px; }
    .item-carrinho p { color: #ccc; }
    .item-carrinho form button {
      background-color: rgb(220, 30, 30);
      color: #fff;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
    }
    .item-carrinho form button:hover { background-color: rgb(255, 70, 70); }
    .total {
      margin-top: 20px;
      font-size: 18px;
      font-weight: bold;
      color: rgb(255, 70, 70);
    }
    .comprar-btn {
      margin-top: 20px;
      background-color: green;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }
    .comprar-btn:hover { background-color: darkgreen; }
    .mensagem {
      margin-top: 20px;
      color: lime;
      font-weight: bold;
    }
    .continuar-btn {
      margin-top: 20px;
      margin-left: 10px;
      background: rgb(220, 30, 30);
      color: #fff;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      display: inline-block;
      font-weight: bold;
      border: none;
      transition: background 0.3s;
    }
    .continuar-btn:hover { background: rgb(255, 70, 70); }
  </style>
</head>
<body>

<header>
  <div class="logo"> JLER </div>
  <div class="cart-nav">
    <a href="../login/logout.php" class="logout-btn">Sair</a>
  </div>
</header>

<ul class="topnav">
  <li><a href="index.php">Home</a></li>
  <li><a href="produtos.php">Produtos</a></li>
  <li><a href="../estoque/index.php">Estoque</a></li>
</ul>

<div class="container">
  <h1>Seu Carrinho</h1>

  <div class="carrinho-lista">
    <?php 
    $total = 0;
    if (!empty($_SESSION["carrinho"])): 
      foreach ($_SESSION["carrinho"] as $item): 
        if (isset($estoque[$item])): 
          $valor = $estoque[$item]["valor"];
          $imagem = $estoque[$item]["imagem"];
          $total += $valor;
    ?>
      <div class="item-carrinho">
        <img src="../estoque/<?php echo $imagem; ?>" alt="<?php echo htmlspecialchars($item); ?>">
        <div class="item-info">
          <h4><?php echo htmlspecialchars($item); ?></h4>
          <p>R$ <?php echo number_format($valor, 2, ',', '.'); ?></p>
        </div>
        <form method="POST">
          <input type="hidden" name="remover" value="<?php echo htmlspecialchars($item); ?>">
          <button type="submit">Remover</button>
        </form>
      </div>
    <?php 
        endif;
      endforeach; 
    else: ?>
      <p>Nenhum produto no carrinho.</p>
    <?php endif; ?>
  </div>

  <?php if ($total > 0): ?>
    <div class="total">Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></div>
    <div style="display:flex;gap:10px;align-items:center;">
      <form method="POST" style="margin:0;">
        <input type="hidden" name="comprar" value="1">
        <button type="submit" class="comprar-btn">Comprar</button>
      </form>
      <a href="produtos.php" class="continuar-btn">Continuar comprando</a>
    </div>
  <?php endif; ?>

  <?php if (isset($mensagem)): ?>
    <div class="mensagem"><?php echo $mensagem; ?></div>
  <?php endif; ?>
</div>

<footer>
  <p>© 2025 JLER Móveis — Todos os direitos reservados.</p>
</footer>

</body>
</html>
