<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuarioDigitado = trim($_POST['usuario']);
    $senhaDigitada = trim($_POST['senha']);

    $arquivo = "usuarios.json";
    $loginValido = false;

    if (file_exists($arquivo)) {
        $usuarios = json_decode(file_get_contents($arquivo), true);

        foreach ($usuarios as $usuario) {
            if ($usuarioDigitado === $usuario["usuario"] && password_verify($senhaDigitada, $usuario["senha"])) {
                $loginValido = true;
                break;
            }
        }
    }

    if ($loginValido) {
        $_SESSION['usuario'] = $usuarioDigitado;
        header("Location: index.php");
        exit();
    } else {
        echo "<p style='color:red;'>Usuário ou senha incorretos.</p>";
        echo "<a href='login.html'>Voltar</a>";
    }
}
?>
