<?php
$usuario = trim($_POST['usuario']);
$senha = trim($_POST['senha']);

if ($usuario !== "" && $senha !== "") {
  $linha = $usuario . ":" . $senha . "\n";
  $arquivo = fopen("usuarios.txt", "a");
  fwrite($arquivo, $linha);
  fclose($arquivo);

  echo "<p style='color:green;'>Usuário cadastrado com sucesso!</p>";
  echo "<a href='login.html'>Ir para o login</a>";
} else {
  echo "<p style='color:red;'>Preencha todos os campos.</p>";
  echo "<a href='cadastro.html'>Voltar</a>";
}
?>
