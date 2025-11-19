<?php

$arquivo = "estoque.json";
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
    <title>Controle de Estoque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Controle de Estoque</h1>
        
        <form action="estoque.php" method="POST" enctype="multipart/form-data">
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" name="quantidade" min="0" required>

            <label for="valor">Valor (R$):</label>
            <input type="number" id="valor" name="valor" step="0.01" min="0" required>

            <label for="imagem">Imagem do Produto:</label>
            <input type="file" id="imagem" name="imagem" accept="image/*" required>

            <button type="submit">Cadastrar Produto</button>
        </form>

        <hr>

      
        <form action="estoque.php" method="POST">
            <label for="produto">Selecione o Produto:</label>
            <select id="produto" name="produto" required>
                <?php if (!empty($produtos)): ?>
                    <?php foreach ($produtos as $nome => $dados): ?>
                        <option value="<?php echo htmlspecialchars($nome); ?>">
                            <?php echo htmlspecialchars($nome); ?> 
                            (Qtd: <?php echo $dados['quantidade']; ?> | R$ <?php echo number_format($dados['valor'], 2, ',', '.'); ?>)
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option disabled>Nenhum produto cadastrado</option>
                <?php endif; ?>
            </select>

            <label for="acao">Ação:</label>
            <select id="acao" name="acao" required>
                <option value="adicionar">Adicionar</option>
                <option value="remover">Remover</option>
            </select>

            <label for="quantidade_acao">Quantidade:</label>
            <input type="number" id="quantidade_acao" name="quantidade_acao" min="1" required>

            <button type="submit">Atualizar Estoque</button>
        </form>
    </div>
</body>
</html>
