document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Impede o envio tradicional do formulário

    const formData = new FormData(this);

    fetch('registro.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.toLowerCase().includes("sucesso")) {
            alert("Registrado com sucesso!");
            // Redirecionar para login.html (ou qualquer outra)
            window.location.href = "index.php";
        } else {
            alert("Erro: " + data);
        }
    })
    .catch(error => {
        alert("Erro no envio: " + error);
    });
});
