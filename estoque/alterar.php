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

    if (isset($estoque[$nome])) {
        $estoque[$nome]["quantidade"] = $quantidade;
        $estoque[$nome]["valor"] = $valor;
    }

    file_put_contents($arquivo, json_encode($estoque, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

header("Location: index.php");
exit();
