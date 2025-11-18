document.querySelectorAll("form").forEach(form => {
  form.addEventListener("submit", function(e) {
    const usuario = form.querySelector("#usuario");
    const senha = form.querySelector("#senha");

    if (!usuario.value || !senha.value) {
      e.preventDefault();
      alert("Preencha todos os campos!");
    }
  });
});
