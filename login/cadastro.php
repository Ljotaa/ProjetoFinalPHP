<?php
$arquivo = __DIR__ . "/usuarios.json";
$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"] ?? "");
    $senha   = trim($_POST["senha"] ?? "");

    if ($usuario !== "" && $senha !== "") {
        if (!file_exists($arquivo)) {
            file_put_contents($arquivo, json_encode([], JSON_PRETTY_PRINT));
        }

        $usuarios = json_decode(file_get_contents($arquivo), true);
        if (!is_array($usuarios)) $usuarios = [];

        foreach ($usuarios as $u) {
            if ($u["usuario"] === $usuario) {
                $mensagem = "<p style='color:red;'>Usuário já existe!</p>";
                break;
            }
        }

        if ($mensagem === "") {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $usuarios[] = ["usuario" => $usuario, "senha" => $hash];
            file_put_contents($arquivo, json_encode($usuarios, JSON_PRETTY_PRINT));
            $mensagem = "<p style='color:green;'>Usuário cadastrado com sucesso!</p>
                         <a href='login.html'>Ir para login</a>";
        }
    } else {
        $mensagem = "<p style='color:red;'>Preencha todos os campos.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <main>
    <div class="card">
      <h2 class="titulo">Cadastro</h2>
      <?php if ($mensagem) echo $mensagem; ?>
      <form method="post">
        <input type="text" name="usuario" placeholder="Novo usuário" required>
        <input type="password" name="senha" placeholder="Nova senha" required>
        <button type="submit">Cadastrar</button>
      </form>
    </div>
  </main>
</body>
</html>
