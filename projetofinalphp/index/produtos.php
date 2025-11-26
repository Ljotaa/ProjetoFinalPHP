<?php
session_start();
$arquivo = "../estoque/estoque.json";
$produtos = [];
if (file_exists($arquivo)) {
    $json = file_get_contents($arquivo);
    $produtos = json_decode($json, true);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Produtos</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .produtos-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      margin-top: 30px;
    }
    .produto-card {
      background-color: #1a1a1a;
      border: 1px solid rgb(220, 30, 30);
      border-radius: 8px;
      padding: 15px;
      width: 220px;
      text-align: center;
    }
    .produto-card img {
      max-width: 100%;
      height: auto;
      border-radius: 6px;
      margin-bottom: 10px;
    }
    .produto-card h4 { color: #fff; margin-bottom: 6px; }
    .produto-card p { color: #ccc; margin-bottom: 10px; }
    .produto-card form button {
      background-color: rgb(220, 30, 30);
      color: #fff;
      border: none;
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
    }
    .produto-card form button:hover { background-color: rgb(255, 70, 70); }
    .sem-estoque {
      color: rgb(255, 70, 70);
      font-weight: 700;
      margin-top: 8px;
    }
  </style>
</head>
<body>

<header>
  <div class="logo">JLER</div>
  <div class="cart-nav">
    <a href="carrinho.php">🛒</a>
    <a href="../login/logout.php" class="logout-btn">Sair</a>
  </div>
</header>

<ul class="topnav">
  <li><a href="index.php">Home</a></li>
  <li><a class="active" href="produtos.php">Produtos</a></li>

  <li><a href="../estoque/index.php">Estoque</a></li>
</ul>

<div class="container">
  <h1>Produtos Disponíveis</h1>

  <div class="produtos-grid">
    <?php foreach ($produtos as $nome => $dados): ?>
      <div class="produto-card">
        <img src="../estoque/<?php echo $dados['imagem']; ?>" alt="<?php echo htmlspecialchars($nome); ?>">
        <h4><?php echo htmlspecialchars($nome); ?></h4>
        <p>R$ <?php echo number_format($dados['valor'], 2, ',', '.'); ?></p>
        <?php if (isset($dados['quantidade']) && $dados['quantidade'] > 0): ?>
          <form action="carrinho.php" method="POST">
            <input type="hidden" name="produto" value="<?php echo htmlspecialchars($nome); ?>">
            <button type="submit">Adicionar ao carrinho</button>
          </form>
        <?php else: ?>
          <div class="sem-estoque">Sem estoque</div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<footer>
  <p>© 2025 JLER Móveis — Todos os direitos reservados.</p>
</footer>

</body>
</html>
