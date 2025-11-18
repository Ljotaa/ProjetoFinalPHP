<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $novoUsuario = trim($_POST["usuario"]);
  $novaSenha = trim($_POST["senha"]);

  if ($novoUsuario !== "" && $novaSenha !== "") {
    $linha = $novoUsuario . ":" . $novaSenha . "\n";
    file_put_contents("usuarios.txt", $linha, FILE_APPEND);
    echo "<p style='color:green;'>Usuário cadastrado com sucesso!</p>";
    echo "<a href='login.html'>Ir para login</a>";
    exit();
  } else {
    echo "<p style='color:red;'>Preencha todos os campos.</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Usuário</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2 style="text-align:center;">Cadastro de Novo Usuário</h2>
  <form method="POST" style="max-width: 400px; margin: auto;">
    <label for="usuario">Usuário:</label>
    <input type="text" name="usuario" required>

    <label for="senha">Senha:</label>
    <input type="password" name="senha" required>

    <input type="submit" value="Cadastrar">
  </form>
</body>
</html>