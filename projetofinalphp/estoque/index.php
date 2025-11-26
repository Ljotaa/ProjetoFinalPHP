<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../login/login.html");
    exit();
}

if ($_SESSION["usuario"] !== "admin") {
    header("Location: ../login/login.html");
    exit();
}

$usuario = $_SESSION["usuario"];

$arquivoEstoque = "estoque.json";
$arquivoVendas = "vendas.json";

$estoque = [];
$vendas = [];


if (file_exists($arquivoEstoque)) {
    $conteudo = file_get_contents($arquivoEstoque);
    $estoque = json_decode($conteudo, true);
    if (!is_array($estoque)) $estoque = [];
}


if (file_exists($arquivoVendas)) {
    $conteudoV = file_get_contents($arquivoVendas);
    $vendas = json_decode($conteudoV, true);
    if (!is_array($vendas)) $vendas = [];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Gerenciamento de Estoque</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    .abas {
      display: flex;
      gap: 20px;
      margin-top: 30px;
    }
    .aba {
      flex: 1;
      background-color: #1a1a1a;
      border: 1px solid rgb(220,30,30);
      border-radius: 8px;
      padding: 15px;
    }
    .aba h2 {
      color: #fff;
      margin-bottom: 10px;
    }
    .aba table {
      width: 100%;
      border-collapse: collapse;
    }
    .aba table th, .aba table td {
      border: 1px solid rgb(220,30,30);
      padding: 8px;
      text-align: center;
      color: #fff;
    }
    .aba table th {
      background-color: #333;
    }
    .aba img.thumb-produto {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
      border: 1px solid #444;
    }
  </style>
</head>
<body>

<header>
  <div class="logo">JLER - Estoque</div>
  <div class="cart-nav">
    <a href="../login/logout.php" class="logout-btn">Sair</a>
  </div>
</header>


<div class="container">
  <h1>Gerenciamento de Estoque</h1>

  <div class="abas">
  
    <div class="aba">
      <h2>Adicionar Produto</h2>
      <form method="POST" action="adicionar.php" enctype="multipart/form-data">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>
        <label>Quantidade:</label><br>
        <input type="number" name="quantidade" required><br><br>
        <label>Valor:</label><br>
        <input type="number" step="0.01" name="valor" required><br><br>
        <label>Imagem:</label><br>
        <input type="file" name="imagem" accept="image/*"><br><br>
        <button type="submit">Adicionar</button>
      </form>
    </div>


    <div class="aba">
      <h2>Alterar Estoque</h2>
      <?php if (!empty($estoque)): ?>
        <table>
          <tr>
            <th>Imagem</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Valor (R$)</th>
            <th>Ação</th>
          </tr>
          <?php foreach ($estoque as $nome => $dados): ?>
            <tr>
              <td>
                <?php if (!empty($dados["imagem"])): ?>
                  <img src="<?php echo htmlspecialchars($dados["imagem"]); ?>" alt="<?php echo htmlspecialchars($nome); ?>" class="thumb-produto">
                <?php else: ?> –
                <?php endif; ?>
              </td>
              <td><?php echo htmlspecialchars($nome); ?></td>
              <td><?php echo (int)$dados["quantidade"]; ?></td>
              <td>R$ <?php echo number_format((float)$dados["valor"], 2, ',', '.'); ?></td>
              <td>
                <form method="POST" action="alterar.php">
                  <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nome); ?>">
                  <input type="number" name="quantidade" value="<?php echo (int)$dados["quantidade"]; ?>" required>
                  <input type="number" step="0.01" name="valor" value="<?php echo (float)$dados["valor"]; ?>" required>
                  <button type="submit">Salvar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </table>
      <?php else: ?>
        <p>Nenhum produto cadastrado.</p>
      <?php endif; ?>
    </div>

    <div class="aba">
      <h2>Vendas Realizadas</h2>
      <?php if (!empty($vendas)): ?>
        <table>
          <tr>
            <th>Produto</th>
            <th>Quantidade Vendida</th>
            <th>Valor Total (R$)</th>
          </tr>
          <?php foreach ($vendas as $venda): ?>
            <tr>
              <td><?php echo htmlspecialchars($venda["produto"]); ?></td>
              <td><?php echo (int)$venda["quantidade"]; ?></td>
              <td>R$ <?php echo number_format((float)$venda["valor_total"], 2, ',', '.'); ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      <?php else: ?>
        <p>Nenhuma venda registrada.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<footer>
  <p>© 2025 JLER Móveis — Todos os direitos reservados.</p>
</footer>

</body>
</html>
