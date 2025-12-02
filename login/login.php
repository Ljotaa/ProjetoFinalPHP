<?php
session_start();
$arquivo = __DIR__ . "/usuarios.json";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"] ?? "");
    $senha   = trim($_POST["senha"] ?? "");
    $loginValido = false;

    if (file_exists($arquivo)) {
        $usuarios = json_decode(file_get_contents($arquivo), true);
        if (is_array($usuarios)) {
            foreach ($usuarios as $u) {
                if ($u["usuario"] === $usuario && password_verify($senha, $u["senha"])) {
                    $loginValido = true;
                    break;
                }
            }
        }
    }

    if ($loginValido) {
        $_SESSION["usuario"] = $usuario;

       
        if ($usuario === "admin") {
            header("Location: ../estoque/index.php"); 
        } else {
            header("Location: ../index/index.php");   
        }
        exit();
    } else {
        echo "<p style='color:red;'>Usuário ou senha incorretos.</p>";
        echo "<a href='login.html'>Voltar</a>";
    }
}
