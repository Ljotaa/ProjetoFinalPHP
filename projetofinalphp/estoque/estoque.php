<?php
session_start();
header('Content-Type: application/json');

$arquivo = "estoque.json";
$produtos = [];
if (file_exists($arquivo)) {
    $json = file_get_contents($arquivo);
    $produtos = json_decode($json, true);
}
$response = ["status" => "erro"];


if (isset($_POST['nome']) && isset($_FILES['imagem'])) {
    $nome = trim($_POST['nome']);
    $quantidade = intval($_POST['quantidade'] ?? 0);
    $valor = floatval($_POST['valor'] ?? 0);


    $diretorio = "uploads/";
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }
    $base = basename($_FILES["imagem"]["name"]);
    $imagem_nome = time() . "_" . preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', $base);
    $caminho_imagem = $diretorio . $imagem_nome;

    if (is_uploaded_file($_FILES["imagem"]["tmp_name"])) {
        move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho_imagem);
    }

    $produtos[$nome] = [
        "quantidade" => $quantidade,
        "valor" => $valor,
        "imagem" => $caminho_imagem
    ];
    file_put_contents($arquivo, json_encode($produtos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    $response = [
        "status" => "sucesso",
        "nome" => $nome,
        "quantidade" => $quantidade,
        "valor" => $valor,
        "imagem" => $caminho_imagem
    ];
}


if (isset($_POST['produto']) && isset($_POST['acao']) && isset($_POST['quantidade_acao'])) {
    $produto = $_POST['produto'];
    $acao = $_POST['acao'];
    $quantidade_acao = intval($_POST['quantidade_acao']);

    if (isset($produtos[$produto])) {
        if ($acao === "adicionar") {
            $produtos[$produto]["quantidade"] += $quantidade_acao;
        } elseif ($acao === "remover") {
            $produtos[$produto]["quantidade"] -= $quantidade_acao;
            if ($produtos[$produto]["quantidade"] < 0) {
                $produtos[$produto]["quantidade"] = 0;
            }
        }
        file_put_contents($arquivo, json_encode($produtos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $response = [
            "status" => "sucesso",
            "nome" => $produto,
            "imagem" => $produtos[$produto]["imagem"] ?? null
        ];
    }
}

echo json_encode($response);
