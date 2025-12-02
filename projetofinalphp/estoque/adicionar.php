<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../login/login.html");
    exit();
}

$arquivo = "estoque.json";
$estoque = [];

if (file_exists($arquivo)) {
    $estoque = json_decode(file_get_contents($arquivo), true);
    if (!is_array($estoque)) $estoque = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST["nome"];
    $quantidade = (int)$_POST["quantidade"];
    $valor = (float)$_POST["valor"];

    
    $pastaImagensRel = "imagens/";                 
    $pastaImagensFis = __DIR__ . "/imagens/";      

    
    if (!is_dir($pastaImagensFis)) {
        mkdir($pastaImagensFis, 0777, true);
    }

    $imagem = "";
    if (!empty($_FILES["imagem"]["name"])) {
        $nomeArquivo = basename($_FILES["imagem"]["name"]);
        $caminhoFisico = $pastaImagensFis . $nomeArquivo;   
        $caminhoJSON   = $pastaImagensRel . $nomeArquivo;   

        if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminhoFisico)) {
            $imagem = $caminhoJSON;
        }
    }

    $estoque[$nome] = [
        "quantidade" => $quantidade,
        "valor" => $valor,
        "imagem" => $imagem
    ];

    file_put_contents($arquivo, json_encode($estoque, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

header("Location: index.php");
exit();
