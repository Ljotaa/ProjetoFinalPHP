<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header("Location: login.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Intranet da Empresa</title>
  <link rel="stylesheet" href="style.css">

</head>
<body>
  <div class="top-bar">
    <div></div> <!-- espaço vazio à esquerda -->
    <a href="logout.php" class="logout-btn">Sair</a>
  </div>

  <header>
    <h1>Intranet</h1>
  </header>

  <div class="boas-vindas">
    <p>Bem-vindo, <strong><?php echo $_SESSION['usuario']; ?></strong>!</p>
  </div>

  <nav>
    <a href="#">Início</a>
    <a href="#">Lojas</a>
    <a href="#">produtos</a>
    <a href="#">Carrinho</a>
    <a href="#">Suporte</a>
  </nav>

  <main>
    <div class="card">
      <h2>📢 Avisos Recentes</h2>
      <ul>
        <li>Reunião geral na sexta-feira, 15h.</li>
        <li>Nova política de home office disponível em "Documentos".</li>
      </ul>
    </div>

    <div class="card">
      <h2>📂 Links Úteis</h2>
      <ul>
        <li><a href="#">Manual do Colaborador</a></li>
        <li><a href="#">Solicitação de Férias</a></li>
        <li><a href="#">Chamado de Suporte</a></li>
      </ul>
    </div>

    <div class="card">
      <h2>💬 Mural de Mensagens</h2>
      <form id="muralForm">
        <input type="text" id="mensagem" placeholder="Escreva sua mensagem..." style="width: 80%;">
        <button type="submit">Postar</button>
      </form>
      <ul id="mural"></ul>
    </div>
  </main>

  <script>
    const form = document.getElementById("muralForm");
    const mural = document.getElementById("mural");

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      const msg = document.getElementById("mensagem").value;
      if (msg.trim() !== "") {
        const li = document.createElement("li");
        li.textContent = msg;
        mural.appendChild(li);
        document.getElementById("mensagem").value = "";
      }
    });
  </script>
</body>
</html>