<?php

$arquivo = "estoque.json";


$produtos = [];
if (file_exists($arquivo)) {
    $json = file_get_contents($arquivo);
    $produtos = json_decode($json, true);
}


if (isset($_POST['nome']) && isset($_FILES['imagem'])) {
    $nome = $_POST['nome'];
    $quantidade = intval($_POST['quantidade']);
    $valor = floatval($_POST['valor']);

   
    $diretorio = "uploads/";
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }
    $imagem_nome = time() . "_" . basename($_FILES["imagem"]["name"]);
    $caminho_imagem = $diretorio . $imagem_nome;
    move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho_imagem);

 
    $produtos[$nome] = [
        "quantidade" => $quantidade,
        "valor" => $valor,
        "imagem" => $caminho_imagem
    ];
}


if (isset($_POST['produto']) && isset($_POST['acao'])) {
    $produto = $_POST['produto'];
    $acao = $_POST['acao'];
    $quantidade_acao = intval($_POST['quantidade_acao']);

    if (isset($produtos[$produto])) {
        if ($acao == "adicionar") {
            $produtos[$produto]["quantidade"] += $quantidade_acao;
        } elseif ($acao == "remover") {
            $produtos[$produto]["quantidade"] -= $quantidade_acao;
            if ($produtos[$produto]["quantidade"] < 0) {
                $produtos[$produto]["quantidade"] = 0; 
            }
        }
    }
}


file_put_contents($arquivo, json_encode($produtos, JSON_PRETTY_PRINT));


echo "<h2>Estoque Atualizado</h2>";
foreach ($produtos as $nome => $dados) {
    echo "<p><strong>$nome</strong> - Quantidade: {$dados['quantidade']} | Valor: R$ " . number_format($dados['valor'], 2, ',', '.') . "<br>";
    echo "<img src='{$dados['imagem']}' width='100'></p>";
}
echo "<a href='index.php'>Voltar</a>";
?>
