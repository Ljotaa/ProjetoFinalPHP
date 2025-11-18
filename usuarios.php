<?php
session_start(); // Inicia a sessão

$usuarioDigitado = $_POST['usuario'];
$senhaDigitada = $_POST['senha'];

$arquivo = fopen("usuarios.txt", "r");
$loginValido = false;

while (!feof($arquivo)) {
  $linha = fgets($arquivo);
  $linha = trim($linha);

  if ($linha !== "") {
    list($usuario, $senha) = explode(":", $linha);
    if ($usuarioDigitado === $usuario && $senhaDigitada === $senha) {
      $loginValido = true;
      break;
    }
  }
}

fclose($arquivo);

if ($loginValido) {
  $_SESSION['usuario'] = $usuarioDigitado; // Salva na sessão
  header("Location: index.php"); // Redireciona para a intranet
  exit();
} else {
  echo "<p style='color:red;'>Usuário ou senha incorretos.</p>";
  echo "<a href='login.html'>Voltar</a>";
}
?>
